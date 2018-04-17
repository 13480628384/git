<?php
ini_set('date.timezone','Asia/Shanghai');
require_once('mysql/mysqldbwrite.php');



$app_id = $_POST["app_id"];
$out_trade_no = $_POST["out_trade_no"];
$from_username = $_POST["open_id"];



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



//更新微信用户信息表
//1.查询总充值
$now = date("Y-m-d H:i:s");

$weixin_pay_total_sql  = "select sum(p.pay_account) pay_accounts from weixin_pay_rec p where pay_status = '1'  and is_close = '0'  and del_flag = '0' and p.app_id = '$app_id' and p.from_username = '$from_username' " ;

$weixin_pay_total_result = $db->get_var($weixin_pay_total_sql);
//2.更新总充值update_by   2标示js更新
$weixin_userinfo_account = array(
	'pay_total_account' => $weixin_pay_total_result ,
	'update_date' =>$now,
	'update_by'=>'2'
);			
$weixin_userinfo_where  = array('app_id'=>$app_id,'from_username'=>$openId);			
$update_weixin_userinfo_sql = "update weixin_userinfo Set ".$db->get_set($weixin_userinfo_account)."  WHERE ". get_where($weixin_userinfo_where) ;


$update_weixin_userinfo_result = $db->query($update_weixin_userinfo_sql);
echo $update_weixin_userinfo_result;	

?>

