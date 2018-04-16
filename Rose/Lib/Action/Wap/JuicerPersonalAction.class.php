<?php
class JuicerPersonalAction extends JuicerBaseAction {
    protected $Device_consume_weixin_rec;
    protected $Device_consume_alipay_rec;
    protected $Device_info_detail;
    protected $juicer;
    public function _initialize() {
        Vendor('weixin.OneNetApi');
        parent::_initialize();
        $apikey = 'WXHTne4BIQxki8nCSzmyiZBetNw=';
        $apiurl = 'http://api.heclouds.com';
        $sm = new OneNetApi($apikey, $apiurl);
        $this->juicer =$sm;
        $this->Device_consume_weixin_rec = M('ju_device_consume_weixin_rec');
        $this->Device_consume_alipay_rec = M('ju_device_consume_alipay_rec');
        $this->Device_info_detail = M('ju_device_info_detail');
    }
    /*
     * ===============================
     * date 2017/05/05 11:36
     * author chw                START
     * remarks 榨汁机设备信息列表
     * ===============================
     * */
    public function index(){
        //区域用户
        $where['di.del_flag'] = 0;
        $Area = $this->Device_info_detail
            ->alias('di')
            ->join("ju_users ju on ju.id=di.owner_id")
            ->join("ju_area ja on ja.id=di.area_id")
            ->where($where)
            ->field('di.scan_code,di.area_id,di.device_code,di.device_command,di.id,ju.user_login as ju_name,ja.name as ja_name
                ,di.device_type,di.device_status,di.create_date,di.online_status,di.pay_price,ja.id as ja_id,ju.id as ju_id')
            ->order("create_date DESC")
            ->select();
        if($this->type == '2' || $this->type == '4'){
            foreach($Area as $k => $v){
                $parent_ids = M('ju_area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($Area[$k]);
                }
            }
        }
        $this->assign('res',$Area);
        $this->display();
    }
    /*
     * ===============================
     * date 2017/05/05 11:36
     * author chw                END
     * remarks 榨汁机设备信息列表
     * ===============================
     * */

    /*
     * ===============================
     * date 2017/05/05 14:25
     * author chw              START
     * remarks 修改设备信息详情
     * ===============================
     * */
    public function update_detail_device(){
        if(IS_POST){
            $id = trim($_POST['id']);
            $device_code = trim($_POST['device_code']);
            $device_command = trim($_POST['device_command']);
            $pay_price = trim($_POST['pay_price']);
            $data['device_code'] = $device_code;
            $data['device_command'] = $device_command;
            $data['pay_price'] = $pay_price;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if($this->Device_info_detail->where(array('del_flag'=>0,'id'=>$id))->save($data)){
                echo json_encode(array('code'=>200));
            }else{
                echo json_encode(array('code'=>500));
            }
        }else{
            $uid = trim($_GET['id']);
            $res = $this->Device_info_detail->where(array('del_flag'=>0,'id'=>$uid))->find();
            $this->assign('uid',$uid);
            $this->assign('res',$res);
            $this->display();
        }
    }
    /*
     * ===============================
     * date 2017/05/05 14:25
     * author chw              END
     * remarks 修改设备信息详情
     * ===============================
     * */

    /*
     * ===============================
     * date 2017/05/05 14:50
     * author chw              START
     * remarks 微信收益
     * ===============================
     * */
    public function weixin_income(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $where['del_flag'] = 0;
            $where['type'] = '0';
            $list = $this->Device_consume_weixin_rec
                ->where($where)
                ->order("create_date DESC")
                ->limit($n,10)
                ->select();
            //市区或省份显示
            if($this->type == '2' || $this->type == '4'){
                foreach($list as $k=>$v){
                    $parent_ids = M('ju_area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                    $array_parent = explode(',',$parent_ids);
                    if(!in_array(session('area_id'),$array_parent)){
                        unset($list[$k]);
                    }
                }
            }
            $this->assign('list',$list);
            $html=$this->fetch('./tpl/Wap/default/JuicerPersonal_weixin_income_page.html');
            exit($html);
        } else {
            $where['del_flag'] = 0;
            $where['type'] = '0';
            $list = $this->Device_consume_weixin_rec
                ->where($where)
                ->order("create_date DESC")
                ->limit(10)
                ->select();
            //市区或省份显示
            if($this->type == '2' || $this->type == '4'){
                foreach($list as $k=>$v){
                    $parent_ids = M('ju_area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                    $array_parent = explode(',',$parent_ids);
                    if(!in_array(session('area_id'),$array_parent)){
                        unset($list[$k]);
                    }
                }
            }
            $this->assign('list',$list);
            $this->display();
        }
    }
    /*
     * ===============================
     * date 2017/05/05 14:50
     * author chw              END
     * remarks 微信收益
     * ===============================
     * */

    /*
     * ===============================
     * date 2017/05/05 14:50
     * author chw              START
     * remarks 支付宝收益
     * ===============================
     * */
    public function alipay_income(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $where['del_flag'] = 0;
            $where['type'] = '0';
            $list = $this->Device_consume_alipay_rec
                ->where($where)
                ->order("create_date DESC")
                ->limit($n,10)
                ->select();
            //市区或省份显示
            if($this->type == '2' || $this->type == '4'){
                foreach($list as $k=>$v){
                    $parent_ids = M('ju_area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                    $array_parent = explode(',',$parent_ids);
                    if(!in_array(session('area_id'),$array_parent)){
                        unset($list[$k]);
                    }
                }
            }
            $this->assign('list',$list);
            $html=$this->fetch('./tpl/Wap/default/JuicerPersonal_alipay_income_page.html');
            exit($html);
        } else {
            $where['del_flag'] = 0;
            $where['type'] = '0';
            $list = $this->Device_consume_alipay_rec
                ->where($where)
                ->order("create_date DESC")
                ->limit(10)
                ->select();
            //市区或省份显示
            if($this->type == '2' || $this->type == '4'){
                foreach($list as $k=>$v){
                    $parent_ids = M('ju_area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                    $array_parent = explode(',',$parent_ids);
                    if(!in_array(session('area_id'),$array_parent)){
                        unset($list[$k]);
                    }
                }
            }
            $this->assign('list',$list);
            $this->display();
        }
    }
    /*
     * ===============================
     * date 2017/05/05 14:50
     * author chw              END
     * remarks 支付宝收益
     * ===============================
     * */

    /*
     * ===============================
     * date 2017/05/05 15:50
     * author chw              START
     * remarks 个人信息
     * ===============================
     * */
    public function personal(){
        $ju_users = M('ju_users')->where(array('user_status'=>1,'user_type'=>1
        ,'openid'=>trim($this->openid)))->find();
        $this->assign('user',$ju_users);
        $this->display();
    }
    /*
     * ===============================
     * date 2017/05/05 15:50
     * author chw              END
     * remarks 个人信息
     * ===============================
     * */

    //调试设备页面
    public function test_device(){
        $device_command = intval(7989065);
        $ON = array("DEV_STA"=>intval(100));
        $result = $this->juicer->send_data_to_edp($device_command, '1', '1', $ON);
        $id = $_GET['id'];
        if(empty($id)) exit;
        $openid = $_GET['openid'];
        $detail = M('ju_device_info_detail')->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign('openid',$openid);
        $this->assign('detail',$detail);
        $this->display();
    }
    //trig
    public function TRIG(){
        if(IS_POST){
            $BIT = trim($_POST['BIT']);
            $status = trim($_POST['status']);
            $ON = array("$status"=>intval(100),'BIT'=>intval($BIT));
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '1';//为“秒”，默认“0”
            $device_command = intval($_POST['device_command']);
            $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
            $return_result = '';
            if (empty($result)) {
                $return_result = 0;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            if($res->TP == '1'){
                echo json_encode(array('code'=>200,'msg'=>'数据接收成功'));
            }else{
                echo json_encode(array('code'=>201,'msg'=>'数据接收失败'));
            }
        }
    }
    //调试设备
    public function send_test_device(){
        if(IS_POST){
            $BIT = trim($_POST['BIT']);
            $status = trim($_POST['status']);
            $ONE = trim($_POST['one']);
            $ON = array("$status"=>intval($ONE),'BIT'=>intval($BIT));
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '1';//为“秒”，默认“0”
            $device_command = intval($_POST['device_command']);
            $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
            $return_result = '';
            if (empty($result)) {
                $return_result = 0;
                //echo json_encode(array('code'=>201,'msg'=>'数据发送失败','result'=>$return_result));
            } else {
                $return_result = $result['cmd_uuid'];
                //echo json_encode(array('code'=>200,'msg'=>'数据发送成功','result'=>$return_result));
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            if($res->TP == '1'){
                echo json_encode(array('code'=>200,'msg'=>'数据接收成功'));
            }else{
                echo json_encode(array('code'=>201,'msg'=>'数据接收失败'));
            }
            /*if(isset($_POST['dataid']) && !empty($_POST['dataid'])){
                $return_result = $_POST['dataid'];
                $get_dev_status_resp = $this->juicer->get_http($return_result);
                $res = json_decode($get_dev_status_resp);
                if($res->TP == '1'){
                    echo json_encode(array('code'=>200,'msg'=>'数据接收成功'));
                }else{
                    echo json_encode(array('code'=>201,'msg'=>'数据接收失败'));
                }
            }else{
                $BIT = trim($_POST['BIT']);
                $status = trim($_POST['status']);
                $ONE = trim($_POST['one']);
                $ON = array("$status"=>intval($ONE),'BIT'=>intval($BIT));
                $qos = '1'; //1需要响应  0 不需要响应
                $timeout = '1';//为“秒”，默认“0”
                $device_command = intval($_POST['device_command']);
                $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
                $return_result = '';
                if (empty($result)) {
                    $return_result = 0;
                    //echo json_encode(array('code'=>201,'msg'=>'数据发送失败','result'=>$return_result));
                } else {
                    $return_result = $result['cmd_uuid'];
                    //echo json_encode(array('code'=>200,'msg'=>'数据发送成功','result'=>$return_result));
                }
                sleep(1);
                $get_dev_status_resp = $this->juicer->get_http($return_result);
                $res = json_decode($get_dev_status_resp);
                if($res->TP == '1'){
                    echo json_encode(array('code'=>200,'msg'=>'数据接收成功'));
                }else{
                    echo json_encode(array('code'=>201,'msg'=>'数据接收失败'));
                }
            }*/
        }
    }
    //16位调试
    public function send_test_16(){
        if(IS_POST){
            $WRT_REG = trim($_POST['WRT_REG']);
            $VAL16 = trim($_POST['VAL16']);
            $ON = array("WRT_REG"=>intval($WRT_REG),"VAL16"=>intval($VAL16));
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '1';//为“秒”，默认“0”
            $device_command = $_POST['device_command'];
            $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
            $return_result = '';
            if (empty($result)) {
                $return_result = 0;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            if($res->TP == '1'){
                echo json_encode(array('code'=>200,'msg'=>'调试成功'));
            }else{
                echo json_encode(array('code'=>201,'msg'=>'调试失败'));
            }
        }
    }
    //调试110
    public function send_110(){
        if(IS_POST){
            $status = trim($_POST['status']);
            $ON = array("INQ_REG"=>intval($status));
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '1';//为“秒”，默认“0”
            $device_command = $_POST['device_command'];
            $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
            $return_result = '';
            if (empty($result)) {
                $return_result = 0;
            } else {
                $return_result = $result['cmd_uuid'];
            }
            sleep(1);
            $get_dev_status_resp = $this->juicer->get_http($return_result);
            $res = json_decode($get_dev_status_resp);
            if($res){
                if(isset($res->REG_34)){
                    echo json_encode(array('code'=>200,'msg'=>'橙子使用个数:'.$res->REG_34));
                }elseif(isset($res->REG_36)){
                    echo json_encode(array('code'=>200,'msg'=>'榨汁杯数:'.$res->REG_36));
                }elseif(isset($res->REG_110)){
                    echo json_encode(array('code'=>200,'msg'=>$res->REG_110));
                }elseif(isset($res->REG_111)){
                    echo json_encode(array('code'=>200,'msg'=>$res->REG_111));
                }elseif(isset($res->REG_112)){
                    echo json_encode(array('code'=>200,'msg'=>$res->REG_112));
                }else{
                    echo json_encode(array('code'=>200,'msg'=>$res->error));
                }
            }else{
                echo json_encode(array('code'=>201,'msg'=>'调试失败'));
            }
        }
    }
}