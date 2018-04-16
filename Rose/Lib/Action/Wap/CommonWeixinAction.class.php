<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 微信支付
 * */
class CommonWeixinAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    /*=================厕纸机微信支付 [[==================*/
    public function ceji_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/ceji_notify.php',$out_trade_no);
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
        $cpay = M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'pay_status'=>1,
            'is_close'=>0,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID
        ))->sum('pay_account');
        $wei['pay_total_account'] = $cpay;
        $wei['update_date'] = date("Y-m-d H:i:s");
        $wei['update_by'] = 2;
        $userinfo_id = M('weixin_userinfo')->where(array(
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->save($wei);
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
        if($userinfo_id && $weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }
    /*=================厕纸机微信支付 ]]==================*/
    /*=================洗衣机微信支付 [[==================*/
    public function washing_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/washing_notify.php',$out_trade_no);
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
    public function washing_update(){
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
    /*=================洗衣机微信支付 ]]==================*/

    /*=================按摩椅微信支付 [[============================*/
    public function anm_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/anm_notify.php',$out_trade_no);
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
    public function anm_update(){
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
        if(intval($total1-$total2) <=0 ){
            $all = 0;
        } else {
            $all = intval($total1-$total2);
        }
        $f['remarks'] =$all;
        $weixin_ids = M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($f);
        if($weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }
    /*=================按摩椅微信支付 ]]============================*/

    /*===========================电动车微信支付 [[===============================*/
    public function vehicle_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $owner_id = M('device_info')->where(array('del_flag'=>'0','device_command'=>$device_command))->getField('owner_id');
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/vehicle_notify.php',$out_trade_no);
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
    public function vehicle_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = intval(trim($_POST['price']));
        $weixin_userinfo = M('weixin_userinfo');
        $weixin_userinfo->startTrans();
        //$data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 'update';
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
        if(intval($total1-$total2) <=0 ){
            $all = 0;
        } else {
            $all = intval($total1-$total2);
        }
        $f['remarks'] =$all;
        M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($f);
        if($weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }
    /*===========================电动车微信支付 ]]===============================*/

    /*===========================洗车微信支付 [[===============================*/
    public function car_pay(){
        exit();
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $device_command = trim($_POST['device_command']);
        $user = M('device_info')->where(array('device_command'=>$device_command,
            'del_flag'=>'0'))->find();
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'config/car_notify.php',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$user['owner_id'],
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        /*用户充值的钱直接到商户账上*/
        $weixin_consume_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => '洗车',
            'consume_account' =>$price,
            'command_status' => '1',//只有支付成功才改为2
            'consume_status' => '0',//支付成功后改为2会进入运营商
            'di_id' => $user['id'],
            'deivce_command' => $device_command,
            'cmd_uuid' => $out_trade_no,
            'create_date' => date('Y-m-d H:i:s',time()),
            'create_by' =>$user['owner_id'],
            'update_by' =>$openid,
            'type' => '15',
            'update_date' => date('Y-m-d H:i:s',time())
        );
        M('device_consume_rec')->add($weixin_consume_rec);
        /*用户充值的钱直接到商户账上*/
        $cid = M('weixin_pay_rec')->add($weixin_pay_rec);
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function car_update(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $price = intval(trim($_POST['price']));
        $weixin_userinfo = M('weixin_userinfo');
        $weixin_userinfo->startTrans();
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 2;
        //更新运营商得到的钱
        $desc['command_status'] = '2';
        $desc['consume_status'] = '1';
        M('device_consume_rec')->where(array(
            'cmd_uuid'=>$out_trade_no,
            'update_by'=>$openid,
            'type'=>'15'
        ))->save($desc);
        //更新微信支付表
        $weixin_id = M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'out_trade_no'=>$out_trade_no,
            'app_id'=>WxPayConfig::APPID))
            ->save($data);
        $cpay = M('weixin_pay_rec')->where(array(
            'from_username'=>$openid,
            'pay_status'=>1,
            'is_close'=>0,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID
        ))->sum('pay_account');
        $wei['pay_total_account'] = $cpay;
        $wei['update_date'] = date("Y-m-d H:i:s");
        $wei['update_by'] = 2;
        $userinfo_id = M('weixin_userinfo')->where(array(
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->save($wei);
        /*$count = $weixin_userinfo->where(array('status'=>1,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->sum('pay_total_account-consume_total_account');*/

        $total1 = M('weixin_pay_rec')->where(array('from_username'=>trim($openid),
            'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
        $total2 = M('device_consume_rec')->where(array('from_username'=>trim($openid),
            'is_close'=>1,'del_flag'=>0,'command_status'=>array('in','1,2','consume_status'=>'1')))->sum('consume_account');
        $count = $total1-$total2;
        $all = '';
        if($count <=0 ){
            $all = 0;
        } else {
            $all = $count;
        }
        if($userinfo_id && $weixin_id){
            $weixin_userinfo->commit();
            echo json_encode(array('code'=>200,'msg'=>intval($all)));
        } else {
            $weixin_userinfo->rollback();
            echo json_encode(array('code'=>201,'msg'=>intval($all)));
        }
    }

    //洗车微信支付归属到商户账户
    public function car_pay_owner(){
        if(IS_POST){
            $price = $_POST['price'];
            $openid = trim($_POST['openid']);
            $device_command = trim($_POST['device_command']);
            $user = M('device_info')->where(array('device_command'=>$device_command,
                'del_flag'=>'0'))->find();
            $out_trade_no = generateId();
            $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
                get_dirname_url().'config/car_notify.php',$out_trade_no);
            $weixin_pay_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => $openid,
                'pay_status'=>'0',
                'out_trade_no'=>$out_trade_no,
                'pay_account' => $price,
                'is_close' => 1,
                'contents' => $jsapi,
                'create_date'=>date('Y-m-d H:i:s',time()),
                'create_by'=>$user['owner_id'],
                'owner_id'=>$user['owner_id'],
                'update_by'=>'1',
                'update_date'=>date('Y-m-d H:i:s',time())
            );
            /*用户充值的钱直接到商户账上*/
            $weixin_consume_rec = array(
                'id' => generateNum(),
                'app_id' => WxPayConfig::APPID,
                'from_username' => '洗车',
                'consume_account' =>$price,
                'command_status' => '1',//只有支付成功才改为2
                'consume_status' => '0',//支付成功后改为1会进入运营商
                'is_close' => 1,
                'di_id' => $user['id'],
                'deivce_command' => $device_command,
                'cmd_uuid' => $out_trade_no,
                'create_date' => date('Y-m-d H:i:s',time()),
                'create_by' =>$user['owner_id'],
                'owner_id' =>$user['owner_id'],
                'update_by' =>$openid,
                'type' => '15',
                'update_date' => date('Y-m-d H:i:s',time())
            );
            $ss = M('device_consume_rec')->add($weixin_consume_rec);
            /*用户充值的钱直接到商户账上*/
            $cid = M('weixin_pay_rec')->add($weixin_pay_rec);
            if($cid){
                echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
            } else {
                json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
            }
        }
    }
    public function car_update_owner(){
            $openid = trim($_POST['openid']);
            $out_trade_no = trim($_POST['out_trade_no']);
            $device_command = trim($_POST['device_command']);
            $price = intval(trim($_POST['price']));
            $weixin_userinfo = M('weixin_userinfo');
            $user = M('device_info')->where(array('device_command'=>$device_command,
                'del_flag'=>'0'))->find();
            $weixin_userinfo->startTrans();
            $data['pay_status'] = 1;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['update_by'] = 2;
            //更新运营商得到的钱
            $desc['command_status'] = '2';
            $desc['consume_status'] = '1';
            M('device_consume_rec')->where(array(
                'cmd_uuid'=>$out_trade_no,
                'update_by'=>$openid,
                'type'=>'15'
            ))->save($desc);
            //更新微信支付表
            $weixin_id = M('weixin_pay_rec')->where(array(
                'from_username'=>$openid,
                'out_trade_no'=>$out_trade_no,
                'app_id'=>WxPayConfig::APPID))
                ->save($data);
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>trim($openid),
                'pay_status'=>'1','is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id']))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>trim($openid),
                'is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id'],'consume_status'=>'2','command_status'=>array('in','1,2')))->sum('consume_account');
            $count = $total1-$total2;
            $all = '';
            if($count <=0 ){
                $all = 0;
            } else {
                $all = $count;
            }
            if($weixin_id){
                $weixin_userinfo->commit();
                echo json_encode(array('code'=>200,'msg'=>intval($all)));
            } else {
                $weixin_userinfo->rollback();
                echo json_encode(array('code'=>201,'msg'=>intval($all)));
            }
    }
}
?>