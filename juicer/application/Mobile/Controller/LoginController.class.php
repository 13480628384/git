<?php
namespace Common\Controller;
use Think\Controller;
class LoginAction extends Controller {
    public  $APP_ID = null;
    public  $APP_SECRET = null;
    public  $WNO = null;
    protected function _initialize(){
        vendor('JsPay.JsApiPay');//导入类库
        Vendor('weixin.jssdk');
        $WxPayConfig = new \WxPayConfig();
        $this->APP_ID = $WxPayConfig::APPID;
        $this->APP_SECRET = $WxPayConfig::APPSECRET;
    }
    public function binding(){
        $tools = new \JsApiPay();
        $openid = $tools->GetOpenid();
        $this->assign('openid',$openid);
        $this->display();
    }
    //绑定用户唯一号
    public function BindVerfityCode(){
        if(IS_POST){
            $openid = $this->_post('openid');
            $code = $this->_post('code');
            $phone = $this->_post('phone');
            //$login_time = session('login_time');
            $login_time = $_COOKIE['login_time'];
            $SHORT_MESSAGE = $_COOKIE['SHORT_MESSAGE_ROSE2'];
            //$SHORT_MESSAGE = session('SHORT_MESSAGE_ROSE2');
            if($SHORT_MESSAGE != $code){
                echo json_encode(array('code'=>500,'error'=>'验证码错误'));
                exit();
            } elseif( time()-intval($login_time)>60 || empty($SHORT_MESSAGE) ){
                //session($SHORT_MESSAGE,'');
                echo json_encode(array('code'=>500,'error'=>'验证码过期了'));
            }
            //判断手机号码是否已经注册 [[
            $model = M('sys_user');
            $ISphone = $model->where(array('mobile'=>$phone,'del_flag'=>0,'openid'=>$openid))->find();
            if($ISphone){
                exit(json_encode(array('code'=>500,'error'=>'你已经绑定')));
            }
            $bind = $model->where(array('mobile'=>$phone,'del_flag'=>0))->find();
            if(!$bind){
                exit(json_encode(array('code'=>500,'error'=>'该手机号码还没注册')));
            }
            $data['openid'] = $openid;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $cid = $model->where(array('mobile'=>$phone,'del_flag'=>0))->save($data);
            if($cid){
                echo json_encode(array('code'=>200,'url'=>U('Rose2Personal/index',array('openid'=>$openid))));
            } else {
                echo json_encode(array('code'=>200,'error'=>'绑定失败'));
            }
        }
    }
    /*
     * 发送手机验证码
     * */
    public function shortmessage(){
        if(IS_POST){
            $phone = trim($_POST['phone']);
            if(empty($phone)){
                exit(json_encode(array('code'=>800)));
            }
            $false = M('sys_user')->where(array('mobile'=>$phone,'del_flag'=>0))->find();
            if(!$false){
                exit(json_encode(array('code'=>300)));
            }
            $Code = make_rand();
            $result = shortsmessage($phone,$Code);
            if($result == true){
                //session('SHORT_MESSAGE_ROSE2',$Code);
                setcookie('SHORT_MESSAGE_ROSE2',$Code,time()+1800);
                setcookie('login_time',time(),time()+1800);
                //session('login_time',time());
                echo json_encode(array('code'=>200));
            }else{
                echo json_encode(array('code'=>500));
            }
        }
    }
}