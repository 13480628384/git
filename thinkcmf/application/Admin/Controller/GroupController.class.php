<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class GroupController extends AdminbaseController{
    protected $device_relation_group_model;
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
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0))->select();
            //归属公司
            $this->company_id = M('sys_office')->where(array('del_flag'=>0,'type'=>1))->select();
            //归属部门
            $this->office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1)))->select();

        } else {
            //普通管理员
            $company_ids = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('company_id');

            $office_id = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('office_id');
            $this->office_ids = $office_id;
            //归属用户
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'company_id'=>$company_ids))->select();
            //归属公司
            $this->company_id = M('sys_office')->where(array('del_flag'=>0,'type'=>1,'id'=>$company_ids))->select();
            $this->company_ids = $company_ids;
            $this->office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1),'id'=>$office_id))->select();
        }
        $this->device_relation_group_model = D("Common/deivce_group_info");
    }

    // 后台设备信息列表
    public function index(){
        //超级用户管理权限
        $request=I('request.');
        if(!empty($request['owner_id'])){
            $where['di.owner_id']=intval($request['owner_id']);
        }
        if(!empty($request['office_id'])){
            $where['di.office_id']=intval($request['office_id']);
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['di.group_name']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        //显示归属用户
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $where['di.del_flag'] = 0;
            $where['so.del_flag'] = 0;
            $where['su.del_flag'] = 0;
            $device=$this->device_relation_group_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_office so on so.id=di.office_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_relation_group_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_office so on so.id=di.office_id')
                ->where($where)
                ->field('di.create_date,di.group_name,di.ords,di.update_date,so.name,su.login_name,di.id')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            //echo $this->device_relation_group_model->getlastsql();die;
        } else {//普通管理员
            $where['di.office_id'] = $this->office_ids;
            $device=$this->device_relation_group_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_office so on so.id=di.office_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_relation_group_model
                ->alias('di')
                ->join('sys_user su on su.id=di.owner_id')
                ->join('sys_office so on so.id=di.office_id')
                ->where($where)
                ->field('di.create_date,di.group_name,di.ords,di.update_date,so.name,su.login_name,di.id')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        }
        /*$page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","last"=>"尾页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));*/
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("owner_id",$this->owner_id);
        $this->assign('office_id',$this->office_id);
        $this->assign("device",$device);
        $this->display();
    }
    //群组信息修改
    public function edit(){
        $id = I('get.id');
        $device = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign("owner_id",$this->owner_id);
        $this->assign('device',$device);
        $this->assign('office_id',$this->office_id);
        $this->display();
    }
    //设备信息修改ajax
    public function edit_post(){
        if(IS_POST){
            $data['office_id'] = trim($_POST['office_id']);
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['ords'] = trim($_POST['ords']);
            $data['group_name'] = trim($_POST['group_name']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $id = trim($_POST['id']);
            $result = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->save($data);
            if( $result){
                $this->success('修改成功',U('Group/index'));
            } else {
                $this->error('修改失败');
            }
        }
    }
    //群组信息删除
    public function del(){
        $id = I('get.id');
        $device = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->delete();
        if($device){
            $this->success('删除成功',U('Group/index'));
        } else {
            $this->error('删除失败');
        }
    }
    //群组信息添加
    public function add(){
        $this->assign("owner_id",$this->owner_id);
        $this->assign('office_id',$this->office_id);
        $this->display();
    }
    //设备信息添加ajax
    public function add_post(){
       if(IS_POST){
           $data['office_id'] = trim($_POST['office_id']);
           $data['owner_id'] = trim($_POST['owner_id']);
           $data['ords'] = trim($_POST['ords']);
           $data['group_name'] = trim($_POST['group_name']);
           $data['remarks'] = trim($_POST['remarks']);
           $data['update_date'] = date('Y-m-d H:i:s',time());
           $data['create_date'] = date('Y-m-d H:i:s',time());
           $data['create_by'] = get_current_admin_id();
           $data['id'] = generateNum();
           if($this->device_relation_group_model->add($data)){
               $this->success('添加成功',U('Group/index'));
           } else {
               $this->error('添加失败');
           }
       }
    }
}