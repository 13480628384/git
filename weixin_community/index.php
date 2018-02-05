<?php
header('Content-Type:text/html;charset=utf-8');
define('APP_DEBUG',TRUE);//开启错误信息提示
date_default_timezone_set('PRC');
define('APP_NAME','APP');
define('APP_PATH','./APP/');
require "ThinkPHP/ThinkPHP.php";
?>