<?php
ini_set('date.timezone','Asia/Shanghai');
require_once('mysql/mysqldbwrite.php');
require 'devicecommand/OneNetApi.php';
require_once "wxpay/log.php";
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d H').'.log');
$log = Log::Init($logHandler, 15);
$app_id = $_POST["app_id"];
$out_trade_no = $_POST["out_trade_no"];
$from_username = $_POST["open_id"];
$device_command = $_POST["device_command"];
$device_id = $_POST["device_id"];
$price = intval($_POST["price"]);
$update_field = array(
    'pay_status' => '1'
);
$weixin_pay_rec = array(
    'app_id' => $app_id,
    'from_username' => $from_username,
    'out_trade_no'=>$out_trade_no
);
$update_field = array(
    'pay_status' => '1'
);
$update_sql = "update weixin_pay_rec Set ".$db->get_set($update_field)."  WHERE ". get_where($weixin_pay_rec) ;
$update_result = $db->query($update_sql);


/*=====启动设备 [[==*/
//$apikey = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';
$apikey = 'dkzIPZejUbsvxjz3SuXO111O3Qw=';
$apiurl = 'http://api.heclouds.com';
$sm = new OneNetApi($apikey, $apiurl);
$qos = '1';
$timeout = '0';
$datanum = ($price/24)*1440;
$sms = array("T_M"=>$datanum,'P_Y'=>$price);//{"T_M":1;"P_Y":1}
$result = $sm->send_data_to_edp($device_command, $qos, $timeout, $sms);
if($result){
    $date = date("Y-m-d H:i:s");
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
		'cmd_id' => $result['cmd_uuid'],
		'di_id' => $device_id,		
		'deivce_command' =>$device_command ,
		'status' => '1',	
		'resp_status'=>'100',
		'create_date'=>$now,
		'update_by'=>'1',
		'device_type'=>'4',
		'update_date'=>$now	
	);
	$insert_command_result = $db->query("INSERT INTO command_info SET " .$db->get_set($command_info));
    for ($i = 0; $i < $price; $i++) {
        $weixin_consume_rec = array(
            'id' => generateNum(),
            'app_id' => $app_id,
            'from_username' => $from_username,
            'consume_account' => '1',
            'command_status' => '2',
            'consume_status' => '1',
            'type' => '3',
            'di_id' => $device_id,
            'deivce_command' => $device_command,
            'cmd_uuid' => $result['cmd_uuid'],
            'create_date' => $date,
            'create_by' => $from_username,
            'update_by' => $from_username,
            'update_date' => $date
        );
        $weixin_consume_rec_result = $db->query("INSERT INTO device_consume_rec SET " . $db->get_set($weixin_consume_rec));
    }
    echo json_encode(array('code'=>200,'msg'=>$result['cmd_uuid']));
}else{
    echo json_encode(array('code'=>500,'msg'=>'不工作'.$datanum));
}
/*=====启动设备 ]]==*/
?>