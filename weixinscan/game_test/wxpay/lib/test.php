<?php

ini_set("display_errors","On");   
error_reporting(E_ALL);  




include "WxMch.Pay.php";

		$mchPay = new WxMchPay();
        // �û�openid
        $mchPay->setParameter('openid', 'odOIPv-pJHD3Q9ZkxNxt7FlZnSA4');
        // �̻�������
        $mchPay->setParameter('partner_trade_no', 'test-'.time());
        // У���û�����ѡ��
        $mchPay->setParameter('check_name', 'NO_CHECK');
        // ��ҵ������  ��λΪ��
        $mchPay->setParameter('amount', 120);
        // ��ҵ����������Ϣ
        $mchPay->setParameter('desc', '你好');
        // ���ýӿڵĻ���IP��ַ  �Զ���
        $mchPay->setParameter('spbill_create_ip', '127.0.0.1'); # getClientIp()
        // �տ��û�����
        // $mchPay->setParameter('re_user_name', 'Max wen');
        // �豸��Ϣ
        // $mchPay->setParameter('device_info', 'dev_server');


        $response = $mchPay->postXmlSSLCurl_init();
        if( !empty($response) ) {
            $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
            echo json_encode($data);
        }else{
            echo json_encode( array('return_code' => 'FAIL', 'return_msg' => 'transfers_�ӿڳ���', 'return_ext' => array()) );
        }

?>