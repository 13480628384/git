<?php
session_start();
require_once "lib/WxPay.Config.php";
require_once "WxPay.JsApiPay.php";
class VendLoginAction extends Action {
    public  $APP_ID = null;
    public  $APP_SECRET = null;
    public  $WNO = null;
    protected function _initialize(){
        $this->APP_ID = WxPayConfig::APPID;
        $this->APP_SECRET = WxPayConfig::APPSECRET;
    }
    public function login(){
        if(IS_POST){
            $phone = trim($_POST['phone']);
            $passwd = trim($_POST['passwd']);
            $openid = trim($_POST['openid']);
            if(empty($phone) || empty($passwd) || empty($openid)){
                exit(json_encode(array('code'=>500,'msg'=>'数据为空，请检查 ')));
            }
            $res = M('sys_user')->where(array('del_flag'=>0,'phone'=>$phone,'no'=>'售货机'))->find();
            if(!$res){
                exit(json_encode(array('code'=>500,'msg'=>'系统找不到你的手机号码，请联系工作人员')));
            }
            if(sp_compare_password($passwd,$res['password'])){
                $data['openid'] = $openid;
                M('sys_user')->where(array('del_flag'=>0,'phone'=>$phone,'no'=>'售货机'))->save($data);
                session('uid',$res["id"]);
                session('openid',$openid);
                exit(json_encode(array('code'=>200,'msg'=>'登录成功','url'=>U('Vendmanage/index'))));
            } else {
                exit(json_encode(array('code'=>500,'msg'=>'密码错误 ')));
            }
        } else {
            /*$tools = new JsApiPay();
            $openid = $tools->GetOpenid();*/
            $openid = 'odOIPv8267xTj4vLdcQ2xNb3Divo';
            $this->assign('openid',$openid);
            $this->display();
        }
    }
    //找回密码
    public function find_passwd(){
        $this->display();
    }
}