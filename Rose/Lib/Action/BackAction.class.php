<?php
require_once "Wap/Alipay/function.php";
require_once "Wap/Alipay/AopSdk.php";
require_once("Wap/WxPay.JsApiPay.php");
class BackAction extends PayAction{
    public $sm = NULL;
    public $other = NULL;
    public $juicer = NULL;
    public $charger = NULL;
    public $machine = NULL;
    public $washing = NULL;
    public $ceji = NULL;
    public $vehicle = NULL;
    public $zizhu = NULL;
    public $vending_start = NULL;
    public $hyzn = NULL;
    public $glass = NULL;
    public $zhijin = NULL;
    public $chaxie = NULL;
     protected function _initialize(){
         Vendor('weipay.WxPayPubHelper.WxPayPubHelper');
         Vendor('weipay.WxPayPubHelper.WxPay.pub.config');
         Vendor('weixin.OneNetApi');
         $JS_API_CALL_URL = C('JS_API_CALL_URL');
         $NOTIFY_URL = C('WEIXIN_NOTIFY_URL');
         $this->NOTIFY_URL = $NOTIFY_URL;
         $SSLCERT_PATH = './upload/cert/apiclient_cert.pem';
         $SSLKEY_PATH = './upload/cert/apiclient_key.pem';
         WxPayConf_pub::set_config("wx6a9d4afeace38e53","1379294702","BA01B5A812474D21B585F1D27A52636D"
             ,"5afdc3fc90568ed1da990963110bcbb4",$JS_API_CALL_URL,
             $SSLCERT_PATH,$SSLKEY_PATH,$NOTIFY_URL,60);
         parent::_initialize();
         //娃娃机apikey
         $apikey = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';
         $apiurl = 'http://api.heclouds.com';
         $sm = new OneNetApi($apikey, $apiurl);
         $this->sm =$sm;
        //按摩椅apikey
         $apikey1 = 'dkzIPZejUbsvxjz3SuXO111O3Qw=';
         $other = new OneNetApi($apikey1, $apiurl);
         $this->other =$other;
         //充电器apikey
         $apikey2 = 'QdDqTBG=fIRkz25RzXFUQf=hMp0=';
         $other1 = new OneNetApi($apikey2, $apiurl);
         $this->charger =$other1;
         //售货机apikey
         $apikey3 = 'aNR6j1NOK3xOI=nTlvoaqqAh4bw=';
         $other2 = new OneNetApi($apikey3, $apiurl);
         $this->machine=$other2;
         //洗衣机
         $apikeyxi = 'vnH3AAd1oMBNAPBPahnpuHH2L=o=';
         $other3 = new OneNetApi($apikeyxi, $apiurl);
         $this->washing=$other3;
         //小区充电器
         $apikeyxiaoqu = 'Vds=DWm57TTxCIwOn2j3hXq8Czo=';
         $other4 = new OneNetApi($apikeyxiaoqu);
         $this->vehicle = $other4;
         //自助洗车
         $apikeyzizhu = 'WHhxJbOaxk5uOaKVwMqRGMZpMjY=';
         $other5 = new OneNetApi($apikeyzizhu);
         $this->zizhu = $other5;
         //厕纸机
         $apikeyceji = 'o=W3qpk7wqksU9AmzKbDA2RQqkA=';
         $other8 = new OneNetApi($apikeyceji);
         $this->ceji = $other8;
         //榨汁机
         $apikeyjjuicer = 'WXHTne4BIQxki8nCSzmyiZBetNw=';
         $other9 = new OneNetApi($apikeyjjuicer);
         $this->juicer = $other9;
         //售货机
         $apikeyvending_start = '3kP53vnYuE5ffMmg0sre6TbYVWc=';
         $other10 = new OneNetApi($apikeyvending_start);
         $this->vending_start = $other10;

         //单独一个纸巾
         $apikeyhyzh = 'WANmbP735=Dx1OSBj7hg1ZKNwRY=';
         $other11 = new OneNetApi($apikeyhyzh);
         $this->hyzn = $other11;

         //眼保仪
         $apikeyyby = '83XfD3c5PgLVs1f=hIBUTlnFupc=';
         $other12 = new OneNetApi($apikeyyby);
         $this->glass = $other12;

         //纸巾售货机
         $apikeyzhijin = 'NOR4zrZ2QmadbvAEKvjeMFP=eNQ=';
         $other13 = new OneNetApi($apikeyzhijin);
         $this->zhijin = $other13;

         //擦鞋机
         $apikeychaxie = 'NuvLHXoUYxZn99MVEbtVNyjSd9E=';
         $other14 = new OneNetApi($apikeychaxie);
         $this->chaxie = $other14;
     }
    /*
     * 支付宝支付结果信息返回
     * @param return_url 支付同步通知地址
     * @param notify_url 支付异步通知地址
     * @param price 支付价格
     * */
    protected function alipayreturn($return_url,$notify_url,$price,$out_trade_no){
        $aop = new AopClient();
        $aop->gatewayUrl =AlipayConfig::GETWAPURL;
        $aop->appId = AlipayConfig::APPID;
        $aop->rsaPrivateKeyFilePath = constant('CURRENT_FILE_PATH').AlipayConfig::PRIVEKEYFILEPATH;
        $aop->alipayPublicKey = constant('CURRENT_FILE_PATH').AlipayConfig::ALIPAYPUBLICKEY;
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $bizContent = array();
        $bizContent['body'] = '深圳玫瑰充值';
        $bizContent['subject'] = '深圳玫瑰物联-充值';
        $bizContent['out_trade_no']=$out_trade_no;
        $bizContent['timeout_express']='5m';
        $bizContent['total_amount']=$price;
        $bizContent['product_code']='充值用户';
        $bizContent['seller_id']=AlipayConfig::PID;
        $pay_request = new AlipayTradeWapPayRequest ();
        $pay_request->setBizContent(json_encode($bizContent,JSON_UNESCAPED_UNICODE));
        $pay_request->setReturnUrl($return_url);
        $pay_request->setNotifyUrl($notify_url);
        $result = $aop->pageExecute($pay_request);
        return $result;
    }
    /*
     * 微信支付结果信息返回
     * @param openid 用户openid
     * @param money 支付价格
     * @param NOTIFY_URL 支付异步通知地址
     * */
    protected function Weixin_Pay_Result($openid,$money,$NOTIFY_URL,$out_trade_no){
        $jsApi = new JsApi_pub();
        $unifiedOrder = new UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid",$openid);//商品描述
        $unifiedOrder->setParameter("body",'深圳玫瑰物联-充值');//商品描述
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
 ?>