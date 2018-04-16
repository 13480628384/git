<?php
session_start();
require_once "WxPay.JsApiPay.php";
class JuicerBaseAction extends Action
{
    public $openid = null;
    public $user_id = null;
    public $type = null;
    protected function _initialize()
    {
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 ){
            //exit('请用微信打开');
        }
        $this->openid = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        $juicer_openid = session('juicer_openid');
        /*if(empty($juicer_openid)){
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $this->openid = $openid;
            session('juicer_openid',$openid);
        } else {
            $this->openid = session('juicer_openid');
        }*/
        $user = M('ju_users');
        $ju_users = $user->where(array('user_status'=>1,'user_type'=>1,'openid'=>trim($this->openid)))->find();
        if(!$ju_users){
            header("Location:".U("JuicerLogin/binding"));
            exit();
        }
        session('area_id',$ju_users['area_id']);
        $ares = M('ju_area')->where(array('id'=>$ju_users['area_id']))->find();
        $this->type = $ares['type'];
        $this->user_id = $ju_users['id'];
        Vendor('weixin.jssdk');
        $jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
        $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
        $this->assign('signPackage',$signPackage);
        $this->assign('openid',$this->openid);
    }
}
?>