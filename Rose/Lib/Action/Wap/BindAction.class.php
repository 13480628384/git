<?php
require_once "WxPay.JsApiPay.php";
require_once "Alipay/function.php";
class BindAction extends Action{
    public function binding(){
            $weixin_alipay_type = '';
            $user_id = '';
            $scan_code = $_GET['scan_code'];
            $typesd = $_SERVER['HTTP_USER_AGENT'];
            if( strpos($typesd,'MicroMessenger')>0 ){
                $tools = new JsApiPay();
                $openid = $tools->GetOpenid();
                $user_id = $openid;
                $weixin_alipay_type = 1;
            } elseif(strpos($typesd,'AlipayClient') > 0) {
                $user_id = get_user_info();
                $weixin_alipay_type = 2;
            } else{
                exit('请用微信或支付宝打开');
            }
            //$user_id = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
            //$weixin_alipay_type = 1;
            $this->assign('weixin_alipay_type',$weixin_alipay_type);
            $this->assign('user_id',$user_id);
            $this->assign('scan_code',$scan_code);
            $this->display();
    }
    /*
     * 绑定验证
     *
     * */
    public function BindVerfityCode(){
        $username = trim($this->_post('username'));
        $code = $this->_post('code');
        $phone = trim($this->_post('phone'));
        $weixin_alipay_type = $this->_post('weixin_alipay_type');
        $user_id = $this->_post('user_id');
        $scan_code = $this->_post('scan_code');
        $login_time = session('login_time');
        $SHORT_MESSAGE = session('SHORT_MESSAGE');
        //微信已经绑定或者支付宝已经绑定 [[
        $io = M('rose_user_info')->where(array('phone'=>$phone,'del_flag'=>0))->find();
        if($io) {
            if ($weixin_alipay_type == 1) {
                //微信
                $dataalis['openid'] = $user_id;
                $dataalis['update_date'] = date('Y-m-d H:i:s', time());
                $alis = M('rose_user_info')->where(array('phone' => $phone, 'del_flag' => 0))->save($dataalis);
                if ($alis) {
                    echo json_encode(array('code' => 200, 'msg' => '绑定成功',
                        'url' => U('V_2Rose/vip_personal', array('user_id' => $user_id, 'weixin_alipay_type' => 'alipay', 'scan_code' => $scan_code))));
                    exit;
                }
            } else if ($weixin_alipay_type == 2) {
                //支付宝
                $dataali['buyer_id'] = $user_id;
                $dataali['update_date'] = date('Y-m-d H:i:s', time());
                $ali = M('rose_user_info')->where(array('phone' => $phone, 'del_flag' => 0))->save($dataali);
                if ($ali) {
                    echo json_encode(array('code' => 200, 'msg' => '绑定成功',
                        'url' => U('V_2Rose/vip_personal', array('user_id' => $user_id, 'weixin_alipay_type' => 'alipay', 'scan_code' => $scan_code))));
                    exit;
                }
            }
        }
        //微信已经绑定或者支付宝已经绑定 ]]
        if($SHORT_MESSAGE != $code){
            echo json_encode(array('code'=>500,'error'=>'验证码错误'));
            exit();
        } elseif( time()-intval($login_time)>60 || empty($SHORT_MESSAGE) ){
            session($SHORT_MESSAGE,'');
            exit('验证码过期了');
        }
        //判断手机号码是否已经注册 [[
        $model = M('rose_user_info');
        $ISphone = $model->where(array('phone'=>$phone,'del_flag'=>0))->find();
        if($ISphone){
            exit(json_encode(array('code'=>500,'error'=>'手机号码已注册')));
        }

        $ISusername = $model->where(array('nickname'=>$username,'del_flag'=>0))->find();
        if($ISusername){
            exit(json_encode(array('code'=>500,'error'=>'昵称已被使用')));
        }
        //判断手机号码是否已经注册 ]]
        //判断用户是否已经注册 [[
        $borwser = '';
        if($weixin_alipay_type == 1){
            //微信
            $borwser = 'wechat';
        }else if($weixin_alipay_type == 2){
            //支付宝
            $borwser = 'alipay';
        }
        $where['openid'] = $user_id;
        $where['_logic'] = 'or';
        $where['buyer_id'] = $user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $userinfo = $model->where($map)->find();
        if($userinfo){
            exit(json_encode(array('code'=>500,'error'=>'你已经注册了')));
        }
        //判断用户是否已经注册 ]]
        if($weixin_alipay_type == 1){
            $logins['openid'] = $user_id;
        }else{
            $logins['buyer_id'] = $user_id;
        }
        $logins['id'] = generateNum();
        $logins['nickname'] = $username;
        $logins['phone'] = $phone;
        $logins['rose_id'] = ROSE_ID();
        $logins['create_date'] = date('Y-m-d H:i:s',time());
        $logins['update_date'] = date('Y-m-d H:i:s',time());
        $logins['type'] = 1;
        $logins['del_flag'] = 0;
        $success = $model->add($logins);
        if($success) {
            echo json_encode(array('code' => 200, 'msg' => '绑定成功',
                'url' => U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$borwser,'scan_code'=>$scan_code))));
        }else{
            echo json_encode(array('code' => 300));
        }
    }
    /*
     * 发送手机验证码
     * */
    public function shortmessage(){
        $phone = trim($this->_post('phone'));
        if(empty($phone)){
            exit(json_encode(array('code'=>800)));
        }
        $model = M('rose_user_info');
        $ISphone = $model->where(array('phone'=>$phone,'del_flag'=>0))->find();
        if($ISphone){
            exit(json_encode(array('code'=>500,'error'=>'手机号码已注册')));
        }
        $Code = make_rand();
        $result = shortsmessage($phone,$Code);
        if($result == true){
            session('SHORT_MESSAGE',$Code);
            session('login_time',time());
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>400));
        }
    }
}