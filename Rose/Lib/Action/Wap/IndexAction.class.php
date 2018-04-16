<?php
/*
 * author chw
 * date 2016-11-21
 * 玫瑰
 *
 * */
class IndexAction extends BaseAction{
    public function _initialize()
    {
        parent::_initialize();
        //用户是否注册成为生态商
        //用户还没有注册成为生态商，跳转到注册页面

    }
    public function index(){
        //判断扫码是从微信还是支付宝
        $typesd = $_SERVER['HTTP_USER_AGENT'] ;
        $weixin_alipay_url = '';
        $weixin_alipay_type = '';
        $scan_code = isset($_GET['scan_code'])?$_GET['scan_code']:null;
        if(is_null($scan_code) ){
            exit('页面参数错误，请重新扫描');
        }
        if(strpos($typesd,'MicroMessenger')>0){
            $weixin_alipay_type = 1;
            $weixin_alipay_url = "http://wxpay.roseo2o.com/weixinscan/game_test/game.php?scan_code=".$scan_code;
        }elseif(strpos($typesd,'AlipayClient') > 0){
            $weixin_alipay_type = 2;
            $weixin_alipay_url = "http://wxpay.roseo2o.com/alipayscan/game_test/index.php?scan_code=".$scan_code;
        }
        //判断设备是否在线
        $DeviceOnlineModel = M('device_info');
        $IFOnlineRow = $DeviceOnlineModel->where(array('scan_code'=>$scan_code,'device_status'=>1,'del_flag'=>0))->find();
        if($IFOnlineRow == false){
            exit('页面参数错误，请重新扫描');
        }
        $GroupIdModel = M('device_relation_group');
        $Group_Id = $GroupIdModel->where(array('di_id'=>$IFOnlineRow['id']))->getField('dgi_id');
        //导流广告显示，随机显示几条

        $this->assign('weixin_alipay_url',$weixin_alipay_url);
        $this->assign('weixin_alipay_type',$weixin_alipay_type);
        $this->assign('Group_Id',$Group_Id);
        $this->display();
    }
}
?>