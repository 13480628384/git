<?php
require_once "../Lib/Action/Wap/Alipay/function.php";
require_once("../Core/Extend/Vendor/weixin/log.php");
require_once("mysqldbwrite.php");
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'-alipay.log');
define('CURRENT_FILE_PATH',dirname(dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"].'/').'/').'/'.'Lib/Action/Wap/');
$log = Log::Init($logHandler, 15);
$aop = new AopClient ();
$aop->gatewayUrl = AlipayConfig::GETWAPURL;
$aop->appId = AlipayConfig::APPID;
$aop->rsaPrivateKeyFilePath = constant('CURRENT_FILE_PATH').AlipayConfig::PRIVEKEYFILEPATH;
$aop->alipayPublicKey=constant('CURRENT_FILE_PATH').AlipayConfig::ALIPAYPUBLICKEY;
$aop->apiVersion = '1.0';
$async = empty($_GET);
$data = $async ? $_POST : $_GET;
if (empty($data)) {
    return FALSE;
}
$verify_result = $aop->rsaCheckV1($data,$aop->alipayPublicKey);
if($verify_result) {
    $date = date('Y-m-d H:i:s',time());
    if($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
        //更新支付信息
        $app_id = AlipayConfig::APPID;
        $result2 = $db->query("update alipay_pay_rec set trade_status='TRADE_SUCCESS'
		,trade_no='$data[trade_no]',update_date='$date',notify_time='$date' where  buyer_id='$data[buyer_id]'
 			and out_trade_no='$data[out_trade_no]' and del_flag=0");
    }
}