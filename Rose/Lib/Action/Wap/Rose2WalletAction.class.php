<?php
include "lib/WxMch.Pay.php";
require_once "WxPay.JsApiPay.php";
class Rose2WalletAction extends Rose2BaseAction {
    public function index(){
        $this->assign('openid',$this->openid);
        $this->display();
    }
    /*
     * 福尔康提现
     * */
    public function fuerkan(){
       /* $tools = new JsApiPay();
        $openid = $tools->GetOpenid();*/
        $phone = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->getField('phone');
        $this->assign('openid',$this->openid);
        $this->assign('phone',$phone);
        $this->display();
    }
    public function fu_cash(){
        $banlance = M('sys_user')->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->sum('totals-consume');
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
    public function fu_tixian_money(){
        if(isset($_POST['openid']) && isset($_POST['amount'])){
            $SHORT_MESSAGE = $_COOKIE['SHORT_MESSAGE'];
            $login_time = $_COOKIE['login_time'];
            $code = $_POST['code'];
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
            if(empty($openid) ){
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
			$whereed['openid'] = $openid;
			if(M('weixin_enterprise_payment')->where($whereed)->count() > 2){
				echo json_encode( array('result_code' => '4', 'return_msg' => '每天只能提现三次', 'return_ext' => array()) );
				exit();
			}
			$sumc_c = M('weixin_enterprise_payment')->where($whereed)->sum('amount');
			if(intval($sumc_c) >= 1000) {
                echo json_encode( array('result_code' => '4', 'return_msg' => '每天的提现额度是1000元', 'return_ext' => array()) );
                exit();
            }
            $banlance = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->sum('totals-consume');
            if($banlance <= 0){
                echo json_encode( array('result_code' => '4', 'return_msg' => '余额不足', 'return_ext' => array()) );
                exit();
            }
            $sys_user = M('sys_user');
            $percent = $sys_user->where(array('del_flag'=>0,'openid'=>$openid))->find();
            if(empty($percent)){
                echo json_encode( array('result_code' => '4', 'return_msg' => '找不到你的人', 'return_ext' => array()) );
                exit();
            }
            $user_id_xiche = array(
                '2017021015_D4BE324D17C28D58F27CE169576A3231',
                '2017030316_0697603B100E1D99E83D3F2F6C2BC792',
                '2017030318_8D98490B5886001790FCD4327AEB1383',
                '2017030817_E1B1270BD5106FAC9F3AF9204BAE59E0',
                '2017031512_2C0CDA8D862CF1D131A6DCF0CC57FBB0',
                '2017032314_0E735A29527B08311C109F57092096F2',
                '2017042015_A8E54193D45E9140E190C07491C8FB78',
                '2017060114_F3D7369C9EC0A54EF4CC194F955C2048',
                '2017061710_6F7726D2544532465348B662EA5571C4',
                '2017063011_3DC8B7097B1CA52481DF6F3458BC8939',
                '2017070817_D00FD16EFA8FD18FB609FA2F38D7DC83',
                '2017071909_892B8041817D74A1B4FF2E81717D312D',
                '2017072714_A0FF1E7281CCF21C30364796BF81ACC0',
                '2017081714_97B2AEAF96E784C12B258BB42C467AC5'
            );
            if(in_array($percent['id'],$user_id_xiche) && $banlance >= '1000'){
                if($banlance >= '1000'){
                    echo json_encode( array('result_code' => '4', 'return_msg' => '暂时不能提现，请联系客服 13823564759', 'return_ext' => array()) );
                    exit();
                }
            }
            $model = M('weixin_enterprise_payment');
            $model->startTrans();
            $datas['id'] = md5(uniqid());
            $datas['appid'] = WxPayConfig::APPID;
            $datas['mchid'] = WxPayConfig::MCHID;
            $datas['partner_trade_no'] = $orderSn;
            $datas['status'] = 0;
            $datas['openid'] = $openid;
            $datas['check_name'] = 'NO_CHECK';
            $datas['amount'] = $amount;
            $datas['arrival'] = $all;
            $datas['descs'] = '玫瑰物联'.$openid.'电话号码:'.$percent['mobile'].'姓名：'.$percent['name'];
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
			$banlances = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->sum('totals-consume');
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
                    M('weixin_enterprise_payment')->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
                    echo json_encode( array('result_code' => '4', 'return_msg' => '系统网络忙碌', 'return_ext' => array()) );
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
                        ->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn))
                        ->save($ep);
                    //找出转账id
                    $Transfer_id = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->setInc('consume',$amount);
                    //消费记录表更改状态
                    if($epids && $Transfer_id){
                        $desmodel->commit();
                        /*$ban_tol = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->sum('totals-consume');
                        $sum='';
                        if($ban_tol<=0){
                            $sum = 0;
                        }else{
                            $sum = $ban_tol;
                        }
                        $balance = $model->query("SELECT sum(pwi.consume_account) as count
                                FROM
                                    `device_info` di
                                LEFT JOIN device_consume_rec pwi ON pwi.di_id = di.id
                                WHERE
                                    pwi.command_status = 2
                                and pwi.del_flag=0
                                and pwi.type in(1,3,5,9,11,13,15,17)
                                and pwi.transfer_status = 0
                                AND pwi.create_by = '$percent[id]'
                                AND di.del_flag = 0");
                        //支付宝收益
                        $alipay_count = $model->query("SELECT sum(pwi.consume_account) as alipay
                        FROM
                            `device_info` di
                        LEFT JOIN device_consume_rec pwi ON pwi.di_id = di.id
                        WHERE
                            pwi.command_status = 2
                        and pwi.del_flag=0
                        and pwi.type in(2,4,6,10,12,14,16,18)
                        and pwi.transfer_status = 0
                        AND pwi.create_by = '$percent[id]'
                        AND di.del_flag = 0");
                        if(intval($balance[0]['count'])<=0){
                            $w = 0;
                        }else {
                            $w = $balance[0]['count'];
                        }
                        if(intval($alipay_count[0]['alipay']) <= 0){
                            $p = 0;
                        } else {
                            $p = $alipay_count[0]['alipay'];
                        }
                        $to['totals'] = $w+$p;
                        M('sys_user')
                            ->where(array('del_flag'=>0,'id'=>$percent['id']))
                            ->save($to);*/
                        echo json_encode( array('result_code' => 'SUCCESS', 'return_msg' => '提现成功', 'return_ext' => $banlance) );
                    }else{
                        //账户中没钱，则删除数据库的单一数据
                        M('weixin_enterprise_payment')->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
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
    /*===提现记录 ]]==*/
    //微信消费提现 [[
    public function cash_money(){
        $banlance = M('device_info')->join("device_consume_rec pwi ON pwi.di_id = device_info.id")
            ->where(array(
                'pwi.command_status'=>2,
                'pwi.del_flag'=>0,
                'pwi.type'=>array('in','1,2,3,4,5,6,9,10,11,12,13,14,15,16'),
                'pwi.transfer_status'=>0,
                'pwi.create_by'=>$this->user_id,
                'device_info.del_flag'=>0
            ))->sum("pwi.consume_account");
        $office = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        $total = $banlance*$office['percent']/100;
        if($banlance){
            if($banlance < 0){
                $p = 0;
            }else{
                $p=$total;
            }
            echo json_encode(array('result' => 200,'reg'=>$p));
        } else {
            echo json_encode(array('result' => 500));
        }
    }
}