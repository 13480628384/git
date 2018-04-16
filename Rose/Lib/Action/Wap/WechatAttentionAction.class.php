<?php
session_start();
require_once "WxPay.JsApiPay.php";
class WechatAttentionAction extends Action
{
    /*
     * 微信用户访问记录信息（广告）
     * 显示微信广告信息
     * */
    public function Get_User_Info(){
        $tools = new JsApiPay();
        $openid = $tools->GetOpenid();
        //获取用户从微信支付带过来的群组id
        //$openid = trim($_GET['openid']);
        $appid = trim($_GET['appid']);
        $Group_Id = trim($_GET['group_id']);
        $di_id = trim($_GET['di_id']);
        if(empty($openid) || empty($appid) || empty($Group_Id)
            || empty($di_id) || !isset($openid) ||  !isset($di_id)){
            exit('请重新扫描');
        }
        //$openid = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        //查询群组广告表
        $Adv_id = M('adv_relation_group')->where(array('dgi_id'=>$Group_Id,'del_flag'=>0,'status'=>1))->find();
        //找到广告id
        $models = new model();
        $sqlqcode = "SELECT
            awi.id,awi.weixin_name,awi.weixin_intro,awi.qr_url
        FROM adv_weixin_info awi  LEFT JOIN adv_relation_group arg ON arg.adv_id=awi.id
        WHERE  arg.del_flag = 0 AND arg. STATUS = 1 AND arg.dgi_id = '$Group_Id'
        and awi.status=1 and TO_DAYS(NOW()) BETWEEN TO_DAYS(awi.start_date) and  TO_DAYS(awi.end_date) order by rand()";
        //获取终端的广告列表
        $device_command_ads = $_SESSION['device_qcode_ads1~'.$Group_Id];
        if(empty($device_command_ads)){
            //数据库操作
            $device_command_ads = $models->query($sqlqcode);
            $_SESSION['device_qcode_ads1~'.$Group_Id] = $device_command_ads;
        }
        //获取终端广告的指针位置
        $current_ad_index = $_SESSION['current_qcode_ads1~'.$Group_Id];
        if(empty($current_ad_index)){
            $current_ad_index = 0;
        }
        //获取显示的广告
        $group_adv = $device_command_ads[$current_ad_index];
        //处理下一个轮播广告
        $current_ad_index++;
        $current_ad_index = $current_ad_index%count($device_command_ads);
        $_SESSION['current_qcode_ads1~'.$Group_Id] = $current_ad_index;
        //设置失效时间
        $life_time = 120;
        $name = $_SESSION['current_qcode_ads1'.$Group_Id];
        $names = $_SESSION['device_qcode_ads1'.$Group_Id];
        setcookie(session_name($name),session_id(),time()+$life_time,"/");
        setcookie(session_name($names),session_id(),time()+$life_time,"/");
        $_SESSION['adv_openid'] = $openid;
        //插入用户访问记录信息
        $data = array(
            'id'             => generateNum(),
            'wxpay_appid'  => $appid,
            'wxpay_openid' => $openid,
            'adv_id'        => $group_adv['id'],
            'di_id'         => $di_id,
            'status'        =>1,
            'create_by'     =>$openid,
            'create_date'  =>date('Y-m-d H:i:s',time()),
            'update_by'    =>$openid,
            'update_date'   => date('Y-m-d H:i:s',time())
        );
        $model = M('adv_uservisit_rec');//di_id可以不要
        $If = $model->where(array('del_flag'=>0,'adv_id'=>$group_adv['id'],'wxpay_openid'=>$openid))->find();
        //echo $model->getLastSql();die;
        if(!$If){
            $uid = $model->Add($data);
        }else{
			$reg['update_date'] = date('Y-m-d H:i:s',time());
			$reg['di_id'] = $di_id;
			$gid = $model->where(array('del_flag'=>0,'adv_id'=>$group_adv['id'],'wxpay_openid'=>$openid))->save($reg);
		}
        //查找用户是否已经关注过的公众号
        $On = $model->where(array('del_flag'=>0,'status'=>2,'wxpay_openid'=>$openid,'adv_id'=>$Adv_id['adv_id']))->find();
        $this->assign('group_adv',$group_adv);
        $this->assign('On',$On);
        $this->display();
    }
    /*
     * 微信关键字自动回复
     * */
    public function Get_Keyword_Id(){
        $Show_Msg = '';
        $Cid = trim($_GET['id']);//广告id
        if(empty($Cid)) exit();
        //$openid = 'odOIPv8GpkPbShi3iIUYvv4yJLe4';
        $tools = new JsApiPay();
        $openids = $tools->GetOpenid();
        $openid = $_SESSION['adv_openid'];
        if(empty($openid)){
            $Msg = '请重新扫描关注';
            header("Location:".U('error',array('show'=>$Show_Msg,'msg'=>$Msg)));
            exit();
        }
        $model = M("adv_weixin_info");
        //找出广告是否在有效期内  <if condition="$On['adv_id'] eq $group_adv['id']">class="on"</if>
        $Uid = $model->query("select * from adv_weixin_info where status=1
      and del_flag=0 and id='$Cid' and TO_DAYS(NOW()) BETWEEN TO_DAYS(start_date) and  TO_DAYS(end_date) ");
        if(!$Uid){
            $Show_Msg = 2;
            $Msg = '公众号已过期，请重新关注其他公众号';
            header("Location:".U('error',array('show'=>$Show_Msg,'msg'=>$Msg)));
            exit();
        }
        //找出用户是否已经关注过公众号
        $On = M('adv_uservisit_rec')
            ->where(array('del_flag'=>0,'status'=>2,'wxpay_openid'=>$openid,'adv_id'=>$Cid))
            ->find();
			//echo M('adv_uservisit_rec')->getLastSql();die;
        if($On){
            $Show_Msg = 2;
            $Msg = '你已经关注过公众号，请关注其他公众号';
            header("Location:".U('error',array('show'=>$Show_Msg,'msg'=>$Msg)));
            exit();
        }
        //找出对应的设备编号
        $On = M('adv_uservisit_rec')
            ->where(array('del_flag'=>0,'status'=>1,'wxpay_openid'=>$openid,'adv_id'=>$Cid))
            ->find();
        $device_info = M('device_info')->where(array('del_flag'=>0,'device_status'=>1,'id'=>$On['di_id']))->find();
        $this->assign('device_command',$device_info['device_command']);
        $this->assign('scan_code',$device_info['scan_code']);
        $this->assign('openid',$openid);
        $this->assign('Cid',$Cid);
		$this->assign('device_id',$device_info['id']);
        $this->display();
    }
    /*
     * 更新用户广告访问记录表
     * */
    public function update_user(){
        $openid = $_POST['openid'];
        $Cid    = $_POST['Cid'];
        //更新用户广告访问记录表
        $data['status'] = 2;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $Cid_adv = M('adv_uservisit_rec')->where(array('wxpay_openid'=>$openid,'adv_id'=>$Cid))->save($data);
        if($Cid_adv){
            echo json_encode(array('msg'=>200,'data'=>$Cid_adv));
            unset($_SESSION['adv_openid']);
        }
    }
    /*
     * 错误信息页面
     * */
    public function error(){
        $msg = $_GET['msg'];
        $show = $_GET['show'];
        $this->assign('msg',$msg);
        $this->assign('show',$show);
        $this->display();
    }
    /*
     * 投放广告
     * */
    public function Advertising(){
        //群组id
    }
}
?>