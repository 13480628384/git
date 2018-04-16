<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 9:55
 */
class Glass_PayAction extends BackAction
{
    protected function _initialize()
    {
        parent::_initialize();
        define('CURRENT_FILE_PATH', dirname(__FILE__));
    }
    public function pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price,
            get_dirname_url().'Wap/Glass_notify/notify',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'type' => 1,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$owner_id,
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        $cid = M('glass_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function pay_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = intval(trim($_POST['price']));
        $weixin_userinfo = M('weixin_userinfo');
        $weixin_userinfo->startTrans();
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 2;
        //更新微信支付表
        $weixin_id = M('glass_pay_rec')->where(array(
            'type'=>1,
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($data);
        $total1 = M('glass_pay_rec')->where(array('from_username'=>$openid,
            'pay_status'=>'1','is_close'=>0,'del_flag'=>0,'type'=>1))->sum('pay_account');
        $total2 = M('glass_consume_rec')->where(array('from_username'=>$openid,
            'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
        $all = '';
        if(intval($total1-$total2)<=0 ){
            $all = 0;
        } else {
            $all = intval($total1-$total2);
        }
        if($weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }
    //启动
    public function start(){
        $openid = $_POST['openid'];
        $buyer_id = $_POST['buyer_id'];
        $nums = $_POST['nums'];//通道号
        $device_command = $_POST['device_command'];
        $device_id = $_POST['device_id'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $miao = intval($_POST['miao']);
        $price = intval($_POST['price']);
        if($weixin_alipay_type == 'wechat'){
            $total1 = M('glass_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0,'type'=>1))->sum('pay_account');
            $total2 = M('glass_consume_rec')->where(array('from_username'=>$openid,
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
            $sms = array("OP_HD"=>intval($nums),'TG_TIME'=>$miao,'TG_MES'=>only_order());
            $model = M('glass_consume_rec');
            $model->startTrans();
            $result = $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
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
                'device_type'=>'1',
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
                'type' => '1',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
            $consume_accounts = $model->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid,
            ))->sum('consume_account');
            if($command_infos && $device_consume_rec) {
                $model->commit();
                $total1 = M('glass_pay_rec')->where(array('from_username'=>$openid,
                    'pay_status'=>'1','is_close'=>0,'del_flag'=>0,'type'=>1))->sum('pay_account');
                $total2 = M('glass_consume_rec')->where(array('from_username'=>$openid,
                    'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
                echo json_encode(array('code'=>200,'msg'=>'已经开始工作','count'=>intval($total1-$total2)));
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
            $sms = array("OP_HD"=>intval($nums),'TG_TIME'=>$miao,'TG_MES'=>only_order());
            $model = M('device_consume_rec');
            $model->startTrans();
            $result = $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
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
                'device_type'=>'9',
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
                echo json_encode(array('code'=>200,'msg'=>'机器已启动，请准备操作','count'=>intval($countall)));
            } else {
                $model->rollback();
                echo json_encode(array('code'=>500,'msg'=>'临时维护中,请点击其他字母启动'));
            }
        }
    }
    //会员充值
    public function pay_chongzhi(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price,
            get_dirname_url().'Wap/Glass_notify/chongzhi_notify',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'type' => 2,
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
        $cid = M('glass_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function pay_chongzhi_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = trim($_POST['price']);
        $glass_total = trim($_POST['glass_total']);
        $glass_user = M('glass_user');
        $glass_user->startTrans();
        //余数是多出的钱，留着下次输入的值相加
        $sql_totals = $glass_user->where(array('user_id' => $openid))->getField('totals');
        $now_total = $sql_totals+$price;//5+12=17
        $yushu = intval($now_total % $glass_total);//17%10=7
        $cishu = intval($now_total / $glass_total);//17/10=1
        $data['totals'] = $yushu;
        $glass_user->where(array('user_id' => $openid))->save($data);
        $glass_user->where(array('user_id' => $openid))->setInc('time_nums', $cishu);

        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 2;
        //更新微信支付表
        $weixin_id = M('glass_pay_rec')->where(array(
            'from_username'=>$openid,
            'type'=>2,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($data);
        if($weixin_id){
            $glass_user->commit();
            $time_nums = $glass_user->where(array('user_id' => $openid))->getField('time_nums');
            echo json_encode(array('code'=>200,'msg'=>$time_nums));
        } else {
            $glass_user->rollback();
            echo json_encode(array('code'=>201,'msg'=>'0'));
        }
    }
    //vip 充值
    public function vip_pay_chongzhi(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $cishu = trim($_POST['cishu']);
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price,
            get_dirname_url().'Wap/Glass_notify/vip_chongzhi_notify',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'type' =>2,
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$owner_id,
            'update_by'=>$cishu,
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        $cid = M('glass_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    //vip 更新
    public function vip_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = trim($_POST['price']);
        $glass_total = trim($_POST['glass_total']);
        $glass_user = M('glass_user');
        $cishu2 = trim($_POST['cishu']);
        $glass_user->startTrans();
        //余数是多出的钱，留着下次输入的值相加
        $sql_totals = $glass_user->where(array('user_id' => $openid))->getField('totals');
        $now_total = $sql_totals+$price;// 9+2 = 11
        $yushu = intval($now_total % $glass_total);// 1
        $cishu = intval($now_total / $glass_total);
        $data['totals'] = $yushu;
        $glass_user->where(array('user_id' => $openid))->save($data);
        $glass_user->where(array('user_id' => $openid))->setInc('time_nums', $cishu);
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = $cishu2;
        //更新微信支付表
        $weixin_id = M('glass_pay_rec')->where(array(
            'from_username'=>$openid,
            'type'=>2,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($data);
        //$userinfo_id = $glass_user->where(array('user_id'=>$openid))->setInc('totals',$price);
        if($weixin_id){
            $glass_user->commit();
            $time_nums = $glass_user->where(array('user_id' => $openid))->getField('time_nums');
            echo json_encode(array('code'=>200,'msg'=>$time_nums));
        } else {
            $glass_user->rollback();
            echo json_encode(array('code'=>201,'msg'=>'0'));
        }
    }
}