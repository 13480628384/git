<?php
require_once('mysql/mysqldbwrite.php');
ini_set("display_errors","On");   
error_reporting(E_ALL);  
header("Content-type:text/html;charset=utf-8");
//scandir函数在php.ini 开启
include("wxpay/log.php");
//初始化日志
$logHandler= new CLogFileHandler("logs/vending/".date('Y-m-d').'-massage.log');
$log = Log::Init($logHandler, 15);
function isGet(){
	return isset($_SERVER['REQUEST_METHOD'] ) && !strcasecmp($_SERVER['REQUEST_METHOD'],'GET');
}
function isPost(){
	return isset($_SERVER['REQUEST_METHOD'] ) && !strcasecmp($_SERVER['REQUEST_METHOD'],'POST');
}
if(isGet()){
	$data = $_GET;
	
	
	Log::DEBUG('onenet get:' . json_encode($data));
	
	exit($data['msg']);
	
	
}
if(isPost()){
	$body = @file_get_contents('php://input');
	$body_obj = json_decode($body);
	$dev_id = $body_obj->msg->dev_id;
	$type = $body_obj->msg->type;
	
	Log::DEBUG('onenet post2:'.$body);
	if($type == 2){
		$status = $body_obj->msg->status;
		
	}
	
}	
?>