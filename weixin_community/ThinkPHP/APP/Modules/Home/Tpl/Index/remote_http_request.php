<?php
function post($url, $data){//file_get_content
 
         
        $postdata = http_build_query(
 
            $data
 
        );       
 
        $opts = array('http' =>
 
                      array(
 
                          'method'  => 'POST',
 
                          'header'  => 'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
 
                          'content' => $postdata
 
                      )
 
        );        
 
        $context = stream_context_create($opts); 
 
        $result = file_get_contents($url, false, $context); 
        return $result;  
    }




//调用接口的平台服务地址
$url = "http://120.24.81.106:3030/IntelligenceServer2/cgi/message_send.action";//"http://czl026.ngrok.cc/IntelligenceServer2/cgi/message_send.action";

//调用接口的平台服务地址
$data['datas']='201:AAAAA401';

$data['deviceId']=$_POST['device_code'];
$data['transCode']='601';


echo post($url,$data);



?>


