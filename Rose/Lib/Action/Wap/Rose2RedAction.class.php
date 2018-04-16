<?php
//红包提现
include "lib/WxMch.Pay.php";
require_once "ComPay.class.php";
class Rose2RedAction extends Rose2BaseAction {
    public function index(){
        $ban_tol = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->sum('totals-consume');
        $sums='';
        if($ban_tol>=10){
            $shou = round(($ban_tol*0.006),2);
            $sums  = ($ban_tol-$shou);
        } else if($ban_tol<=0){
            $sums = 0;
        }else{
            $sums = $ban_tol;
        }
        $this->assign('sums',$sums);//可提现
        $this->assign('openid',$this->openid);
        $this->display();
    }
    //红包提现
    public function my_cash(){
        if(IS_POST){
            $model = M('weixin_enterprise_payment');
            $openid = trim($_POST['openid']);
            $sum = trim($_POST['sum']);
            if($sum < 100){
                exit(json_encode(array('code'=>201,'msg'=>'余额不足')));
            }
            //判断余额是否足够
            $ban_tol = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->sum('totals-consume');
            if($ban_tol <= 0){
                exit(json_encode(array('code'=>201,'msg'=>'余额不足')));
            }
            //每天只能提现一次
            $onlyone = $model->where(array('openid'=>$openid,'status'=>'1'))->find();
            if($onlyone){
                exit(json_encode(array('code'=>201,'msg'=>'你今天已提现')));
            }
            $sys_user = M('sys_user');
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $percent = $sys_user->where(array('del_flag'=>0,'openid'=>$openid))->find();
            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
                . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $datas['id'] = md5(uniqid());
            $datas['appid'] = WxPayConfig::APPID;
            $datas['mchid'] = WxPayConfig::MCHID;
            $datas['partner_trade_no'] = $orderSn;
            $datas['status'] = 0;
            $datas['openid'] = $openid;
            $datas['check_name'] = 'NO_CHECK';
            $datas['amount'] = $sum/100;
            $datas['arrival'] = $sum/100;
            $datas['descs'] = '红包记录:'.$percent['name'];
            $datas['spbill_create_ip'] = GetIP();
            $datas['create_date'] = date('Y-m-d H:i:s',time());
            $times = date('Y-m-d H:i:s',time());
            $Transfer = $model->add($datas);
            $model->startTrans();
            //提现开始
            $com = new ComPay();
            $response = $com->RedBag($sum,$openid);//返回微信单号
            if(!empty($response)){
                $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
                $array_no = json2arr($data);
                if($array_no['result_code'] == 'SUCCESS') {
                    $ep['status'] = 1;
                    $ep['payment_time'] = $times;
                    $ep['payment_no'] = $array_no['send_listid'];
                    $ep['update_date'] = $times;
                    $epids = $model
                        ->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn))
                        ->save($ep);
                    //扣掉用户提现的钱
                    $Transfer_id = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->setInc('consume',$sum/100);
                    if($epids && $Transfer_id){
                        $model->commit();
                        echo json_encode( array('code' => '200', 'msg' => '提现成功', 'url' => U('Rose2Wallet/Present',array('openid'=>$openid))) );
                    }else{
                        $model->rollback();
                        echo json_encode( array('code' => '201', 'msg' => '提现失败') );
                    }
                }else{
                    echo json_encode( array('code' => '201', 'msg' => $array_no['err_code_des']) );
                }
            }
        }
    }
}