<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 支付宝支付
 * */
class StartAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    /*
     * ===============================
     * 洗车启动
     * ===============================
     * */
    public function car_start(){
        $openid = $_POST['openid'];//微信
        $buyer_id = $_POST['buyer_id'];//支付宝
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
            $count = $total1-$total2;
            if( $count<=0 ) {
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
            $result = $this->zizhu->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->zizhu->error_no();
                $error = $this->zizhu->error();
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
                'device_type'=>'7',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
            $device_consume_rec = '';
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
                'create_by' =>'旧版本洗车用户消费的，不再进入到商家账户上',
                'update_by' => '1',
                'type' => '15',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos  && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                $countall = $total1-$total2;
                if($countall <0){
                    $countalls = 0;
                }else{
                    $countalls = $countall;
                }
                echo json_encode(array('code'=>200,'msg'=>'已经开始工作','count'=>intval($countalls)));
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
            $result = $this->zizhu->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->zizhu->error_no();
                $error = $this->zizhu->error();
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
                'device_type'=>'7',
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
                'create_by' =>'旧版本洗车用户消费的，不再进入到商家账户上',
                'update_by' => '1',
                'type' => '16',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos && $device_consume_rec) {
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
                if($countall <0){
                    $countalls = 0;
                }else{
                    $countalls = $countall;
                }
                echo json_encode(array('code'=>200,'msg'=>'已启动，请准备操作','count'=>intval($countalls)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        }
    }


    //微信洗车商户
    public function car_start_pay(){
        $openid = $_POST['openid'];//微信
        $buyer_id = $_POST['buyer_id'];//支付宝
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $user = M('device_info')->where(array('device_command'=>$device_command,
            'del_flag'=>'0'))->find();
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];
        if($weixin_alipay_type == 'wechat'){
            //查询余额是否足够
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id']))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id'],'consume_status'=>2,'command_status'=>array('in','1,2')))->sum('consume_account');
            $count = $total1-$total2;
            if( $count<=0 ) {
                echo json_encode(array('code'=>500,'msg'=>'洗额不足,请充值'));
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
            $result = $this->zizhu->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->zizhu->error_no();
                $error = $this->zizhu->error();
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
                'device_type'=>'7',
                'create_date'=>$now,
                'update_by'=>'1',
                'update_date'=>$now
            );
            $command_infos = M('command_info')->add($command_info);
            $owner_id = M('device_info')->where(array('id'=>$device_id,'del_flag'=>0))->getField('owner_id');
            //消费记录
            $device_consume_rec = '';
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => $openid,
                'consume_account' =>$price,
                'command_status' => '1',
                'transfer_status' => '1',
                'consume_status' => '2',
                'is_close' => '1',
                'di_id' => $device_id,
                'deivce_command' => $device_command,
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' =>$owner_id,
                'owner_id' =>$owner_id,
                'update_by' => '1',
                'type' => '15',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id']))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id'],'consume_status'=>2,'command_status'=>array('in','1,2')))->sum('consume_account');
                $count = $total1-$total2;
                if( $count<=0 ) {
                    $countall = 0;
                } else {
                    $countall = $count;
                }
                echo json_encode(array('code'=>200,'msg'=>'已经开始工作','count'=>intval($countall)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        } else if($weixin_alipay_type == 'alipay'){
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>1,'buyer_id'=>$buyer_id
            ,'app_id'=>AlipayConfig::APPID,'owner_id'=>$user['owner_id']))->sum('total_amount');
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>1,
                'del_flag'=>0,
                'consume_status'=>2,
                'owner_id'=>$user['owner_id'],
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
                echo json_encode(array('code'=>202,'msg'=>'洗额不足,请充值'));
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
            $result = $this->zizhu->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->zizhu->error_no();
                $error = $this->zizhu->error();
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
                'device_type'=>'7',
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
                    'consume_status' => '2',
                    'transfer_status' => '1',
                    'is_close' => '1',
                    'di_id' => $device_id,
                    'deivce_command' => $device_command,
                    'cmd_uuid' => $return_result,
                    'create_date' => $now,
                    'create_by' =>$owner_id,
                    'owner_id' =>$owner_id,
                    'update_by' => '1',
                    'type' => '16',
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos  && $device_consume_rec) {
                $model->commit();
                $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
                ,'del_flag'=>0,'is_close'=>1,'buyer_id'=>$buyer_id
                ,'app_id'=>AlipayConfig::APPID,'owner_id'=>$user['owner_id']))->sum('total_amount');
                $consume_accounts = M('device_consume_rec')->where(array(
                    'command_status'=>array('in','1,2'),
                    'is_close'=>1,
                    'del_flag'=>0,
                    'consume_status'=>2,
                    'owner_id'=>$user['owner_id'],
                    'from_username'=>$buyer_id,
                ))->sum('consume_account');
                $countall = $coun_apl-$consume_accounts;
                echo json_encode(array('code'=>200,'msg'=>'已启动，请准备操作','count'=>intval($countall)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        }
    }
}
?>