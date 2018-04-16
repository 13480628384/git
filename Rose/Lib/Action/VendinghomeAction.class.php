<?php
session_start();
require_once("Wap/WxPay.JsApiPay.php");
class VendinghomeAction extends Action
{
    public $openid = null;
    public $user_id = null;
    public $APPID =null;

    protected function _initialize()
    {
        $this->assign('site_url', $site_url = C('site_url'));
        $this->assign('cur_url', $cur_url = urlencode(__SELF__));
        $this->assign('STATICS_URL', C('site_url') . '/tpl/Wap/default/');
        $res = M('sys_user')->where(array('del_flag'=>0,'openid'=>session('openid')
        ,'no'=>'售货机','id'=>session('uid')))->find();
        if(!$res){
            header("Location:".U("VendLogin/login"));
            exit();
        }
        $this->user_id = $res['id'];
        $this->openid = $res['openid'];
        /*
         * 引入微信js接口
        */
        Vendor('weixin.jssdk');
        //vendor("ShortMessage.TopSdk");
        $this->APPID = WxPayConfig::APPID;
        $this->assign('appid',$this->APPID);
        $jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
        $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
        $this->assign('signPackage',$signPackage);
    }
}
?>

