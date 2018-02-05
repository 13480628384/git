<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DeviceController extends AdminbaseController{

    protected $device_info_model;
    protected $owner_id;
    protected $company_id;
    protected $company_ids;
    protected $office_id;
    protected $office_ids;
    protected $area;
    public function _initialize() {
        parent::_initialize();
        //归属区域
        $this->area = D('sys_area')->where(array('del_flag'=>0,'type'=>4))->order('parent_id desc')->select();
        //超级管理员
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            //归属用户
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0))->order('create_date desc')->select();
            //归属公司
            $this->company_id = M('sys_office')->where(array('del_flag'=>0,'type'=>1))->order('create_date desc')->select();
            //归属部门
            $this->office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1)))->order('create_date desc')->select();

        } else {
        //普通管理员
            $company_ids = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('company_id');

            $office_id = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('office_id');
            //归属用户
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'company_id'=>$company_ids))->select();
            //归属公司
            $this->company_id = M('sys_office')->where(array('del_flag'=>0,'type'=>1,'id'=>$company_ids))->select();
            $this->company_ids = $company_ids;
            $this->office_ids = $office_id;
            $this->office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1),'id'=>$office_id))->select();
            //$this->office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1,'parent_id'=>$office_id)))->select();
        }
        $this->device_info_model = D("Common/Device_info");
    }
    // 后台设备信息列表
    public function index(){
        //超级用户管理权限
        $request=I('request.');
        if(!empty($request['device_type'])){
            $where['di.device_type']=$request['device_type'];
        }if(!empty($request['owner_id'])){
            $where['di.owner_id']=$request['owner_id'];
        }if(!empty($request['company_id'])){
            $where['di.company_id']=$request['company_id'];
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
        //显示归属用户
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $where['di.del_flag'] = 0;
            $where['sa.del_flag'] = 0;
            $where['so.del_flag'] = 0;
            $where['su.del_flag'] = 0;
            $device=$this->device_info_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_area sa on sa.id=di.area_id')
                ->join('sys_office so on so.id=di.office_id')
                ->join('sys_office com on com.id=di.company_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_info_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_area sa on sa.id=di.area_id')
                ->join('sys_office so on so.id=di.office_id')
                ->join('sys_office com on com.id=di.company_id')
                ->where($where)
                ->field('di.scan_code,su.name,sa.name as area_name
                ,so.name as office_name,com.name as company_name,di.install_date,di.device_code,di.device_command,di.id
                ,di.device_type,di.device_status,di.create_date')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        } else {//普通管理员

            $device=$this->device_info_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_area sa on sa.id=di.area_id')
                ->join('sys_office so on so.id=di.office_id')
                ->join('sys_office comm on comm.id=di.company_id')
                ->where(array(
                    'di.del_flag'=>0,
                    'sa.del_flag'=>0,
                    'so.del_flag'=>0,
                    'su.del_flag'=>0,
                    'di.office_id'=>$this->office_ids,
                ))
                ->count();
            $where['di.del_flag'] = 0;
            $where['di.office_id'] = $this->office_ids;
            $page = $this->page($device,30);
            $list = $this->device_info_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_area sa on sa.id=di.area_id')
                ->join('sys_office so on so.id=di.office_id')
                ->join('sys_office com on com.id=di.company_id')
                ->where($where)
                ->field('di.scan_code,su.name,sa.name as area_name
                ,so.name as office_name,com.name as company_name,di.install_date,di.device_code,di.device_command,di.id
                ,di.device_type,di.device_status,di.create_date')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        }//echo $this->device_info_model->getlastsql();die;
        /*$page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","last"=>"尾页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));*/
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("owner_id",$this->owner_id);
        $this->assign("company_id",$this->company_id);
        $this->assign("device",$device);
        $this->display();
    }
    //设备信息修改
    public function device_edit(){
        $id = I('get.id');
        $device = $this->device_info_model->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign("owner_id",$this->owner_id);
        $this->assign('device',$device);
        $this->assign('area',$this->area);
        $this->assign('office_id',$this->office_id);
        $this->assign("company_id",$this->company_id);
        $this->display();
    }
    //设备信息修改ajax
    public function edit_post(){
        if(IS_POST){
            $data['company_id'] = trim($_POST['company_id']);
            $data['office_id'] = trim($_POST['office_id']);
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['area_id'] = trim($_POST['area']);
            $data['scan_code'] = trim($_POST['scan_code']);
            $data['install_date'] = trim($_POST['install_date']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['device_type'] = trim($_POST['device_type']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['remarks'] = trim($_POST['remarks']);
            $di_id = trim($_POST['di_id']);
            $model = M('device_relation_group');
            //判断设备是否安装
            $relation = $model->where(array('del_flag'=>0,'di_id'=>$di_id))->find();
            if($relation){
                //判断该部门下是否有群组
                $group = M('deivce_group_info')->where(array('id'=>$relation['dgi_id'],'del_flag'=>0))->find();
                if(!$group) $this->error('该部门下没有群组,请在该部门下先建立群组');
                $model->startTrans();
                $rela['create_by'] = trim($_POST['office_id']);
                $rela['update_date'] = date('Y-m-d H:i:s',time());
                $rela['device_code'] = trim($_POST['device_code']);
                $rela['device_type'] = trim($_POST['device_type']);
                $rela['device_command'] = trim($_POST['device_command']);
                $rela['dgi_id'] = $group['id'];
                $resu = $model->where(array('device_code'=>trim($_POST['device_code']),'del_flag'=>0))->save($rela);
                $result = $this->device_info_model->where(array('id'=>$di_id,'del_flag'=>0))->save($data);
                if($resu && $result){
                    $model->commit();
                    $this->success('修改成功',U('Device/index'));
                } else {
                    $model->rollback();
                    $this->error('修改失败');
                }
            } else {
                $result = $this->device_info_model->where(array('id'=>$di_id,'del_flag'=>0))->save($data);
                if( $result){
                    $this->success('修改成功',U('Device/index'));
                } else {
                    $this->error('修改失败');
                }
            }

        }
    }
    //单个设备安装
    public function device_package(){
        $id = I('get.id');
        $device = $this->device_info_model->where(array('id'=>$id,'del_flag'=>0))->find();
        $relation = M('device_relation_group')->where(array('di_id'=>$id,'del_flag'=>0))->find();
        if($relation){
            $this->error('该设备已经安装');
        }
        //找出群组名称
        if($this->role_type == 2 || get_current_admin_id() == 1){
            $group = M('deivce_group_info')->where(array('del_flag'=>0))->select();
        }else {
            $group = M('deivce_group_info')->where(array('del_flag'=>0,'office_id'=>get_current_office_id()))->select();
        }
        $this->assign('id',$device);
        $this->assign('group',$group);
        $this->display();
    }
    //单个设备安装提交
    public function package_post(){
        $id = $_POST['id'];
        $group_word = $_POST['group_word'];
        $group_id = $_POST['group_name'];
        if(empty($group_id)){
            $this->error('请选择群组名称，或先添加群组名称');
        }
        $device_command = $this->device_info_model->where(array('id'=>$id,'del_flag'=>0))->find();
        if($device_command['device_type'] == '5'){
            $res['charger'] = '1=5-2=18-3=31-4=43';
        }else if($device_command['device_type'] == '4'){
            $res['ANM'] = '1=5-2=18-3=31';
        }else if($device_command['device_type'] == '6'){
            $res['charger'] = '1=5-2=18-3=31';
        }else if($device_command['device_type'] == '7'){
            $res['charger'] = '7=10';
        }
        $res['group_word'] = $group_word;
        $res['id'] = generateNum();
        $res['di_id'] = $id;
        $res['dgi_id'] = $group_id;
        $res['device_code'] = $device_command['device_code'];
        $res['device_command'] = $device_command['device_command'];
        $res['device_type'] = $device_command['device_type'];
        //$res['ANM'] = $device_command['ANM'];
        $res['update_by'] = $device_command['office_id'];
        $res['create_by'] = $device_command['office_id'];
        $res['ords'] = rand(1,99);
        $res['status'] = 1;
        $res['create_date'] = date('Y-m-d H:i:s',time());
        $res['update_date'] = date('Y-m-d H:i:s',time());
        $uid = M('device_relation_group')->add($res);
        if($uid){
            $this->success('安装成功',U('Device/index'));
        } else {
            $this->error('安装失败');
        }
    }
    //设备信息删除
    public function device_del(){
        $id = I('get.id');
        $device = $this->device_info_model->where(array('id'=>$id,'del_flag'=>0))->delete();
        $relation = M('device_relation_group')->where(array('di_id'=>$id,'del_flag'=>0))->find();
        if($relation){
            M('device_relation_group')->where(array('di_id'=>$id,'del_flag'=>0))->delete();
            if($relation && $device){
                $this->success('删除成功',U('Device/index'));
            } else {
                $this->error('删除失败');
            }
        } else {
            if($device){
                $this->success('删除成功',U('Device/index'));
            } else {
                $this->error('删除失败');
            }
        }
    }
    //设备信息添加
    public function device_add(){
        $this->assign("owner_id",$this->owner_id);
        $this->assign('area',$this->area);
        $this->assign('office_id',$this->office_id);
        $this->assign("company_id",$this->company_id);
        $this->display();
    }
    //设备信息添加ajax
    public function add_post(){
        if($_POST){
            //判断扫描码是否存在
            $scan_code = $this->device_info_model->where(array('scan_code'=>trim($_POST['scan_code'])))->find();
            $device_code = $this->device_info_model->where(array('device_code'=>trim($_POST['device_code'])))->find();
            $device_command = $this->device_info_model->where(array('device_command'=>trim($_POST['device_command'])))->find();
            $pay = trim($_POST['pay_price']);
            IF(empty($pay))$this->error('请输入价格');
            if($scan_code || $device_code || $device_command){
                $this->error('扫描码或设备硬件码或指令码已经存在');
            }
            $data['company_id'] = trim($_POST['company_id']);
            $data['id'] = generateNum();
            $data['office_id'] = trim($_POST['office_id']);
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['area_id'] = trim($_POST['area']);
            $data['scan_code'] = trim($_POST['scan_code']);
            $data['install_date'] = trim($_POST['install_date']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['device_type'] = trim($_POST['device_type']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = get_current_admin_id();
            $data['install_type'] = 1;
            $meigui = $_POST['meigui'];
            $one1 = $_POST['one1'];
            $reai = $_POST['reai'];
            $one2 = $_POST['one2'];
            $zhao = $_POST['zhao'];
            $one3 = $_POST['one3'];
            $heh = $_POST['heh'];
            $one4 = $_POST['one4'];
            $car_fen = $meigui.'-'.$one1.','.$reai.'-'.$one2.','.$zhao.'-'.$one3.','.$heh.'-'.$one4;
            $data['car_fen'] = $car_fen;
            if($this->device_info_model->add($data)){
                //添加成功即可安装步骤
                $device_commandeds = $this->device_info_model->
                where(array('device_code'=>trim($_POST['device_code']),'del_flag'=>0))->find();
                if($device_commandeds['device_type'] == '5'){
                    $res['charger'] = '1=5-2=18-3=31-4=43';
                }else if($device_commandeds['device_type'] == '4'){
                    $res['ANM'] = '1=5-2=18-3=31';
                }else if($device_commandeds['device_type'] == '6'){
                    $res['charger'] = '1=5-2=18-3=31';
                }else if($device_commandeds['device_type'] == '7'){
                    $res['charger'] = '7=10';
                }else if($device_commandeds['device_type'] == '9'){
                    $res['charger'] = '10-1800=10-1800=10-1800=10-1800';
                }
                //归属部门
                $group = M('deivce_group_info')->
                where(array('del_flag'=>0,'owner_id'=>trim($_POST['owner_id'])))->find();
                if(empty($group)){
                    $this->device_info_model->
                    where(array('device_code'=>trim($_POST['device_code']),'del_flag'=>0))->delete();
                    $this->error('归属部门的群组不存在，请先新建立群组');
                }
                $res['group_word'] = 'A';
                $res['id'] = generateNum();
                $res['di_id'] = $device_commandeds['id'];
                $res['dgi_id'] = $group['id'];
                $res['device_code'] = $device_commandeds['device_code'];
                $res['device_command'] = $device_commandeds['device_command'];
                $res['device_type'] = $device_commandeds['device_type'];
                $res['update_by'] = $device_commandeds['office_id'];
                $res['create_by'] = $device_commandeds['office_id'];
                $res['ords'] = rand(1,99);
                $res['status'] = 1;
                $res['create_date'] = date('Y-m-d H:i:s',time());
                $res['update_date'] = date('Y-m-d H:i:s',time());
                M('device_relation_group')->add($res);
                $this->success('设备添加成功',U('Device/index'));
            } else {
                $this->error('设备添加失败');
            }
        }
    }
}