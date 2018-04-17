<?php
    require 'devicecommand/OneNetApi.php';
    require_once('mysql/mysqldbwrite.php');
    ini_set("display_errors","On");
    error_reporting(E_ALL);
    header("Content-type:text/html;charset=utf-8");
    require_once "util.php";
    include("wxpay/log.php");
    $logHandler= new CLogFileHandler("logs/glass/".date('Y-m-d').'-glasstest123123123.log');
    $log = Log::Init($logHandler, 15);
    $raw_input = file_get_contents('php://input');
    $resolved_body = Util::resolveBody($raw_input);
Log::DEBUG('one1:' . json_encode($resolved_body));
    function object_arrays($array){
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = object_arrays($value);
            }
        }
        return $array;
    }
    $apikeyyby = '83XfD3c5PgLVs1f=hIBUTlnFupc=';
    $glass = new OneNetApi($apikeyyby);
    $body = json_decode($raw_input, TRUE);
    //$array = str_replace('}','',$json);
    $count=strpos($resolved_body,"}");
    $str=substr_replace($resolved_body,"",$count,1);
    $array = json_decode($str);
    $array_res = object_arrays($array);

    $nums = isset($array_res['OP_HD'])?$array_res['OP_HD']:0;//通道号
    $device_command = isset($array_res['dev_id'])?$array_res['dev_id']:0;//设备号
    $phone = isset($array_res['TEL_CHK'])?$array_res['TEL_CHK']:0;
    $passwd = isset($array_res['PWR_CHK'])?$array_res['PWR_CHK']:0;
    //找出openid
    $result = $db->get_row("select * from glass_user where phone='$phone' and password='$passwd'");
    $result_phone = $db->get_row("select * from glass_user where phone='$phone'");
    $qos = '1'; //1需要响应  0 不需要响应
    $timeout = '0';//为“秒”，默认“0”
Log::DEBUG('one2:' . json_encode($resolved_body));
    if(!$result_phone){
        $sms = array('TEL_PROC'=>'3','OP_HD'=>$nums,'TEL_CHK'=>$phone,);
        $glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
        Log::DEBUG('one2:' . json_encode($resolved_body));
        exit;
    }
Log::DEBUG('one3:' . json_encode($resolved_body));
    if($result){
        //判断是否还能不能可以玩，即次数是否大于0
        if($result['time_nums'] <= 0){
            $sms = array('TEL_PROC'=>'1','OP_HD'=>$nums,'TEL_CHK'=>$phone);
            $glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
            exit;
        }
        //余额充足，启动消费
        $sms = array("OP_HD"=>intval($nums),'TG_TIME'=>'1800','TG_MES'=>only_order());
        $res = $glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
        if(!empty($res)){
            //消费成功减去次数
            M('glass_user')->where(array('phone'=>$phone,'password'=>$passwd))->setDec('time_nums');
            $glass->send_data_to_edp($device_command, $qos, $timeout,
                array('TEL_PROC'=>'0','OP_HD'=>$nums,'TEL_CHK'=>$phone));
        }
    } else {
        $sms = array('TEL_PROC'=>'2','OP_HD'=>$nums,'TEL_CHK'=>$phone);
        $glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
        exit;
    }