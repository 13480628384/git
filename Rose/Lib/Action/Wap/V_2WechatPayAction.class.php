<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 微信-支付扫码
 * */
class V_2WechatPayAction extends BackAction{
    public $weixin_alipay_type = null;
    public $user_id = null;
    public $scan_code = null;
    public $NOTIFY_URL = null;
    protected function _initialize(){
        parent::_initialize();
        $this->NOTIFY_URL = C('WEIXIN_NOTIFY_URL');
    }
    /*=================微信购买黄玫瑰 [[==================*/
    public function weixin_buy_yellow(){
        $number      = trim($_POST['number']);
        $user_id             = trim($_POST['user_id']);
        $quotient_id         = trim($_POST['quotient_id']);
        $price         = trim($_POST['price']);
        $out_trade_no = generateId();
        $jsapipay = $this->Weixin_Pay_Result($user_id,$price*100,get_dirname_url().'config/weixinpay_yellow_rose_notify.php',$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'user_id' => $user_id,
            'account'=>$number,
            'price'=>$price,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/weixinpay_yellow_rose_notify.php',
            'body'=>'深圳玫瑰物联微信支付-黄玫瑰支付',
            'type'=>1,
            'quotient_id'=>$quotient_id,
            'create_by'=>$user_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($weixin_pay_rec);
        if($uid){
            echo json_encode(array('code'=>200,'jsapi'=>$jsapipay,'out_trade_no'=>$out_trade_no));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付错误，请重新支付'));
        }
    }
    public function weixin_buy_yellow_update(){
        $out_trade_no = $_POST['out_trade_no'];
        $quotient_id = $_POST['quotient_id'];
        $user_id = $_POST['user_id'];
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $mdoel = M('rose_eco_business_recharge_record');
        $uid = $mdoel->where(array(
            'user_id'=>$user_id,
            'app_id'=>WxPayConfig::APPID,
            'out_trade_no'=>$out_trade_no,
            'type'=>1
        ))->save($data);
        $counts = $mdoel->where(array(
            'quotient_id'=>$quotient_id,
            'type'=>array('in','1,2'),
            'pay_status'=>1
        ))->sum('account');
        $rose['update_date'] = date('Y-m-d H:i:s',time());
        $rose['yellow_rose'] = $counts;
        $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->save($rose);
        if($cid && $uid){
            $count = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->getField('yellow_rose');
            echo json_encode(array('code'=>200,'count'=>intval($count)));
        } else {
            echo json_encode(array('code'=>500));
        }
    }
    /*=================微信购买黄玫瑰 ]]==================*/


    /*=================微信购买红玫瑰 [[==================*/
    public function weixin_send_red(){
        $number      = trim($_POST['number']);
        $user_id             = trim($_POST['user_id']);
        $quotient_id         = trim($_POST['quotient_id']);
        $send_id         = trim($_POST['send_id']);
        $price         = trim($_POST['price']);
        $out_trade_no = generateId();
        $jsapipay = $this->Weixin_Pay_Result($send_id,$price*100,get_dirname_url().'config/weixinpay_red_send_rose_notify.php',$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'user_id' => $send_id,
            'account'=>$number,
            'price'=>$price,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/weixinpay_red_send_rose_notify.php',
            'body'=>'深圳玫瑰物联微信支付-红玫瑰支付',
            'type'=>3,
            'quotient_id'=>$quotient_id,
            'give_quotient_id'=>$send_id,
            'create_by'=>$send_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($weixin_pay_rec);
        if($uid){
            echo json_encode(array('code'=>200,'jsapi'=>$jsapipay,'out_trade_no'=>$out_trade_no));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付错误，请重新支付'));
        }
    }
    public function weixin_send_red_update(){
        $out_trade_no = $_POST['out_trade_no'];
        $quotient_id = $_POST['quotient_id'];
        $user_id = $_POST['user_id'];
        $send_id = $_POST['send_id'];
        $number = $_POST['number'];
        $weixin_alipay_type = $_POST['weixin_alipay_type'];
        $scan_code = $_POST['scan_code'];
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $mdoel = M('rose_eco_business_recharge_record');
        $uid = $mdoel->where(array(
            'user_id'=>$send_id,
            'app_id'=>WxPayConfig::APPID,
            'out_trade_no'=>$out_trade_no,
            'type'=>3
        ))->save($data);
        /*$counts = $mdoel->where(array(
            'quotient_id'=>$quotient_id,
            'type'=>array('in','3,4'),
            'pay_status'=>1
        ))->sum('account');
        $rose['update_date'] = date('Y-m-d H:i:s',time());
        $rose['red_rose'] = $counts;*/
        $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->setInc('red_rose',intval($number));
        if($cid && $uid){
            //$count = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->getField('ecological_red_rose');
            echo json_encode(array('code'=>200,'url'=>U('V_2WechantDollMachine/index',
                array('user_id'=>$send_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code))));
        } else {
            echo json_encode(array('code'=>500));
        }
    }
    /*=================微信购买红玫瑰 ]]==================*/


    /*=================微信购买生态红玫瑰 [[==================*/
    public function buy_red_rose(){
        $buyrose_number      = trim($_POST['buyrose_number']);
        $user_id             = trim($_POST['user_id']);
        $quotient_id         = trim($_POST['quotient_id']);
        $out_trade_no = generateId();
        $jsapipay = $this->Weixin_Pay_Result($user_id,($buyrose_number/10)*100,get_dirname_url().'config/weixinpay_red_rose_notify.php',$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'user_id' => $user_id,
            'account'=>$buyrose_number,
            'price'=>$buyrose_number/10,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/weixinpay_red_rose_notify.php',
            'body'=>'深圳玫瑰物联微信支付-生态红玫瑰支付',
            'type'=>5,
            'quotient_id'=>$quotient_id,
            'create_by'=>$user_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($weixin_pay_rec);
        //echo M('rose_eco_business_recharge_record')->getLastSql();die;
        if($uid){
            echo json_encode(array('code'=>200,'jsapi'=>$jsapipay,'out_trade_no'=>$out_trade_no));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付错误，请重新支付'));
        }
    }
    public function rose_update(){
        $out_trade_no = $_POST['out_trade_no'];
        $quotient_id = $_POST['quotient_id'];
        $user_id = $_POST['user_id'];
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $mdoel = M('rose_eco_business_recharge_record');
        $uid = $mdoel->where(array(
            'user_id'=>$user_id,
            'app_id'=>WxPayConfig::APPID,
            'out_trade_no'=>$out_trade_no,
            'type'=>5
        ))->save($data);
        $counts = $mdoel->where(array(
            'quotient_id'=>$quotient_id,
            'type'=>array('in','5,6'),
            'pay_status'=>1
        ))->sum('account');
        $rose['update_date'] = date('Y-m-d H:i:s',time());
        $rose['ecological_red_rose'] = $counts;
        $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->save($rose);
        if($cid && $uid){
            $count = M('rose_user_info')->where(array('id'=>$quotient_id,'openid'=>$user_id,'del_flag'=>0))->getField('ecological_red_rose');
            echo json_encode(array('code'=>200,'count'=>$count));
        } else {
            echo json_encode(array('code'=>500));
        }
    }
    /*=================微信购买生态红玫瑰 ]]==================*/
    //娃娃机微信支付
    public function weixin_pay(){
        $out_trade_no = generateId();
        $openid = trim($_POST['openid']);
        $price = trim($_POST['price']);
        $jsapipay = $this->Weixin_Pay_Result($openid,$price*100,get_dirname_url().'config/weixinpay_notify.php',$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => WxPayConfig::APPID,
            'from_username' => $openid,
            'pay_status'=>'0',
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapipay,
            'create_date'=>$now,
            'create_by'=>'1',
            'update_by'=>'1',
            'update_date'=>$now
        );
        $uid = M('weixin_pay_rec')->add($weixin_pay_rec);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>$jsapipay,'out_trade_no'=>$out_trade_no));
        } else {
            echo json_encode(array('code'=>201,'msg'=>'支付错误，请重新支付'));
        }
    }
    public function weixin_pay_update(){
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
        $count = $weixin_userinfo->where(array('status'=>1,
            'del_flag'=>0,
            'app_id'=>WxPayConfig::APPID,
            'from_username'=>$openid
        ))->sum('pay_total_account-consume_total_account');
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
}
?>