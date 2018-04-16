<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 微信支付
 * */
class ZhijinpayAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    /*=================厕纸机微信支付 [[==================*/
    public function ceji_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $user_id = M('device_info')->where(array('device_command'=>$device_command,'del_flag'=>'0'))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'Wap/Notify/notify',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$user_id,
            'owner_id'=>$user_id,
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
    public function ceji_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = intval(trim($_POST['price']));
        $weixin_userinfo = M('weixin_userinfo');
        $weixin_userinfo->startTrans();
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 2;
        //更新微信支付表
        $weixin_id = M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($data);
        $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
            'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
        $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
            'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
        $all = '';
        if(intval($total1-$total2)<=0 ){
            $all = 0;
        } else {
            $all = intval($total1-$total2);
        }
        $da['remarks'] = $all;
        M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($da);
        if($weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }
    /*=================厕纸机微信支付 ]]==================*/
    //纸巾启动
    public function start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $price = $_POST['price'];
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $huodao = $_POST['huodao'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $times = $_POST['times'];//包数
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
            $timeout = '0';//为“秒”，默认“0”{"OP_HD":1,"TG_NUM":1,"TG_MES":"1366565"}
            $sms = array("OP_HD"=>intval($huodao),'TG_NUM'=>intval($times),'TG_MES'=>only_order());
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->zhijin->send_data_to_edp($device_command, $qos, $timeout, $sms);
            $return_result = 0;
            if (empty($result)) {
                $return_result = 0;
                $error_code = $this->zhijin->error_no();
                $error = $this->zhijin->error();
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
                'device_type'=>'10',
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
                    'create_by' =>$owner_id,
                    'update_by' => '1',
                    'type' => '17',
                    'update_date' => $now
                );
                $device_consume_rec = $model->add($weixin_consume_rec);
            if($command_infos  && $device_consume_rec) {
                $model->commit();
                $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
                $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                echo json_encode(array('code'=>200,'msg'=>'已经开始工作','count'=>intval($total1-$total2)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中'));
            }
        }
    }
}
?>