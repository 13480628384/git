<?php
/*
 * date 2016-12-12
 * 用户消费记录查询
 * */
class GlassWeixinUserConsumeAction extends Action {
    protected function _initialize(){
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            exit('请用微信或支付宝打开');
        }
    }
    //微信消费记录查询
    public function index(){
        $scanc_code = $_GET['scan_code'];
        $model = M('glass_consume_rec');
        $openid = $_GET['openid'];
        $balance = $model
            ->where(array('from_username'=>$openid,'del_flag'=>0))
            ->field('consume_account,consume_status,create_date')
            ->order("create_date DESC")
            ->limit(20)
            ->select();
        $this->assign('openid', $openid);
        $this->assign('scan_code', $scanc_code);
        $this->assign('balance', $balance);
        $this->display();
    }

    //微信支付记录
    public function weixin_pay(){
        $scanc_code = $_GET['scan_code'];
        $model = M('glass_pay_rec');
        $openid = $_GET['openid'];
        $balance = $model
            ->where(array('from_username'=>$openid,'del_flag'=>0))
            ->field('pay_account,pay_status,create_date')
            ->order("create_date DESC")
            ->limit(20)
            ->select();
        $this->assign('openid', $openid);
        $this->assign('scan_code', $scanc_code);
        $this->assign('balance', $balance);
        $this->display();
    }
}