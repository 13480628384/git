<?php
    namespace app\index\controller;
    use Think\Db;
    use think\Request;
    use think\view\driver\Think;
    class WeiXinPay {
        public function notify(){
            $notify = new \Notify_pub();
            $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
            $logpath = LOG_PATH.'weixin_pay2.log';
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
                    $da['status'] = 1;
                    $da['update_date'] = date('Y-m-d H:i:s',time());
                    M('place')->where(array('openid'=>$paydata['openid'],
                        'out_trade_no'=>$paydata['out_trade_no']))->save($da);
                }
            }else{
                $paydata = $notify->getData();
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['status'] = 1;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                M('weixin_pay_rec')->where(array('openid'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($da);
            }
        }
    }