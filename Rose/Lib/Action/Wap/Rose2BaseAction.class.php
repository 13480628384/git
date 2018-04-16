<?php
session_start();
require_once "WxPay.JsApiPay.php";
class Rose2BaseAction extends Action
{
    public $openid = null;
    public $user_id = null;
    public $office_id = null;
    protected function _initialize()
    {
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 ){
            //exit('请用微信打开');
        }
        /*if(IS_GET && isset($_REQUEST['openid'])){
			if(empty($_COOKIE['openid'])){
				exit('出错了');
			}
        }*/
       /* if(IS_POST){
            $this->openid = $_COOKIE['openid'];
        } else if(empty($_COOKIE['openid'])) {
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
			setcookie('openid',$openid,time()+3600*2);
            $this->openid = $openid;
        }else if($_REQUEST['openid']){
            if($_REQUEST['openid'] != $_COOKIE['openid']){
                $tools = new JsApiPay();
                $openid = $tools->GetOpenid();
                setcookie('openid',$openid,time()+3600*2);
                $this->openid = $openid;
            }else{
                $this->openid = $_REQUEST['openid'];
            }
        }else if($_COOKIE['openid']){
            $this->openid = $_COOKIE['openid'];
        }*/
        $this->openid = 'odOIPv9UcOs4N_jKX0JECOwADE7s';
        $sys_user = M('sys_user');
        $no = $sys_user->where(array('del_flag'=>0,'openid'=>trim($this->openid),'login_flag'=>'0'))->find();
        if($no){
            exit('请联系网站管理员');
        }
        $office = $sys_user->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->find();
        if(!$office){
            header("Location:".U("Rose2Login/login"));
            exit();
        }
        Vendor('weixin.jssdk');
        $jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
        $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
        $this->assign('signPackage',$signPackage);
        $this->user_id = $office['id'];
        $this->office_id = $office['office_id'];
        $user_ids = M('weixin_userinfo')->where(array('del_flag'=>0,'from_username'=>$this->openid))->find();
        $this->assign('headimgurl',$user_ids['headimgurl']);
        $this->assign('nickname',$user_ids['nickname']);
        $this->assign('user_id',$office['id']);
    }
}
?>