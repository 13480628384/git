<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DeviceController extends AdminbaseController{
    protected $Device_info_detail;
    protected $juicer;
    public function _initialize() {
        include "OneNetApi.php";
        parent::_initialize();
        include "Off_Tree.class.php";
        $apikey = 'WXHTne4BIQxki8nCSzmyiZBetNw=';
        $apiurl = 'http://api.heclouds.com';
        $sm = new \OneNetApi($apikey, $apiurl);
        $this->juicer =$sm;
        //归属地区
        $tree = new \Off_Tree();
        $parentid = 0;
        $area = M('area')->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $parentid ? '' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        $this->Device_info_detail = D("Common/Device_info_detail");
    }

    // 设备管理列表
    public function index(){
        $request=I('request.');
        if(!empty($request['area_id'])){
            $where['di.area_id']=$request['area_id'];
        }if(!empty($request['online_status'])){
            $where['di.online_status']=$request['online_status'];
        }if($request['online_status'] == '0'){
            $where['di.online_status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['di.scan_code']  = array('like', "%$keyword%");
            $keyword_complex['di.device_code']  = array('like',"%$keyword%");
            $keyword_complex['di.device_command']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['di.del_flag'] = 0;
        $count=$this->Device_info_detail
            ->alias('di')
            ->join("ju_users ju on ju.id=di.owner_id")
            ->join("ju_area ja on ja.id=di.area_id")
            ->where($where)->field('di.area_id')->select();
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k => $v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $page = $this->page(count($count), 20);
        $Area = $this->Device_info_detail
            ->alias('di')
            ->join("ju_users ju on ju.id=di.owner_id")
            ->join("ju_area ja on ja.id=di.area_id")
            ->where($where)
            ->field('di.scan_code,di.area_id,di.device_code,di.device_command,di.id,ju.user_login as ju_name,ja.name as ja_name
                ,di.device_type,di.device_status,di.create_date,di.online_status,di.pay_price,ja.id as ja_id,ju.id as ju_id')
            ->order("create_date DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($Area as $k => $v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($Area[$k]);
                }
            }
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$Area);
        $this->display();
    }
    /*
     * ==========================
     * 区域管理添加 START
     * ==========================
     */
    public function add(){
        //归属地区
        $tree = new \Off_Tree();
        //判断登录用户是本市用户还是省份用户,如果是本市用户则不用更改用户，如果是省份用户，显示省份内的用户即可
        if($this->area_type == '2'){
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'))->select();
            foreach($users as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent) && session('area_id') != $v['area_id']){
                    unset($users[$k]);
                }
            }
            //p($users);die;
        } else if($this->area_type == '4'){
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'
            ,'id'=>session('ADMIN_ID')))->select();
        } else {
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'))->select();
        }
        $sys_area = M('area')->where(array('type'=>4))->order(array("sort" => "ASC"))->select();
        foreach($sys_area as $k =>$v){
            $array_parent = explode(',',$v['parent_ids']);
            if(!in_array(session('area_id'),$array_parent)){
                unset($sys_area[$k]);
            }
        }
        $this->assign('sys_area',$sys_area);
        $this->assign('users',$users);
        $this->display();
    }
    public function add_post(){
        if(IS_POST){
            $area_id = trim($_POST['area_id']);
            $user_login = trim($_POST['user_login']);
            $scan_code = trim($_POST['scan_code']);
            $device_code = trim($_POST['device_code']);
            $device_command = trim($_POST['device_command']);
            $pay_price = trim($_POST['pay_price']);
            $status = trim($_POST['status']);
            $online_status = trim($_POST['online_status']);
            if(empty($device_code) || empty($scan_code)
                || empty($area_id) || empty($user_login)
                || empty($device_command) || empty($pay_price)){
                $this->error("参数不能为空！");
            }
            $keyword_complex=array();
            $keyword_complex['scan_code']  = array('like', "%$scan_code%");
            $keyword_complex['device_code']  = array('like',"%$device_code%");
            $keyword_complex['device_command']  = array('like',"%$device_command%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
            $where['del_flag'] = 0;
            $if = $this->Device_info_detail->where($where)->find();
            if($if){
                $this->error("编码已经存在！");
            }
            $data['area_id'] = $area_id;
            $data['id'] = generateNum();
            $data['scan_code'] = $scan_code;
            $data['device_command'] = $device_command;
            $data['device_code'] = $device_code;
            $data['device_status'] = $status;
            $data['online_status'] = $online_status;
            $data['pay_price'] = $pay_price;
            $data['owner_id'] = $user_login;
            $data['create_by'] = session('ADMIN_ID');
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if($this->Device_info_detail->add($data)){
                $this->success('添加成功',U('Device/index'));
            }else{
                $this->error('添加失败');
            }
        }
    }
    /*
     * ==========================
     * 区域管理添加 END
     * ==========================
     */

    /*
     * ==========================
     * 设备管理编辑 START
     * ==========================
     */
    public function edit(){
        //归属地区
        $tree = new \Off_Tree();
        $area_id = $_GET['area_id'];
        $id = $_GET['id'];
        $user_id = $_GET['user_id'];
        //判断登录用户是本市用户还是省份用户,如果是本市用户则不用更改用户，如果是省份用户，显示省份内的用户即可
        if($this->area_type == '2'){
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'))->select();
            foreach($users as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent) && session('area_id') != $v['area_id']){
                    unset($users[$k]);
                }
            }
         //p($users);die;
        } else if($this->area_type == '4'){
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'
            ,'id'=>session('ADMIN_ID')))->select();
        } else {
            $users = M('users')->where(array('user_type'=>'1','user_status'=>'1'))->select();
        }
        $sys_area = M('area')->where(array('type'=>4))->order(array("sort" => "ASC"))->select();
        foreach($sys_area as $k =>$v){
            $array_parent = explode(',',$v['parent_ids']);
            if(!in_array(session('area_id'),$array_parent)){
                unset($sys_area[$k]);
            }
        }
        $device =$this->Device_info_detail->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign('device',$device);
        $this->assign('users',$users);
        $this->assign('sys_area',$sys_area);
        $this->display();
    }
    public function edit_post(){
        if(IS_POST){
            $area_id = trim($_POST['area_id']);
            $id = trim($_POST['id']);
            $user_login = trim($_POST['user_login']);
            $scan_code = trim($_POST['scan_code']);
            $device_code = trim($_POST['device_code']);
            $device_command = trim($_POST['device_command']);
            $pay_price = trim($_POST['pay_price']);
            $status = trim($_POST['status']);
            $online_status = trim($_POST['online_status']);
            if(empty($device_code) || empty($scan_code)
                || empty($area_id) || empty($user_login)
                || empty($device_command) || empty($pay_price)){
                $this->error("参数不能为空！");
            }
            $area = M('area')->where(array('id'=>$area_id))->find();
            if($area['type'] == '1' || $area['type'] == '2'){
                $this->error("请选择归属区域！");
            }
            /*$keyword_complex=array();
            $keyword_complex['scan_code']  = array('like', "%$scan_code%");
            $keyword_complex['device_code']  = array('like',"%$device_code%");
            $keyword_complex['device_command']  = array('like',"%$device_command%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
            $if = $this->Device_info_detail->where($where)->find();
            if($if){
                $this->error("编码已经存在！");
            }*/
            $data['area_id'] = $area_id;
            $data['scan_code'] = $scan_code;
            $data['device_command'] = $device_command;
            $data['device_code'] = $device_code;
            $data['device_status'] = $status;
            $data['online_status'] = $online_status;
            $data['pay_price'] = $pay_price;
            $data['owner_id'] = $user_login;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if($this->Device_info_detail->where(array('id'=>$id,'del_flag'=>0))->save($data)){
                $this->success('修改成功',U('Device/index'));
            }else{
                $this->error('修改失败');
            }
        }
    }
    /*
     * ==========================
     * 区域管理编辑 END
     * ==========================
     */

    /*
     * ==========================
     * 区域管理删除
     * ==========================
     */
    public function delete(){
        $id = $_GET['id'];
        $data['del_flag'] = 1;
        if ($this->Device_info_detail->where(array('id'=>$id,'del_flag'=>0))->save($data)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    /*
     * 调试设备
     * @param $id 设备id
     * */
    public function test(){
        $id = $_GET['id'];
        if(empty($id)) exit;
        $detail = M('device_info_detail')->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign('detail',$detail);
        $this->display();
    }
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
                $ON = array("$status"=>intval(100),'BIT'=>intval($BIT));
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
                $ON = array("$status"=>intval(100),'BIT'=>intval($BIT));
                $qos = '1'; //1需要响应  0 不需要响应
                $timeout = '1';//为“秒”，默认“0”
                $device_command = intval($_POST['device_command']);
                $result = $this->juicer->send_data_to_edp($device_command, $qos, $timeout, $ON);
                $return_result = '';
                if (empty($result)) {
                    $return_result = 0;
                    echo json_encode(array('code'=>201,'msg'=>'数据发送失败','result'=>$return_result));
                } else {
                    $return_result = $result['cmd_uuid'];
                    echo json_encode(array('code'=>200,'msg'=>'数据发送成功','result'=>$return_result));
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
                    echo json_encode(array('code'=>200,'msg'=>'数据为空'));
                }
            }else{
                echo json_encode(array('code'=>201,'msg'=>'调试失败'));
            }
        }
    }
}