<?php
class BackSubAction extends PayAction{
    public $sm = NULL;
    public $other = NULL;
    public $juicer = NULL;
    public $charger = NULL;
    public $machine = NULL;
    public $washing = NULL;
    public $ceji = NULL;
    public $vehicle = NULL;
    public $zizhu = NULL;
    protected function _initialize(){
        Vendor('weipay.WxPayPubHelper.WxPayPubHelper');
        Vendor('weipay.WxPayPubHelper.WxPay.pub.config');
        Vendor('weixin.OneNetApi');
        $JS_API_CALL_URL = C('JS_API_CALL_URL');
        $NOTIFY_URL = C('WEIXIN_NOTIFY_URL');
        $this->NOTIFY_URL = $NOTIFY_URL;
        $SSLCERT_PATH = './upload/cert/apiclient_cert.pem';
        $SSLKEY_PATH = './upload/cert/apiclient_key.pem';
        WxPayConf_pub::set_config("wx6a9d4afeace38e53","1380376102","BA01B5A812474D21B585F1D27A596541"
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
    }
}
?>