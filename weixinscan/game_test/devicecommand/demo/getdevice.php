<?php

/*
require '../OneNetApi.php';

$apikey = '3Ta83OdKsljZzlz5H0ImSa0nNok=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);

$device_id = '3264608';
$device = $sm->device($device_id);
$error_code = 0;
$error = '';
if (empty($device)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}

//展现设备
var_dump($device);