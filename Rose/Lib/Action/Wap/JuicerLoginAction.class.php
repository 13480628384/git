<?php
session_start();
require_once "lib/WxPay.Config.php";
require_once "WxPay.JsApiPay.php";
class JuicerLoginAction extends Action {
    public  $APP_ID = null;
    public  $APP_SECRET = null;
    public  $WNO = null;
    protected function _initialize(){
        $this->APP_ID = WxPayConfig::APPID;
        $this->APP_SECRET = WxPayConfig::APPSECRET;
    }
    /*
     * 绑定用户页面展示
     * */
    public function binding(){
        $tools = new \JsApiPay();
        $openid = $tools->GetOpenid();
        $this->assign('openid',$openid);
        $this->display();
    }
    //绑定用户唯一号
    public function BindVerfityCode(){
        $openid = $this->_post('openid');
        $code = $this->_post('code');
        $phone = $this->_post('phone');
        $login_time = $_COOKIE['login_time'];
        $SHORT_MESSAGE = $_COOKIE['SHORT_MESSAGE_ROSE2'];
        if($SHORT_MESSAGE != $code){
            echo json_encode(array('code'=>500,'error'=>'验证码错误'));
            exit();
        } elseif( time()-intval($login_time)>60 || empty($SHORT_MESSAGE) ){
            echo json_encode(array('code'=>500,'error'=>'验证码过期了'));
        }
        //判断手机号码是否已经注册 [[
        $model = M('ju_users');
        $ISphone = $model->where(array('phone'=>$phone,'user_status'=>1,
            'user_type'=>1,'openid'=>$openid))->find();
        if($ISphone){
            exit(json_encode(array('code'=>500,'error'=>'你已经绑定')));
        }
        $bind = $model->where(array('phone'=>$phone))->find();
        if(!$bind){
            exit(json_encode(array('code'=>500,'error'=>'该手机号码还没注册')));
        }
        $data['openid'] = $openid;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $cid = $model->where(array('phone'=>$phone,'user_status'=>1,'user_type'=>1))->save($data);
        if($cid){
            session('juicer_openid',$openid);
            echo json_encode(array('code'=>200,'url'=>U('JuicerPersonal/index',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>200,'error'=>'绑定失败'));
        }
    }
    /*
     * 发送手机验证码
     * */
    public function shortmessage(){
        $phone = trim($_POST['phone']);
        if(empty($phone)){
            exit(json_encode(array('code'=>800)));
        }
        $false = M('ju_users')->where(array('phone'=>$phone,'user_status'=>1))->find();
        if(!$false){
            exit(json_encode(array('code'=>300)));
        }
        $Code = make_rand();
        $result = shortsmessage($phone,$Code);
        if($result == true){
            setcookie('SHORT_MESSAGE_ROSE2',$Code,time()+1800);
            setcookie('login_time',time(),time()+1800);
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>500));
        }
    }
}