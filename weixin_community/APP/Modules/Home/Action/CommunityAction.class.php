<?
require_once('WxPay.JsApiPay.php');
class CommunityAction extends BaseAction{
    public function index()
    {
        $group_id = trim($_GET['group_id']);
        $openid = trim($_GET['openid']);
        $device_command = trim($_GET['device_command']);
        $mchid = trim($_GET['mchid']);
        $new = trim($_GET['new']);
        //$tools = new JsApiPay();
        //$openid = $tools->GetOpenid();
        if (!empty($openid)) {
            $_SESSION['wei_openid'] = $openid;
        } else {
            $openid = $_SESSION['wei_openid'];
        }
        //获取个人信息
        $array = M('service_userinfo')->where(array('openid' => $openid, 'del_flag' => 0))->find();
        //朋友圈评论内容   微社区数据库
        if (empty($new) || $new == 1) {
            $friend_info = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!=''  ORDER BY friend_info.create_date DESC LIMIT 10
        ");
            //最热
        } else if ($new == 2){
            $friend_info = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!=''  ORDER BY friend_info.community_total DESC LIMIT 10
        ");
        }else if($new == 3){
            $friend_info = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' and friend_info.group_id='$group_id'  ORDER BY friend_info.create_date DESC LIMIT 10
        ");
        }else{
            $friend_info = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' and friend_info.seclect_top=1  ORDER BY friend_info.create_date DESC LIMIT 10
        ");
        }
        $friend_info = object_array($friend_info);
        //p($friend_info);die;
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
        $this->assign('reply',$reply);
        $this->assign('new',$new);
        $this->assign('is_openid',$array['openid']);
        $this->assign('create_openid',$openid);
        $this->assign('friend_info',$friend_info);
        $this->assign('device_command',$device_command);
        $this->assign('mchid',$mchid);
        $this->assign('group_id',$group_id);
        $this->display();
    }
    /*
     * author chw
     * time 2016-07-027 10:49
     * param 回复评论
     *
     * */
    public function com_reply(){
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
     * time 2016-07-07
     * param 对象转为数组
     *
     * */
    public function object_array($array){
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
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
     * time 2016-07-12 18:39
     * param 回复评论
     * */
    public function pl_url(){
        $friendinfo_id = $_POST['friendinfo_id'];
        $uid = $this->community->get_row("select community_total,create_date from friend_info where id='$friendinfo_id'");
        $data['replybuyer_id'] = $_POST['replybuyer_id'];
        $data['userid'] = $_POST['userid'];
        $data['id'] = guid();
        $data['text'] = $_POST['content'];
        $data['friendinfo_id'] = $_POST['friendinfo_id'];
        $data['create_by'] = $_POST['userid'];
        $data['update_by'] = $_POST['userid'];
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        //$cid = $this->community->add('friend_reply',$data);
        $cid = $this->community->query("insert into friend_reply set ".$this->community->get_set($data));
        if($cid){
            $datas['community_total']=$uid['community_total']+1;
            $where="id='$friendinfo_id'";
            //$all = $this->community->edit('friend_info',$datas,$where);
            $all = $this->community->query("update friend_info set community_total=community_total+1 where id='$friendinfo_id'");
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>500));
        }
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
        $data['remarks'] = '主-点赞';
        //$uid = $this->community->add('friend_like',$data);
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
        $new = $_POST['new'];
        $group_id = $_POST['group_id'];
        if(empty($new) || $new==1){
            $list = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' ORDER BY friend_info.create_date DESC LIMIT $current,$PageSize
        ");
        }else if($new == 2){
            $list = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' ORDER BY friend_info.community_total DESC LIMIT $current,$PageSize
        ");
        }else if($new == 3){
            $list = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' and friend_info.group_id='$group_id' ORDER BY friend_info.community_total DESC LIMIT $current,$PageSize
        ");
        }else{
            $list = $this->community->get_results("SELECT
	  friend_info.create_date,friend_info.text,friend_info.community_total,friend_info.praise,friend_info.pic,
	  su.nickname,friend_info.id,friend_info.create_by FROM `friend_info` JOIN service_userinfo su ON su.openid = friend_info.create_by
      WHERE (friend_info.del_flag = 0) and su.openid!='' and friend_info.seclect_top=1 ORDER BY friend_info.community_total DESC LIMIT $current,$PageSize
        ");
        }
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
        $html=$this->fetch("community_page");
        if(empty($list)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    public function time_tran($the_time) {
        $now_time = date("Y-m-d H:i:s", time());
        //echo $now_time;
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return $the_time;
                        }
                    }
                }
            }
        }
    }
    /*
     * author chw
     * time 2016-07-28
     * param 评论的详情页
     * */
    public function detail(){
        $id = I('id');
        $create_openid = I('create_openid');
        $openid = I('openid');
        $device_command = I('device_command');
        $group_id = I('group_id');
        $mchid = I('mchid');
        //查询头像
        $userinfo = M('service_userinfo')->where(array('openid'=>$openid))->find();
        //查询评论xinx

        $detail = $this->community->get_row("select * from friend_info where del_flag=0 and id='$id'");
        $detail = object_array($detail);
        //回复
        $reply = $this->community->get_results("SELECT su.nickname AS re_name,fr.create_date,
	su.openid,fr.friendinfo_id AS fr_id,fr.text AS re_text,fr.replybuyer_id,su.headimgurl,
	re.nickname as reply_name FROM `friend_reply` AS fr
    LEFT JOIN service_userinfo AS su ON su.openid = fr.userid
    LEFT JOIN service_userinfo as re on re.openid=fr.replybuyer_id
    WHERE (fr.del_flag = 0) AND (su.openid != '') ORDER BY fr.create_date asc");
        $reply = object_array($reply);
        foreach($reply as $k=>$v){
            $reply[$k]['time_tran'] = $this->time_tran("$v[create_date]");
        }
        $url = 'http://'.$_SERVER['SERVER_NAME'];
        $this->assign('reply',$reply);
        $this->assign('url',$url);
        $this->assign('group_id',$group_id);
        $this->assign('mchid',$mchid);
        $this->assign('device_command',$device_command);
        $this->assign('userinfo',$userinfo);
        $this->assign('detail',$detail);
        $this->assign('openid',$openid);
        $this->assign('create_openid',$create_openid);
        $this->assign('id',$id);
        $this->display();
    }
    public function Comment(){
        //$userid = $_SESSION['wei_openid'];//回复者
        $userid = $_POST['create_openid'];//回复者
        $id = $_POST['id'];
        $uid = $this->community->get_row("select community_total,create_date from friend_info where id='$id'");
        $Comment_Input = $_POST['Comment_Input'];
        $Openid = $_POST['Openid'];//回复给某个人的
        $data['replybuyer_id'] = $Openid;
        $data['userid'] = $userid;
        $data['id'] = guid();
        $data['text'] = $Comment_Input;
        $data['friendinfo_id'] = $id;
        $data['create_by'] = $userid;
        $data['update_by'] = $userid;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $cid = $this->community->query("INSERT INTO friend_reply SET " . $this->community->get_set($data));
        //回复
        $reply = $this->community->get_row("SELECT su.nickname AS re_name,fr.create_date,
	su.openid,fr.friendinfo_id AS fr_id,fr.text AS re_text,fr.replybuyer_id,su.headimgurl,
	re.nickname as reply_name FROM `friend_reply` AS fr
    LEFT JOIN service_userinfo AS su ON su.openid = fr.userid
    LEFT JOIN service_userinfo as re on re.openid=fr.replybuyer_id
    WHERE (fr.del_flag = 0) AND (su.openid != '') AND fr.friendinfo_id='$id' ORDER BY fr.create_date desc limit 1");
        $reply = object_array($reply);
        array_push($reply,$this->time_tran("$reply[create_date]"));
        if($cid){
            //$datas['community_total']=$uid['community_total']+1;
            //$where="id='$id'";
            $all = $this->community->query("update friend_info set community_total=community_total+1 where id='$id'");
            echo json_encode(array('code'=>200,'reply'=>$reply));
        }else{
            echo json_encode(array('code'=>500));
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
        $all = $this->community->get_row("select praise from friend_info  id='$id' and del_flag=0");
        $all = object_array($all);
        if($all){
            echo json_encode(array('code'=>200,'all'=>$all['praise']));
        }else{
            echo json_encode(array('code'=>300));
        }
    }
}
?>