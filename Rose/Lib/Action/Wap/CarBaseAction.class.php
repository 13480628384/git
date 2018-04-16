<?php
session_start();
require_once "WxPay.JsApiPayCar.php";
class CarBaseAction extends Action
{
    public $openid = null;
    public $user_id = null;
    public $office_id = null;
    protected function _initialize()
    {
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 ){
            //exit('请用微信打开');
        }
        if($_REQUEST['user_id']){
            $this->user_id = $_REQUEST['user_id'];
            setcookie('user_id',$_REQUEST['user_id'],time()+3600*2);
        }
        $sys_user = M('sys_user');
        $no = $sys_user->where(array('del_flag'=>0,'id'=>trim($this->user_id),'login_flag'=>'0'))->find();
        if($no){
            exit('请联系网站管理员');
        }
        $office = $sys_user->where(array('del_flag'=>0,'id'=>trim($this->user_id)))->find();
        if(!$office){
            header("Location:".U("Rose2Login/login"));
            exit();
        }
        $this->user_id = $office['id'];
    }
}
?>