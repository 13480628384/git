<?php
//Զ�̻�ȡָ��ִ��״̬���Ҹ���command_info,pay_weixin_info��
require 'config.php';
require 'OneNetApi.php';
require '/home/wwwroot/wxpay.roseo2o.com/weixinscan/game_test/mysql/mysqldbwrite.php';
//require_once('../mysql/mysqldbwrite.php');

$apikey_wwj = 'GsWYEhoTo=z=7AvcvHW3rFwsS94=';
$apikey_amy = 'dkzIPZejUbsvxjz3SuXO111O3Qw=';
$apikey_wash = 'vnH3AAd1oMBNAPBPahnpuHH2L=o=';
$apikey_diandongche = 'Vds=DWm57TTxCIwOn2j3hXq8Czo=';//电动车充电
$apikey_xiche = 'WHhxJbOaxk5uOaKVwMqRGMZpMjY=';//洗车
//$apikey_ceji = 'o=W3qpk7wqksU9AmzKbDA2RQqkA=';//厕纸机
$apikeydangdu_ceji = 'WANmbP735=Dx1OSBj7hg1ZKNwRY=';//单独厕纸机
$apikeyyanbaoyi = '83XfD3c5PgLVs1f=hIBUTlnFupc=';//眼保仪
$apikeyzhijin = 'NOR4zrZ2QmadbvAEKvjeMFP=eNQ=';//纸巾
$apikeychaxie = 'NuvLHXoUYxZn99MVEbtVNyjSd9E=';//擦鞋姐


$cmd_records = $db->get_results("SELECT cmd_id ,device_type FROM command_info where status = '1' and resp_status = '100' and create_date > '2016-12-01' and create_date <= NOW() - interval 2 minute  and TO_DAYS( NOW( )) - TO_DAYS( create_date) <= 1 ORDER BY create_date desc  limit 100 ");

if(is_null($cmd_records) || count($cmd_records)==0){
    exit();
}

//��������
$sm_wwj = new OneNetApi($apikey_wwj, $apiurl);
$sm_amy = new OneNetApi($apikey_amy, $apiurl);
$sm_wash = new OneNetApi($apikey_wash, $apiurl);
$sm_che = new OneNetApi($apikey_diandongche, $apiurl);
$sm_xiche = new OneNetApi($apikey_xiche, $apiurl);
//$sm_ceji = new OneNetApi($apikey_ceji, $apiurl);
$dangdu_ceji = new OneNetApi($apikeydangdu_ceji, $apiurl);
$yanbaoyi = new OneNetApi($apikeyyanbaoyi, $apiurl);
$zhijin = new OneNetApi($apikeyzhijin, $apiurl);
$chaxie = new OneNetApi($apikeychaxie, $apiurl);
$results = array();
$results_remark = array();
foreach ( $cmd_records as $cmd_record )
{
    //1���޻� 2�����  3�Զ��ۻ���
    if(in_array($cmd_record->device_type, array("1","2","3"))){
        $is_success = $sm_wwj->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_wwj->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_wwj->raw_response();
        }else {
            if($sm_wwj->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_wwj->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_wwj->http_code());
            }

        }
    }

    //4��Ħ��
    if($cmd_record->device_type == '4'){
        $is_success = $sm_amy->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_amy->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_amy->raw_response();
        }else {
            if($sm_amy->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_amy->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_amy->http_code());
            }

        }
    }
    if($cmd_record->device_type == '5'){
        $is_success = $sm_wash->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_wash->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_wash->raw_response();
        }else {
            if($sm_wash->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_wash->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_wash->http_code());
            }
        }
    }
    if($cmd_record->device_type == '6'){
        $is_success = $sm_che->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_che->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_che->raw_response();
        }else {
            if($sm_che->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_che->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_che->http_code());
            }
        }
    }
    if($cmd_record->device_type == '7'){
        $is_success = $sm_xiche->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_xiche->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_xiche->raw_response();
        }else {
            if($sm_xiche->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_xiche->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_xiche->http_code());
            }
        }
    }
    if($cmd_record->device_type == '8'){
        /*$is_success = $sm_ceji->get_dev_status_resp($cmd_record->cmd_id);
        if( $sm_ceji->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$sm_ceji->raw_response();
        }else {
            if($sm_ceji->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$sm_ceji->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($sm_ceji->http_code());
            }
        }*/
        //单独的纸巾机 start
        $is_success = $dangdu_ceji->get_dev_status_resp($cmd_record->cmd_id);
        if( $dangdu_ceji->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';//400也代表成功
            $results_remark[$cmd_record->cmd_id]=$dangdu_ceji->raw_response();
        }else {
            if($dangdu_ceji->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$dangdu_ceji->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($dangdu_ceji->http_code());
            }
        }
        //单独的纸巾机 end
    }
    if($cmd_record->device_type == '9'){
        $is_success = $yanbaoyi->get_dev_status_resp($cmd_record->cmd_id);
        if( $yanbaoyi->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$yanbaoyi->raw_response();
        }else {
            if($yanbaoyi->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$yanbaoyi->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($yanbaoyi->http_code());
            }
        }
    }
    if($cmd_record->device_type == '10'){
        $is_success = $zhijin->get_dev_status_resp($cmd_record->cmd_id);
        if( $zhijin->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$zhijin->raw_response();
        }else {
            if($zhijin->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$zhijin->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($zhijin->http_code());
            }
        }
    }
    if($cmd_record->device_type == '11'){
        $is_success = $chaxie->get_dev_status_resp($cmd_record->cmd_id);
        if( $chaxie->http_code()== '200' && $is_success){
            $results[$cmd_record->cmd_id]='200';
            $results_remark[$cmd_record->cmd_id]=$chaxie->raw_response();
        }else {
            if($chaxie->http_code()== '200'){
                $results[$cmd_record->cmd_id]= '520';
                $results_remark[$cmd_record->cmd_id]=$chaxie->error();
            } else {
                $results[$cmd_record->cmd_id]= '221';
                $results_remark[$cmd_record->cmd_id]='http erro:'.($chaxie->http_code());
            }
        }
    }
    usleep(100);
}

//print_r($results);die;
//û�в�ѯ����
if(count($results)==0){
    exit();
}



/*

200���豸���ͳɹ�����������
520: �豸���ͳɹ������ش���
221����ѯ����httpcode �쳣
*/

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

//����ָ��ͼ�¼��
$now = date("Y-m-d H:i:s");

if(count($result_221)){
    $sql_221 = "update command_info set  status = '0',resp_status = '221', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_221)).")";
    $update_result = $db->query($sql_221);
}

if(count($result_520)){
    $sql_520 = "update command_info set status = '0', resp_status = '520', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_520)).")";
    $update_result = $db->query($sql_520);
}

if(count($result_200)){
    $sql_200 = "update command_info set resp_status = '200', update_date='$now' where cmd_id in(". implode(',',array_map('change_to_quotes',$result_200)).")";
    $update_result = $db->query($sql_200);
}

//print_r($success_pay);

//print_r($fail_pay);

//����֧����Ϣ��
//�ɹ�����
if(count($success_pay)){
    $success_sql = "update device_consume_rec  set  command_status = '2',update_date='$now'   where cmd_uuid in(". implode(',',array_map('change_to_quotes',$success_pay)).")";
    //echo $success_sql;
    $update_result = $db->query($success_sql);
}

//ʧ�ܷ���
if(count($fail_pay)){
    $fail_sql = "update device_consume_rec set  command_status = '3',update_date='$now'   where cmd_uuid in(". implode(',',array_map('change_to_quotes',$fail_pay)).")";
    //echo $fail_sql;
    $update_result = $db->query($fail_sql);
}



?>