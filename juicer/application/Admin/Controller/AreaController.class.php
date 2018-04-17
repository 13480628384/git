<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class AreaController extends AdminbaseController{

    protected $users_model,$role_model;

    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->users_model = D("Common/Area");
    }

    // 区域管理列表
    public function index(){
        $count=$this->users_model->count();
        $page = $this->page($count, 20);
        $Area = $this->users_model
            ->order("create_date DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("Area",$Area);
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
        $area_id = I('get.area_id');
        $area = M('area')->where(array("type"=>array('neq','4')))->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $area_id ? 'selected' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        $this->display();
    }
    public function add_post(){
        if(IS_POST){
            $area_id = trim($_POST['area_id']);
            $name = trim($_POST['name']);
            $sort = trim($_POST['sort']);
            $code = trim($_POST['code']);
            if(empty($name) || empty($code) || empty($sort)){
                $this->error("参数不能为空！");
            }
            $area = $this->users_model->where(array('id'=>$area_id))->find();
            if($area['type'] == '1'){
                $data['type'] = 2;
            }else{
                $data['type'] = 4;
            }
            $data['id'] = generateNum();
            $data['parent_id'] = $area_id;
            $data['parent_ids'] = $area['parent_ids'].$area['id'].',';
            $data['name'] = $name;
            $data['code'] = $code;
            $data['sort'] = $sort;
            $data['create_by'] = 1;
            $data['update_by'] = 1;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['create_date'] = date('Y-m-d H:i:s',time());
            if($this->users_model->add($data)){
                $this->success('添加成功',U('Area/index'));
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
     * 区域管理编辑 START
     * ==========================
     */
    public function edit(){
        //归属地区
        $tree = new \Off_Tree();
        $area_id = $_GET['id'];
        $parent_id = $_GET['parent_id'];
        $area = M('area')->where(array("type"=>array('neq','4')))->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $parent_id ? 'selected' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        $area = $this->users_model->where(array('id'=>$area_id))->find();
        $this->assign('area',$area);
        $this->display();
    }
    public function edit_post(){
        if(IS_POST){
            $area_id = trim($_POST['area_id']);
            $name = trim($_POST['name']);
            $id = trim($_POST['id']);
            $sort = trim($_POST['sort']);
            $code = trim($_POST['code']);
            if(empty($name) || empty($code) || empty($sort)){
                $this->error("参数不能为空！");
            }
            $area = $this->users_model->where(array('id'=>$area_id))->find();
            if($area['type'] == '1'){
                $data['type'] = 2;
            }else{
                $data['type'] = 4;
            }
            $data['parent_id'] = $area_id;
            $data['parent_ids'] = $area['parent_ids'].$area['id'].',';
            $data['name'] = $name;
            $data['code'] = $code;
            $data['sort'] = $sort;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if($this->users_model->where(array('id'=>$id))->save($data)){
                $this->success('修改成功',U('Area/index'));
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
        if ($this->users_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
}