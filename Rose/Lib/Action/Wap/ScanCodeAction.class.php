<?php
session_start();
require_once "WxPay.JsApiPay.php";
require_once "Alipay/function.php";
class ScanCodeAction extends Action{
    /*
     * 扫码进来，判断是微信还是支付宝，如果是微信或者支付宝，微信显示进来
     * @param scan_code 扫码的二维码编号
     * @param weixin_alipay_type 微信或支付宝
     * author sniperchw
     * date 2016/12/27
     * */
    public function index(){
        //判断扫码是从微信还是支付宝
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        $weixin_alipay_type = '';
        $scan_code = isset($_GET['scan_code']) ? $_GET['scan_code']:null;
        if( is_null($scan_code) ){
            exit('页面参数错误，请重新扫描');
        }
        //$user_id = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        //$openid = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        $user_id = '';
        //判断设备是否在线
        $DeviceOnlineModel = M('device_info');
        $IFOnlineRow = $DeviceOnlineModel->where(array('scan_code'=>$scan_code,'device_status'=>1,'del_flag'=>0))->find();
        if( $IFOnlineRow == false ){
            exit('页面参数错误，请重新扫描');
        }
        //微信APP
        if( strpos($typesd,'MicroMessenger')>0 ){
            /*if($IFOnlineRow['device_type'] == 4){
                header("Location:http://wxpay.roseo2o.com/weixinscan/game_test/anm.php?scan_code=".$scan_code);
                exit;
            }*/
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $user_id = $openid;
            $uided = M('weixin_userinfo')->where(array('from_username'=>$openid,'del_flag'=>0))->find();
            if(!$uided && $openid){
                $dataeded['id'] = generateNum();
                $dataeded['app_id'] = WxPayConfig::APPID;
                $dataeded['from_username'] = $openid;
                $dataeded['consume_total_account'] = 1;
                $dataeded['pay_total_account'] = 0;
                $dataeded['total_account'] = 0;
                $dataeded['create_by'] = $openid;
                $dataeded['create_date'] = date('Y-m-d H:i:s',time());
                $dataeded['update_by'] = $openid;
                $dataeded['update_date'] = date('Y-m-d H:i:s',time());
                $dataeded['remarks'] = '微信';
                $uided = M('alipay_userinfo')->add($dataeded);
            }
            //更新会员的openid，没有就插入
            $weixin_alipay_type = 'wechat';
        } elseif(strpos($typesd,'AlipayClient') > 0) {
            /*if($IFOnlineRow['device_type'] == 4){
                header("Location:http://wxpay.roseo2o.com/alipayscan/game_test/anm.php?scan_code=".$scan_code);
                exit;
            }*/
        //支付宝APP
            $buyer_id = get_user_info();
            $user_id = $buyer_id;
            $weixin_alipay_type = 'alipay';
            $uid = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if(!$uid && $buyer_id){
                $dataeded['id'] = generateNum();
                $dataeded['app_id'] = AlipayConfig::APPID;
                $dataeded['buyer_id'] = $buyer_id;
                $dataeded['status'] = 1;
                $dataeded['pay_total_account'] = 0;
                $dataeded['total_account'] = 0;
                $dataeded['create_by'] = $buyer_id;
                $dataeded['create_date'] = date('Y-m-d H:i:s',time());
                $dataeded['update_by'] = $buyer_id;
                $dataeded['update_date'] = date('Y-m-d H:i:s',time());
                $dataeded['remarks'] = '支付宝';
                $uided = M('alipay_userinfo')->add($dataeded);
            }
        } else {
            exit('请用微信或支付宝打开');
        }
        //找出群组id
        $GroupIdModel = M('device_relation_group');
        $Group_Id = $GroupIdModel->where(array('di_id'=>$IFOnlineRow['id']))->getField('dgi_id');
        //导流广告显示，随机显示几条
        /*==========================扫码广告 [[==========================*/
        if( strpos($typesd,'MicroMessenger')>0 ) {
            $appid = WxPayConfig::APPID;
            $IS_IF = '';
            $di_id = $IFOnlineRow['id'];
            if (empty($openid) || empty($appid) || empty($Group_Id)
                || empty($di_id) || !isset($openid) || !isset($di_id)
            ) {
                exit('请重新扫描');
            }
            //查询群组广告表
            $Adv_id = M('adv_relation_group')->where(array('dgi_id' => $Group_Id, 'del_flag' => 0, 'status' => 1))->find();
            //找到广告id
            $models = new model();
            $sqlqcode = "SELECT
            awi.id,awi.weixin_name,awi.weixin_intro,awi.qr_url
        FROM adv_weixin_info awi  LEFT JOIN adv_relation_group arg ON arg.adv_id=awi.id
        WHERE  arg.del_flag = 0 AND arg. STATUS = 1 AND arg.dgi_id = '$Group_Id'
        and awi.status=1 and TO_DAYS(NOW()) BETWEEN TO_DAYS(awi.start_date) and  TO_DAYS(awi.end_date) order by rand()";
            $is_adv = $models->query($sqlqcode);
            if($is_adv){
                $IS_IF = 1;
            }else{
                $IS_IF = 2;
            }
            //获取终端的广告列表
            $device_command_ads = $_SESSION['device_qcode_ads1~' . $Group_Id];
            if (empty($device_command_ads)) {
                //数据库操作
                $device_command_ads = $models->query($sqlqcode);
                $_SESSION['device_qcode_ads1~' . $Group_Id] = $device_command_ads;
            }
            //获取终端广告的指针位置
            $current_ad_index = $_SESSION['current_qcode_ads1~' . $Group_Id];
            if (empty($current_ad_index)) {
                $current_ad_index = 0;
            }
            //获取显示的广告
            $group_adv = $device_command_ads[$current_ad_index];
            //处理下一个轮播广告
            $current_ad_index++;
            $current_ad_index = $current_ad_index % count($device_command_ads);
            $_SESSION['current_qcode_ads1~' . $Group_Id] = $current_ad_index;
            //设置失效时间
            $life_time = 120;
            $name = $_SESSION['current_qcode_ads1' . $Group_Id];
            $names = $_SESSION['device_qcode_ads1' . $Group_Id];
            setcookie(session_name($name), session_id(), time() + $life_time, "/");
            setcookie(session_name($names), session_id(), time() + $life_time, "/");
            $_SESSION['adv_openid'] = $openid;
            //插入用户访问记录信息
            $data = array(
                'id' => generateNum(),
                'wxpay_appid' => $appid,
                'wxpay_openid' => $openid,
                'adv_id' => $group_adv['id'],
                'di_id' => $di_id,
                'status' => 1,
                'create_by' => $openid,
                'create_date' => date('Y-m-d H:i:s', time()),
                'update_by' => $openid,
                'update_date' => date('Y-m-d H:i:s', time())
            );
            $model = M('adv_uservisit_rec');//di_id可以不要
            $If = $model->where(array('del_flag' => 0, 'adv_id' => $group_adv['id'], 'wxpay_openid' => $openid))->find();
            //echo $model->getLastSql();die;
            if (!$If) {
                $uid = $model->Add($data);
            } else {
                $reg['update_date'] = date('Y-m-d H:i:s', time());
                $reg['di_id'] = $di_id;
                $gid = $model->where(array('del_flag' => 0, 'adv_id' => $group_adv['id'], 'wxpay_openid' => $openid))->save($reg);
            }
            //查找用户是否已经关注过的公众号
            $On = $model->where(array('del_flag' => 0, 'status' => 2, 'wxpay_openid' => $openid, 'adv_id' => $Adv_id['adv_id']))->find();
            $this->assign('group_adv', $group_adv);
            $this->assign('IS_IF', $IS_IF);//判断是否有广告
            $this->assign('On', $On);
       }
        /*==========================扫码广告 ]]==========================*/
        //$user_id = '2088802658990276';
        //$weixin_alipay_type = 'alipay';
        $where['buyer_id'] = $user_id;
        $where['_logic'] = 'or';
        $where['openid'] = $user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $roseed = M('rose_user_info')->where($map)->find();
        //echo M('rose_user_info')->getLastSql();die;
        /*==========================导流广告 [[==========================*/
        $rose_adv = M('rose_eco_advertising_info')->where(array(
            'del_flag'=>0,
            'audit_status'=>1,
            'online'=>1
        ))->order('rand()')->limit(2)->select();
        //消耗黄玫瑰和添加展示数
        foreach($rose_adv as $key=>$v){
            M('rose_eco_advertising_info')->where(array('id'=>$v['id']))->setInc('show_number');
            $quoention = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->find();
            if(intval($quoention['yellow_rose'])>0){
                M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->setDec('yellow_rose');
                $rose_adv[$key]['count'] = 2;
            }
        }
        $this->assign('rose_adv',$rose_adv);
        /*==========================导流广告 ]]==========================*/
        $PublicAdv = M('adv_relation_group')->where(array('dgi_id'=>$Group_Id,'del_flag'=>0))->find();
        //echo $weixin_alipay_type;die;
        $this->assign('weixin_alipay_type',$weixin_alipay_type);
        $this->assign('user_id',$user_id);//用户的唯一ID，即还没绑定
        $this->assign('PublicAdv',$PublicAdv);
        $this->assign('scan_code',$scan_code);
        $this->assign('rose_adv',$rose_adv);
        $this->assign('rose',$roseed);
        $this->display();
    }

}
?>