<?php 
//加载用户余额
ini_set('date.timezone','Asia/Shanghai');
require_once('mysql/mysqldbwrite.php');
$app_id = $_POST["app_id"];
$from_username = $_POST["open_id"];



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
echo $counts;

?>

