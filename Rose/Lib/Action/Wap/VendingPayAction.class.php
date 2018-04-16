<?php
/*
 * date 2017/9/15
 * auhtor sniperchw
 * 微信支付
 * */
class VendingPayAction extends BackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    public function weixin_pay(){
        $price = $_POST['price'];
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $rose = trim($_POST['rose']);
        $device_code = trim($_POST['device_code']);
        $out_trade_no = generateId();
        $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
            get_dirname_url().'Wap/VendingNotify/weixin_notify',$out_trade_no);
        $weixin_pay_rec = array(
            'id' => generateNum(),
            'type'=>'1',
            'app_id' => WxPayConfig::APPID,
            'wechat_alipay_id' => $openid,
            'pay_status'=>'0',
            'is_close'=>'0',
            'owner_id'=>$owner_id,
            'out_trade_no'=>$out_trade_no,
            'pay_account' => $price,
            'contents' => $jsapi,
            'create_date'=>date('Y-m-d H:i:s',time()),
            'create_by'=>$openid,
            'remarks'=>$device_code,
            'update_date'=>date('Y-m-d H:i:s',time())
        );
        $cid = M('goods_weixin_alipay_pay_rec')->add($weixin_pay_rec);

        //赠送玫瑰币 START
        if(!empty($rose) || $rose !=0){
            $weixin_rose_rec = array(
                'id' => generateNum(),
                'type'=>'1',
                'wechat_alipay_id' => $openid,
                'status'=>'0',
                'is_close'=>'0',
                'owner_id'=>$owner_id,
                'out_trade_no'=>$out_trade_no,
                'account' => $rose,
                'create_date'=>date('Y-m-d H:i:s',time()),
                'create_by'=>$openid,
                'remarks'=>$device_code,
                'update_date'=>date('Y-m-d H:i:s',time())
            );
            M('goods_rose')->add($weixin_rose_rec);
        }
        //赠送玫瑰币 END
        if($cid){
            echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
        } else {
            echo json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
        }
    }
    public function weixin_update(){
        $openid = trim($_POST['openid']);
        $device_code = trim($_POST['device_code']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $goods_weixin_alipay_pay_rec = M('goods_weixin_alipay_pay_rec');
        $goods_weixin_alipay_pay_rec->startTrans();
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $result = $goods_weixin_alipay_pay_rec->where(array(
            'out_trade_no'=>$out_trade_no,'wechat_alipay_id'=>$openid,'type'=>'1'))->save($data);
        //赠送玫瑰更新 START
        $rose['status'] = 1;
        $rose['update_date'] = date('Y-m-d H:i:s',time());
        M('goods_rose')->where(array('del_flag'=>'0','wechat_alipay_id'=>$openid,'type'=>'1',
            'out_trade_no'=>$out_trade_no))->save($rose);
        //赠送玫瑰更新 END
        if($result){
            $goods_weixin_alipay_pay_rec->commit();
            echo json_encode(array('code'=>'200','url'=>U('VendingWeixin/index',array('openid'=>$openid,'device_code'=>$device_code))));
        } else {
            $goods_weixin_alipay_pay_rec->rollback();
            echo json_encode(array('code'=>'500'));
        }
    }

    //支付宝支付
    public function alipay_pay(){
        if(IS_POST){
            $price = (float)$_POST['price'];
            $buyer_id = trim($_POST['buyer_id']);
            $owner_id = trim($_POST['owner_id']);
            $rose = trim($_POST['rose']);
            $device_code = trim($_POST['device_code']);
            $out_trade_no = only_order();
            $result = $this->alipayreturn(Get_Url().U('alipay_update'),
                get_dirname_url().'Wap/VendingNotify/alipay_notify',$price,$out_trade_no);
            $alipay_pay_rec = array(
                'id' => generateNum(),
                'type'=>'2',
                'app_id' =>AlipayConfig::APPID,
                'wechat_alipay_id' => $buyer_id,
                'pay_status'=>'0',
                'is_close'=>'0',
                'owner_id'=>$owner_id,
                'out_trade_no'=>$out_trade_no,
                'pay_account' => $price,
                'contents' => $result,
                'create_date'=>date('Y-m-d H:i:s',time()),
                'create_by'=>$buyer_id,
                'remarks'=>$device_code,
                'update_date'=>date('Y-m-d H:i:s',time())
            );
            M('goods_weixin_alipay_pay_rec')->add($alipay_pay_rec);
            //赠送玫瑰币 START
            if(!empty($rose) || $rose !=0){
                $weixin_rose_rec = array(
                    'id' => generateNum(),
                    'type'=>'2',
                    'wechat_alipay_id' => $buyer_id,
                    'status'=>'0',
                    'is_close'=>'0',
                    'owner_id'=>$owner_id,
                    'out_trade_no'=>$out_trade_no,
                    'account' => $rose,
                    'create_date'=>date('Y-m-d H:i:s',time()),
                    'create_by'=>$buyer_id,
                    'remarks'=>$device_code,
                    'update_date'=>date('Y-m-d H:i:s',time())
                );
                M('goods_rose')->add($weixin_rose_rec);
            }
            //赠送玫瑰币 END
            session('buyer_id',$buyer_id);
            session('device_code',$device_code);
            echo $result;
        }
    }
    //支付宝支付更新
    public function alipay_update(){
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
        $buyer_id = session('buyer_id');
        $device_code = session('device_code');
        $verify_result = $aop->verifyNotice($data);
        if($verify_result){
            $dataed['pay_status'] = '1';
            $dataed['update_date'] = $date;
            //$dataed['transaction_id'] = $data['trade_no'];
            $where['out_trade_no'] = $data['out_trade_no'];
            $where['del_flag'] = 0;
            $where['wechat_alipay_id'] = $buyer_id;
            M('goods_weixin_alipay_pay_rec')->where($where)->save($dataed);

            //赠送玫瑰更新 START
            $rose['status'] = 1;
            $rose['update_date'] = date('Y-m-d H:i:s',time());
            M('goods_rose')->where(array('del_flag'=>'0','wechat_alipay_id'=>$buyer_id,'type'=>'2',
                'out_trade_no'=>$data['out_trade_no']))->save($rose);
            header('Location:'.U('VendingAlipay/index',array('buyer_id'=>$buyer_id,'device_code'=>$device_code)));
            exit;
        }
    }
}
?>