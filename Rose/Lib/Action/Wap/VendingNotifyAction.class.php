<?php
class VendingNotifyAction extends VendBackAction
{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    public function weixin_notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'weixin_pay1.log';
        Log::write($xml,'INFO','',$logpath,'');
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        if($notify->checkSign() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['pay_status'] = 1;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                M('goods_weixin_alipay_pay_rec')->where(array('wechat_alipay_id'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no'],'type'=>'1'))->save($da);
                Log::write(json_encode($paydata),'notify','',$logpath,'');

                $rose['status'] = 1;
                $rose['update_date'] = date('Y-m-d H:i:s',time());
                $rose['transaction_id'] = $paydata['transaction_id'];
                M('goods_rose')->where(array('del_flag'=>'0','wechat_alipay_id'=>$paydata['openid'],'type'=>'1',
                    'out_trade_no'=>$paydata['out_trade_no']))->save($rose);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('goods_weixin_alipay_pay_rec')->where(array('wechat_alipay_id'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no'],'type'=>'1'))->save($da);
        }
    }
    //支付宝异步通知
    public function alipay_notify(){
        $logpath = LOG_PATH.'alipay_notify.log';
        Log::write(json_encode(111111111111),'notify','',$logpath,'');
        $aop = new AopClient ();
        $aop->gatewayUrl = AlipayConfig::GETWAPURL;
        $aop->appId = AlipayConfig::APPID;
        $aop->rsaPrivateKeyFilePath = constant('CUL').AlipayConfig::PRIVEKEYFILEPATH;
        $aop->alipayPublicKey=constant('CUL').AlipayConfig::ALIPAYPUBLICKEY;
        $aop->apiVersion = '1.0';
        $async = empty($_GET);
        $data = $_POST;
        if (empty($data)) {
            return FALSE;
        }
        if($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
            $da['transaction_id'] = $data['trade_no'];
            $da['update_date'] = date('Y-m-d H:i:s',time());
            $da['pay_status'] = 1;
            M('goods_weixin_alipay_pay_rec')->where(array('buyer_id'=>$data['buyer_id'],
                'out_trade_no'=>$data['out_trade_no'],'type'=>'2'))->save($da);

            $rose['status'] = 1;
            $rose['update_date'] = date('Y-m-d H:i:s',time());
            $rose['transaction_id'] = $data['trade_no'];
            M('goods_rose')->where(array('del_flag'=>'0','wechat_alipay_id'=>$data['buyer_id'],'type'=>'2',
                'out_trade_no'=>$data['out_trade_no']))->save($rose);
        }
        echo "success";
    }
}