<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:46
 */
class NotifyAction extends VendBackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    public function chaxienotify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'weixin_pay2.log';
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
                M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($da);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no']))->save($da);
        }
    }
    public function notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'weixin_pay2.log';
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
                M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($da);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no']))->save($da);
        }
    }
    public function fennotify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'fennotify.log';
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
                M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($da);
                $car['transaction_id'] = $paydata['transaction_id'];
                $car['status'] = '1';
                $car['update_date'] = date('Y-m-d H:i:s',time());
                M('car_pay')->where(array('openid'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($car);

                if($paydata['total_fee'] == '199'){
                    $car_mem['transaction_id'] = $paydata['transaction_id'];
                    $car_mem['status'] = '1';
                    $car_mem['update_date'] = date('Y-m-d H:i:s',time());
                    M('car_member_pay')->where(array('openid'=>$paydata['openid'],
                        'out_trade_no'=>$paydata['out_trade_no']))->save($car_mem);
                }
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('weixin_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no']))->save($da);
            $car['transaction_id'] = $paydata['transaction_id'];
            $car['status'] = '1';
            $car['update_date'] = date('Y-m-d H:i:s',time());
            M('car_pay')->where(array('openid'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no']))->save($car);
            if($paydata['total_fee'] == '199'){
                $car_mem['transaction_id'] = $paydata['transaction_id'];
                $car_mem['status'] = '1';
                $car_mem['update_date'] = date('Y-m-d H:i:s',time());
                M('car_member_pay')->where(array('openid'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($car_mem);
            }
        }
    }
}