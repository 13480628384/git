<?php
require 'config.php';
require 'OneNetApi.php';
require '/home/wwwroot/wxpay.roseo2o.com/weixinscan/game_test/mysql/mysqldbwrite.php';
$apikey_vending = '3kP53vnYuE5ffMmg0sre6TbYVWc=';
$cmd_records = $db->get_results("SELECT cmd_id FROM vending_command_info where status = '1' and resp_status = '100' and create_date > '2016-12-01' and create_date <= NOW() - interval 2 minute  and TO_DAYS( NOW( )) - TO_DAYS( create_date) <= 1 ORDER BY create_date desc  limit 100 ");
if(is_null($cmd_records) || count($cmd_records)==0){
    exit();
}
$vending = new OneNetApi($apikey_vending, $apiurl);
$results = array();
$results_remark = array();
foreach ( $cmd_records as $cmd_record )
{
    $is_success = $vending->get_dev_status_resp($cmd_record->cmd_id);
    if( $vending->http_code()== '200' && $is_success){
        $results[$cmd_record->cmd_id]='200';
        $results_remark[$cmd_record->cmd_id]=$vending->raw_response();
    }else {
        if($vending->http_code()== '200'){
            $results[$cmd_record->cmd_id]= '520';
            $results_remark[$cmd_record->cmd_id]=$vending->error();
        } else {
            $results[$cmd_record->cmd_id]= '221';
            $results_remark[$cmd_record->cmd_id]='http erro:'.($vending->http_code());
        }
    }
    usleep(100);
}
if(count($results)==0){
    exit();
}
$result_400 = array();
$result_403 = array();
$result_404 = array();
$result_220 = array();
$result_221 = array();
$result_520 = array();
$result_200 = array();
$result_432 = array();
$result_433 = array();


$success_pay = array();
$fail_pay = array();
//ƴװsql����id����
foreach($results as $key=>$value){
    if($value == '221'){
        $result_221[]=$key;
        $fail_pay[] = $key;
        continue;
    }
    if($value == '520'){
        $result_520[]=$key;
        $fail_pay[] = $key;
        continue;
    }
    if($value == '200'){
        $result_200[]=$key;
        $success_pay[]=$key;
        continue;
    }
}


function change_to_quotes($str) {
    return sprintf("'%s'", $str);
}

$now = date("Y-m-d H:i:s");

if(count($result_221)){
    $sql_221 = "update vending_command_info set  status = '0',resp_status = '221', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_221)).")";
    $update_result = $db->query($sql_221);
}

if(count($result_520)){
    $sql_520 = "update vending_command_info set status = '0', resp_status = '520', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_520)).")";
    $update_result = $db->query($sql_520);
}

if(count($result_200)){
    $sql_200 = "update vending_command_info set resp_status = '200', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_200)).")";
    $update_result = $db->query($sql_200);
}
if(count($success_pay)){
    $success_sql = "update goods_consume_rec  set  command_status = '2',update_date='$now'   where cmd_uuid in(". implode(',',array_map('change_to_quotes',$success_pay)).")";
    //echo $success_sql;
    $update_result = $db->query($success_sql);
}

//ʧ�ܷ���
if(count($fail_pay)){
    $fail_sql = "update goods_consume_rec set  command_status = '3',update_date='$now'   where cmd_uuid in(". implode(',',array_map('change_to_quotes',$fail_pay)).")";
    //echo $fail_sql;
    $update_result = $db->query($fail_sql);
}
?>