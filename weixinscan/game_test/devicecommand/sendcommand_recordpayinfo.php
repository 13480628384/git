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
//insert pay_weixin_info
$app_id = $_POST["app_id"];
$from_username = $_POST["open_id"];
$price=$_POST["price"];

/*===============查询余额是否足够 [[=================*/
$pay_account_sql = "select sum(pay_account) total_account from weixin_pay_rec wu where pay_status = '1' and is_close=0 and del_flag = 0 and from_username = '$from_username'";
$wei_pay = $db->get_row($pay_account_sql);

$pay_account_sql = "select sum(consume_account) consume_account from device_consume_rec wu where command_status in (1,2)  and is_close=0 and del_flag = 0 and from_username = '$from_username'";
$wei_consume = $db->get_row($pay_account_sql);

$count = $wei_pay->total_account-$wei_consume->consume_account;
if($count<=0){
    $counts = 0;
}else{
    $counts = intval($count);
}
if($counts <= 0){
	exit(2);
}
/*===============查询余额是否足够 ]]=================*/
//产品rose_init 的master apkKey
//$apikey = '3Ta83OdKsljZzlz5H0ImSa0nNok=';
$apikey = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';
$apiurl = 'http://api.heclouds.com';


//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

//$device_command = '3264608';
$qos = '1'; //1需要响应  0 不需要响应
$timeout = '0';//为“秒”，默认“0”
$sms = array("TG_NUM"=>intval($price));
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
//$return_result = 1582423;
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

$owner_id = $db->get_row("select owner_id from device_info where id='$device_id' and del_flag=0");


    //新增消费记录
	$weixin_consume_rec = array(
		'id' =>  generateNum(),
		'app_id' => $app_id,
		'from_username' => $from_username,
		'consume_account' =>$price,	//消费金额
		'command_status'=>'1',
		'consume_status'=>'1',
		'type'=>'1',
		'di_id'=>$device_id,
		'deivce_command'=>$device_command,
		'cmd_uuid'=>$return_result,
		'create_date'=>$now,
		'create_by'=>$owner_id->owner_id,
		'update_by'=>'1',
		'update_date'=>$now
	);
	$weixin_consume_rec_result = $db->query("INSERT INTO device_consume_rec SET " .$db->get_set($weixin_consume_rec));


$weixin_consume_accounts_sql  = "select sum(p.consume_account) consume_accounts from device_consume_rec p where command_status in('1','2')  and is_close = '0'  and del_flag = '0' and p.app_id = '$app_id' and p.from_username = '$from_username' " ;
$weixin_consume_accounts_result = $db->get_var($weixin_consume_accounts_sql);



//2.更新总充值update_by   4标示消费更新
$weixin_userinfo_account = array(
	'consume_total_account' => $weixin_consume_accounts_result ,
	'update_date' =>$now,
	'update_by'=>'4'
);
$weixin_userinfo_where  = array('app_id'=>$app_id,'from_username'=>$from_username);
$update_weixin_userinfo_sql = "update weixin_userinfo Set ".$db->get_set($weixin_userinfo_account)."  WHERE ". get_where($weixin_userinfo_where) ;
$update_weixin_userinfo_result = $db->query($update_weixin_userinfo_sql);

echo $return_result;

