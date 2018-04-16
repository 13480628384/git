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
        $cid = $db->query("update weixin_pay_rec set pay_status=1,transaction_id='$paydata[transaction_id]' where out_trade_no='$paydata[out_trade_no]' and from_username='$paydata[openid]'");
        Log::DEBUG(json_encode($paydata));
        $cid = $db->query("update device_consume_rec set command_status=2,consume_status=1 where cmd_uuid='$paydata[out_trade_no]' and update_by='$paydata[openid]' and type=15");
        //2.更新总充值 update_by 3 标示异步更新 total_fee
        $weixin_pay_total_sql  = "select sum(p.pay_account) pay_accounts from weixin_pay_rec p where pay_status = '1'  and is_close = '0'  and del_flag = '0' and p.app_id = '$paydata[appid]' and p.from_username = '$paydata[openid]' " ;
        $weixin_pay_total_result = $this->db->get_var($weixin_pay_total_sql);
        $date = date("Y-m-d H:i:s");
        $uid = $db->query("update weixin_userinfo set pay_total_account='$weixin_pay_total_result',update_date='$date',create_by=3 where app_id='$paydata[appid]' and from_username='$paydata[openid]'");
    }
}else{
}
?>