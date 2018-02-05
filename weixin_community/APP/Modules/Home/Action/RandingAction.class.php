<?php
require_once('WxPay.JsApiPay.php');
class RandingAction extends BaseAction{
    /*
     * author chw
     * time 2016-08-11 17:40
     * param 今日全区排名算法
     * */
    public function Now_Region_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Now_Region_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,wu.nickname,wu.city FROM
`service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where group_id='$group_id'
  and status=1 and del_flag=0)  and spi.is_archive=0  and spi.sys_time>CURDATE() GROUP BY wu.openid ORDER BY count desc
");
        foreach ($Now_Region_Randing as $key => $value) {
            $Now_Region_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Now_Region_Randing[$key]['my_pai'] = $key+1;
            }
        }
        //p($Now_Region_Randing);die;
        $this->assign('Now_Region_Randing',$Now_Region_Randing);
        $html=$this->fetch("Now_Region_Randing");
        if(empty($Now_Region_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 17:50
     * param 今日全国排名算法
     * */
    public function Now_Counity_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Now_Counity_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,wu.nickname,wu.city FROM
`service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where
  status=1 and del_flag=0)  and spi.is_archive=0  and spi.sys_time>CURDATE() GROUP BY wu.openid ORDER BY count desc
");
        foreach ($Now_Counity_Randing as $key => $value) {
            $Now_Counity_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Now_Counity_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Now_Counity_Randing',$Now_Counity_Randing);
        $html=$this->fetch("Now_Counity_Randing");
        if(empty($Now_Counity_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:00
     * param 一周单机排名算法
     * */
    public function Week_Stand_alone_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Week_Stand_alone_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code='$device_command'  and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc

");
        foreach ($Week_Stand_alone_Randing as $key => $value) {
            $Week_Stand_alone_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Week_Stand_alone_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Week_Stand_alone_Randing',$Week_Stand_alone_Randing);
        $html=$this->fetch("Week_Stand_alone_Randing");
        if(empty($Week_Stand_alone_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:10
     * param 一周全区排名算法
     * */
    public function Week_Region_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Week_Region_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where group_id='$group_id' and status=1 and del_flag=0)  and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc

");
        foreach ($Week_Region_Randing as $key => $value) {
            $Week_Region_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Week_Region_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Week_Region_Randing',$Week_Region_Randing);
        $html=$this->fetch("Week_Region_Randing");
        if(empty($Week_Region_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:20
     * param 一周全国排名算法
     * */
    public function Week_Counity_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Week_Counity_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where status=1 and del_flag=0)  and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc

");
        foreach ($Week_Counity_Randing as $key => $value) {
            $Week_Counity_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Week_Counity_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Week_Counity_Randing',$Week_Counity_Randing);
        $html=$this->fetch("Week_Counity_Randing");
        if(empty($Week_Counity_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:30
     * param 一个月单机排名算法
     * */
    public function Month_Stand_alone_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Month_Stand_alone_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code='$device_command' and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc;

");
        foreach ($Month_Stand_alone_Randing as $key => $value) {
            $Month_Stand_alone_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Month_Stand_alone_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Month_Stand_alone_Randing',$Month_Stand_alone_Randing);
        $html=$this->fetch("Month_Stand_alone_Randing");
        if(empty($Month_Stand_alone_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:40
     * param 一个月全区排名算法
     * */
    public function Month_Region_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Month_Region_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where group_id='$group_id' and status=1 and del_flag=0) and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc;

");
        foreach ($Month_Region_Randing as $key => $value) {
            $Month_Region_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Month_Region_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Month_Region_Randing',$Month_Region_Randing);
        $html=$this->fetch("Month_Region_Randing");
        if(empty($Month_Region_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
    /*
     * author chw
     * time 2016-08-11 18:50
     * param 一个月全国排名算法
     * */
    public function Month_Counity_Randing(){
        $group_id = $_POST['group_id'];
        $openid = $_POST['openid'];
        $device_command = $_POST['device_command'];
        $Month_Counity_Randing = M('service_pay_info')->query("SELECT count(1) as count,wu.openid,wu.headimgurl,
wu.nickname,wu.city FROM `service_pay_info` spi LEFT
join service_userinfo wu on wu.openid=spi.from_username where spi.type=0 and spi.status=1
 and spi.del_flag=0 and wu.openid!='' and spi.device_code in(SELECT device_command from device_group where  status=1 and del_flag=0) and spi.is_archive=0
  and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(spi.sys_time) GROUP BY wu.openid ORDER BY count desc;

");
        foreach ($Month_Counity_Randing as $key => $value) {
            $Month_Counity_Randing[$key]['user_count'] = $key+1;
            if($value['openid']==$openid){
                $Month_Counity_Randing[$key]['my_pai'] = $key+1;
            }
        }
        $this->assign('Month_Counity_Randing',$Month_Counity_Randing);
        $html=$this->fetch("Month_Counity_Randing");
        if(empty($Month_Counity_Randing)){
            exit(-1);
        }else{
            exit($html);
        }
    }
}
?>