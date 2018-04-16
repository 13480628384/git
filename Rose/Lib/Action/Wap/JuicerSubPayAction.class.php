<?php
//榨汁机
require_once("JsApiPaySub.php");
class JuicerSubPayAction extends BackSubAction{
    //服务商支付
    public function weixin_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $di_id = trim($_POST['di_id']);
        $out_trade_no = generateId();
        $tools = new JsApiPaySub();
        $input = new WxPayUnifiedOrder();
        $input->SetBody("深圳玫瑰物联-智能终端充值：".$price."元");
        $input->SetAttach("深圳玫瑰物联技术有限公司提供");
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($price*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("1");
        $input->SetNotify_url(get_dirname_url().'Wap/JuicerSubPay/notify');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $input->SetSub_Mch_id('1453395802');
        $order = Api::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','id'=>$di_id))->find();
        if($ju_device_info_detail['online_status'] == '0'){
            echo json_encode(array('code'=>500,'error'=>'设备不在线，请支付其他设备'));exit();
        }
        $now = date("Y-m-d H:i:s");
        $weixin_consume_rec3 = array(
            'id' => generateNum(),
            'type' => 1,
            'app_id' =>Config::APPID,
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
            echo json_encode(array('jsapi'=>$jsApiParameters,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }

    public function notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        if($notify->checkSignSub() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
        if($notify->checkSignSub() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['command_status'] = 2;
                $da['status'] = 1;
                $da['remarks'] = $paydata['transaction_id'];
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
            if($res->TP == '1') {
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
                    'resp_status'=>'520',
                    'create_date'=>$now,
                    'update_by'=>'1',
                    'update_date'=>$now
                );
                M('ju_command_info')->add($command_info1);
                $weixin_consume_rec3 = array(
                    'id' => generateNum(),
                    'type' => 0,
                    'app_id' =>Config::APPID,
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
                echo json_encode(array('code'=>'201','msg'=>'榨汁失败'));
            }
        }else{
            echo json_encode(array('code'=>201,'msg'=>'榨汁失败'));
        }
    }
    /*
     * 用户退款申请
     * */
    public function user_unfund(){
        $openid = trim($_GET['openid']);
        $scan_code = trim($_GET['scan_code']);
        $area_id = trim($_GET['area_id']);
        if(empty($openid)|| !isset($openid)){
            exit('参数错误');
        }
        //查出退款总金额
        $count = M('ju_device_consume_weixin_rec')->where(array('type'=>0,
            'openid'=>$openid,'command_status'=>3,
            'status'=>1,'del_flag'=>0))
            ->sum('consume_account');
        $sum = 0;
        $count = abs($count);
        //查出正在申请的金额
        $consum = M('ju_user_unfund')->where(array('openid'=>$openid,'status'=>0))->sum('count');
        if($count-$consum <= 0){
            $sum = 0;
        } else {
            $sum = $count-$consum;
        }
        $this->assign('sums',$sum);
        $this->assign('openid',$openid);
        $this->assign('area_id',$area_id);
        $this->assign('scan_code',$scan_code);
        $this->display();
    }
    /*
     * 用户申请提交
     * */
    public function user_cash(){
        $openid = trim($_POST['openid']);
        $sum = trim($_POST['sum']);
        $scan_code = trim($_POST['scan_code']);
        $area_id = trim($_POST['area_id']);
        $today = date('Y-m-d 00:00:00');
        $where['openid'] = $openid;
        $where['status'] = 0;
        $where['create_date'] = array('egt', $today);
        if(M('ju_user_unfund')->where($where)->find()){
            echo json_encode(array('code'=>500,'msg'=>'每天只能申请一次哦，请明天再来(｀・ω・´)'));
            exit();
        }
        $data['id'] = generateId();
        $data['openid'] = $openid;
        $data['create_by'] = $openid;
        $data['remarks'] = '正在申请退款';
        $data['count'] = $sum;
        $data['out_trade_no'] = generateId();
        $data['status'] = 0;
        $data['scan_code'] = $scan_code;
        $data['area_id'] = $area_id;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        if(M('ju_user_unfund')->add($data)){
            echo json_encode(array('code'=>200,'msg'=>'申请成功，退款审核会在1-3个工作日退款，请留意退款消息','url'=>U('JuicerCode/index',array('openid'=>$openid,'scan_code'=>$scan_code))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'申请失败'));
        }
    }
}