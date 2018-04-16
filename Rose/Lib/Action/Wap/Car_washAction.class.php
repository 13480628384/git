<?php
class Car_washAction extends BackAction{
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
        //判断微信支付宝
        if( strpos($typesd,'MicroMessenger')>0 ){
            if(empty($_GET['openid'])) exit('请重新扫码');
            $weixin_alipay_type = 'wechat';
            $this->type = 'wechat';
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>trim($_GET['openid']),
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>trim($_GET['openid']),
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            $count = $total1-$total2;
            if($count<=0){
                header('Location:'.U('Car_Wash_Pay/index',array('openid'=>$_GET['openid'],'scan_code'=>$_GET['scan_code'])));
                exit;
            }
        } elseif(strpos($typesd,'AlipayClient') > 0) {
            if(empty($_GET['buyer_id'])) exit('请重新扫码');
            $this->type = 'alipay';
            $weixin_alipay_type = 'alipay';
            $coun_apl = M('alipay_pay_rec')->where(array('trade_status'=>'TRADE_SUCCESS'
            ,'del_flag'=>0,'is_close'=>0,'buyer_id'=>trim($_GET['buyer_id']),
                'app_id'=>AlipayConfig::APPID))->sum('total_amount');
            //echo  M('alipay_pay_rec')->getLastSql();die;
            $consume_accounts = M('device_consume_rec')->where(array(
                'command_status'=>array('in','1,2'),
                'is_close'=>0,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'from_username'=>trim($_GET['buyer_id']),
            ))->sum('consume_account');
            $count = $coun_apl-$consume_accounts;
            if($count<=0){
                header('Location:'.U('Car_Wash_Pay/index',array('buyer_id'=>$_GET['buyer_id'],'scan_code'=>$_GET['scan_code'])));
                exit;
            }
        } else {
            exit();
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
        $dgi = M('device_relation_group')->where(array('di_id'=>$device_info['id'],'del_flag'=>0,'device_type'=>7))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('online_status',$dgi['online_status']);
        $this->assign('device_command',$dgi['device_command']);
        $this->assign('device_id',$dgi['di_id']);
        $this->assign('out',$out);
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
            ->field('su.phone,su.title')->find();
        $this->assign('phone',$phone['phone']);
        $this->assign('title',$phone['title']);
        $this->display();
    }

}