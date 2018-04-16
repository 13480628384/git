<?php
/*
 * date 2016-11-24
 * auhtor sniperchw
 * 支付宝支付
 * */
class AlipayAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    /*=======================黄玫瑰支付宝支付 [[==========================*/
    public function alipay_buy_yellow(){
        $number      = trim($_POST['number']);
        $user_id             = trim($_POST['user_id']);
        $quotient_id         = trim($_POST['quotient_id']);
        $price         = trim($_POST['price']);
        $scan_code         = trim($_POST['scan_code']);
        $weixin_alipay_type         = trim($_POST['weixin_alipay_type']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('yellow_rose_url'),
            get_dirname_url().'config/alipay_yellow_rose_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => AlipayConfig::APPID,
            'user_id' => $user_id,
            'account'=>$number,
            'price'=>$price,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/alipay_yellow_rose_notify.php',
            'body'=>'深圳玫瑰物联支付宝支付-黄玫瑰支付',
            'type'=>2,
            'quotient_id'=>$quotient_id,
            'create_by'=>$user_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($weixin_pay_rec);
        session('yellow_rose_alipay_user_id',$user_id);
        session('scan_code',$scan_code);
        session('yellow_rose_alipay_quotient_id',$quotient_id);
        session('weixin_alipay_type',$weixin_alipay_type);
        echo $result;
    }
    public function yellow_rose_url(){
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
        $user_id = session('yellow_rose_alipay_user_id');
        $quotient_id = session('yellow_rose_alipay_quotient_id');
        $weixin_alipay_type = session('weixin_alipay_type');
        $scan_code = session('scan_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $dataed['pay_status'] = 1;
            $dataed['update_date'] = date('Y-m-d H:i:s',time());
            $mdoel = M('rose_eco_business_recharge_record');
            $uid = $mdoel->where(array(
                'user_id'=>$user_id,
                'app_id'=>AlipayConfig::APPID,
                'out_trade_no'=>$data['out_trade_no'],
                'type'=>2
            ))->save($dataed);
            $counts = $mdoel->where(array(
                'quotient_id'=>$quotient_id,
                'type'=>array('in','1,2'),
                'pay_status'=>1
            ))->sum('account');
            $rose['update_date'] = date('Y-m-d H:i:s',time());
            $rose['yellow_rose'] = $counts;
            $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'del_flag'=>0))->save($rose);
            header('Location:'.U('Adv/adv',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$quotient_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    /*=======================黄玫瑰支付宝支付 ]]==========================*/
    /*=======================红玫瑰支付宝支付 [[==========================*/
    public function alipay_send_red(){
        $number      = trim($_POST['number']);
        $user_id             = trim($_POST['user_id']);//赠送人id
        $quotient_id         = trim($_POST['quotient_id']);
        $send_id         = trim($_POST['send_id']);//支付人的id
        $price         = trim($_POST['price']);
        $weixin_alipay_type         = trim($_POST['weixin_alipay_type']);
        $scan_code         = trim($_POST['scan_code']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('sned_red_rose_url'),
            get_dirname_url().'config/alipay_red_send_rose_notify.php',$price,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'app_id' => AlipayConfig::APPID,
            'user_id' => $send_id,
            'account'=>$number,
            'price'=>$price,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/alipay_red_send_rose_notify.php',
            'body'=>'深圳玫瑰物联支付宝支付-红玫瑰支付',
            'type'=>4,
            'quotient_id'=>$quotient_id,
            'give_quotient_id'=>$send_id,
            'create_by'=>$send_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($weixin_pay_rec);
        session('red_rose_alipay_user_id',$user_id);
        session('red_rose_alipay_buyer_id',$send_id);
        session('red_rose_alipay_quotient_id',$quotient_id);
        session('weixin_alipay_type',$weixin_alipay_type);
        session('scan_code',$scan_code);
        session('number',$number);
        echo $result;
    }
    public function sned_red_rose_url(){
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
        $send_id = session('red_rose_alipay_buyer_id');
        $number = session('number');
        $scan_code = session('scan_code');
        $user_id = session('red_rose_alipay_user_id');
        $quotient_id = session('red_rose_alipay_quotient_id');
        $weixin_alipay_type = session('weixin_alipay_type');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $dataed['pay_status'] = 1;
            $dataed['update_date'] = date('Y-m-d H:i:s',time());
            $mdoel = M('rose_eco_business_recharge_record');
            $uid = $mdoel->where(array(
                'user_id'=>$send_id,
                'app_id'=>AlipayConfig::APPID,
                'out_trade_no'=>$data['out_trade_no'],
                'type'=>4
            ))->save($dataed);
            /*$counts = $mdoel->where(array(
                'quotient_id'=>$quotient_id,
                'type'=>array('in','3,4'),
                'pay_status'=>1
            ))->sum('account');
            $rose['update_date'] = date('Y-m-d H:i:s',time());
            $rose['red_rose'] = $counts;*/
            $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'del_flag'=>0))->setInc('red_rose',$number);
            header('Location:'.U('V_2AlipayDollMachine/index',array('user_id'=>$send_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code)));
            exit;
        }
    }
    /*=======================红玫瑰支付宝支付 ]]==========================*/
    /*=======================生态红玫瑰支付宝支付 [[==========================*/
    public function buy_red_rose(){
        $buyrose_number      = trim($_POST['buyrose_number']);
        $user_id             = trim($_POST['user_id']);
        $quotient_id         = trim($_POST['quotient_id']);
        $weixin_alipay_type         = trim($_POST['weixin_alipay_type']);
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('red_rose_url'),
            get_dirname_url().'config/alipay_red_rose_notify.php',$buyrose_number/10,$out_trade_no);
        $now = date("Y-m-d H:i:s");
        $alipay_pay_rec = array(
            'id' => generateNum(),
            'app_id' =>AlipayConfig::APPID,
            'user_id' => $user_id,
            'account'=>$buyrose_number,
            'price'=>$buyrose_number/10,
            'pay_status'=>0,
            'out_trade_no'=>$out_trade_no,
            'notify_url'=>get_dirname_url().'config/alipay_red_rose_notify.php',
            'body'=>'深圳玫瑰物联微信支付-生态红玫瑰支付',
            'type'=>6,
            'quotient_id'=>$quotient_id,
            'create_by'=>$user_id,
            'create_date'=>$now,
            'update_date'=>$now,
            'return_timetamp'=>$now,
        );
        $uid = M('rose_eco_business_recharge_record')->add($alipay_pay_rec);
        session('red_rose_alipay_buyer_id',$user_id);
        session('red_rose_alipay_quotient_id',$quotient_id);
        session('weixin_alipay_type',$weixin_alipay_type);
        echo $result;
    }
    public function red_rose_url(){
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
        $user_id = session('red_rose_alipay_buyer_id');
        $quotient_id = session('red_rose_alipay_quotient_id');
        $weixin_alipay_type = session('weixin_alipay_type');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result) {
            $dataed['pay_status'] = 1;
            $dataed['update_date'] = date('Y-m-d H:i:s',time());
            $mdoel = M('rose_eco_business_recharge_record');
            $uid = $mdoel->where(array(
                'user_id'=>$user_id,
                'app_id'=>AlipayConfig::APPID,
                'out_trade_no'=>$data['out_trade_no'],
                'type'=>6
            ))->save($dataed);
            $counts = $mdoel->where(array(
                'quotient_id'=>$quotient_id,
                'type'=>array('in','5,6'),
                'pay_status'=>1
            ))->sum('account');
            $rose['update_date'] = date('Y-m-d H:i:s',time());
            $rose['ecological_red_rose'] = $counts;
            $cid = M('rose_user_info')->where(array('id'=>$quotient_id,'buyer_id'=>$user_id,'del_flag'=>0))->save($rose);
            header('Location:'.U('V_2Rose/quotient',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type)));
            exit;
        }
    }
    /*=======================生态红玫瑰支付宝支付 ]]==========================*/
    //娃娃机支付宝支付
    public function alipay_pay(){
        $price = trim($_POST['price']);
        $buyer_id = trim($_POST['buyer_id']);
        $scan_code = trim($_POST['scan_code']);
        if(empty($price) || !isset($price) || empty($buyer_id) || !isset($buyer_id)){
            exit(json_encode(array('code'=>209)));
        }
        $out_trade_no = generateId();
        $result = $this->alipayreturn(Get_Url().U('return_url'),get_dirname_url().'config/alipay_notify.php',$price,$out_trade_no);
        $date = date('Y-m-d H:i:s',time());
        $data['id'] = generateNum();
        $data['app_id'] = AlipayConfig::APPID;
        $data['out_trade_no'] = $out_trade_no;
        $data['return_url'] = U('return_url');
        $data['notify_url'] = U('notify_url');
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
        session('doll_alipay_buyer_id',$buyer_id);
        session('doll_alipay_scan_code',$scan_code);
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
        $buyer_id = session('doll_alipay_buyer_id');
        $scan_code = session('doll_alipay_scan_code');
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
            header('Location:'.U('V_2AlipayDollMachine/index',array('user_id'=>$buyer_id,'scan_code'=>$scan_code)));
            exit;
        }
    }
    public function notify_url(){
        $logpath = LOG_PATH.date('y_m_d').'.alipay.log';
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
        Log::write(json_encode($data),'INFOdf','',$logpath,'');
        $verify_result = $aop->rsaCheckV1($data,$aop->alipayPublicKey);
        if($verify_result) {
            $date = date('Y-m-d H:i:s',time());
            if($data['trade_status'] == 'TRADE_SUCCESS' || $data['trade_status'] == 'TRADE_FINISHED') {
                //更新支付信息
                $update['trade_status'] = 'TRADE_SUCCESS';
                $update['trade_no'] = $data['trade_no'];
                $update['update_date'] = $date;
                $update['notify_time'] = $date;
                $up = M('alipay_pay_rec')->where(array(
                    'buyer_id'=>$data['buyer_id'],
                    'out_trade_no'=>$data['out_trade_no'],
                    'del_flag'=>0,
                ))->save($update);
                $map['trade_status'] = 'TRADE_SUCCESS';
                $map['del_flag'] = array('eq', 0);
                $map['is_close'] = array('eq', 0);
                $map['app_id'] = AlipayConfig::APPID;
                $map['buyer_id'] = $data['buyer_id'];
                //找出支付成功的总金额
                $count = M('alipay_pay_rec')->where($map)->sum('total_amount');
                $coun['pay_total_account'] = $count;
                $coun['update_date'] = $date;
                $co = M('alipay_userinfo')->where($map)->save($coun);
                Log::write(json_encode($data),'INFO','',$logpath,'');
            }
        }
    }
}
?>