<?php

ini_set("display_errors","On");   
error_reporting(E_ALL);  




include "WxMch.Pay.php";

		$mchPay = new WxMchPay();
        // 用户openid
        $mchPay->setParameter('openid', 'odOIPv-pJHD3Q9ZkxNxt7FlZnSA4');
        // 商户订单号
        $mchPay->setParameter('partner_trade_no', 'test-'.time());
        // 校验用户姓名选项
        $mchPay->setParameter('check_name', 'NO_CHECK');
        // 企业付款金额  单位为分
        $mchPay->setParameter('amount', 120);
        // 企业付款描述信息
        $mchPay->setParameter('desc', '浣犲ソ');
        // 调用接口的机器IP地址  自定义
        $mchPay->setParameter('spbill_create_ip', '127.0.0.1'); # getClientIp()
        // 收款用户姓名
        // $mchPay->setParameter('re_user_name', 'Max wen');
        // 设备信息
        // $mchPay->setParameter('device_info', 'dev_server');


        $response = $mchPay->postXmlSSLCurl_init();
        if( !empty($response) ) {
            $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
            echo json_encode($data);
        }else{
            echo json_encode( array('return_code' => 'FAIL', 'return_msg' => 'transfers_接口出错', 'return_ext' => array()) );
        }

?>