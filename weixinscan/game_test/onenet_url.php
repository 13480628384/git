<?php
require_once('mysql/mysqldbwrite.php');
ini_set("display_errors","On");   
error_reporting(E_ALL);  
header("Content-type:text/html;charset=utf-8");
//scandir函数在php.ini 开启
include("wxpay/log.php");
//初始化日志
$logHandler= new CLogFileHandler("logs/".date('m-d').'.log');
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
}
if(isPost()){
	$body = @file_get_contents('php://input');
	$body_obj = json_decode($body);
	$dev_id = $body_obj->msg->dev_id;
	$type = $body_obj->msg->type;
	
	Log::DEBUG('onenet post2:'.$body);
	if($type == 2){
		$status = $body_obj->msg->status;
		if($status == 1){
			$result = $db->query("update device_relation_group set online_status=1 where device_command='$dev_id' and del_flag=0");
			//Log::DEBUG('onenet post:' . ($status));	
		}else if($status == 0){
			$result2 = $db->query("update device_relation_group set online_status=0 where device_command='$dev_id' and del_flag=0");
		}
	} else if ( $type == 1 ){
		function generateNums() {
			$charid = strtoupper(md5(uniqid(mt_rand(), true)));
			$uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
			return date('YmdH_',time()).$uuid;
		}
		$ds_id = $body_obj->msg->ds_id;
		$date = date('Y-m-d H:i:s',time());
		/*====找出某个设备昨天的数量===*/
		$YesterdayCountDevicesOne = $db->get_row("select * from device_record
			where TO_DAYS(NOW()) - TO_DAYS(create_date) = 1 and dev_id='$dev_id'");
		if(empty($YesterdayCountDevicesOne)){
			$YesterdayCountDevicesOne->EDP_CON = 0;
			$YesterdayCountDevicesOne->VAL_OUT = 0;
			$YesterdayCountDevicesOne->SEV_OP = 0;
			$YesterdayCountDevicesOne->LOC_OP = 0;
		}
		/*===找出今天某个设备的总数量==*/
		$value = $body_obj->msg->value;
		$device_type = '';
		switch($ds_id){
			case 'EDP_CON';/*=======设备每次登陆onenet次数========*/
				$device_type = 1;
				break;
			case 'VAL_OUT';/*===========出货数===========*/
				$device_type = 2;
				break;
			case 'SEV_OP';/*===========线上启动数========*/
				$device_type = 3;
				break;
			case 'LOC_OP';/*============本地投币数========*/
				$device_type = 4;
				break;
		}
		$del = $db->get_row("Select dev_id From device_record Group By id Having Count(*) >1");
		$del_result = $db->query("delete from device_record where dev_id='$del->id' limit 1");
		/*==今天的设备数减去昨天的设备总数量得到今天的数==*/
		$EDP_CON = intval($value-$YesterdayCountDevicesOne->EDP_CON);
		$VAL_OUT = intval($value-$YesterdayCountDevicesOne->VAL_OUT);
		$SEV_OP  = intval($value-$YesterdayCountDevicesOne->SEV_OP);
		$LOC_OP  = intval($value-$YesterdayCountDevicesOne->LOC_OP);
		/*==判断今天是否已插入设备数，如果有更新，没有则插入==*/
		$TodayCountDevicesOne = $db->get_row("select * from device_record where create_date>curdate() and dev_id='$dev_id'");
		if( $device_type == 1 ){
			if($TodayCountDevicesOne){
				$res = $db->query("update device_record set
			every_login='$EDP_CON',
			update_date='$date',EDP_CON='$value'
  			where create_date>curdate() and dev_id='$dev_id'");
			}else{
				$datas['id'] = generateNums();
				$datas['every_login'] = $EDP_CON;
				$datas['create_date'] = $date;
				$datas['dev_id'] = $dev_id;
				$datas['update_date'] = $date;
				$datas['EDP_CON'] = $value;
				$res2 = $db->query("insert into device_record set ".$db->get_set($datas));
			}
		} else if( $device_type == 2 ){
			if($TodayCountDevicesOne){
				$res = $db->query("update device_record set
			Unit_shipment='$VAL_OUT',
			update_date='$date',VAL_OUT='$value'
  			where create_date>curdate() and dev_id='$dev_id'");
			}else{
				$datas['id'] = generateNums();
				$datas['Unit_shipment'] = $VAL_OUT;
				$datas['create_date'] = $date;
				$datas['dev_id'] = $dev_id;
				$datas['update_date'] = $date;
				$datas['VAL_OUT'] = $value;
				$res2 = $db->query("insert into device_record set ".$db->get_set($datas));
			}
		}else if( $device_type == 3 ){
			if($TodayCountDevicesOne){
				$res = $db->query("update device_record set
			Server_startup='$SEV_OP',
			update_date='$date',SEV_OP='$value'
  			where create_date>curdate() and dev_id='$dev_id'");
			}else{
				$datas['id'] = generateNums();
				$datas['Server_startup'] = $SEV_OP;
				$datas['create_date'] = $date;
				$datas['dev_id'] = $dev_id;
				$datas['update_date'] = $date;
				$datas['SEV_OP'] = $value;
				$res2 = $db->query("insert into device_record set ".$db->get_set($datas));
			}
			//Log::DEBUG(generateNums().'---'.$SEV_OP.'----'.$date.'---'.$dev_id.'---'.$value.'000000000000');
		}else if( $device_type == 4 ){
			if($TodayCountDevicesOne){
				$res = $db->query("update device_record set
			Number_coins='$LOC_OP',
			update_date='$date',LOC_OP='$value'
  			where create_date>curdate() and dev_id='$dev_id'");
			}else{
				$datas['id'] = generateNums();
				$datas['Number_coins'] = $LOC_OP;
				$datas['create_date'] = $date;
				$datas['dev_id'] = $dev_id;
				$datas['update_date'] = $date;
				$datas['LOC_OP'] = $value;
				$res2 = $db->query("insert into device_record set ".$db->get_set($datas));
			}
		}
	}
	
	
	
}	
?>