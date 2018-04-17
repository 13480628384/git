<?php
require_once('mysql/mysqldbread.php');
$device_command = $_POST["device_command"];
$query_not_online_sql  = "select * from device_relation_group dg where status='1' and del_flag=0 and device_command='$device_command' and online_status='1' ";
$no_online_result = $db->get_row($query_not_online_sql);
if($no_online_result){
    echo json_encode(array('code'=>200));
} else {
    echo json_encode(array('code'=>500));
}
