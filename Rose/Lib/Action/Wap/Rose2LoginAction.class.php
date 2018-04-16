<?php
session_start();
require_once "lib/WxPay.Config.php";
require_once "WxPay.JsApiPay.php";
class Rose2LoginAction extends Action {
    public  $APP_ID = null;
    public  $APP_SECRET = null;
    public  $WNO = null;
    protected function _initialize(){
        $this->APP_ID = WxPayConfig::APPID;
        $this->APP_SECRET = WxPayConfig::APPSECRET;
        $this->WNO = 'gh_383e03dfa5b9';
    }
    public function login(){
        //$REDIRECT_URI = urlencode('http://'.$_SERVER['SERVER_NAME'].U('get_openid'));
        //$REDIRECT_URI = urlencode("http://wxpay.roseo2o.com/adv_merchant/index.php?m=Login&a=get_openid");
        //$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->APP_ID&redirect_uri=$REDIRECT_URI&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        header("location:".U('binding'));
        $this->display();
    }
    //获取用户信息
    public function get_openid(){
        //$this->redirect('binding',array('openid'=>$head->openid));
        /*$code = $_GET['code'];
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->APP_ID&secret=$this->APP_SECRET&code=$code&grant_type=authorization_code";
        $return_results = file_get_contents($access_token_url);
        $return_json =json_decode($return_results);
        $access_token = $return_json->access_token;
        $openid = $return_json->openid;
        $get_openid = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $edopenid = file_get_contents($get_openid);
        $head = json_decode($edopenid);*/
        /*$obj = new JsApiPay();
        $head = $obj->GetUserInfo();*/
        /*$model = M('weixin_userinfo');
        $userinfo = $model->where(array('from_username'=>$head->openid,'del_flag'=>0))->find();
        //判断用户是否注册
        if(!$userinfo && $head->openid){
            $data['id'] = generateNum();
            $data['app_id'] = $this->APP_ID;
            $data['wno'] = $this->WNO;
            $data['nickname'] = $head->nickname;
            $data['headimgurl'] = $head->headimgurl;
            $data['province'] = $head->province;
            $data['city'] = $head->city;
            $data['country'] = $head->country;
            $data['sex'] = $head->sex;
            $data['from_username'] = $head->openid;
            $data['create_by'] = $head->openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_by'] = $head->openid;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['remarks'] = "用户登录注册";
            $data['type'] = 2;
            $uid = $model->add($data);
            if($uid){
                //跳转到填写绑定号页面
                $this->redirect('binding',array('openid'=>$head->openid));
            }else{
                //跳转到填写绑定号页面
                $this->redirect('binding',array('openid'=>$head->openid));
            }
        }else{
            $data['id'] = generateNum();
            $data['app_id'] = $this->APP_ID;
            $data['wno'] = $this->WNO;
            $data['nickname'] = $head->nickname;
            $data['headimgurl'] = $head->headimgurl;
            $data['province'] = $head->province;
            $data['city'] = $head->city;
            $data['country'] = $head->country;
            $data['sex'] = $head->sex;
            $data['from_username'] = $head->openid;
            $data['create_by'] = $head->openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_by'] = $head->openid;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['remarks'] = "用户登录注册";
            $data['type'] = 2;
            $uid = $model->where(array('from_username'=>$head->openid,'del_flag'=>0))->save($data);
            //跳转到填写绑定号页面
            $this->redirect('binding',array('openid'=>$head->openid));
        }*/
    }
    public function binding(){
        $tools = new JsApiPay();
        $openid = $tools->GetOpenid();
        $userinfo = M('weixin_userinfo')->where(array('from_username'=>$openid,'del_flag'=>0))->find();
        if(!$userinfo){
            $data['id'] = generateNum();
            $data['app_id'] = $this->APP_ID;
            $data['wno'] = $this->WNO;
            $data['from_username'] = $openid;
            $data['create_by'] = $openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_by'] = $openid;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['remarks'] = "用户登录注册";
            $data['type'] = 2;
            $uid = M('weixin_userinfo')->add($data);
        }
        $this->assign('openid',$openid);
        $this->display();
    }
    //绑定用户唯一号
    public function BindVerfityCode(){
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
    /*
     * 发送手机验证码
     * */
    public function shortmessage(){
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