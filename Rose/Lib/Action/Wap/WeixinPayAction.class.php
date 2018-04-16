<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 微信支付
 * */
class WeixinPayAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    /*====================售货机微信支付 [[=======================*/
    public function weixin_machine_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/weixinpay_machine_notify.php',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$owner_id,
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        $cid = M('weixin_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function weixin_machine_update(){
        $out_trade_no = $_POST["out_trade_no"];
        $openid = $_POST["openid"];
        $device_command = $_POST["device_command"];
        $device_id = $_POST["device_id"];
        $times = intval($_POST["times"]);
        $price = intval($_POST["price"]);
        $dataed['pay_status'] = 1;
        $update = M('weixin_pay_rec')->where(array(
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no
        ))->save($dataed);
        $qos = '1';
        $timeout = '0';
        $sms = array("HD"=>2,'OUT_N'=>$times,'SP_Y'=>$price);
        $result = $this->machine->send_data_to_edp($device_command, $qos, $timeout, $sms);
        if($result){
            $date = date("Y-m-d H:i:s");
            //新增指令记录
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $result['cmd_uuid'],
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$date,
                'update_by'=>'1',
                'device_type'=>'3',
                'update_date'=>$date
            );
            $command_info_result = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            for ($i = 0; $i < $price; $i++) {
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => WxPayConfig::APPID,
                    'from_username' => $openid,
                    'consume_account' => '1',
                    'command_status' => '1',
                    'consume_status' => '1',
                    'type' => '11',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $result['cmd_uuid'],
                    'create_date' => $date,
                    'create_by' => $owner_id,
                    'update_by' => $openid,
                    'update_date' => $date
                );
                $cdj = M('device_consume_rec')->add($weixin_consume_rec);
            }
            echo json_encode(array('code'=>200,'msg'=>$result['cmd_uuid']));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'不工作'));
        }
    }
    /*====================售货机微信支付 ]]=======================*/
    /*====================售货机支付宝支付 [[=======================*/
    public function alipay_pay_machine(){
        $price = trim($_POST['price']);
        $buyer_id = trim($_POST['buyer_id']);
        $scan_code = trim($_POST['scan_code']);
        $device_command = trim($_POST['device_command']);
        $device_id = trim($_POST['device_id']);
        $times = trim($_POST['times']);
        if(empty($price) || !isset($price) || empty($buyer_id) || !isset($buyer_id)){
            exit(json_encode(array('code'=>209,'error'=>'参数错误')));
        }
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('machine_url'),get_dirname_url().'config/machine_alipay_notify.php',$price,$out_trade_no);
        $date = date('Y-m-d H:i:s',time());
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = Get_Url().U('return_url');
        $data['notify_url'] = get_dirname_url().'config/machine_alipay_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $buyer_id;
        $data['create_date'] = $date;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $date;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        session('machine_alipay_buyer_id',$buyer_id);
        session('machine_alipay_scan_code',$scan_code);
        session('machine_alipay_device_command',$device_command);
        session('machine_alipay_device_id',$device_id);
        session('machine_alipay_times',$times);
        session('machine_alipay_price',$price);
        echo $result;
    }
    public function machine_url(){
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
        $buyer_id = session('machine_alipay_buyer_id');
        $scan_code = session('machine_alipay_scan_code');
        $device_command = session('machine_alipay_device_command');
        $device_id = session('machine_alipay_device_id');
        $times = session('machine_alipay_times');
        $price = session('machine_alipay_price');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $qos = '1';
            $timeout = '0';
           // $sms = array("T_M"=>intval($times),'P_Y'=>intval($price));
            $sms = array("HD"=>2,'OUT_N'=>$times,'SP_Y'=>$price);
            $result1 = $this->machine->send_data_to_edp($device_command, $qos, $timeout, $sms);
            if($result1) {
                $data['return_timestamp'] = $data['timestamp'];
                $data['update_date'] = $date;
                $where['out_trade_no'] = $data['out_trade_no'];
                $where['del_flag'] = 0;
                $where['buyer_id'] = $buyer_id;
                $result = M('alipay_pay_rec')->where($where)->save($data);
                $date = date("Y-m-d H:i:s");
                //新增指令记录
                $command_info = array(
                    'id' => generateNum(),
                    'cmd_id' => $result1['cmd_uuid'],
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'status' => '1',
                    'resp_status' => '100',
                    'create_date' => $date,
                    'update_by' => '1',
                    'device_type' => '3',
                    'update_date' => $date
                );
                $command_info_result = M('command_info')->add($command_info);
                $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
                for ($i = 0; $i < $price; $i++) {
                    $weixin_consume_rec = array(
                        'id' => generateNum(),
                        'app_id' => AlipayConfig::APPID,
                        'from_username' => $buyer_id,
                        'consume_account' => '1',
                        'command_status' => '1',
                        'consume_status' => '1',
                        'type' => '12',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $result1['cmd_uuid'],
                        'create_date' => $date,
                        'create_by' => $owner_id,
                        'update_by' => $buyer_id,
                        'update_date' => $date
                    );
                    $cdj = M('device_consume_rec')->add($weixin_consume_rec);
                }
            }
            header('Location:' . U('Machine/alipay',
                    array('start'=>1,'user_id' => $buyer_id,
                        'scan_code' => $scan_code
                    )));
            exit;
        }
    }
    /*====================售货机支付宝支付 ]]=======================*/

    //免费充电
    public function free(){
        $qos = '1';
        $timeout = '0';
        $sms = array("TG_NUM"=>1);
        //$sms = array("T_M"=>30,'P_Y'=>0);
        $device_id = $_POST['device_id'];
        $device_command = $_POST['device_command'];
        $result = $this->charger->send_data_to_edp($device_command, $qos, $timeout, $sms);
        if($result){
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $result['cmd_uuid'],
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>date('Y-m-d H:i:s',time()),
                'update_by'=>'1',
                'device_type'=>'2',
                'update_date'=>date('Y-m-d H:i:s',time())
            );
            $command_info_result = M('command_info')->add($command_info);
            if($command_info_result){
                echo json_encode(array('code'=>200));
            } else {
                echo json_encode(array('code'=>500));
            }
        }else {
            echo json_encode(array('code'=>500));
        }

    }
    /*====================充电器微信支付 [[=======================*/
    public function weixin_pay(){
        $price = trim($_POST['price']);
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,100*$price,
            get_dirname_url().'config/weixinpay_charger_notify.php',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$owner_id,
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        $cid = M('weixin_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function weixin_update(){
        $out_trade_no = $_POST["out_trade_no"];
        $openid = $_POST["openid"];
        $device_command = $_POST["device_command"];
        $device_id = $_POST["device_id"];
        $times = intval($_POST["times"]);
        $price = intval($_POST["price"]);
        $dataed['pay_status'] = 1;
        $update = M('weixin_pay_rec')->where(array(
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no
        ))->save($dataed);
        $qos = '1';
        $timeout = '0';
        $sms = array("TG_NUM"=>1);
        //$sms = array("T_M"=>$times,'P_Y'=>$price);
        $result = $this->charger->send_data_to_edp($device_command, $qos, $timeout, $sms);
        if($result){
            $date = date("Y-m-d H:i:s");
            //新增指令记录
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $result['cmd_uuid'],
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$date,
                'update_by'=>'1',
                'device_type'=>'2',
                'update_date'=>$date
            );
            $command_info_result = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            for ($i = 0; $i < $price; $i++) {
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => WxPayConfig::APPID,
                    'from_username' => $openid,
                    'consume_account' => '1',
                    'command_status' => '1',
                    'consume_status' => '1',
                    'type' => '5',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $result['cmd_uuid'],
                    'create_date' => $date,
                    'create_by' => $owner_id,
                    'update_by' => $openid,
                    'update_date' => $date
                );
                $cdj = M('device_consume_rec')->add($weixin_consume_rec);
            }
            echo json_encode(array('code'=>200,'msg'=>$result['cmd_uuid']));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'不工作'));
        }
    }
    /*====================充电器微信支付 ]]=======================*/

    /*===================充电器支付宝支付 [[=======================*/
    public function alipay_pay(){
        $price = trim($_POST['price']);
        $buyer_id = trim($_POST['buyer_id']);
        $scan_code = trim($_POST['scan_code']);
        $device_command = trim($_POST['device_command']);
        $device_id = trim($_POST['device_id']);
        $times = trim($_POST['times']);
        if(empty($price) || !isset($price) || empty($buyer_id) || !isset($buyer_id)){
            exit(json_encode(array('code'=>209,'error'=>'参数错误')));
        }
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('return_url'),get_dirname_url().'config/charger_alipay_notify.php',$price,$out_trade_no);
        $date = date('Y-m-d H:i:s',time());
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = Get_Url().U('return_url');
        $data['notify_url'] = get_dirname_url().'config/charger_alipay_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $buyer_id;
        $data['create_date'] = $date;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $date;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        session('charger_alipay_buyer_id',$buyer_id);
        session('charger_alipay_scan_code',$scan_code);
        session('charger_alipay_device_command',$device_command);
        session('charger_alipay_device_id',$device_id);
        session('charger_alipay_times',$times);
        session('charger_alipay_price',$price);
        echo $result;
    }
    public function return_url(){
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
        $buyer_id = session('charger_alipay_buyer_id');
        $scan_code = session('charger_alipay_scan_code');
        $device_command = session('charger_alipay_device_command');
        $device_id = session('charger_alipay_device_id');
        $times = session('charger_alipay_times');
        $price = session('charger_alipay_price');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $qos = '1';
            $timeout = '0';
            $sms = array("TG_NUM"=>1);
            //$sms = array("T_M"=>intval($times),'P_Y'=>intval($price));
            $result1 = $this->charger->send_data_to_edp($device_command, $qos, $timeout, $sms);
            if($result1) {
                $data['return_timestamp'] = $data['timestamp'];
                $data['update_date'] = $date;
                $where['out_trade_no'] = $data['out_trade_no'];
                $where['del_flag'] = 0;
                $where['buyer_id'] = $buyer_id;
                $result = M('alipay_pay_rec')->where($where)->save($data);
                $date = date("Y-m-d H:i:s");
                //新增指令记录
                $command_info = array(
                    'id' => generateNum(),
                    'cmd_id' => $result1['cmd_uuid'],
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'status' => '1',
                    'resp_status' => '100',
                    'create_date' => $date,
                    'update_by' => '1',
                    'device_type' => '2',
                    'update_date' => $date
                );
                $command_info_result = M('command_info')->add($command_info);
                $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
                for ($i = 0; $i < $price; $i++) {
                    $weixin_consume_rec = array(
                        'id' => generateNum(),
                        'app_id' => AlipayConfig::APPID,
                        'from_username' => $buyer_id,
                        'consume_account' => '1',
                        'command_status' => '1',
                        'consume_status' => '1',
                        'type' => '6',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $result1['cmd_uuid'],
                        'create_date' => $date,
                        'create_by' => $owner_id,
                        'update_by' => $buyer_id,
                        'update_date' => $date
                    );
                    $cdj = M('device_consume_rec')->add($weixin_consume_rec);
                }
            }
            header('Location:' . U('Charger/alipay',
                    array('start'=>1,'user_id' => $buyer_id,
                        'scan_code' => $scan_code
                    )));
            exit;
        }
    }
    /*===================充电器支付宝支付 ]]=======================*/
}
?>