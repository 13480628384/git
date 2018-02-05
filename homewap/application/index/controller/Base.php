<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Base extends Controller {
    protected $openid = null;
    protected function _initialize(){
        Vendor('weipay.WxPayPubHelper.WxPayPubHelper');
        Vendor('weipay.WxPayPubHelper.WxPay.pub.config');
        $JS_API_CALL_URL = 'JS_API_CALL_URL';
        $NOTIFY_URL = 'WEIXIN_NOTIFY_URL';
        $this->NOTIFY_URL = $NOTIFY_URL;
        $SSLCERT_PATH = './upload/cert/apiclient_cert.pem';
        $SSLKEY_PATH = './upload/cert/apiclient_key.pem';
        \WxPayConf_pub::set_config("wx6a9d4afeace38e53","1379294702","BA01B5A812474D21B585F1D27A52636D"
            ,"5afdc3fc90568ed1da990963110bcbb4",$JS_API_CALL_URL,
            $SSLCERT_PATH,$SSLKEY_PATH,$NOTIFY_URL,60);
        $openid = session('openid');
       // $openid = 'odOIPv0FULcQz0pfsKpnf88N9NXU';
        $this->openid = 'odOIPv0FULcQz0pfsKpnf88N9NXU';
        /*$user_result = Db::table('weixin_userinfo')->where(array('openid'=>$openid))->find();
        $this->openid = $openid;
        if(!$user_result || empty($openid)){
            $this->redirect('Login/resigter');
            exit();
        }
        $this->user_id = $user_result['id'];
        $this->assign([
            'openid'=>$user_result['openid'],
        ]);*/
    }
    /*
   * 微信支付结果信息返回
   * @param openid 用户openid
   * @param money 支付价格
   * @param NOTIFY_URL 支付异步通知地址
   * */
    protected function Weixin_Pay_Result($openid,$money,$NOTIFY_URL,$out_trade_no){
        $jsApi = new \JsApi_pub();
        $unifiedOrder = new \UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid",$openid);//商品描述
        $unifiedOrder->setParameter("body",'红家君助');//商品描述
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee",$money);//总金额
        $unifiedOrder->setParameter("notify_url",$NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        return $jsApiParameters;
    }
}