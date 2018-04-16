<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 14:45
 */
class GlassregiterAction extends BackAction{
    public $scan_code = null;
    public $type = null;
    protected function _initialize(){
        parent::_initialize();
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
    }
    public function index(){
        $openid = trim($_GET['openid']);
        $scan_code = trim($_GET['scan_code']);
        if(empty($openid)) exit('确少参数');
        $this->assign('openid',$openid);
        $this->assign('scan_code',$scan_code);
        $this->display();
    }
    //注册会员提交
    public function add_post(){
        if($_POST){
            $openid = trim($_POST['openid']);
            $phone = trim($_POST['phone']);
            $password = trim($_POST['password']);
            $name = trim($_POST['name']);
            //判断是否已经注册过
            $where['user_id'] = $openid;
            $where['phone'] = $phone;
            $where['_logic'] = 'OR';
            if(M('glass_user')->where($where)->find()){
                exit(json_encode(array('code'=>'500','msg'=>'你已经注册过了')));
            }
            $data['id'] = generateId();
            $data['user_id'] = $openid;
            $data['phone'] = $phone;
            $data['totals'] = 0;
            $data['time_nums'] = 0;
            $data['name'] = $name;
            $data['password'] = $password;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('glass_user')->add($data)){
                echo json_encode(array('code'=>'200','msg'=>'注册成功'));
            } else {
                echo json_encode(array('code'=>'500','msg'=>'网络错误，请重试'));
            }
            //不能重复提交
        }
    }
    //会员充值入口
    public function pay_index(){
        $openid = trim($_GET['openid']);
        $scan_code = trim($_GET['scan_code']);
        $owner_id = trim($_GET['owner_id']);
        if(empty($openid)) exit('确少参数');
        if(empty($scan_code)) exit('确少参数,二维码');
        //找出多少钱启动一次，方便后面算法
        $user_id = M('device_info')->where(array('scan_code'=>$scan_code,'del_flag'=>0))->getField('owner_id');
        $result = M('sys_user')->where(array('id'=>$user_id,'del_flag'=>0))->find();
        $cishu = M('glass_user')->where(array('user_id'=>$openid))->getField('time_nums');
        $res = explode('-',$result['glass_price']);
        $this->assign('openid',$openid);
        $this->assign('scan_code',$scan_code);
        $this->assign('kewan',$cishu);
        $this->assign('owner_id',$owner_id);
        $this->assign('result',$result);
        $this->assign('res',$res);
        $this->display();
    }
}