<?php
require_once("Wap/WxPay.JsApiPay.php");
class V_2BaseAction extends Action
{
    public $openid = null;
    public $buyer_id = null;
    public $quotient_id = null;
    public $wxusers = null;
    public $user_id = null;
    public $tpl =null;
    public $borwser =null;
    public $APPID =null;
    public $autoShare = false;

    protected function _initialize()
    {
    	$this->assign('site_url', $site_url = C('site_url'));
    	$this->assign('cur_url', $cur_url = urlencode(__SELF__));
    	$this->assign('STATICS_URL', C('site_url') . '/tpl/Wap/default/');
        //用户id，id是微信id或者支付宝id
        if($_REQUEST['user_id']){
            $this->user_id =$_REQUEST['user_id'];
        }
        if($_REQUEST['borwser']){
            $this->borwser =$_REQUEST['borwser'];
        }
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

