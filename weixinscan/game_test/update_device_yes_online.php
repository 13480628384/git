<?php
require 'devicecommand/OneNetApi.php';
include "mysql/mysqldbwrite.php";
include "wxpay/log.php";
//require '/home/wwwroot/wxpay.roseo2o.com/GetDeviceLists/mysql/mysqldbwrite.php';
$apiurl = 'http://api.heclouds.com';
$apikey_wwj = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';
$apikey_amy = 'dkzIPZejUbsvxjz3SuXO111O3Qw=';
$apikey3 = 'aNR6j1NOK3xOI=nTlvoaqqAh4bw=';//售货机
$apikeyxi = 'vnH3AAd1oMBNAPBPahnpuHH2L=o=';//洗衣机
$apikey2 = 'QdDqTBG=fIRkz25RzXFUQf=hMp0=';//充电器
$apikeyxiaoqu = 'Vds=DWm57TTxCIwOn2j3hXq8Czo=';//电动车
$apikeyxiche = 'WHhxJbOaxk5uOaKVwMqRGMZpMjY=';//洗车
$apikeycezhiji = 'o=W3qpk7wqksU9AmzKbDA2RQqkA=';//厕纸机
$apikeydangducezhiji = 'WANmbP735=Dx1OSBj7hg1ZKNwRY=';//单独厕纸机
$apikeyyanbaoyi = '83XfD3c5PgLVs1f=hIBUTlnFupc=';//眼保仪
$apikeychaxie = 'NuvLHXoUYxZn99MVEbtVNyjSd9E=';//擦鞋姐
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'-page.log');
$log = Log::Init($logHandler, 15);
function update_device_online_status($db,$apiurl,$apikey,$type,$types){
    $sm = new OneNetApi($apikey, $apiurl);
    $next_number = '';
    $sum=0;
    $array = array();
    $num = 1;
    $number = 1;
    //查询所有在线的设备id
    while(true){
        if($num>20){
            break;
        }
        $data = $sm->device_list($num,30,NULL,NULL,1);
        foreach($data['devices'] as $key=>$v){
            $array[] = $v['id'];
        }
        if($sum==$data['total_count']){
            break;
        }
        $num++;
        //Log::DEBUG('page:'.json_encode($data).$num);
        $sum += $number;
    }
    //更新所有已经在线的设备
    $imp  = "'" . implode("','", $array) . "'"  ;
    $update_sql = "update device_relation_group set online_status = 1 where  del_flag=0 and  device_command in($imp) and  device_type in ($types)";
    $result2 = $db->query($update_sql);
    //$sql_no = "update device_relation_group set online_status = 0 where  del_flag=0 and  device_command not in($imp) and  device_type in ($types)";
    //$result1 = $db->query($sql_no);
    if($result2){
        echo(json_encode(array('code'=>200,'msg'=>$num.'type'.$type)));
    }else{
        echo (json_encode(array('code'=>500,'msg'=>$num.'type'.$type)));
    }

}
//在线设备更新
update_device_online_status($db,$apiurl,$apikey_wwj,'1',"'1'");
update_device_online_status($db,$apiurl,$apikey_amy,'2',"'4'");
update_device_online_status($db,$apiurl,$apikey2,'3',"'2'");
update_device_online_status($db,$apiurl,$apikey3,'4',"'3'");
update_device_online_status($db,$apiurl,$apikeyxi,'5',"'5'");
update_device_online_status($db,$apiurl,$apikeyxiaoqu,'6',"'6'");
update_device_online_status($db,$apiurl,$apikeyxiche,'7',"'7'");
update_device_online_status($db,$apiurl,$apikeycezhiji,'8',"'8'");
update_device_online_status($db,$apiurl,$apikeydangducezhiji,'8',"'8'");
update_device_online_status($db,$apiurl,$apikeyyanbaoyi,'9',"'9'");
update_device_online_status($db,$apiurl,$apikeychaxie,'11',"'11'");
?>