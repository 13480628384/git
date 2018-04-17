<?php
session_start();
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
$out_trade_no = WxPayConfig::MCHID.date("YmdHis");
//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
$tools = new JsApiPay();
$input = new WxPayUnifiedOrder;
$input->Setpartner_trade_no($out_trade_no);
$input->Setcheck_name("NO_CHECK");
$input->Setamount(1);;
$input->Setdesc("技术测试");
$input->Setspbill_create_ip("127.0.0.1");
$input->SetOpenid("og5WUjmApU2pOqbZlxrppXCNhsio");
$input->SetNotify_url("http://v.sniperchw.com/servicepay/notify.php");
$order = WxPayApi::transfers($input);
echo $order;die;
$jsApiParameters = $tools->GetJsApiParameters($order);
print_r($order);echo '<hr/>';
echo $jsApiParameters;
echo '<hr/>';

?>

