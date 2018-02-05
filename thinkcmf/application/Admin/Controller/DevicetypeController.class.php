<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class DevicetypeController extends AdminbaseController{
    protected $device_type_model;
    public function _initialize() {
        parent::_initialize();
        $this->device_type_model = D("device_type");
    }
    public function index(){
            $device=$this->device_type_model
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_type_model->order("create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        /*$page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","last"=>"尾页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));*/
        $this->assign("page", $page->show('admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //设备信息修改
    public function edit(){
        $id = I('get.id');
        $device = $this->device_type_model->where(array('id'=>$id))->find();
        $this->assign('device',$device);
        $this->display();
    }
    //设备信息修改ajax
    public function edit_post(){
        if(IS_POST){
            $id = $_POST['id'];
            $data['device_type'] = trim($_POST['device_type']);
            $data['desc'] = trim($_POST['desc']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $result = $this->device_type_model->where(array('id'=>$id))->save($data);
            if( $result){
                $this->success('修改成功',U('Devicetype/index'));
            } else {
                $this->error('修改失败');
            }
        }
    }
    //设备信息删除
    public function del(){
        $id = I('get.id');
        $relation = $this->device_type_model->where(array('id'=>$id))->delete();
        if($relation){
            $this->success('删除成功',U('Devicetype/index'));
        } else {
            $this->error('删除失败');
        }
    }
    public function add(){
        $this->display();
    }
    //设备信息添加ajax
    public function add_post(){
        if(IS_POST){
            $data['device_type'] = trim($_POST['device_type']);
            $data['id'] = generateNum();
            $data['desc'] = trim($_POST['desc']);
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if($this->device_type_model->add($data)){
                $this->success('设备类型添加成功',U('Devicetype/index'));
            } else {
                $this->error('设备类型添加失败');
            }
        }
    }
}