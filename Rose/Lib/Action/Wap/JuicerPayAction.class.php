<?php
//榨汁机
class JuicerPayAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    //启动
    public function start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $di_id = $_POST['di_id'];
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','id'=>$di_id))->find();
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];
        if($weixin_alipay_type == 'alipay'){
            $count = M('ju_device_consume_alipay_rec')->where(array('buyer_id'=>trim($buyer_id),
                'status'=>'1','is_close'=>0,'del_flag'=>'0'))
                ->group('buyer_id')->sum('consume_account');
            if( intval($count)<=0 ) {
                echo json_encode(array('code'=>201,'msg'=>'余额不足,请充值'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $min = pow(10 , (6 - 1));
            $max = pow(10,6) - 1;
            $rand = rand($min, $max);
            $sms = array("SEV_OP"=>intval($times),'P_Y'=>intval($price),'TFC'=>intval($rand));
            $model = M('ju_device_consume_alipay_rec');
            $model->startTrans();
            $result = $this->juicer->send_data_to_edp($ju_device_info_detail['device_command'], $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->juicer->error_no();
                $error = $this->juicer->error();
                echo json_encode(array('code'=>500,'msg'=>'榨汁失败'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $di_id,
                'deivce_command' =>$ju_device_info_detail['device_command'],
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('ju_command_info')->add($command_info);
            $alipay_consume_rec3 = array(
                'id' => generateNum(),
                'type' => 0,
                'app_id' =>AlipayConfig::APPID,
                'buyer_id' => $buyer_id,
                'area_id' => $ju_device_info_detail['area_id'],
                'consume_account' =>'-'.$price,
                'command_status' => '1',
                'status' => '1',
                'di_id' => $di_id,
                'device_type' =>1,
                'device_command' => $ju_device_info_detail['device_command'],
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' =>$ju_device_info_detail['owner_id'],
                'update_date' => $now
            );
            $device_consume_rec4 = $model->add($alipay_consume_rec3);
            //消耗的物料
            $wuliao = array(
                'id'=>generateNum(),
                'di_id'=>$di_id,
                'orange'=>'5',
                'glass'=>'1',
                'straw'=>'1',
                'film'=>'1',
                'create_by'=>$ju_device_info_detail['owner_id'],
                'create_date'=>$now,
                'update_date'=>$now,
                'area_id'=>$ju_device_info_detail['area_id']
            );
            M('ju_meter_descime')->add($wuliao);
            if($command_infos && $device_consume_rec4){
                $model->commit();
                $count = M('ju_device_consume_alipay_rec')->where(array('buyer_id'=>trim($buyer_id),
                    'status'=>'1','is_close'=>0,'del_flag'=>'0'))
                    ->group('buyer_id')->sum('consume_account');
                $pay = '';
                if($count<=0){
                    $pay = 0;
                } else {
                    $pay = trim(intval($count));
                }
                echo json_encode(array('code'=>200,'msg'=>'榨汁已启动，请准备操作','count'=>intval($pay)));
            }else{
                $model->rollback();
                echo json_encode(array('code'=>201,'msg'=>'临时维护中'));
            }
        }
    }
    //微信支付
    public function weixin_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $di_id = trim($_POST['di_id']);
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'Wap/JuicerPay/notify',$out_trade_no);
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','id'=>$di_id))->find();
        if($ju_device_info_detail['online_status'] == '0'){
            echo json_encode(array('code'=>500,'error'=>'设备不在线，请支付其他设备'));exit();
        }
        $now = date("Y-m-d H:i:s");
        $weixin_consume_rec3 = array(
            'id' => generateNum(),
            'type' => 1,
            'app_id' =>WxPayConfig::APPID,
            'openid' => $openid,
            'out_trade_no'=>$out_trade_no,
            'area_id' => $ju_device_info_detail['area_id'],
            'consume_account' =>$price,
            'command_status' => '1',
            'status' => '0',
            'di_id' => $di_id,
            'device_type' => 1,
            'device_command' => $ju_device_info_detail['device_command'],
            'create_date' =>$now,
            'create_by' =>$ju_device_info_detail['owner_id'],
            'update_date' => $now
        );
        $device_consume_rec3 = M('ju_device_consume_weixin_rec')->add($weixin_consume_rec3);
        if($device_consume_rec3){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
        if($notify->checkSign() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['command_status'] = 2;
                $da['status'] = 1;
                M('ju_device_consume_weixin_rec')->where(array('openid'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no'],'type'=>'1'))->save($da);
            }
        }
    }
    //微信支付更新
    public function update(){
        $out_trade_no = $_POST['out_trade_no'];
        $openid = $_POST['openid'];
        $times = $_POST['times'];
        $di_id = $_POST['di_id'];
        $price = $_POST['price'];
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','id'=>$di_id))->find();
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['status'] = 1;
        $data['command_status'] = 2;
        if(M('ju_device_consume_weixin_rec')->where(array('openid'=>$openid,
            'out_trade_no'=>$out_trade_no,'type'=>'1'))->save($data)){
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $min = pow(10 , (6 - 1));
            $max = pow(10,6) - 1;
            $rand = rand($min, $max);
            $sms = array("SEV_OP"=>intval($times),'P_Y'=>intval($price),'TFC'=>intval($rand));
            $result = $this->juicer->send_data_to_edp($ju_device_info_detail['device_command'], $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            $now = date("Y-m-d H:i:s");
            if($res->TP =='255'){
                echo json_encode(array('code'=>'201','msg'=>'设备忙，请等待榨汁完成'));
            } elseif($res->TP != '0') {
                $command_info = array(
                    'id' => generateNum(),
                    'cmd_id' => $return_result,
                    'di_id' => $di_id,
                    'deivce_command' =>$ju_device_info_detail['device_command'],
                    'status' => '1',
                    'resp_status'=>'100',
                    'create_date'=>$now,
                    'update_by'=>'1',
                    'update_date'=>$now
                );
                M('ju_command_info')->add($command_info);
                $weixin_consume_rec3 = array(
                    'id' => generateNum(),
                    'type' => 0,
                    'app_id' =>WxPayConfig::APPID,
                    'openid' => $openid,
                    'area_id' => $ju_device_info_detail['area_id'],
                    'consume_account' =>'-'.$price,
                    'command_status' => '2',
                    'status' => '1',
                    'di_id' => $di_id,
                    'device_type' => 1,
                    'device_command' => $ju_device_info_detail['device_command'],
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$ju_device_info_detail['owner_id'],
                    'update_date' => $now
                );
                M('ju_device_consume_weixin_rec')->add($weixin_consume_rec3);
                //消耗的物料数量
                $wuliao = array(
                    'id'=>generateNum(),
                    'di_id'=>$di_id,
                    'orange'=>'5',
                    'glass'=>'1',
                    'straw'=>'1',
                    'film'=>'1',
                    'create_by'=>$ju_device_info_detail['owner_id'],
                    'create_date'=>$now,
                    'update_date'=>$now,
                    'area_id'=>$ju_device_info_detail['area_id']
                );
                M('ju_meter_descime')->add($wuliao);
                echo json_encode(array('code'=>200,'msg'=>'榨汁开始'));
            } else {
                $command_info1 = array(
                    'id' => generateNum(),
                    'cmd_id' => $return_result,
                    'di_id' => $di_id,
                    'deivce_command' =>$ju_device_info_detail['device_command'],
                    'status' => '0',
                    'resp_status'=>'100',
                    'create_date'=>$now,
                    'update_by'=>'1',
                    'update_date'=>$now
                );
                M('ju_command_info')->add($command_info1);
                $weixin_consume_rec3 = array(
                    'id' => generateNum(),
                    'type' => 0,
                    'app_id' =>WxPayConfig::APPID,
                    'openid' => $openid,
                    'area_id' => $ju_device_info_detail['area_id'],
                    'consume_account' =>'-'.$price,
                    'command_status' => '3',
                    'status' => '1',
                    'di_id' => $di_id,
                    'device_type' => 1,
                    'device_command' => $ju_device_info_detail['device_command'],
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$ju_device_info_detail['owner_id'],
                    'update_date' => $now
                );
                M('ju_device_consume_weixin_rec')->add($weixin_consume_rec3);
                echo json_encode(array('code'=>'201','msg'=>'设备忙，请等待榨汁完成'));
            }
        }else{
            echo json_encode(array('code'=>201,'msg'=>'设备忙，请等待榨汁完成'));
        }

    }
    //支付宝支付
    public function alipay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $di_id         = trim($_POST['di_id']);
        $times         = trim($_POST['times']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('alipay_update'),
            get_dirname_url().'Wap/JuicerPay/alipay_notify',$price,$out_trade_no);
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','id'=>$di_id))->find();
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['type'] = 1;
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['buyer_id'] = $buyer_id;
        $data['area_id'] = $ju_device_info_detail['area_id'];
        $data['command_status'] = 1;
        $data['consume_account'] = $price;
        $data['status'] = 0;
        $data['di_id'] = $di_id;
        $data['device_command'] = $ju_device_info_detail['device_command'];
        $data['create_by'] = $ju_device_info_detail['owner_id'];
        $data['create_date'] = $now;
        $data['update_date'] = $now;
        $data['remarks'] = $result;
        $uid = M('ju_device_consume_alipay_rec')->add($data);
        session('buyer_id',$buyer_id);
        session('times',$times);
        session('di_id',$di_id);
        session('price',$price);
        session('scan_code',$ju_device_info_detail['scan_code']);
        echo $result;
    }
    public function alipay_notify(){
        $logpath = LOG_PATH.date('y_m_d').'.alipay';
        $aop = new AopClient ();
        $aop->gatewayUrl = AlipayConfig::GETWAPURL;
        $aop->appId = AlipayConfig::APPID;
        $aop->rsaPrivateKeyFilePath = constant('CUL').AlipayConfig::PRIVEKEYFILEPATH;
        $aop->alipayPublicKey=constant('CUL').AlipayConfig::ALIPAYPUBLICKEY;
        $aop->apiVersion = '1.0';
        $async = empty($_GET);
        $data = $_POST;
        //Log::write(json_encode($data),'INFO','',$logpath,'');
        if (empty($data)) {
            return FALSE;
        }
        //Log::write(555555,'INFO5','',$logpath,'');
        //$verify_result = $aop->rsaCheckV1($data,$aop->alipayPublicKey);
        //Log::write($verify_result,'INFO3','',$logpath,'');
        //if($verify_result) {
            if($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
                $da['transaction_id'] = $data['trade_no'];
                $da['command_status'] = 2;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                $da['status'] = 1;
                M('ju_device_consume_alipay_rec')->where(array('buyer_id'=>$data['buyer_id'],
                    'out_trade_no'=>$data['out_trade_no'],'type'=>'1'))->save($da);
                //Log::write(M('ju_device_consume_alipay_rec')->getLastSql(),'INFO3','',$logpath,'');
            }
        //Log::write(json_encode($data),'INFO5','',$logpath,'');
       // }
    }
    public function alipay_update(){
        $aop = new AopClient ();
        $aop->gatewayUrl = AlipayConfig::GETWAPURL;
        $aop->appId = AlipayConfig::APPID;
        $aop->rsaPrivateKeyFilePath = constant('CURRENT_FILE_PATH').AlipayConfig::PRIVEKEYFILEPATH;
        $aop->alipayPublicKey=constant('CURRENT_FILE_PATH').AlipayConfig::ALIPAYPUBLICKEY;
        $aop->apiVersion = '1.0';
        $async = empty($_GET);
        $data = $async ? $_POST : $_GET;
        if (empty($data)) {
            return FALSE;
        }
        $date = date('Y-m-d H:i:s',time());
        $buyer_id = session('buyer_id');
        $times = session('times');
        $price = session('price');
        $di_id = session('di_id');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $dataed['command_status'] = 2;
            $dataed['status'] = 1;
            $dataed['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            M('ju_device_consume_alipay_rec')->where($where)->save($dataed);
            $ju_device_info_detail = M('ju_device_info_detail')
                ->where(array('del_flag'=>'0','id'=>$di_id))->find();
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $min = pow(10 , (6 - 1));
            $max = pow(10,6) - 1;
            $rand = rand($min, $max);
            $sms = array("SEV_OP"=>intval($times),'P_Y'=>intval($price),'TFC'=>intval($rand));
            $result = $this->juicer->send_data_to_edp($ju_device_info_detail['device_command'], $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            $now = date("Y-m-d H:i:s");
            if($res->TP =='255'){
                echo json_encode(array('code'=>'201','msg'=>'设备忙，请等待榨汁完成'));
            }
            elseif($res->TP != '0') {
                $command_info = array(
                    'id' => generateNum(),
                    'cmd_id' => $return_result,
                    'di_id' => $di_id,
                    'deivce_command' =>$ju_device_info_detail['device_command'],
                    'status' => '1',
                    'resp_status'=>'200',
                    'create_date'=>$now,
                    'update_by'=>'1',
                    'update_date'=>$now
                );
                M('ju_command_info')->add($command_info);
                $weixin_consume_rec3 = array(
                    'id' => generateNum(),
                    'type' => 0,
                    'app_id' =>AlipayConfig::APPID,
                    'buyer_id' => $buyer_id,
                    'area_id' => $ju_device_info_detail['area_id'],
                    'consume_account' =>'-'.$price,
                    'command_status' => '2',
                    'status' => '1',
                    'di_id' => $di_id,
                    'device_type' => 1,
                    'device_command' => $ju_device_info_detail['device_command'],
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$ju_device_info_detail['owner_id'],
                    'update_date' => $now
                );
                M('ju_device_consume_alipay_rec')->add($weixin_consume_rec3);
                //消耗的物料数量
                $wuliao = array(
                    'id'=>generateNum(),
                    'di_id'=>$di_id,
                    'orange'=>'5',
                    'glass'=>'1',
                    'straw'=>'1',
                    'film'=>'1',
                    'create_by'=>$ju_device_info_detail['owner_id'],
                    'create_date'=>$now,
                    'update_date'=>$now,
                    'area_id'=>$ju_device_info_detail['area_id']
                );
                M('ju_meter_descime')->add($wuliao);
                echo json_encode(array('code'=>200,'msg'=>'榨汁开始'));
            } else {
                $command_info1 = array(
                    'id' => generateNum(),
                    'cmd_id' => $return_result,
                    'di_id' => $di_id,
                    'deivce_command' =>$ju_device_info_detail['device_command'],
                    'status' => '0',
                    'resp_status'=>'520',
                    'create_date'=>$now,
                    'update_by'=>'1',
                    'update_date'=>$now
                );
                M('ju_command_info')->add($command_info1);
                $weixin_consume_rec3 = array(
                    'id' => generateNum(),
                    'type' => 0,
                    'app_id' =>AlipayConfig::APPID,
                    'buyer_id' => $buyer_id,
                    'area_id' => $ju_device_info_detail['area_id'],
                    'consume_account' =>'-'.$price,
                    'command_status' => '3',
                    'status' => '1',
                    'di_id' => $di_id,
                    'device_type' => 1,
                    'device_command' => $ju_device_info_detail['device_command'],
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$ju_device_info_detail['owner_id'],
                    'update_date' => $now
                );
                M('ju_device_consume_alipay_rec')->add($weixin_consume_rec3);
                echo json_encode(array('code'=>'201','msg'=>'设备忙，请等待榨汁完成'));
            }
            header('Location:'.U('JuicerCode/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
}