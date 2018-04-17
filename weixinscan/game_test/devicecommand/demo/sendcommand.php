<?php

require '../OneNetApi.php';

$apikey = '3Ta83OdKsljZzlz5H0ImSa0nNok=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

$device_id = '3780776';//'3264608';
$qos = '1'; //1需要响应  0 不需要响应
$timeout = '0';//为“秒”，默认“0”
$sms = array("TG_NUM"=>1);
$result = $sm->send_data_to_edp($device_id, $qos, $timeout, $sms);
$error_code = 0;
$error = '';


$result = $sm->send_data_to_edp($device_command, $qos, $timeout, $sms);
$error_code = 0;
$error = '';


//返回值
$return_result = "0";
if (empty($result)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
	
	echo $error;
	
}
if($result){
	$return_result= $result['cmd_uuid'];
	echo  $return_result;
} else {
	echo "0";
	return;
}



/*

if (empty($result)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}


if($result){
	$response_result = $sm->get_dev_status_resp($result['cmd_uuid']);	
	echo $response_result;	
} else {
	echo "false";
}



*/

?>