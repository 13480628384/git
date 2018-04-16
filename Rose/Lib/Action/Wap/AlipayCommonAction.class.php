<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 支付宝支付
 * */
class AlipayCommonAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    //厕纸机免费启动
    public function mian_ceji(){
        $price = $_POST['price'];
        $device_id = $_POST['device_id'];
        $all_duan = $_POST['times'];
        $device_command = $_POST['device_command'];
        $qos = '1'; //1需要响应  0 不需要响应
        $timeout = '0';//为“秒”，默认“0”
        $sms = array('TG_MOT'=>intval($all_duan),"P_Y"=>intval($price));
        $result = $this->hyzn->send_data_to_edp($device_command, $qos, $timeout, $sms);
        /*if($device_command == '17109372'){
            $result = $this->hyzn->send_data_to_edp($device_command, $qos, $timeout, $sms);
        }else{
            $result = $this->ceji->send_data_to_edp($device_command, $qos, $timeout, $sms);
        }*/
        $return_result = 0;
        if (empty($result)) {
            $return_result = 0;
            $error_code = $this->hyzn->error_no();
            $error = $this->hyzn->error();
            echo json_encode(array('code'=>500,'msg'=>'领取失败'));
            exit;
        } else {
            $return_result = $result['cmd_uuid'];
        }
        $now = date("Y-m-d H:i:s");
        $command_info = array(
            'id' => generateNum(),
            'cmd_id' => $return_result,
            'di_id' => $device_id,
            'deivce_command' =>$device_command ,
            'status' => '1',
            'resp_status'=>'100',
            'device_type'=>'8',
            'create_date'=>$now,
            'update_by'=>'1',
            'update_date'=>$now
        );
        $command_infos = M('command_info')->add($command_info);
        if($command_infos){
            echo json_encode(array('code'=>500,'msg'=>'领取成功'));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'领取失败'));
        }
    }
    //厕纸机启动
    public function ceji_start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $all_duan = $_POST['times'];
        if($weixin_alipay_type == 'wechat'){
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            if( intval($total1-$total2)<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array('TG_MOT'=>intval($all_duan),"P_Y"=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->ceji->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->ceji->error_no();
                $error = $this->ceji->error();
                echo json_encode(array('code'=>500,'msg'=>'领取失败'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'device_type'=>'8',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            //消费记录
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            for($i=0;$i<$price;$i++) {
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => WxPayConfig::APPID,
                    'from_username' => $openid,
                    'consume_account' =>1,
                    'command_status' => '1',
                    'consume_status' => '1',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' => $owner_id,
                    'update_by' => '1',
                    'type' => '17',
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            }
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid,
            ))->sum('consume_account');
            $weixin_userinfo_account = array(
                'consume_total_account' => $consume_accounts,
                'update_date' =>$now,
                'update_by'=>'4'
            );
            $weixin_userinfo = M('weixin_userinfo')->where(array(
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid
            ))->save($weixin_userinfo_account);
            if($command_infos && $weixin_userinfo && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                echo json_encode(array('code'=>200,'msg'=>'领取成功','count'=>intval($total1-$total2)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'领取失败'));
            }
        } else if($weixin_alipay_type == 'alipay'){
            $alipay_userinfo = M('alipay_userinfo');
            //查询余额是否足够
            $count = $alipay_userinfo->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'buyer_id'=>$buyer_id
            ))->sum("pay_total_account-consume_total_account");
            $total_count = '';
            if( intval($count)<=0 ) {
                $total_count = 0;
            } else {
                $total_count = $count;
            }
            if( intval($total_count)<=0 ) {
                echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("P_Y"=>intval($price),'TG_MOT'=>$all_duan);
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->washing->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->sm->error_no();
                $error = $this->sm->error();
                echo json_encode(array('code'=>500,'msg'=>'领取失败'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$now,
                'device_type'=>'8',
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
            for($i=0;$i<$price;$i++) {
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => AlipayConfig::APPID,
                    'from_username' => $buyer_id,
                    'consume_account' =>1,
                    'command_status' => '1',
                    'consume_status' => '1',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$owner_id,
                    'update_by' => '1',
                    'type' => '18',
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            }
            //查出总的消费金额
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>$buyer_id,
            ))->sum('consume_account');
            $weixin_userinfo_account = array(
                'consume_total_account' => $consume_accounts ,
                'update_date' =>$now,
                'update_by'=>'4'
            );
            $weixin_userinfo = M('alipay_userinfo')->where(array(
                'app_id'=>AlipayConfig::APPID,
                'buyer_id'=>$buyer_id,
                'del_flag'=>0
            ))->save($weixin_userinfo_account);
            if($command_infos && $weixin_userinfo && $device_consume_rec) {
                $model->commit();
                $countall = M('alipay_userinfo')->where(array(
                    'app_id'=>AlipayConfig::APPID,
                    'buyer_id'=>$buyer_id
                ))->sum('pay_total_account-consume_total_account');
                echo json_encode(array('code'=>200,'msg'=>'领取成功','count'=>intval($countall)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'领取失败'));
            }
        }
    }
    //洗衣机启动
    public function start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];
        if($weixin_alipay_type == 'wechat'){
            //查询余额是否足够
           /* $count = M('weixin_userinfo')->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid
            ))->find();
            if( intval($count['pay_total_account']-$count['consume_total_account'])<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }*/
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            if( intval($total1-$total2)<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("TG_NUM"=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->washing->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->washing->error_no();
                $error = $this->washing->error();
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'device_type'=>'5',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            //消费记录
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => $openid,
                'consume_account' =>$price,
                'command_status' => '1',
                'consume_status' => '1',
                'di_id' => $device_id,
                'deivce_command' => $device_command,
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' => $owner_id,
                'update_by' => '1',
                'type' => '9',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos  && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                $alls =$total1-$total2;
                if($alls <= 0){
                    $aylf = 0;
                } else {
                    $aylf = $alls;
                }
                echo json_encode(array('code'=>200,'msg'=>'洗衣机已经开始工作','count'=>intval($aylf)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中,请点击其他字母启动'));
            }
        } else if($weixin_alipay_type == 'alipay'){
            $alipay_userinfo = M('alipay_userinfo');
            //查询余额是否足够
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>$buyer_id,
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>$buyer_id,
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
            $total_count = '';
            if( intval($count)<=0 ) {
                $total_count = 0;
            } else {
                $total_count = $count;
            }
            if( intval($total_count)<=0 ) {
                echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("TG_NUM"=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->washing->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$now,
                'device_type'=>'5',
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => AlipayConfig::APPID,
                'from_username' => $buyer_id,
                'consume_account' =>$price,
                'command_status' => '1',
                'consume_status' => '1',
                'di_id' => $device_id,
                'deivce_command' => $device_command,
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' =>$owner_id,
                'update_by' => '1',
                'type' => '10',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos  && $device_consume_rec) {
                $model->commit();
                $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
                ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>$buyer_id,
                    'app_id'=>AlipayConfig::APPID))->sum('total_amount');
                $consume_accounts = M('device_consume_rec')->where(array(
                    'command_status'=>array('in','1,2'),
                    'is_close'=>0,
                    'del_flag'=>0,
                    'app_id'=>AlipayConfig::APPID,
                    'from_username'=>$buyer_id,
                ))->sum('consume_account');
                $countall = $coun_apl-$consume_accounts;
                if($countall <= 0){
                    $countalls = 0;
                }else {
                    $countalls = $countall;
                }
                echo json_encode(array('code'=>200,'msg'=>'机器已启动，请准备操作','count'=>intval($countalls)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中,请点击其他字母启动'));
            }
        }
    }
    //洗衣机支付宝支付
    public function washing_pay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $owner_id = M('device_info')->where(array('del_flag'=>'0','scan_code'=>$scan_code))->getField('owner_id');

        $result = $this->alipayreturn(Get_Url().U('washing_update'),
            get_dirname_url().'config/alipay_wash_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('washing_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_wash_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $owner_id;
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $uid = M('alipay_pay_rec')->add($data);
        session('washing_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        echo $result;
    }
    public function washing_update(){
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
        $buyer_id = session('washing_buyer_id');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>trim($buyer_id),
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>trim($buyer_id),
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
            $co = '';
            if($count<=0){
                $co = 0;
            } else {
                $co = $count;
            }
            $da['remarks'] = $co;
            M('alipay_pay_rec')->where($where)->save($da);
            header('Location:'.U('Washing/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }

    //厕纸机支付宝支付
    public function ceji_pay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('ceji_update'),
            get_dirname_url().'config/alipay_ceji_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('washing_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_ceji_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $buyer_id;
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        session('ceji_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        echo $result;
    }
    public function ceji_update(){
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
        $buyer_id = session('ceji_buyer_id');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $id = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if($id){
                $dataeds['return_timestamp'] = $data['timestamp'];
                $dataeds['update_date'] = $date;
                $dataeds_where['buyer_id'] = $buyer_id;
                $dataeds_where['out_trade_no'] = $data['out_trade_no'];
                $dataeds_where['del_flag'] = 0;
                $cid = M('alipay_pay_rec')->where($dataeds_where)->save($dataeds);
            } else {
                $dataed['id'] = generateNum();
                $dataed['app_id'] = AlipayConfig::APPID;
                $dataed['buyer_id'] = $buyer_id;
                $dataed['status'] = 1;
                $dataed['pay_total_account'] = $data['total_amount'];
                $dataed['total_account'] = 0;
                $dataed['create_by'] = $buyer_id;
                $dataed['create_date'] = $date;
                $dataed['update_by'] = $buyer_id;
                $dataed['update_date'] = $date;
                $dataed['remarks'] = '支付宝买家支付';
                $uid = M('alipay_userinfo')->add($dataed);
            }
            $map['trade_status'] = 'TRADE_SUCCESS';
            $map['del_flag'] = array('eq', 0);
            $map['is_close'] = array('eq', 0);
            $map['app_id'] = AlipayConfig::APPID;
            $map['buyer_id'] = $buyer_id;
            $count = M('alipay_pay_rec')->where($map)->sum('total_amount');
            $co = '';
            if($count<=0){
                $co = 0;
            } else {
                $co = $count;
            }
            header('Location:'.U('Ce_ji/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    //按摩椅支付
    public function anm_pay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('anm_update'),
            get_dirname_url().'config/alipay_anm_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('anm_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_anm_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $buyer_id;
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        session('anm_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        echo $result;
    }
    public function anm_update(){
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
        $buyer_id = session('anm_buyer_id');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $id = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if($id){
                $dataeds['return_timestamp'] = $data['timestamp'];
                $dataeds['update_date'] = $date;
                $dataeds_where['buyer_id'] = $buyer_id;
                $dataeds_where['out_trade_no'] = $data['out_trade_no'];
                $dataeds_where['del_flag'] = 0;
                $cid = M('alipay_pay_rec')->where($dataeds_where)->save($dataeds);
            } else {
                $dataed['id'] = generateNum();
                $dataed['app_id'] = AlipayConfig::APPID;
                $dataed['buyer_id'] = $buyer_id;
                $dataed['status'] = 1;
                $dataed['pay_total_account'] = $data['total_amount'];
                $dataed['total_account'] = 0;
                $dataed['create_by'] = $buyer_id;
                $dataed['create_date'] = $date;
                $dataed['update_by'] = $buyer_id;
                $dataed['update_date'] = $date;
                $dataed['remarks'] = '支付宝买家支付';
                $uid = M('alipay_userinfo')->add($dataed);
            }
            $map['trade_status'] = 'TRADE_SUCCESS';
            $map['del_flag'] = array('eq', 0);
            $map['is_close'] = array('eq', 0);
            $map['app_id'] = AlipayConfig::APPID;
            $map['buyer_id'] = $buyer_id;
            $count = M('alipay_pay_rec')->where($map)->sum('total_amount');
            $co = '';
            if($count<=0){
                $co = 0;
            } else {
                $co = $count;
            }
            header('Location:'.U('Anm/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    //按摩椅启动
    public function anm_start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];
        if($weixin_alipay_type == 'wechat'){
            //查询余额是否足够
            /*$count = M('weixin_userinfo')->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid
            ))->find();
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            if( intval($count['pay_total_account']-$count['consume_total_account'])<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }*/
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            if( intval($total1-$total2)<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("T_M"=>intval($times),'P_Y'=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->other->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->other->error_no();
                $error = $this->other->error();
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'device_type'=>'4',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //找出设备归属部门
            $device_info = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->find();
            $sys_user = M('sys_user')->where(array('del_flag'=>0,'id'=>$owner_id))->find();
            //查出设备所在的用户是否有两个部门
            if($device_info['company_id'] == '2017041315_E15FAA1AA148A91061FBA5A92FD89AB5'){
                if(!empty($device_info['fencheng'])){
                    $one_array = explode(',',$device_info['fencheng']);
                    $two_array = array();
                    foreach($one_array as $key => $v){
                        $two_array[$key] = explode('-',$v);

                    }
                    foreach($two_array as $k=>$v){
                        if($v[0] == '' || $v[1] == '0'){
                            unset($two_array[$k]);
                        }
                    }
                    foreach($two_array as $v){
                        $weixin_consume_rec = array(
                            'id' => generateNum(),
                            'app_id' => WxPayConfig::APPID,
                            'from_username' => $openid,
                            'consume_account' =>$price*$v[1]/100,
                            'command_status' => '1',
                            'consume_status' => '1',
                            'di_id' => $device_id,
                            'deivce_command' => $device_command,
                            'cmd_uuid' => $return_result,
                            'create_date' => $now,
                            'create_by' =>$v[0],
                            'update_by' => '1',
                            'type' => '3',
                            'update_date' => $now
                        );
                        $device_consume_rec = $model->add($weixin_consume_rec);
                    }
                } else {
                    $weixin_consume_rec3 = array(
                        'id' => generateNum(),
                        'app_id' => WxPayConfig::APPID,
                        'from_username' => $openid,
                        'consume_account' =>$price,
                        'command_status' => '1',
                        'consume_status' => '1',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $return_result,
                        'create_date' => $now,
                        'create_by' =>$owner_id,
                        'update_by' => '1',
                        'type' => '3',
                        'update_date' => $now
                    );
                    $device_consume_rec3 = $model->add($weixin_consume_rec3);
                }

            }else{
                for($i=0; $i<$price;$i++) {
                    $weixin_consume_rec3 = array(
                        'id' => generateNum(),
                        'app_id' => WxPayConfig::APPID,
                        'from_username' => $openid,
                        'consume_account' =>1,
                        'command_status' => '1',
                        'consume_status' => '1',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $return_result,
                        'create_date' => $now,
                        'create_by' =>$owner_id,
                        'update_by' => '1',
                        'type' => '3',
                        'update_date' => $now
                    );
                    $device_consume_rec3 = $model->add($weixin_consume_rec3);
                }
            }
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid,
            ))->sum('consume_account');
            $weixin_userinfo_account = array(
                'consume_total_account' => $consume_accounts,
                'update_date' =>$now,
                'update_by'=>'4'
            );
            $weixin_userinfo = M('weixin_userinfo')->where(array(
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid
            ))->save($weixin_userinfo_account);
            if($command_infos && $weixin_userinfo) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                echo json_encode(array('code'=>200,'msg'=>'按摩椅已经开始工作','count'=>intval($total1-$total2)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中,请点击其他字母启动'));
            }
        } else if($weixin_alipay_type == 'alipay'){
            $alipay_userinfo = M('alipay_userinfo');
            //查询余额是否足够
            $count = $alipay_userinfo->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'buyer_id'=>$buyer_id
            ))->sum("pay_total_account-consume_total_account");
            $total_count = '';
            if( intval($count)<=0 ) {
                $total_count = 0;
            } else {
                $total_count = $count;
            }
            if( intval($total_count)<=0 ) {
                echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("T_M"=>intval($times),'P_Y'=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->other->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->sm->error_no();
                $error = $this->sm->error();
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$now,
                'device_type'=>'4',
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //找出设备归属部门
            $device_info = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->find();
            $sys_user = M('sys_user')->where(array('del_flag'=>0,'id'=>$owner_id))->find();
            //查出设备所在的用户是否有两个部门
            if($device_info['company_id'] == '2017041315_E15FAA1AA148A91061FBA5A92FD89AB5'){
                if(!empty($device_info['fencheng'])){
                    $one_array = explode(',',$device_info['fencheng']);
                    $two_array = array();
                    foreach($one_array as $key => $v){
                        $two_array[$key] = explode('-',$v);

                    }
                    foreach($two_array as $k=>$v){
                        if($v[0] == '' || $v[1] == '0'){
                            unset($two_array[$k]);
                        }
                    }
                    foreach($two_array as $v){
                        $weixin_consume_rec = array(
                            'id' => generateNum(),
                            'app_id' =>AlipayConfig::APPID,
                            'from_username' => $buyer_id,
                            'consume_account' =>$price*$v[1]/100,
                            'command_status' => '1',
                            'consume_status' => '1',
                            'di_id' => $device_id,
                            'deivce_command' => $device_command,
                            'cmd_uuid' => $return_result,
                            'create_date' => $now,
                            'create_by' =>$v[0],
                            'update_by' => '1',
                            'type' => '4',
                            'update_date' => $now
                        );
                        $device_consume_rec = $model->add($weixin_consume_rec);
                    }
                }else{
                    $weixin_consume_rec2 = array(
                        'id' => generateNum(),
                        'app_id' =>AlipayConfig::APPID,
                        'from_username' => $buyer_id,
                        'consume_account' =>$price,
                        'command_status' => '1',
                        'consume_status' => '1',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $return_result,
                        'create_date' => $now,
                        'create_by' =>$owner_id,
                        'update_by' => '1',
                        'type' => '4',
                        'update_date' => $now
                    );
                    $device_consume_rec2 = $model->add($weixin_consume_rec2);
                }
            }else{
                for($i=0; $i<$price;$i++) {
                    $weixin_consume_rec3 = array(
                        'id' => generateNum(),
                        'app_id' => AlipayConfig::APPID,
                        'from_username' => $buyer_id,
                        'consume_account' =>1,
                        'command_status' => '1',
                        'consume_status' => '1',
                        'di_id' => $device_id,
                        'deivce_command' => $device_command,
                        'cmd_uuid' => $return_result,
                        'create_date' => $now,
                        'create_by' =>$owner_id,
                        'update_by' => '1',
                        'type' => '4',
                        'update_date' => $now
                    );
                    $device_consume_rec3 = $model->add($weixin_consume_rec3);
                }
            }
            //查出总的消费金额
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>$buyer_id,
            ))->sum('consume_account');
            $weixin_userinfo_account = array(
                'consume_total_account' => $consume_accounts ,
                'update_date' =>$now,
                'update_by'=>'4'
            );
            $weixin_userinfo = M('alipay_userinfo')->where(array(
                'app_id'=>AlipayConfig::APPID,
                'buyer_id'=>$buyer_id,
                'del_flag'=>0
            ))->save($weixin_userinfo_account);
            if($command_infos && $weixin_userinfo) {
                $model->commit();
                $countall = M('alipay_userinfo')->where(array(
                    'app_id'=>AlipayConfig::APPID,
                    'buyer_id'=>$buyer_id
                ))->sum('pay_total_account-consume_total_account');
                echo json_encode(array('code'=>200,'msg'=>'机器已启动，请准备操作','count'=>intval($countall)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中,请点击其他字母启动'));
            }
        }
    }
    /*
     * ==========================================
     * 电动车支付宝支付
     * ==========================================
     * */
    public function vehicle_pay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','scan_code'=>$scan_code))->getField('owner_id');
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('vehicle_update'),
            get_dirname_url().'config/alipay_vehicle_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('vehicle_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_vehicle_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $owner_id;
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $uid = M('alipay_pay_rec')->add($data);
        session('ve_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        echo $result;
    }
    public function vehicle_update(){
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
        $buyer_id = session('ve_buyer_id');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>$buyer_id,
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>$buyer_id,
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
            $da['remarks'] = $count;
            M('alipay_pay_rec')->where($where)->save($da);
            header('Location:'.U('Vehicle/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    /*
     * ===============================
     * 电动车启动
     * ===============================
     * */
    public function vehicle_start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];
        if($weixin_alipay_type == 'wechat'){
            //查询余额是否足够
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            if( intval($total1-$total2)<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("TG_NUM"=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->vehicle->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->vehicle->error_no();
                $error = $this->vehicle->error();
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'device_type'=>'6',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
            $device_consume_rec = '';
            for($i=0;$i<$price;$i++) {
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => WxPayConfig::APPID,
                    'from_username' => $openid,
                    'consume_account' =>1,
                    'command_status' => '1',
                    'consume_status' => '1',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$owner_id,
                    'update_by' => '1',
                    'type' => '13',//电动车微信消费
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            }
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid,
            ))->sum('consume_account');
            $weixin_userinfo_account = array(
                'consume_total_account' => $consume_accounts,
                'update_date' =>$now,
                'update_by'=>'4'
            );
            $weixin_userinfo = M('weixin_userinfo')->where(array(
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid
            ))->save($weixin_userinfo_account);
            if($command_infos && $weixin_userinfo && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                echo json_encode(array('code'=>200,'msg'=>'充电已经开始工作','count'=>intval($total1-$total2)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        } else if($weixin_alipay_type == 'alipay'){
            $alipay_userinfo = M('alipay_userinfo');
            //查询余额是否足够
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>$buyer_id,
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            //echo  M('alipay_pay_rec')->getLastSql();die;
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>$buyer_id,
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
            $total_count = '';
            if( intval($count)<=0 ) {
                $total_count = 0;
            } else {
                $total_count = $count;
            }
            if( intval($total_count)<=0 ) {
                echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
                exit;
            }
            $id = M('device_relation_group')->where(array('del_flag'=>0,
                'online_status'=>0,
                'device_command'=>$device_command,
                'status'=>1
            ))->find();
            if($id){
                echo json_encode(array('code'=>500,'msg'=>'设备不在线，请启动其他设备'));
                exit;
            }
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            $sms = array("TG_NUM"=>intval($price));
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->vehicle->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->vehicle->error_no();
                $error = $this->vehicle->error();
                echo json_encode(array('code'=>500,'msg'=>'设备启动失败，请启动其他设备'));
                exit;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            $now = date("Y-m-d H:i:s");
            $command_info = array(
                'id' => generateNum(),
                'cmd_id' => $return_result,
                'di_id' => $device_id,
                'deivce_command' =>$device_command ,
                'status' => '1',
                'resp_status'=>'100',
                'create_date'=>$now,
                'device_type'=>'6',
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
                $weixin_consume_rec = array(
                    'id' => generateNum(),
                    'app_id' => AlipayConfig::APPID,
                    'from_username' => $buyer_id,
                    'consume_account' =>$price,
                    'command_status' => '1',
                    'consume_status' => '1',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$owner_id,
                    'update_by' => '1',
                    'type' => '14',
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos && $device_consume_rec) {
                $model->commit();
                $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
                ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>$buyer_id,
                    'app_id'=>AlipayConfig::APPID))->sum('total_amount');
                //echo  M('alipay_pay_rec')->getLastSql();die;
                $consume_accounts = M('device_consume_rec')->where(array(
                    'command_status'=>array('in','1,2'),
                    'is_close'=>0,
                    'del_flag'=>0,
                    'app_id'=>AlipayConfig::APPID,
                    'from_username'=>$buyer_id,
                ))->sum('consume_account');
                $count = $coun_apl-$consume_accounts;
                $total_counst = '';
                if( intval($count)<=0 ) {
                    $total_counst = 0;
                } else {
                    $total_counst = $count;
                }
                echo json_encode(array('code'=>200,'msg'=>'充电已启动，请准备操作','count'=>intval($total_counst)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        }
    }

    /*
     * ==========================================
     * 洗车支付宝支付
     * ==========================================
     * */
    public function car_ali_pay(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('car_ali_update'),
            get_dirname_url().'config/alipay_car_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('car_ali_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_car_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $buyer_id;
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        $user = M('device_info')->where(array('scan_code'=>$scan_code,
            'del_flag'=>'0'))->find();
        /*用户充值的钱直接到商户账上*/
        $weixin_consume_rec = array(
            'id' => generateNum(),
            'app_id' => AlipayConfig::APPID,
            'from_username' => '洗车',
            'consume_account' =>$price,
            'command_status' => '1',//只有支付成功才改为2
            'consume_status' => '0',//支付成功后改为2,1代表消费成功后钱会进入运营商
            'di_id' => $user['id'],
            'deivce_command' => $user['device_command'],
            'cmd_uuid' => $out_trade_no,
            'create_date' => date('Y-m-d H:i:s',time()),
            'create_by' =>$user['owner_id'],
            'update_by' =>$buyer_id,
            'type' => '16',
            'update_date' => date('Y-m-d H:i:s',time())
        );
        M('device_consume_rec')->add($weixin_consume_rec);
        /*用户充值的钱直接到商户账上*/
        session('ve_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        session('price',$price);
        echo $result;
    }
    public function car_ali_update(){
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
        $buyer_id = session('ve_buyer_id');
        $scan_code = session('scan_code');
        $price = session('price');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            //更新运营商得到的钱
            $desc['command_status'] = '2';
            $desc['consume_status'] = '1';
            M('device_consume_rec')->where(array(
                'cmd_uuid'=>$data['out_trade_no'],
                'update_by'=>$buyer_id,
                'type'=>'16'
            ))->save($desc);
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $id = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if($id){
                $dataeds['return_timestamp'] = $data['timestamp'];
                $dataeds['update_date'] = $date;
                $dataeds_where['buyer_id'] = $buyer_id;
                $dataeds_where['out_trade_no'] = $data['out_trade_no'];
                $dataeds_where['del_flag'] = 0;
                $cid = M('alipay_pay_rec')->where($dataeds_where)->save($dataeds);
            } else {
                $dataed['id'] = generateNum();
                $dataed['app_id'] = AlipayConfig::APPID;
                $dataed['buyer_id'] = $buyer_id;
                $dataed['status'] = 1;
                $dataed['pay_total_account'] = $data['total_amount'];
                $dataed['total_account'] = 0;
                $dataed['create_by'] = $buyer_id;
                $dataed['create_date'] = $date;
                $dataed['update_by'] = $buyer_id;
                $dataed['update_date'] = $date;
                $dataed['remarks'] = '支付宝买家支付';
                $uid = M('alipay_userinfo')->add($dataed);
            }
            $map['trade_status'] = 'TRADE_SUCCESS';
            $map['del_flag'] = array('eq', 0);
            $map['is_close'] = array('eq', 0);
            $map['app_id'] = AlipayConfig::APPID;
            $map['buyer_id'] = $buyer_id;
            $count = M('alipay_pay_rec')->where($map)->sum('total_amount');
            $co = '';
            if($count<=0){
                $co = 0;
            } else {
                $co = $count;
            }
            header('Location:'.U('Car_wash/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    //洗车商户
    public function car_ali_pay_xiche(){
        $buyer_id             = trim($_POST['buyer_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('car_ali_pay_xiche_update'),
            get_dirname_url().'config/alipay_car_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
		$user = M('device_info')->where(array('scan_code'=>$scan_code,
            'del_flag'=>'0'))->find();
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('car_ali_pay_xiche_update');
        $data['notify_url'] = get_dirname_url().'config/alipay_car_notify.php';
        $data['body'] = "深圳玫瑰物联-充值：$price";
        $data['subject'] =  '深圳玫瑰物联-充值';
        $data['seller_id'] = AlipayConfig::PID;
        $data['buyer_id'] = $buyer_id;
        $data['total_amount'] = $price;
        $data['is_close'] = 1;
        $data['trade_status'] = 'WAIT_BUYER_PAY';
        $data['create_by'] = $user['owner_id'];
        $data['owner_id'] = $user['owner_id'];
        $data['create_date'] = $now;
        $data['update_by'] = $buyer_id;
        $data['update_date'] = $now;
        $data['remarks'] = $result;
        $uid = M('alipay_pay_rec')->add($data);
        /*用户充值的钱直接到商户账上*/
        $weixin_consume_rec = array(
            'id' => generateNum(),
            'app_id' => AlipayConfig::APPID,
            'from_username' => '洗车',
            'consume_account' =>$price,
            'command_status' => '1',
            'consume_status' => '0',
            'is_close' => '1',
            'di_id' => $user['id'],
            'deivce_command' => $user['device_command'],
            'cmd_uuid' => $out_trade_no,
            'create_date' => date('Y-m-d H:i:s',time()),
            'create_by' =>$user['owner_id'],
            'owner_id' =>$user['owner_id'],
            'update_by' =>$buyer_id,
            'type' => '16',
            'update_date' => date('Y-m-d H:i:s',time())
        );
        M('device_consume_rec')->add($weixin_consume_rec);
        /*用户充值的钱直接到商户账上*/
        session('ve_buyer_id',$buyer_id);
        session('scan_code',$scan_code);
        session('price',$price);
        echo $result;
    }
    public function car_ali_pay_xiche_update(){
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
        $buyer_id = session('ve_buyer_id');
        $scan_code = session('scan_code');
        $user = M('device_info')->where(array('scan_code'=>$scan_code,
            'del_flag'=>'0'))->find();
        $price = session('price');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            //更新运营商得到的钱
            $desc['command_status'] = '2';
            $desc['consume_status'] = '1';
            M('device_consume_rec')->where(array(
                'cmd_uuid'=>$data['out_trade_no'],
                'update_by'=>$buyer_id,
                'type'=>'16'
            ))->save($desc);
            $data['return_timestamp'] = $data['timestamp'];
            $data['update_date'] = $date;
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['buyer_id'] = $buyer_id;
            $result = M('alipay_pay_rec')->where($where)->save($data);
            $id = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if($id){
                $dataeds['return_timestamp'] = $data['timestamp'];
                $dataeds['update_date'] = $date;
                $dataeds_where['buyer_id'] = $buyer_id;
                $dataeds_where['out_trade_no'] = $data['out_trade_no'];
                $dataeds_where['del_flag'] = 0;
                $cid = M('alipay_pay_rec')->where($dataeds_where)->save($dataeds);
            } else {
                $dataed['id'] = generateNum();
                $dataed['app_id'] = AlipayConfig::APPID;
                $dataed['buyer_id'] = $buyer_id;
                $dataed['status'] = 1;
                $dataed['pay_total_account'] = $data['total_amount'];
                $dataed['total_account'] = 0;
                $dataed['create_by'] = $buyer_id;
                $dataed['create_date'] = $date;
                $dataed['update_by'] = $buyer_id;
                $dataed['update_date'] = $date;
                $dataed['remarks'] = '支付宝买家支付';
                $uid = M('alipay_userinfo')->add($dataed);
            }
            $map['trade_status'] = 'TRADE_SUCCESS';
            $map['del_flag'] = array('eq', 0);
            $map['is_close'] = array('eq', 0);
            $map['owner_id'] = $user['owner_id'];
            $map['app_id'] = AlipayConfig::APPID;
            $map['buyer_id'] = $buyer_id;
            $count = M('alipay_pay_rec')->where($map)->sum('total_amount');
            $co = '';
            if($count<=0){
                $co = 0;
            } else {
                $co = $count;
            }
            header('Location:'.U('Car_Wash_Pay/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
}
?>