<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 14:45
 */
class GlassAction extends BackAction{
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
            $total1 = M('glass_pay_rec')->where(array('from_username'=>trim($_GET['openid']),
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0,'type'=>1))->sum('pay_account');
            $total2 = M('glass_consume_rec')->where(array('from_username'=>trim($_GET['openid']),
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            $count = $total1-$total2;
            //判断用户是否已经注册
            $result = M('glass_user')->where(array('user_id'=>$_GET['openid']))->find();
            $this->assign('result',$result);
        } elseif(strpos($typesd,'AlipayClient') > 0) {
            exit('支付宝暂时不能使用，请使用微信扫码');
            if(empty($_GET['buyer_id'])) exit('请重新扫码');
            $this->type = 'alipay';
            $weixin_alipay_type = 'alipay';
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>trim($_GET['buyer_id']),
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>trim($_GET['buyer_id']),
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
        }
        $pay = '';
        if($count<=0){
            $pay = 0;
        } else {
            $pay = trim(intval($count));
        }
        $this->assign('weixin_alipay_type',$weixin_alipay_type);
        $this->assign('count',$pay);
        $scan_code = isset($_GET['scan_code'])?$_GET['scan_code']:null;
        if(is_null($scan_code) ){
            exit('参数错误');
        }
        $device_info = M('device_info')
            ->where(array(
                'scan_code'=>$scan_code,
                'device_status'=>1,
                'del_flag'=>0))
            ->find();
        if(!$device_info){
            exit('不存在编码');
        }
        $dgi = M('device_relation_group')->where(array('di_id'=>$device_info['id'],'del_flag'=>0,'device_type'=>9))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('online_status',$dgi['online_status']);
        $this->assign('device_command',$dgi['device_command']);
        $this->assign('device_id',$dgi['di_id']);
        $this->assign('out',$out);
        $this->assign('owner_id',$device_info['owner_id']);
        $this->scan_code = $scan_code;
        $this->assign('scan_code',$scan_code);
    }
    public function index(){
        if($this->type == 'wechat'){
            $openid = trim($_GET['openid']);
            $is = M('weixin_userinfo')->where(array('from_username'=>$openid,'del_flag'=>0))->find();
            if(!$is && $openid){
                $data['id'] = generateNum();
                $data['app_id'] = WxPayConfig::APPID;
                $data['from_username'] = $openid;
                $data['create_by'] = $openid;
                $data['update_by'] = $openid;
                $data['create_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
                $dui = M('weixin_userinfo')->add($data);
            }
            $this->assign('openid',$openid);
        } else if($this->type =='alipay'){
            $buyer_id = trim($_GET['buyer_id']);
            $is = M('alipay_userinfo')->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))->find();
            if(!$is && $buyer_id){
                $data['id'] = generateNum();
                $data['app_id'] = AlipayConfig::APPID;
                $data['from_username'] = $buyer_id;
                $data['create_by'] = $buyer_id;
                $data['update_by'] = $buyer_id;
                $data['create_date'] = date('Y-m-d H:i:s');
                $data['update_date'] = date('Y-m-d H:i:s');
                $dui = M('weixin_userinfo')->add($data);
            }
            $this->assign('buyer_id',$buyer_id);
        }
        $phone = M('sys_user')->alias('su')->join('LEFT JOIN device_info di on di.owner_id=su.id')
            ->where(array('di.scan_code'=>$this->scan_code,'di.del_flag'=>0,'su.del_flag'=>0))
            ->getfield('su.phone');
        $this->assign('phone',$phone);
        $this->display();
    }

}