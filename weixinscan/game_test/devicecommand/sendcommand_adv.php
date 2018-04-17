<?php

ini_set('date.timezone','Asia/Shanghai');

require_once('../mysql/mysqldbwrite.php');
require 'OneNetApi.php';

require_once '../wxpay/log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$device_id = isset($_POST['device_id'])?$_POST["device_id"]:'';//对应device_info id 
$device_command = isset($_POST['device_command'])?$_POST["device_command"]:'';//对应硬件的id

if($device_id == '' || $device_command == ''){
	echo "0";
	return ;
}




//产品rose_init 的master apkKey 
//$apikey = '3Ta83OdKsljZzlz5H0ImSa0nNok=';
$apikey = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';

$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

//$device_command = '3264608';
$qos = '1'; //1需要响应  0 不需要响应
$timeout = '0';//为“秒”，默认“0”
$sms = array("TG_NUM"=>1);
$result = $sm->send_data_to_edp($device_command, $qos, $timeout, $sms);
$error_code = 0;
$error = '';


//返回值
$return_result = "0";
if (empty($result)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
	
}
if($result){
	$return_result= $result['cmd_uuid'];
} else {
	echo "0";
	return;
}


//随机数生产函数：
//获取唯一序列号 时间前缀
 function generateNum() {
    //strtoupper转换成全大写的
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
    return date('YmdH_',time()).$uuid;
}

$table_id =generateNum();


//暂时不优化事务一致性的问题command_info 及pay_weixin_info 及时插入
$now = date("Y-m-d H:i:s");


//新增指令记录
$command_info = array(
	'id' => $table_id,
	'cmd_id' => $return_result,
	'di_id' => $device_id,		
	'deivce_command' =>$device_command ,
	'status' => '1',	
	'resp_status'=>'100',
	'create_date'=>$now,
	'update_by'=>'1',
	'update_date'=>$now	
);
$insert_command_result = $db->query("INSERT INTO command_info SET " .$db->get_set($command_info));

echo $insert_command_result;

