<?php
$typesd = $_SERVER['HTTP_USER_AGENT'] ;
ini_set('date.timezone','Asia/Shanghai');
require_once "wxpay/lib/WxPay.Api.php";
require_once "wxpay/WxPay.JsApiPay.php";
$device_code = $_GET['device_code'];
if(strstr($device_code,'/vending/')){
    $last = explode('/',$device_code);
    $device_code = $last[2];
}
if(strpos($typesd,'AlipayClient')>0){
    $url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=VendingAlipay&a=index&device_code=$device_code";
    header("Location:".$url);
    exit();
}else{
    $tools = new JsApiPay();
    $openId = $tools->GetOpenid();
    $wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=VendingWeixin&a=index&openid=$openId&device_code=$device_code";
    header("Location:".$wei_url);
    exit;
}