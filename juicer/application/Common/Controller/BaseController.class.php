<?php
namespace Common\Controller;
use Think\Controller;
class BaseController extends Controller{
    protected $Material;
    protected $Meter_descime;
    protected $openid;

    protected function _initialize() {
        vendor('JsPay.JsApiPay');//导入类库
        Vendor('weixin.jssdk');
        $WxPayConfig = new \WxPayConfig();
        /*$tools = new \JsApiPay();
        $openid = $tools->GetOpenid();
        setcookie('openid',$openid,time()+3600*2);
        $this->openid = $openid;*/
        $this->openid = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        $user = M('users');
        $users = $user->where(array('user_status'=>1,'user_type'=>1,'openid'=>trim($this->openid)))->find();
        if(!$users){
            header("Location:".U("Rose2Login/binding"));
            exit();
        }
        $jssdk = new \JSSDK($WxPayConfig::APPID, $WxPayConfig::APPSECRET);
        $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
        $this->assign('signPackage',$signPackage);
        $this->user_id = $users['id'];
    }
}