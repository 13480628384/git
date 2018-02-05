<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class Rose2OrderflowController extends AdminbaseController {
    protected $user_id;
    protected $openid;
    public function _initialize() {
        parent::_initialize();
        $users_obj= M("sys_user");
        $user=$users_obj->where(array('id'=>$_GET['id'],'del_flag'=>0))->find();
        $this->user_id = $user['id'];
        $this->openid = $user['openid'];
        $this->name = $user['name'];
    }
    // 后台本站用户列表
    public function index(){
        if(empty($_GET['id']) || !isset($_GET['id'])){
            exit('参数错误');
        }
        $model = M('device_consume_rec');
        $yesterday = strtotime(date('Ymd',strtotime('-365 day')));
        $serven = date('Y-m-d H:i:s',$yesterday);
        $result = $model
            ->where(array(
                'command_status'=>'2',
                'consume_status'=>array('in','1,2'),
                'transfer_status'=>'0',
                'del_flag'=>'0',
                'create_by'=>$this->user_id,
                'create_date'=>array('egt',$serven)
            ))
            ->field('month(create_date) as month,sum(consume_account) as acount')
            ->group('month(create_date)')
            ->select();
        //提现金额
        $banlance = M('weixin_enterprise_payment')->where(array('status'=>'1','openid'=>$this->openid))
            ->field('month(create_date) as month,sum(amount) as amount')
            ->group('month(create_date)')
            ->select();

        //预存金额
        $pay = M('weixin_pay_rec')->where(array('pay_status'=>'1','is_close'=>'0','create_by'=>$this->user_id))
            ->field('month(create_date) as month,sum(pay_account) pay_account')
            ->group('month(create_date)')
            ->select();
        if($pay){
            $res = array_merge($result,$banlance,$pay);
        } else {
            $res = array_merge($result,$banlance);
        }
        $resu = array();
        foreach ($res as $k =>$v){
            $month = $v['month'];
            $resu[$month][] = $v;
        }
        $results = array();
        $this->arr_manage($resu, $results);
        $this->assign([
            'result'=>$results,
            'name'=>$this->name,
            'user_id'=>$this->user_id
        ]);
        $this->display("Rose2Orderflow:index");
    }
    /*
    * ================
    * 根据月份展示设备消费情况
    * 2017-12-27
    * ================
    * */
    public function acount(){
        if(isset($_GET['month'])){
            $month = $_GET['month'];
            $user_id = $_GET['user_id'];
            $result = M('device_consume_rec')
                ->query("SELECT `from_username`,`deivce_command`,`create_date`,`consume_account`,`type`,`id`,`command_status` 
FROM `device_consume_rec` WHERE command_status='2' and consume_status in(1,2) and transfer_status='0' and del_flag='0'
and create_by = '$user_id' and month(create_date) = $month");
            $this->assign('result',$result);
            $this->assign('month',$month);
            $this->display();
        }
    }
    /*
     * =======================
     * 每个月份的提现记录
     * 2017-12-27
     * =======================
     * */
    public function tixian(){
        if(isset($_GET['month'])){
            $month = $_GET['month'];
            $user_id = $_GET['user_id'];
            $user=M('sys_user')->where(array('id'=>$user_id,'del_flag'=>0))->getField('openid');
            $result = M('weixin_enterprise_payment')
                ->query("SELECT partner_trade_no,create_date,amount,status FROM `weixin_enterprise_payment` WHERE openid = '$user' and month(create_date) = $month order by create_date desc");
            $this->assign('result',$result);
            $this->assign('month',$month);
            $this->display();
        }
    }
    /*
     * ================
     * 预存金额（充值金额）
     * 2017-12-28
     * ================
     * */
    public function yucun(){
        if(isset($_GET['month'])){
            $month = $_GET['month'];
            $user_id = $_GET['user_id'];
            $result = M('weixin_pay_rec')
                ->query("SELECT from_username,create_date,pay_account,out_trade_no,pay_status FROM `weixin_pay_rec` WHERE create_by = '$user_id' and month(create_date) = $month  order by create_date desc");
            $this->assign('result',$result);
            $this->assign('month',$month);
            $this->display();
        }
    }
    /*
     * ================
     * 多维数组相同的合并
     * 2017-12-27
     * ================
     * */
    public function arr_manage($data, &$result) {
        foreach ($data as $key => $value) {
            if ($value['month']) {
                foreach ($value as $k => $v) {
                    $result[$value['month']][$k] = $v;
                }
            } elseif (is_array($value)) {
                $this->arr_manage($value, $result);
            }
        }
    }
}
