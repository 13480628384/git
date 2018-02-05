<?php
require_once('WxPay.JsApiPay.php');
class PersonalAction extends BaseAction{
    public function index(){
        $openid = trim($_GET['openid']);
        $device_command = trim($_GET['device_command']);
        $group_id = trim($_GET['group_id']);
        $mchid = trim($_GET['mchid']);
        //今日我的排名
        $my_ranking = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code='$device_command' and spi.is_archive=0  and spi.sys_time>CURDATE() GROUP BY wu.openid ORDER BY count desc");
        //p($my_ranking);die;
        foreach ($my_ranking as $key => $value) {
            $my_ranking[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $my_ranking[$key]['my_pai'] = $key+1;
            }
        }
        //p($my_ranking);die;
        $personal = M('service_userinfo')->where(array('openid'=>$openid))->find();
        $community = $this->community->get_results("select * from friend_info where create_by='$openid' and del_flag=0  ORDER BY create_date desc");
        $community = object_array($community);
        foreach($community as $k=>$v){
            $pic = explode(',',$v['pic']);
            $pics = array();
            foreach($pic as $p){
                $community[$k]['imgs'] = $p;
            }
        }
        //p($community);die;
        $this->assign('personal',$personal);
        $this->assign('device_command',$device_command);
        $this->assign('group_id',$group_id);
        $this->assign('community',$community);
        $this->assign('my_ranking',$my_ranking);
        $this->assign('openid',$openid);
        $this->assign('mchid',$mchid);
        $this->display();
    }
    public function update(){
        $device_command = trim($_GET['device_command']);
        $group_id = trim($_GET['group_id']);
        $openid = trim($_GET['openid']);
        $personal = M('service_userinfo')->where(array('openid'=>$openid))->find();
        $this->assign('personal',$personal);
        $this->assign('device_command',$device_command);
        $this->assign('group_id',$group_id);
        $this->assign('openid',$openid);
        $this->display();
    }
    public function personal(){
        $nickname = trim($_POST['nickname']);
        $headimgurl = trim($_POST['headimgurl']);
        $openid = trim($_POST['openid']);
        $data['headimgurl'] = $headimgurl;
        $data['nickname'] = $nickname;
        $cid = M('service_userinfo')->where(array('openid'=>$openid))->save($data);
        if($cid){
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>500));
        }
    }
}
?>