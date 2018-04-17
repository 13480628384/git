<?php
require 'devicecommand/OneNetApi.php';
include "mysql/mysqldbwrite.php";
$apiurl = 'http://api.heclouds.com';
$apikey_wwj = '3kP53vnYuE5ffMmg0sre6TbYVWc=';
function update_device_online_status($db,$apiurl,$apikey,$type){
    $sm = new OneNetApi($apikey, $apiurl);
    $next_number = '';
    $sum=0;
    $array = array();
    $num = 1;
    $number = 1;
    //查询所有在线的设备id
    while(true){
        if($num>20){
            echo json_encode(array('msg'=>500,'code'=>'参数有误'));
            exit;
        }
        $data = $sm->device_list($num,30,NULL,NULL,NULL);
        //print_r($data);echo $num;die;
        foreach($data['devices'] as $key=>$v){
            $array[] = $v['id'];
        }
        if($sum==$data['total_count']){
            break;
        }
        $num++;
        $sum += $number;
    }
    //更新所有已经在线的设备
    $imp  = "'" . implode("','", $array) . "'"  ;
    $update_sql = "update goods_vending set online_status = 0 where  del_flag=0 and  device_command in($imp)";
    $result2 = $db->query($update_sql);
    if($result2){
        echo(json_encode(array('code'=>200,'msg'=>$num.'type'.$type)));
    }else{
        echo (json_encode(array('code'=>500,'msg'=>$num.'type'.$type)));
    }

}
update_device_online_status($db,$apiurl,$apikey_wwj,'1');
?>