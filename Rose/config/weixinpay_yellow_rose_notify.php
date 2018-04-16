<?php
require_once("../Core/Extend/Vendor/weipay/WxPayPubHelper/WxPayPubHelper.php");
require_once("../Core/Extend/Vendor/weipay/WxPayPubHelper/WxPay.pub.config.php");
require_once("../Core/Extend/Vendor/weixin/log.php");
require_once("mysqldbwrite.php");
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//使用通用通知接口
$notify = new Notify_pub();
//存储微信的回调
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$notify->saveData($xml);
//验证签名，并回应微信。
//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
//尽可能提高通知的成功率，但微信不保证通知最终能成功。
// $this->log_result("【checkSign】:\n".$notify->checkSign()."\n");
if($notify->checkSign() == FALSE){
    $notify->setReturnParameter("return_code","FAIL");//返回状态码
    $notify->setReturnParameter("return_msg","签名失败");//返回信息
}else{
    $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
}
$returnXml = $notify->returnXml();
echo $returnXml;
// $this->log_result("【返回回调信息】:\n".$returnXml."\n");
//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
//以log文件形式记录回调信息
if($notify->checkSign() == TRUE)
{
    $paydata = $notify->getData();
    if ($notify->data["return_code"] == "FAIL") {
        //此处应该更新一下订单状态，商户自行增删操作
        Log::DEBUG(json_encode('【return_code通信出错】'.$xml));
    }
    elseif($notify->data["result_code"] == "FAIL"){
        //此处应该更新一下订单状态，商户自行增删操作
        Log::DEBUG(json_encode('【return_code业务出错】'.$xml));
    }
    else{
        //此处应该更新一下订单状态，商户自行增删操作
        $cid = $db->query("update rose_eco_business_recharge_record set pay_status=1,transaction_id='$paydata[transaction_id]' where type=1 and out_trade_no='$paydata[out_trade_no]' and user_id='$paydata[openid]'");
        Log::DEBUG(json_encode($paydata));
        //2.更新总充值 update_by 3 标示异步更新 total_fee
        //$weixin_pay_total_sql  = "select sum(account) pay_accounts from rose_eco_business_recharge_record  where pay_status = '1'  and del_flag = '0' and p.type in(5,6) and p.user_id = '$paydata[openid]' " ;
        //$weixin_pay_total_result = $this->db->get_var($weixin_pay_total_sql);
        //$date = date("Y-m-d H:i:s");
        //$uid = $db->query("update rose_user_info set ecological_red_rose='$weixin_pay_total_result',update_date='$date' where del_flag=0 and openid='$paydata[openid]'");
    }
}else{
}
?>