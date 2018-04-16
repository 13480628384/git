<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16
 * Time: 10:24
 */
include "car/WxMch.Pay.php";
require_once "WxPay.JsApiPayCar.php";
class CarWalletAction extends CarBaseAction
{
    public function index()
    {
        /*$tools = new JsApiPay();
        $openid = $tools->GetOpenid();*/
        $openid = 'oe0IduIKY2XjstuYAA1kOjkU7I08';
        //查出总余额是多少
        $model = M('car_pay');
        $where['status'] = '1';
        $where['create_by'] = $this->user_id;
        $car_pay = $model->where($where)->sum('account');
        //更新总余额
        if(!M('car_user')->where(array('user_id'=>$this->user_id))->find()){
            $data['id'] = generateNum();
            $data['user_id'] = $this->user_id;
            $data['openid'] = $openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            M('car_user')->add($data);
        }
        $car['totals'] = $car_pay;
        M('car_user')->where(array('user_id'=>$this->user_id))->save($car);
        $this->assign('openid', $openid);
        $this->assign('user_id', $this->user_id);
        $this->assign('car_pay', round($car_pay,2));
        $this->display();
    }
    public function cash_money(){
        sleep(1);
        $banlance = M('car_user')->where(array('openid'=>trim($this->openid)))->sum('totals-consume');
        if($banlance){
            if($banlance < 0){
                $p = 0;
            }else{
                $p=$banlance;
            }
            echo json_encode(array('result' => 200,'reg'=>$p));
        } else {
            echo json_encode(array('result' => 500));
        }
    }
    public function tixian_money(){
        if(isset($_POST['openid']) && isset($_POST['amount'])){
            $SHORT_MESSAGE = $_COOKIE['SHORT_MESSAGE'];
            $login_time = $_COOKIE['login_time'];
            $code = $_POST['code'];
            echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '暂时还不能提现', 'return_ext' => array()) );
            exit();
            if($SHORT_MESSAGE != $code){
                echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '验证码错误', 'return_ext' => array()) );
                exit();
            } elseif( time()-intval($login_time)>60 || empty($SHORT_MESSAGE) ){
                session($SHORT_MESSAGE,'');
                echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '验证码过期', 'return_ext' => array()) );
                exit();
            }
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
                . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $amount = trim($_POST['amount']);//输入的金额
            $all = '';
            if($amount<100){
                echo json_encode( array('result_code' => '4', 'return_msg' => '100以上才可以提现', 'return_ext' => array()) );
                exit();
            }
            if($amount>=10){
                $shou = round(($amount*0.006),2);
                $all  = ($amount-$shou);
            }else{
                $all = $amount;
            }
            $openid = trim($_POST['openid']);
            $user_id = trim($_POST['user_id']);
            if(empty($openid) || empty($user_id) ){
                echo json_encode( array('result_code' => '4', 'return_msg' => '缺少参数', 'return_ext' => array()) );
                exit();
            }
            if($amount > 1000) {
                echo json_encode( array('result_code' => '4', 'return_msg' => '每天的提现额度是1000元', 'return_ext' => array()) );
                exit();
            }
            //每天不能提现超过三次
            $today = date('Y-m-d 00:00:00');
            $whereed['create_date'] = array('egt', $today);
            $whereed['status'] = '1';
            $whereed['openid'] = $user_id;
            if(M('weixin_enterprise_payment')->where($whereed)->count() > 2){
                echo json_encode( array('result_code' => '4', 'return_msg' => '每天只能提现三次', 'return_ext' => array()) );
                exit();
            }
            $sumc_c = M('weixin_enterprise_payment')->where($whereed)->sum('amount');
            if(intval($sumc_c) >= 1000) {
                echo json_encode( array('result_code' => '4', 'return_msg' => '每天的提现额度是1000元', 'return_ext' => array()) );
                exit();
            }
            $banlance = M('car_user')->where(array('openid'=>$openid))->sum('totals-consume');
            if($banlance <= 0){
                echo json_encode( array('result_code' => '4', 'return_msg' => '余额不足', 'return_ext' => array()) );
                exit();
            }
            $sys_user = M('sys_user');
            $percent = $sys_user->where(array('del_flag'=>0,'id'=>$user_id))->find();
            if(empty($percent)){
                echo json_encode( array('result_code' => '4', 'return_msg' => '找不到你的人', 'return_ext' => array()) );
                exit();
            }
            $model = M('weixin_enterprise_payment');
            $model->startTrans();
            $datas['id'] = md5(uniqid());
            $datas['appid'] = 'car';
            $datas['mchid'] = WxPayConfig::MCHID;
            $datas['partner_trade_no'] = $orderSn;
            $datas['status'] = 0;
            $datas['openid'] = $user_id;
            $datas['check_name'] = 'NO_CHECK';
            $datas['amount'] = $amount;
            $datas['arrival'] = $all;
            $datas['descs'] = '玫瑰物联'.$user_id.'电话号码:'.$percent['mobile'].'姓名：'.$percent['name'];
            $datas['spbill_create_ip'] = GetIP();
            $datas['create_date'] = date('Y-m-d H:i:s',time());
            $times = date('Y-m-d H:i:s',time());
            $Transfer = $model->add($datas);
            if($Transfer){
                $model->commit();
            }else{
                $model->rollback();
                echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
                exit();
            }
            $banlances = M('car_user')->where(array('openid'=>$openid))->sum('totals-consume');
            if($banlances <= '0'){
                echo json_encode( array('result_code' => '4', 'return_msg' => '余额不足', 'return_ext' => array()) );
                exit();
            }
            $mchPay = new WxMchPay();
            $mchPay->setParameter('openid',$openid);
            $mchPay->setParameter('partner_trade_no', $orderSn);
            $mchPay->setParameter('check_name', 'NO_CHECK');
            $mchPay->setParameter('amount', $all*100);
            $mchPay->setParameter('desc', '玫瑰物联');
            $mchPay->setParameter('spbill_create_ip',GetIP());
            $response = $mchPay->postXmlSSLCurl_init();
            if( !empty($response) ) {
                $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
                $array_no = json2arr($data);
                if(empty($array_no['payment_time']) || !isset($array_no['payment_time'])){
                    //账户中没钱，则删除数据库的单一数据
                    M('weixin_enterprise_payment')->where(array('openid'=>$user_id,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
                    echo json_encode( array('result_code' => '4', 'return_msg' => print_r($array_no), 'return_ext' => array()) );
                    exit();
                }
                if($array_no['result_code'] == 'SUCCESS' || $array_no['return_code'] == 'SUCCESS'){
                    //更新转账状态
                    $desmodel = M('weixin_enterprise_payment');
                    $ep['status'] = 1;
                    $ep['payment_time'] = $array_no['payment_time'];
                    $ep['payment_no'] = $array_no['payment_no'];
                    $ep['update_date'] = $times;
                    $epids = $desmodel
                        ->where(array('openid'=>$user_id,'partner_trade_no'=>$orderSn))
                        ->save($ep);
                    //找出转账id
                    $Transfer_id = M('car_user')->where(array('openid'=>$openid))->setInc('consume',$amount);
                    //消费记录表更改状态
                    if($epids && $Transfer_id){
                        $desmodel->commit();
                        echo json_encode( array('result_code' => 'SUCCESS', 'return_msg' => '提现成功', 'return_ext' => $banlance) );
                    }else{
                        //账户中没钱，则删除数据库的单一数据
                        M('weixin_enterprise_payment')->where(array('openid'=>$user_id,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
                        $desmodel->rollback();
                        echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
                    }
                }else{
                    echo json_encode( array('result_code' => '3', 'return_msg' => $array_no['err_code_des'], 'return_ext' => array()) );
                }
            }else{
                echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
            }
        }
    }
    /*
     * 发送手机验证码,必须是公司内注册的员工才可以发送
     * */
    public function shortmessage(){
        $phone = trim($this->_post('phone'));
        if(empty($phone)){
            exit(json_encode(array('code'=>800)));
        }
        $sys_user = M('sys_user');
        $percent = $sys_user->where(array('del_flag'=>0,'phone'=>$phone))->find();
        /*if(empty($percent)){
            exit(json_encode(array('code'=>500,'error'=>'手机号码还没注册')));
        }*/
        $Code = make_rand();
        $result = cashing($phone,$Code);
        if($result == true){
            //session('SHORT_MESSAGE',$Code);
            //session('login_time',time());
            setcookie('SHORT_MESSAGE',$Code,time()+1800);
            setcookie('login_time',time(),time()+1800);
            echo json_encode(array('code'=>200));
        }else{
            echo json_encode(array('code'=>400));
        }
    }
    /*===提现记录 [[==*/
    public function Present(){
        if(IS_POST){
            $n = $_POST['n']*20;
            $present_list = M('weixin_enterprise_payment')->where(array('openid'=>$this->openid))
                ->order("create_date desc")->limit($n,20)->select();
            $this->assign('openid', $this->openid);
            $this->assign('present_list', $present_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2Wallet_Present_list_page.html');
            exit($html);
        } else {
            $present_list = M('weixin_enterprise_payment')->where(array('openid'=>$this->openid))
                ->order("create_date desc")->limit(20)->select();
            $this->assign('present_list',$present_list);
            $this->assign('openid',$this->openid);
            $this->display();
        }
    }
}