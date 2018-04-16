<?php
/*
 * 微信-支付扫码
 * @param scan_code 微信扫码的二维码信息
 * author sniperchw
 * date 2016/12/27
 * */
class V_2WechantDollMachineAction extends BackAction{
    public $weixin_alipay_type = null;
    public $user_id = null;
    public $scan_code = null;
    protected function _initialize(){
        parent::_initialize();
        if($_REQUEST['user_id']){
            $this->user_id =$_REQUEST['user_id'];
        }
        if($_REQUEST['weixin_alipay_type']){
            $this->weixin_alipay_type =$_REQUEST['weixin_alipay_type'];
        }
        if($_REQUEST['scan_code']){
            $this->scan_code =$_REQUEST['scan_code'];
            session('weixin_scan_code',$_REQUEST['scan_code']);
        }
    }
    public function index(){
        $DeviceOnlineModel = M('device_info');
        $IFOnlineRow = $DeviceOnlineModel->where(array('scan_code'=>$this->scan_code,
            'device_status'=>1,'del_flag'=>0))->find();
        if( $IFOnlineRow == false){
            exit('页面参数错误，请重新扫描');
        }
        //余额查询
        $weixin_info = M('weixin_userinfo');
        $count = $weixin_info->where(array(
            'status'=>1,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$this->user_id
        ))->find();
        if(!$count){
            $weixin_userinfo = array(
                'id' =>  generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => $this->user_id,
                'total_account' => 0,
                'status'=>'1',
                'create_date'=>date('Y-m-d H:i:s',time()),
                'create_by'=>'1',
                'update_by'=>'1',
                'update_date'=>date('Y-m-d H:i:s',time())
            );
            $add = $weixin_info->add($weixin_userinfo);
        }
       /* $total_count = '';
        if( intval($count['pay_total_account']-$count['consume_total_account'])<=0 ) {
            $total_count = 0;
        } else {
            $total_count = intval($count['pay_total_account']-$count['consume_total_account']);
        }*/
        $total1 = M('weixin_pay_rec')->where(array('from_username'=>$this->user_id,
            'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
        $total2 = M('device_consume_rec')->where(array('from_username'=>$this->user_id,
            'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
        $countall = $total1-$total2;
        $total_count = '';
        if($countall<=0){
            $total_count = 0;
        } else {
            $total_count = trim(intval($countall));
        }
        $di_id = M('device_info')->where(array('scan_code'=>$this->scan_code,
            'device_status'=>1,'del_flag'=>0))->find();
        //查询群组设备是否有在线的，没有就不能充值
        $ok = M('device_relation_group')->where(array('di_id'=>$di_id['id'],'del_falg'=>0))->getField('dgi_id');
        $isok = M('device_relation_group')->where(array('dgi_id'=>$ok,'del_flag'=>0,'online_status'=>1))->find();
        $inok = '';
        if(!$isok){
            $inok = 1;
        }
        $device_relation_group = M('device_relation_group')->query("SELECT drg.* FROM device_relation_group drg,
	    device_relation_group one_drg
        WHERE
            drg. STATUS = '1'
        AND one_drg.del_flag = '0'
        AND drg.dgi_id = one_drg.dgi_id
        AND drg.del_flag = '0'
        AND drg.device_type = 1
        AND one_drg.di_id = '$di_id[id]'
        ORDER BY
	    drg.ords");
        foreach($device_relation_group as $key => $value){
            if($value['device_command'] == $di_id['device_command']){
                $device_relation_group[$key]['on'] = 1;
            }
            $device_relation_group[$key]['pay_price'] = intval($value['pay_price']);
        }
        //红玫瑰余额
        $red_rose = M('rose_user_info')->where(array('openid'=>$this->user_id,'del_flag'=>0))->find();
        $roseed = M('rose_user_info')->where(array('openid'=>$this->user_id,'del_flag'=>0))->find();
        /*==========================导流广告 [[==========================*/
        $rose_adv = M('rose_eco_advertising_info')->where(array(
            'del_flag'=>0,
            'audit_status'=>1,
            'online'=>1
        ))->order('rand()')->limit(2)->select();
        //消耗黄玫瑰和添加展示数
        foreach($rose_adv as $key=>$v){
            M('rose_eco_advertising_info')->where(array('id'=>$v['id']))->setInc('show_number');
            $quoention = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->find();
            if(intval($quoention['yellow_rose'])>0){
                M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->setDec('yellow_rose');
                $rose_adv[$key]['count'] = 2;
            }
        }
        $this->assign('rose_adv',$rose_adv);
        /*==========================导流广告 ]]==========================*/
        if($roseed['red_rose']<=0){
            $co = 0;
        } else {
            $co = $roseed['red_rose'];
        }
        $this->assign('co',$co);
        $this->assign('openid',$this->user_id);
        $this->assign('red_rose',$red_rose);
        $this->assign('total_count',$total_count);
        $this->assign('device_relation_group',$device_relation_group);
        $this->assign('inok',$inok);
        $this->display();
    }
    //查询设备是否在线
    public function online_status(){
        $device_command = trim($_POST['device_command']);
        $id = M('device_relation_group')->where(array('del_flag'=>0,
            'online_status'=>0,
            'device_command'=>$device_command,
            'status'=>1
        ))->find();
        if($id){
            echo json_encode(array('code'=>200));
        } else {
            echo json_encode(array('code'=>500));
        }
    }
    //启动设备
    public function send_device_command(){
        $di_id = isset($_POST['di_id'])?$_POST["di_id"]:'';
        $device_command = isset($_POST['device_command'])?$_POST["device_command"]:'';
        $price = trim($_POST['price']);
        $openid = trim($_POST['openid']);
        $group_word = trim($_POST['group_word']);
        if($di_id == '' || $device_command == '' && $openid=='' && $price==''){
            echo json_encode(array('code'=>201,'msg'=>'缺少参数'));
            exit;
        }
        //查询余额是否足够
        $count = M('weixin_userinfo')->where(array(
            'status'=>1,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->find();
        if( intval($count['pay_total_account']-$count['consume_total_account'])<=0 ) {
            echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
            exit;
        }
        $id = M('device_relation_group')->where(array('del_flag'=>0,
            'online_status'=>0,
            'device_command'=>$device_command,
            'status'=>1
        ))->find();
        if($id){
            echo json_encode(array('code'=>203,'msg'=>'设备不在线，请启动其他设备'));
            exit;
        }
        $qos = '1'; //1需要响应  0 不需要响应
        $timeout = '0';//为“秒”，默认“0”
        $sms = array("TG_NUM"=>intval($price));
        $model = M('device_consume_rec');
        $model->startTrans();
        $result = $this->sm->send_data_to_edp($device_command, $qos, $timeout, $sms);
        $return_result = 0;
        if (empty($result)) {
            $return_result = 0;
            $error_code = $this->sm->error_no();
            $error = $this->sm->error();
            echo json_encode(array('code'=>203,'msg'=>$group_word.'设备启动失败，请启动其他设备'));
            exit;
        } else {
            $return_result = $result['cmd_uuid'];
        }
        $now = date("Y-m-d H:i:s");
        $command_info = array(
            'id' => generateNum(),
            'cmd_id' => $return_result,
            'di_id' => $di_id,
            'deivce_command' =>$device_command ,
            'status' => '1',
            'resp_status'=>'100',
            'create_date'=>$now,
            'update_by'=>'1',
            'update_date'=>$now
        );
        $command_infos = M('command_info')->add($command_info);
        $owner_id = M('device_info')->where(array('id'=>$di_id,'del_flag'=>0))->getField('owner_id');
        //消费记录
        for($i=0;$i<$price;$i++) {
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => $openid,
                'consume_account' =>1,
                'command_status' => '1',
                'consume_status' => '1',
                'di_id' => $di_id,
                'deivce_command' => $device_command,
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' =>$owner_id,
                'update_by' => '1',
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
            'consume_total_account' => $consume_accounts ,
            'update_date' =>$now,
            'update_by'=>'4'
        );
        $weixin_userinfo = M('weixin_userinfo')->where(array(
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->save($weixin_userinfo_account);
        if($command_infos && $weixin_userinfo && $device_consume_rec) {
            $model->commit();
            /*$countall = M('weixin_userinfo')->where(array(
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$openid,
                'del_flag'=>0
            ))->sum('pay_total_account-consume_total_account');*/
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>$openid,
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>$openid,
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            $countall = $total1-$total2;
            $pay = '';
            if($countall<=0){
                $pay = 0;
            } else {
                $pay = trim(intval($countall));
            }
            echo json_encode(array('code'=>204,'msg'=>$group_word.'机器已启动，请准备操作','count'=>intval($pay)));
        } else {
            $model->rollback();
            echo json_encode(array('code'=>205,'msg'=>$group_word.'临时维护中,请点击其他字母启动'));
        }
    }
    //玫瑰启动设备
    public function send_rose_device_command(){
        $di_id = isset($_POST['di_id'])?$_POST["di_id"]:'';
        $device_command = isset($_POST['device_command'])?$_POST["device_command"]:'';
        $price = trim($_POST['price']);
        $rose_start = ($price/10);
        $openid = trim($_POST['openid']);
        $group_word = trim($_POST['group_word']);
        if($di_id == '' || $device_command == '' && $openid=='' && $price==''){
            echo json_encode(array('code'=>201,'msg'=>'缺少参数'));
            exit;
        }
        //查询余额是否足够
        $count = M('rose_user_info')->where(array(
            'openid'=>$openid,
            'del_flag'=>0
        ))->find();
        if( intval($count['red_rose'])<=0 ) {
            echo json_encode(array('code'=>202,'msg'=>'余额不足,请充值'));
            exit;
        }
        $id = M('device_relation_group')->where(array('del_flag'=>0,
            'online_status'=>0,
            'device_command'=>$device_command,
            'status'=>1
        ))->find();
        if($id){
            echo json_encode(array('code'=>203,'msg'=>'设备不在线，请启动其他设备'));
            exit;
        }
        $qos = '1'; //1需要响应  0 不需要响应
        $timeout = '0';//为“秒”，默认“0”
        $sms = array("TG_NUM"=>intval($rose_start));
        $model = M('device_consume_rec');
        $model->startTrans();
        $result = $this->sm->send_data_to_edp($device_command, $qos, $timeout, $sms);
        $return_result = 0;
        if (empty($result)) {
            $return_result = 0;
            $error_code = $this->sm->error_no();
            $error = $this->sm->error();
            echo json_encode(array('code'=>203,'msg'=>$group_word.'设备启动失败，请启动其他设备'));
            exit;
        } else {
            $return_result = $result['cmd_uuid'];
        }
        $now = date("Y-m-d H:i:s");
        $command_info = array(
            'id' => generateNum(),
            'cmd_id' => $return_result,
            'di_id' => $di_id,
            'deivce_command' =>$device_command ,
            'status' => '1',
            'resp_status'=>'100',
            'create_date'=>$now,
            'update_by'=>'1',
            'update_date'=>$now
        );
        $command_infos = M('command_info')->add($command_info);
        $owner_id = M('device_info')->where(array('id'=>$di_id,'del_flag'=>0))->getField('owner_id');
        //消费记录
        for($i=0;$i<$price;$i++) {
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'type' => 7,
                'from_username' => $openid,
                'consume_account' =>1,
                'command_status' => '1',
                'consume_status' => '1',
                'di_id' => $di_id,
                'deivce_command' => $device_command,
                'cmd_uuid' => $return_result,
                'create_date' => $now,
                'create_by' =>$owner_id,
                'update_by' => '1',
                'update_date' => $now
            );
            $device_consume_rec = $model->add($weixin_consume_rec);
        }
        $weixin_userinfo = M('rose_user_info')->where(array(
            'openid'=>$openid,
            'del_flag'=>0
        ))->setDec('red_rose',$price);
        if($command_infos && $weixin_userinfo && $device_consume_rec) {
            $model->commit();
            $counted = M('rose_user_info')->where(array(
                'openid'=>$openid,
                'del_flag'=>0
            ))->getField('red_rose');
            $c = '';
            if($counted<=0){
                $c = 0;
            } else {
                $c=$counted;
            }
            echo json_encode(array('code'=>204,'msg'=>$group_word.'机器已启动，请准备操作','count'=>intval($c)));
        } else {
            $model->rollback();
            echo json_encode(array('code'=>205,'msg'=>$group_word.'临时维护中,请点击其他字母启动'));
        }
    }
}
?>