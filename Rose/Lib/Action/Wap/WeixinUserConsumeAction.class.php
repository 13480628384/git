<?php
/*
 * date 2016-12-12
 * 用户消费记录查询
 * */
class WeixinUserConsumeAction extends Action {
    protected function _initialize(){
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
    }
    //微信消费记录查询
    public function index(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $openid = $_POST['openid'];
            $model = M('device_consume_rec');
            $balance = $model
                ->where(array('from_username'=>$openid,'del_flag'=>0))
                ->field('sum(consume_account) consume_account,consume_status,create_date,command_status')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('openid', $openid);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_index_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $openid = $_GET['openid'];
            $balance = $model
                ->where(array('from_username'=>$openid,'del_flag'=>0))
                ->field('sum(consume_account) consume_account,consume_status,create_date,command_status')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('openid', $openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }

    //支付宝消费记录
    public function alipay_index(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $buyer_id = $_POST['buyer_id'];
            $model = M('device_consume_rec');
            $balance = $model
                ->where(array('from_username'=>$buyer_id,'del_flag'=>0))
                ->field('sum(consume_account) consume_account,command_status,create_date')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('buyer_id', $buyer_id);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_alipay_index_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $buyer_id = $_GET['buyer_id'];
            $balance = $model
                ->where(array('from_username'=>$buyer_id,'del_flag'=>0))
                ->field('sum(consume_account) consume_account,command_status,create_date')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('buyer_id', $buyer_id);
            $this->assign('balance', $balance);
            $this->display();
        }
    }

    //微信支付记录
    public function weixin_pay(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $openid = $_POST['openid'];
            $model = M('weixin_pay_rec');
            $balance = $model
                ->where(array('from_username'=>$openid,'del_flag'=>0))
                ->field('pay_account,pay_status,create_date')
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('openid', $openid);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_weixin_pay_page.html');
            exit($html);
        } else {
            $model = M('weixin_pay_rec');
            $openid = $_GET['openid'];
            $balance = $model
                ->where(array('from_username'=>$openid,'del_flag'=>0))
                ->field('pay_account,pay_status,create_date')
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('openid', $openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //支付宝支付记录
    public function alipay_pay(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $buyer_id = $_POST['buyer_id'];
            $model = M('alipay_pay_rec');
            $balance = $model
                ->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))
                ->field('trade_status,total_amount,create_date')
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('buyer_id', $buyer_id);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_alipay_pay_page.html');
            exit($html);
        } else {
            $model = M('alipay_pay_rec');
            $buyer_id = $_GET['buyer_id'];
            $balance = $model
                ->where(array('buyer_id'=>$buyer_id,'del_flag'=>0))
                ->field('trade_status,total_amount,create_date')
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('buyer_id', $buyer_id);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //榨汁机微信消费
    public function juicer_index(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $openid = $_POST['openid'];
            $model = M('ju_device_consume_weixin_rec');
            $balance = $model
                ->where(array('openid'=>$openid,'del_flag'=>0,'type'=>0))
                ->field('sum(consume_account) consume_account,create_date,command_status')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('openid', $openid);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_juicer_index_page.html');
            exit($html);
        } else {
            $model = M('ju_device_consume_weixin_rec');
            $openid = $_GET['openid'];
            $balance = $model
                ->where(array('openid'=>$openid,'del_flag'=>0,'type'=>0))
                ->field('sum(consume_account) consume_account,create_date,command_status')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('openid', $openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //支付宝消费
    public function juicer_alipay_index(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $buyer_id = $_POST['buyer_id'];
            $model = M('ju_device_consume_alipay_rec');
            $balance = $model
                ->where(array('buyer_id'=>$buyer_id,'del_flag'=>0,'type'=>0))
                ->field('sum(consume_account) consume_account,command_status,create_date')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit($n,20)
                ->select();
            $this->assign('balance',$balance);
            $this->assign('buyer_id', $buyer_id);
            $html=$this->fetch('./tpl/Wap/default/WeixinUserConsume_juicer_alipay_index_page.html.html');
            exit($html);
        } else {
            $model = M('ju_device_consume_alipay_rec');
            $buyer_id = $_GET['buyer_id'];
            $balance = $model
                ->where(array('buyer_id'=>$buyer_id,'del_flag'=>0,'type'=>0))
                ->field('sum(consume_account) consume_account,command_status,create_date')
                ->group("create_date")
                ->order("create_date DESC")
                ->limit(20)
                ->select();
            $this->assign('buyer_id', $buyer_id);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    /*
     * ======================
     * 用户消费报表
     * 2017-12-28
     * ======================
     * */
    public function user_consume(){
        $yesterday = strtotime(date('Ymd',strtotime('-30 day')));
        $serven = date('Y-m-d H:i:s',$yesterday);
        $result = M('device_consume_rec')->where(array(
                        'from_username' => $_GET['openid'],
                        'create_date'=>array('egt',$serven)
                    ))
                    ->field('from_username,create_date,sum(consume_account) as consume_account,id,day(create_date) as d')
                    ->order('create_date desc')
                    ->group('day(create_date)')
                    ->limit(100)
                    ->select();
        //支付的记录
        $pay = M('weixin_pay_rec')->where(array(
            'from_username'=>$_GET['openid'],
            'create_date'=>array('egt',$serven)
        ))->field('from_username,create_date,pay_status,sum(pay_account) as pay_account,transaction_id,day(create_date) as d')
            ->group('day(create_date)')
            ->select();
        if($pay){
            $res = array_merge($result,$pay);
        } else {
            $res = array_merge($result);
        }
        $resu = array();
        foreach ($res as $k =>$v){
            $month = $v['d'];
            $resu[$month][] = $v;
        }
        $results = array();
        $this->array_merages($resu, $results);
        $this->assign('result',$results);
        $this->display();
    }
    /*
     * ===============
     * 用户消费和支付合并
     * 2017-12-28
     * ===============
     * */
    public function array_merages($array, &$result){
        foreach ($array as $k => $v){
            if($v['d']){
                foreach($v as $key=>$va){
                    $result[$v['d']][$key] = $va;
                }
            }else if(is_array($v)){
                $this->array_merages($v,$result);
            }
        }
    }
}