<?php
session_start();
require_once('WxPay.JsApiPay.php');
class IndexAction extends BaseAction
{
	public function index()
	{
        /*$community_device_command = $_GET['device_command'];
        $type = $_GET['type'];
        $group_id = $_GET['group_id'];
        $openid = $_GET['openid'];
        $mchid = $_GET['mchid'];*/
        //小娃娃机
        $sc_small = $_GET['sc_small'];
        //小娃娃机
        //$openid = 'oXgRcuKECWvDLQo9M-7_TaBkdfDY';
        $tools = new JsApiPay();
        $openid = $tools->GetOpenid();
        $community_device_command = '861694034016582';
        $mchid = '1341024101';
        $type = '2';
        $group_id = 'AAAB';
        if(!empty($community_device_command)){
            $_SESSION['c_type'] = $type;
            $_SESSION['c_mchid'] = $mchid;
            $_SESSION['c_openid'] = $openid;
            $_SESSION['c_current_group'] = $group_id;
            $_SESSION['community_device_command'] = $community_device_command;
        }else{
            $type = $_SESSION['c_type'];
            $openid = $_SESSION['c_openid'];
            $mchid = $_SESSION['c_mchid'];
            $group_id = $_SESSION['c_current_group'];
            $community_device_command=$_SESSION['community_device_command'];
        }
        //获取个人信息
        $array = M('service_userinfo')->where(array('openid'=>$openid,'del_flag'=>0))->find();
        //查询余额
        /*$mchid = M('weixin_pay_config')->join('join ter_device td on td.payconfig_id=weixin_pay_config.id')
            ->where(array(
                'td.device_command'=>$community_device_command,
                'td.status'=>1,
                'td.del_flag'=>0,
                'weixin_pay_config.pay_type'=>1,
                'weixin_pay_config.status'=>1,
                'weixin_pay_config.del_flag'=>0
            ))->find();*/
        $total = M('service_pay_info')->where(array('status'=>1,'app_id'=>$mchid,'from_username'=>$openid))->sum('ticket');
        //echo M('service_pay_info')->getLastSql();die;
        if(empty($total)){
            $total = 0;
        }else{
            $total = $total;
        }

        //获取积分
        $user_integral = M('service_userinfo')->where(array('openid'=>$openid,'del_flag'=>0))->getField('user_integral');
        //echo M('service_userinfo')->getLastSql();echo 1111;die;
        //查出对应的群组字母
        $code = M('device_group')->where(array('device_command'=>$community_device_command,'del_flag'=>0,'status'=>1))->find();
        $give_coin = explode(',',$code['give_coin']);
        $give_coin_array = array();
        foreach($give_coin as $key=>$v){
            $give_coin_array[]['number'] = explode('-',$v);
        }

        //总人数
        $count = M('service_userinfo')->count();
        //本机历史排名
        //$cache = S('cache');
        //if(!$cache){
            $ranking = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,wu.nickname,wu.city FROM `service_pay_info` spi left join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code='$community_device_command' GROUP BY wu.openid ORDER BY count desc limit 5");
        /*    $cache = S('cache',$ranking,1800);
        }else{
            $ranking = S('cache');
        }*/

        //p($friend_info);echo '<hr/>';p($reply);die;
        //个人当天一台机器的总分数
        $list = M('service_userinfo')->field('nickname,headimgurl,city')->where(array('openid'=>$openid))->find();
        $model = new model();

        //总排名
        $total_ranking = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code='$community_device_command' and spi.is_archive=0  and spi.sys_time>CURDATE() GROUP BY wu.openid ORDER BY count desc");
        //p($total_ranking);
        //echo M('service_pay_info')->getLastSql();die;
        foreach ($total_ranking as $key => $value) {
            $total_ranking[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $total_ranking[$key]['my_pai'] = $key+1;
            }else{
                $total_ranking[$key]['my_pai'] = $key+1;
            }
        }
        $new_two = array();
        $no_array = array();
        foreach ($total_ranking as $key => $value) {
            if($value['openid']==$openid){
                $total_ranking[$key]['my'] = 1;
                if($key==0){
                    //第一名
                    $total_ranking = array_slice($total_ranking,$key,2);
                }
                elseif($key<2){
                    // 1.前两名
                    $total_ranking = array_slice($total_ranking,$key-1,2);
                }elseif($key>1&&$key<5){
                    //2.第三，四，五名
                    $total_ranking = array_slice($total_ranking,0,5);
                }elseif($key>4){
                    //3.第六，七，八名
                    $new_two = array_slice($total_ranking,0,2);
                    $total_ranking = array_slice($total_ranking,$key-1,3);
                }
            }else{
                $no_array = array_slice($total_ranking,0,2);
            }
        }
        $new_one_array = '';
        if(isset($new_two) && !empty($new_two)){
            $new_one_array = array_merge($new_two,$total_ranking);//在第六名之后
        }
        $meiyou = count($no_array);
        $qianwu = count($total_ranking);
        if(empty($new_one_array)){
            $houliu = 0;
        }else {
            $houliu = count($new_one_array);
        }
        $yiwei = $this->arrayChange($total_ranking);
        //echo $qianwu.'---'.$houliu.'--'.$meiyou;
        //后六名截取
        $new_one_array_1 = array_slice($new_one_array,0,2);
        $new_one_array_2 = array_slice($new_one_array,2,3);
        //前5名截取
        $total_ranking_1 = array_slice($total_ranking,0,2);
        $total_ranking_2 = array_slice($total_ranking,2,3);
        //echo $qianwu.'--'.$houliu;echo '<hr>';p($yiwei);
        //die;
        //p($new_one_array_2);
        //die;
        /*我的排名 ]]*/
        /*if(isset($_GET['hot'])&&!empty($_GET['hot'])){
            $hot = "friend_info.community_total";
        }else{
            $hot = "friend_info.create_date";
        }*/
        //朋友圈评论内容   微社区数据库
        $friend_info = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' ORDER BY friend_info.create_date DESC LIMIT 10
        ");
        $friend_info = object_array($friend_info);
        //回复
        $reply = $this->community->get_results("SELECT su.nickname AS re_name,
	su.openid,fr.friendinfo_id AS fr_id,fr.text AS re_text,fr.replybuyer_id,
	re.nickname as reply_name FROM `friend_reply` AS fr
    LEFT JOIN service_userinfo AS su ON su.openid = fr.userid
    LEFT JOIN service_userinfo as re on re.openid=fr.replybuyer_id
    WHERE (fr.del_flag = 0) AND (su.openid != '') ORDER BY fr.create_date asc");
        $reply = object_array($reply);
        foreach($friend_info as $k=>$v){
            foreach($reply as $key=>$va){
                if($v['id']==$va['fr_id']){
                    $friend_info[$k]['is_on'] = 1;
                }
            }
        }
        //p($zan);die;
        $this->assign('device_command',$community_device_command);
        $this->assign('user_integral',$user_integral);
        $this->assign('reply',$reply);
        $this->assign('headimgurl',$array['headimgurl']);
        $this->assign('nickname',$array['nickname']);
        $this->assign('is_openid',$array['openid']);
        $this->assign('group_code',$code);
        $this->assign('give_coin_array',$give_coin_array);
        $this->assign('new_one_array_1',$new_one_array_1);
        $this->assign('new_one_array_2',$new_one_array_2);
        $this->assign('total_ranking_1',$total_ranking_1);
        $this->assign('total_ranking_2',$total_ranking_2);
        $this->assign('new_one_array',$new_one_array);
        $this->assign('qianwu',$qianwu);
        $this->assign('sc_small',$sc_small);
        $this->assign('houliu',$houliu);
        $this->assign('no_array',$no_array);
        $this->assign('group_id',$group_id);
        $this->assign('type',$type);
        $this->assign('ranking',$ranking);
        $this->assign('friend_info',$friend_info);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->assign('mchid',$mchid);
        $this->assign('total_ranking',$total_ranking);
        $this->assign('openid',$openid);
        $this->assign('yiwei',$yiwei);
        $this->assign('list',$list);
		$this->display();
	}
    public function get_openid(){
        $REDIRECT_URI= 'http://'.$_SERVER['HTTP_HOST'].'/weixin_community/APP/Modules/Home/Action/get.php';//strategy
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->APP_ID&redirect_uri=".$REDIRECT_URI."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        header("Location:$url");
        $this->display('get_openid');
    }
    /*
     * author chw
     * time 2016-07-04
     * param加载用户评论列表
     *
     * */
    public function user_ajax(){
        if($_POST['page']){
            $page = $_POST['page'];
        }else{
            $page = 0;
        }
        $PageSize = 10;
        $current = ($page-1)*$PageSize;

        $list = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' ORDER BY friend_info.create_date DESC LIMIT $current,$PageSize
        ");
        $list = object_array($list);
        $reply = $this->community->get_results("SELECT su.nickname AS re_name,
	su.openid,fr.friendinfo_id AS fr_id,fr.text AS re_text,fr.replybuyer_id,
	re.nickname as reply_name FROM `friend_reply` AS fr
    LEFT JOIN service_userinfo AS su ON su.openid = fr.userid
    LEFT JOIN service_userinfo as re on re.openid=fr.replybuyer_id
    WHERE (fr.del_flag = 0) AND (su.openid != '') ORDER BY fr.create_date asc");
        $reply = object_array($reply);
        foreach($list as $k=>$v){
            foreach($reply as $key=>$va){
                if($v['id']==$va['fr_id']){
                    $list[$k]['is_on'] = 1;
                }
            }
        }
        $this->assign('friend_info',$list);
        $this->assign('userid',$_POST['userid']);
        $this->assign('is_openid',$_POST['is_openid']);
        $this->assign('reply',$reply);
        $html=$this->fetch("index_page");
        if(empty($list)){
            exit(-1);
        }else{
            exit($html);
        }
        /*if($list){
            echo json_encode(array('datas'=>$list,'reply'=>$reply));
        }else{
            echo json_encode(array('msg'=>2));
        }*/
    }
    /*
     * author chw
     * time 2016-07-05
     * param 字母消费数据插入
     * */
    public function community_add(){
        $mchid = $_POST['mchid'];
        $openid = $_POST['openid'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $pay_info = array(
            'id' => guid(),
            'app_id' => $mchid,
            'from_username' => $openid,
            'ticket' => '-'.$price,
            'type' => '0',
            'status'=>'1',
            'device_id'=>$device_id,
            'device_code'=>$device_command,
            'command_id'=>guid(),
            'create_date'=>date('Y-m-d H:i:s',time())
        );
        $uid = M('service_pay_info')->add($pay_info);
        $ucid = M('service_userinfo')->where(array('openid'=>$openid))->setInc('user_integral');
    }
    /*
     * author chw
     * time 2016-07-05
     * param 启动多次消费数据插入
     * */
    public function community_more(){
        $mchid = $_POST['mchid'];
        $openid = $_POST['openid'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $pay_info = array(
            'id' => guid(),
            'app_id' => $mchid,
            'from_username' => $openid,
            'ticket' => '-'.$price,
            'type' => '0',
            'status'=>'1',
            'device_id'=>$device_id,
            'device_code'=>$device_command,
            'command_id'=>guid(),
            'create_date'=>date('Y-m-d H:i:s',time())
        );
        $uid = M('service_pay_info')->add($pay_info);
        $ucid = M('service_userinfo')->where(array('openid'=>$openid))->setInc('user_integral',$price);
        echo $uid;
    }
    /*
     * author chw
     * time 2016-07-07 22:07
     * param 点赞
     * */
    public function click_friend(){
        $id = $_POST['id'];
        $openid = $_POST['openid'];
        $cid = $this->community->query("update friend_info set praise=praise+1 where id='$id'");
        $data['id'] = guid();
        $data['userid'] = $openid;
        $data['friendinfo_id'] = $id;
        $data['create_by'] = $openid;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = $openid;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['remarks'] = '点赞';
        $uid = $this->community->query("INSERT INTO friend_like SET " . $this->community->get_set($data));
        if($uid){
            $all = $this->community->get_row("select praise from friend_info where id='$id'");
            $all = object_array($all);
            echo json_encode(array('code'=>200,'all'=>$all['praise']));
        }else{
            echo json_encode(array('code'=>300));
        }
    }
    /*
     * author chw
     * time 2016-07-08 16:44
     * param 多维数组转为一维数组
     * */
    public function arrayChange($a){
        static $arr2;
        foreach($a as $v)
        {
            if(is_array($v))
            {
                $this->arrayChange($v);
            }else{

                $arr2[]=$v;
            }
        }
        return $arr2;
    }
    /*
     * author chw
     * time 2016-07-08 17:00
     * param 写攻略
     * */
    public function strategy(){
        $openid = $_GET['openid'];
        $nickname = $_GET['nickname'];
        $sex = $_GET['sex'];
        $language = $_GET['language'];
        $city = $_GET['city'];
        $province = $_GET['province'];
        $country = $_GET['country'];
        $headimgurl = $_GET['headimgurl'];
        $issopenid = M('service_userinfo')->where(array('openid' => $openid, 'del_flag' => 0))->find();
        //echo M('service_userinfo')->getLastSql();die;
        //插入用户信息
        if ($issopenid == false && $sex) {
            $datas['id'] = md5(uniqid());
            $datas['wno'] = $this->WNO;
            $datas['nickname'] = $nickname;
            $datas['sex'] = $sex;
            $datas['openid'] = $openid;
            $datas['language'] = $language;
            $datas['city'] = $city;
            $datas['province'] = $province;
            $datas['country'] = $country;
            $datas['headimgurl'] = $headimgurl;
            $datas['subscribe'] = 1;
            $datas['user_integral'] = 0;
            $datas['create_time'] = date('Y-m-d H:i:s', time());
            $datas['subscribe_time'] = date('Y-m-d H:i:s', time());
            $datas['subscribe_datetime'] = date('Y-m-d H:i:s', time());
            $opid = M('service_userinfo')->add($datas);
        }
        $this->assign('token',$this->token);
        $this->assign('openid',$openid);
        $this->display();
    }
    /*
     * author chw
     * time 2016-07-08 17:20
     * param 标题，内容检查是否包含暴力色情
     * */
    public function strate_url_of(){
        $title = trim($_POST['add_title']);
        $search = $this->community->get_row("select * from illegal_words where word like '%$title%'");
        if($search){
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>500));
        }
    }
    /*
     * author chw
     * time 2016-07-08 17:50
     * param 提交评论
     * */
    public function submit_content(){
        //$title = $_POST['title'];
        $content = $_POST['content'];
        $openid = $_POST['openid'];
        $pic = $_POST['select_id'];
        $data['id'] = guid();
        $data['text'] = $content;
        $data['pic'] = $pic;
        $data['group_id'] = $_SESSION['c_current_group'];
        $data['create_by'] = $openid;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = $openid;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['remarks'] =0;
        $data['community_total'] = 0;
        $data['praise'] = 0;
        $uid = $this->community->query("INSERT INTO friend_info SET " . $this->community->get_set($data));
        if($uid){
            echo json_encode(array('code'=>200));
        }
    }
    /*
     * author chw
     * time 2016-07-09 11:19
     * param 回复评论
     *
     * */
    public function reply(){
        $userid = $_GET['userid'];
        $replybuyer_id = $_GET['replybuyer_id'];
        $friendinfo_id = $_GET['friendinfo_id'];
        $this->assign('replybuyer_id',$replybuyer_id);
        $this->assign('friendinfo_id',$friendinfo_id);
        $this->assign('userid',$userid);
        $this->display();
    }

    /*
     * author chw
     * time 2016-07-12 18:39
     * param 回复评论
     * */
    public function pl_url(){
        $friendinfo_id = $_POST['friendinfo_id'];
        $uid = $this->community->get_row("select community_total,create_date from friend_info where id='$friendinfo_id'");
        $uid = object_array($uid);
        $data['replybuyer_id'] = $_POST['replybuyer_id'];
        $data['userid'] = $_POST['userid'];
        $data['id'] = guid();
        $data['text'] = $_POST['content'];
        $data['friendinfo_id'] = $_POST['friendinfo_id'];
        $data['create_by'] = $_POST['userid'];
        $data['update_by'] = $_POST['userid'];
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $cid = $this->community->query("insert into friend_reply set ".$this->community->get_set($data));
        $all = $this->community->query("update friend_info set community_total=community_total+1 where id='$friendinfo_id'");
        if($cid && $all){
            //$datas['community_total']=$uid['community_total']+1;
            //$where="id='$friendinfo_id'";
            //$all = $this->community->query("update friend_info set community_total=community_total+1 where id='$friendinfo_id'");
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>500));
        }
    }
    /*
     * author chw
     * time 2016-07-27 11:25
     * param 点赞
     * */
    public function click_zan_more(){
        $id = $_POST['id'];
        $openid = $_POST['openid'];
        $all = $this->community->get_row("select * from friend_like where userid='$openid' and friendinfo_id='$id' and del_flag=0");
        if($all){
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>300));
        }
    }
    /*
     * author chw
     * time 2016-07-27 11:25
     * param 取消赞
     * */
    public function cancel(){
        $id = $_POST['id'];
        $openid = $_POST['openid'];
        //echo $id.'----'.$openid;die;
        $cid = $this->community->query("update friend_info set praise=praise-1 where id='$id'");
        $cancel = $this->community->query("delete from friend_like where userid='$openid' and friendinfo_id='$id' and del_flag=0");
        $all = $this->community->get_row("select praise from friend_info where id='$id' and del_flag=0");
        $all = object_array($all);
        if($all){
            echo json_encode(array('code'=>200,'all'=>$all['praise']));
        }else{
            echo json_encode(array('code'=>300));
        }
    }
}
?>