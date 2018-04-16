<?php
session_start();
require_once("Wap/WxPay.JsApiPay.php");
require_once "Wap/Alipay/function.php";
class V_2RoseBaseAction extends Action{
        public $buyer_id = null;
        public $wxusers = null;
        public $user_id = null;
        public $type = null;
        public $rose_id = null;
        public $weixin_alipay_type =null;
        public $APPID =null;
        public $autoShare = false;
        public $scan_code = false;
        protected function _initialize()
        {
            $typesd = $_SERVER['HTTP_USER_AGENT'];
            if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
                exit('请用微信或支付宝打开');
            }
            //微信或支付宝

            $this->assign('site_url', $site_url = C('site_url'));
            $this->assign('cur_url', $cur_url = urlencode(__SELF__));
            $this->assign('STATICS_URL', C('site_url') . '/tpl/Wap/default/');
            //用户id，id是微信id或者支付宝id
            if($_REQUEST['user_id']){
                $this->user_id =$_REQUEST['user_id'];
                session('user_id',$this->user_id);
            }

            if(session('user_id') != $_REQUEST['user_id']){
                exit('网络错误');
            }
            if($_REQUEST['weixin_alipay_type']){
                $this->weixin_alipay_type =$_REQUEST['weixin_alipay_type'];
            }
            if($_REQUEST['scan_code']){
                $this->scan_code =$_REQUEST['scan_code'];
            }

            /*if(strpos($typesd,'MicroMessenger')>0){
                $tools = new JsApiPay();
                $openided = $tools->GetOpenid();
                if($this->user_id != $openided){
                    exit('哎呀，出错了＞﹏＜');
                }
            }
            if(strpos($typesd,'AlipayClient')>0){
                $buyer_id = get_user_info();
                if($this->user_id != $buyer_id){
                    exit('哎呀，出错了＞﹏＜');
                }
            }*/
            if(empty($this->scan_code))exit('编码错误');
            $model = M('rose_user_info');
            $where['buyer_id'] = $this->user_id;
            $where['_logic'] = 'or';
            $where['openid'] = $this->user_id;
            $map['_complex'] = $where;
            $map['del_flag'] = array('eq',0);
            $rose = $model->where($map)->find();
            if(!$rose){
                header("Location:".U("Bind/binding",array('user_id'=>$this->user_id,'weixin_alipay_type'=>$this->weixin_alipay_type,'scan_code'=>$this->scan_code)));
                exit();
            }
            $type_array = explode(',',$rose['type']);//2代表有广告商功能的
            $type = '';
            if(in_array(2,$type_array)){
                $this->type = 2;
            } else{
                $this->type = 1;
            }
            //$this->type = $rose['type'];//1生态商，2广告商，3运营商
            $this->rose_id = $rose['id'];
            /*
             * 引入微信js接口
            */
            Vendor('weixin.jssdk');
            $this->APPID = WxPayConfig::APPID;
            $this->assign('appid',$this->APPID);
            $this->assign('user_id',$this->user_id);
            $this->assign('scan_code',$this->scan_code);
            $this->assign('rose',$rose);
            $this->assign('weixin_alipay_type',$this->weixin_alipay_type);
            $this->assign('type',$this->type);//1生态商，2广告商，3运营商
            $jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
            $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
            $this->assign('signPackage',$signPackage);
        }
}