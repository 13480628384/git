<?php
//榨汁机
class JuicerCodeAction extends BackAction{
    public $scan_code = null;
    public $type = null;
    protected function _initialize(){
        parent::_initialize();
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
        $weixin_alipay_type = '';
        $count = '';
        if( strpos($typesd,'MicroMessenger')>0 ){
            if(empty($_GET['openid'])) exit('请重新扫码');
            $weixin_alipay_type = 'wechat';
            $this->type = 'wechat';
            /*$count = M('ju_device_consume_weixin_rec')->where(array('openid'=>trim($_GET['openid']),
                'status'=>'1','is_close'=>0,'del_flag'=>'0'))
                ->group('openid')->sum('consume_account');*/
        } elseif(strpos($typesd,'AlipayClient') > 0) {
            if(empty($_GET['buyer_id'])) exit('请重新扫码');
            $this->type = 'alipay';
            $weixin_alipay_type = 'alipay';
            /*$count = M('ju_device_consume_alipay_rec')->where(array('buyer_id'=>trim($_GET['buyer_id']),
                'status'=>'1','is_close'=>0,'del_flag'=>'0'))
                ->group('buyer_id')->sum('consume_account');*/
        }
        $scan_code = isset($_GET['scan_code'])?$_GET['scan_code']:null;
        $ju_device_info_detail = M('ju_device_info_detail')
            ->where(array('del_flag'=>'0','scan_code'=>$scan_code))->find();
        $new = implode('=',explode('-',$ju_device_info_detail['pay_price']));
        $out = explode('=',$new);
        $this->assign('weixin_alipay_type',$weixin_alipay_type);
        $this->assign('out',$out);
        $this->assign('ju_device_info_detail',$ju_device_info_detail);
        if(is_null($scan_code) ){
            exit('参数错误');
        }
        $this->scan_code = $scan_code;
        $this->assign('scan_code',$scan_code);
    }
    public function index(){
        //$this->assign('openid','odOIPv5RJwDqO94UaCbpKQvdjhLE');
        if($this->type == 'wechat'){
            $openid = trim($_GET['openid']);

            $this->assign('openid',$openid);
        } else if($this->type =='alipay'){
            $buyer_id = trim($_GET['buyer_id']);
            $this->assign('buyer_id',$buyer_id);
        }
        $this->display();
    }
}