<?php
class ChargerAction extends BackAction{
    public $scan_code = null;
    protected function _initialize(){
        parent::_initialize();
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            exit('请用微信或支付宝打开');
        }
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
        //select * from device_relation_group where di_id='$device_info->id' and del_flag=0 and device_type=4
        $dgi = M('device_relation_group')->where(array('di_id'=>$device_info['id'],'del_flag'=>0,'device_type'=>2))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('online_status',$dgi['online_status']);
        $this->assign('device_command',$dgi['device_command']);
        $this->assign('device_id',$dgi['di_id']);
        $this->assign('out',$out);
        $this->scan_code = $scan_code;
        $this->assign('scan_code',$scan_code);
    }
    /*=========================微信充电器支付 [[============================*/
    public function index(){
        $openid = trim($_GET['openid']);
        //$openid = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
        $this->assign('openid',$openid);
        $this->display();
    }
    /*=========================微信充电器支付 ]]============================*/

    /*=========================支付宝充电器支付 [[============================*/
    public function alipay(){
        $buyer_id = trim($_GET['buyer_id']);
        $start = trim($_GET['start']);
        //$buyer_id = '2088802658990276';
        $this->assign('buyer_id',$buyer_id);
        $this->assign('start',$start);
        $this->display();
    }
    /*=========================支付宝充电器支付 ]]============================*/
}