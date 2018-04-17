<?php 
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';


//获取唯一序列号 时间前缀
 function generateNum() {
    //strtoupper转换成全大写的
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
    return date('YmdH_',time()).$uuid;
}

function generateSuffix() {
    //strtoupper转换成全大写的
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid =substr($charid, 0, 8).substr($charid, 8, 4);
    return '_'.$uuid;
}


$total_price = $_POST['total_price'];
$openId = $_POST['open_id'];
$out_trade_no = date("YmdHis").generateSuffix();


//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//①、获取用户openid

$tools = new JsApiPay();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("深圳玫瑰物联-智能终端充值：".$total_price."元");
$input->SetAttach("深圳玫瑰物联技术有限公司提供");
$input->SetOut_trade_no($out_trade_no);//商户订单号
$input->SetTotal_fee($total_price*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("梦想广加");
// /home/wwwroot/wxpay.roseo2o.cn/weixinscan/game/wxpay/notify.php
$input->SetNotify_url("http://wxpay.roseo2o.com/weixinscan/game_test/wxpay/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);


require_once('../mysql/mysqldbwrite.php');

$app_id = $order["appid"];
/* old 
$pay_info = array(
	'id' => generateNum(),
	'app_id' => $app_id,
	'from_username' => $openId,
	'type'=>'1',
	'pay_status'=>'0',
	'out_trade_no'=>$out_trade_no,
	'pay_account' => $total_price,
	'contents' => $jsApiParameters
);

//  1:插入  0：不插入
$result = $db->query("INSERT INTO pay_weixin_info SET " .$db->get_set($pay_info));

*/


$now = date("Y-m-d H:i:s");

$weixin_pay_rec = array(
	'id' => generateNum(),
	'app_id' => $app_id,
	'from_username' => $openId,	
	'pay_status'=>'0',
	'out_trade_no'=>$out_trade_no,
	'pay_account' => $total_price,
	'contents' => $jsApiParameters,
	'create_date'=>$now,
	'create_by'=>'1',
	'update_by'=>'1',
	'update_date'=>$now	
);

$weixin_pay_rec_result = $db->query("INSERT INTO weixin_pay_rec SET " .$db->get_set($weixin_pay_rec));



if(0 == $weixin_pay_rec_result ){
    echo "0";
} else {
	//封装return_result
	$return_result = array('jsApiParameters'=>$jsApiParameters,'outTradeNo'=>$out_trade_no);
    echo json_encode($return_result);
}

?>

