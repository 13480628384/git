<?php

require_once('mysql/mysqldbread.php');


$device_command = $_POST["device_command"];

//查询有不在线，则返回1 
$query_not_online_sql  = "select COUNT(1) from device_relation_group dg where status='1'  and device_command='$device_command' and online_status='0' ";

$no_online_result = $db->get_var($query_not_online_sql);

if(1 == $no_online_result){
    echo "0";
    exit;
}
echo "1";
?>

