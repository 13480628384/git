<?php
class UserefundAction extends Action{
    public function index(){
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0){
            exit('请用微信打开');
        }
        $openid = $_GET['openid'];
        $total = $_GET['total'];
        $device_command = $_GET['device_command'];
        $owner_id = M('device_info')->where(array(
            'device_command'=>$device_command,'del_flag'=>0))->getField('owner_id');
        if(empty($openid) || !isset($openid)){
            exit('非法参数');
        }
        $this->assign('openid',$openid);
        $this->assign('owner_id',$owner_id);
        $this->assign('total',$total);
        $this->display();
    }
    //申请退款审核
    public function check_total(){
        $openid = trim($_POST['openid']);
        $today = date('Y-m-d 00:00:00');
        if(M('userefund')->where(array('openid'=>$openid,
            'apple_time'=>array('egt',$today)))->find()){
            echo json_encode( array('code' => '201', 'msg' => '您每天只能申请一次，请谅解') );
            exit;
        }
        $total = $_POST['total'];
        $remarks = $_POST['remarks'];
        $name = $_POST['name'];
        $owner_id = $_POST['owner_id'];
        $wechatid = $_POST['wechatid'];
        $phone = $_POST['phone'];
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        $data['id'] = generateNum();
        $data['openid'] = $openid;
        $data['partner_trade_no'] = $orderSn;
        $data['name'] = $name;
        $data['wechatid'] = $wechatid;
        $data['phone'] = $phone;
        $data['total'] = $total;
        $data['remarks'] = $remarks;
        $data['owner_id'] = $owner_id;
        $data['status'] = '0';
        $data['apple_time'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        if(M('userefund')->add($data)){
            echo json_encode( array('code' => '200', 'msg' => '提交成功，请留意您的账号信息') );
        }else{
            echo json_encode( array('code' => '201', 'msg' => '提交失败，请核实您的信息是否正确') );
        }
    }
}