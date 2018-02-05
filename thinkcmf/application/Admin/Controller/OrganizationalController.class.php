<?php
/**
 * Menu(机构管理)
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class OrganizationalController extends AdminbaseController {

    protected $sys_office_model;
    protected $auth_rule_model;
    protected $company;
    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->sys_office_model = D("sys_office");
        $this->auth_rule_model = D("Common/AuthRule");
        $shang = $this->sys_office_model->where(array('id'=>get_current_office_id()))->find();
        $this->company = $this->sys_office_model->where(array('id'=>$shang['parent_id']))->getField('id');
        $this->assign("admin_id", get_current_admin_id());
        $this->assign("company_id", $this->company);
    }

    // 后台菜单列表
    public function index() {
        session('admin_organizational_index','Organizational/index');
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $result = $this->sys_office_model
                ->join("left join sys_area sa on sa.id=sys_office.area_id")
                ->where(array('sys_office.del_flag'=>0))
                ->field('sys_office.area_id,sys_office.parent_id,sys_office.name,sys_office.id,sys_office.code,sa.name as area_name,sys_office.type')
                ->order(array("sys_office.parent_id" => "asc"))
                ->select();
            foreach($result as $k =>$v) {
                if($v['type'] == 1){
                    $result[$k]['type'] = '公司';
                }elseif($result[$k]['type'] == 2){
                    $result[$k]['type'] = '部门';
                }elseif($result[$k]['type'] == 3){
                    $result[$k]['type'] = '小组';
                }else{
                    $result[$k]['type'] = '其他';
                }
            }
            $results = $this->toLevel($result,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
            $this->assign("category_list", $results);
        } else {
            $where['sys_office.parent_id'] = $this->company;
            $where['_logic'] = 'or';
            $where['sys_office.id'] = $this->company;
            $where['_logic'] = 'or';
            $where['sys_office.parent_id'] = 0;
            $map['_complex'] = $where;
            $map['sys_office.del_flag'] = array('eq',0);
            $result = $this->sys_office_model
                ->join("left join sys_area sa on sa.id=sys_office.area_id")
                ->where(array('sys_office.del_flag'=>0))
                ->field('sys_office.parent_id,sys_office.parent_ids,sys_office.area_id,sys_office.name,
                sys_office.id,sys_office.code,sa.name as area_name,sys_office.type')
                ->order(array("sys_office.parent_id" => "asc"))
                ->select();
            //echo  $this->sys_office_model->getlastsql();die;
            foreach($result as $k =>$v) {
                if($v['type'] == 1){
                    $result[$k]['type'] = '公司';
                }elseif($result[$k]['type'] == 2){
                    $result[$k]['type'] = '部门';
                }elseif($result[$k]['type'] == 3){
                    $result[$k]['type'] = '小组';
                }else{
                    $result[$k]['type'] = '其他';
                }
            }
            $results = $this->toLevel($result,'&nbsp;&nbsp;&nbsp;&nbsp;',0);
            foreach($results as $k =>$v){
                $array = explode(',',$v['parent_ids']);
                if($v['parent_id'] != $this->company && !in_array($this->company,$array)
                    && $v['id']!=$this->company){
                    unset($results[$k]);
                }
            }
            //p($results);die;
            $this->assign("company_id", $this->company);
            $this->assign("category_list", $results);
        }
        $this->display();
    }
    public function toLevel($cate, $delimiter = '———', $parent_id = 0, $level = 0) {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level + 1;
                $v['delimiter'] = str_repeat($delimiter, $level);
                $arr[] = $v;
                $arr = array_merge($arr, $this->toLevel($cate, $delimiter, $v['id'], $v['level']));
            }
        }
        return $arr;
    }
    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    protected function _get_level($id, $array = array(), $i = 0) {

        if ($array[$id]['parentid']==0 || empty($array[$array[$id]['parentid']]) || $array[$id]['parentid']==$id){
            return  $i;
        }else{
            $i++;
            return $this->_get_level($array[$id]['parentid'],$array,$i);
        }
    }
    // 后台菜单添加
    public function add() {
        $tree = new \Off_Tree();
        $parentid = 0;
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            //上级机构
            $result = $this->sys_office_model->order(array("sort" => "ASC"))->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            //主副责任人
            $name = M('sys_user')->where(array('del_flag'=>0))->select();
            $this->assign('name',$name);
        } else {
            $name = M('sys_user')->where(array('del_flag'=>0,'office_id'=>get_current_office_id()))->select();
            $this->assign('name',$name);
            $shang = $this->sys_office_model->where(array('id'=>get_current_office_id()))->find();
            $select_categorys = $this->sys_office_model->where(array('id'=>$shang['parent_id']))->find();
            $this->assign("select_categorys", $select_categorys);
        }
        //归属地区
        $area = M('sys_area')->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        $this->assign("admin_id",get_current_admin_id());

        $this->display();
    }

    // 后台菜单添加提交
    public function add_post() {
        if (IS_POST) {
            //找出上级机构
            $office = M('sys_office')->where(array('id'=>$_POST['parent_id']))->find();
            $data['parent_ids'] = $office['parent_ids'].$office['id'].',';
            $data['id'] = generateNum();
            $data['parent_id'] = $_POST['parent_id'];
            $data['area_id'] = $_POST['area_id'];
            $data['name'] = $_POST['name'];
            $data['code'] = $_POST['code'];
            $data['type'] = $_POST['type'];
            $data['grade'] = $_POST['grade'];
            $data['USEABLE'] = $_POST['USEABLE'];
            $data['PRIMARY_PERSON'] = $_POST['PRIMARY_PERSON'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['address'] = $_POST['address'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['zip_code'] = $_POST['zip_code'];
            $data['master'] = $_POST['master'];
            $data['phone'] = $_POST['phone'];
            $data['fax'] = $_POST['fax'];
            $data['email'] = $_POST['email'];
            $data['remarks'] = $_POST['remarks'];
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = get_current_admin_id();
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('sys_office')->add($data)){
                $this->success('添加成功',U('Organizational/index'));
            } else {
                $this->error('添加失败');
            }
        }
    }
    //修改机构
    public function org_edit(){
        $parentid = I('get.parent_id');
        $tree = new \Off_Tree();
        //上级机构
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            //上级机构
            $result = $this->sys_office_model->order(array("sort" => "ASC"))->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            //主副责任人
            $name = M('sys_user')->where(array('del_flag'=>0))->select();
            $this->assign('name',$name);
        } else {
            //主副责任人
            $name = M('sys_user')->where(array('del_flag'=>0,'office_id'=>get_current_office_id()))->select();
            $this->assign('name',$name);
            $shang = $this->sys_office_model->where(array('id'=>get_current_office_id()))->find();
            $select_categorys = $this->sys_office_model->where(array('id'=>$shang['parent_id']))->find();
            $this->assign("select_categorys", $select_categorys);
        }
       /* $result = $this->sys_office_model->order(array("sort" => "ASC"))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("select_categorys", $select_categorys);*/
        //归属地区
        $area_id = I('get.area_id');
        $area = M('sys_area')->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $area_id ? 'selected' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        //机构信息
        $id = I('get.id');
        $org = $this->sys_office_model->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign('org',$org);
        $this->display();
    }
    //机构管理编辑
    public function edit_post(){
        if (IS_POST) {
            //找出上级机构
            if($_POST['parent_id'] == $_POST['id']){
                $this->error('上级机构不能是本身机构');
            }
            $office = M('sys_office')->where(array('id'=>$_POST['parent_id']))->find();
            $data['parent_ids'] = $office['parent_ids'].$office['id'].',';
            $data['parent_id'] = $_POST['parent_id'];
            $data['area_id'] = $_POST['area_id'];
            $data['name'] = $_POST['name'];
            $data['code'] = $_POST['code'];
            $data['type'] = $_POST['type'];
            $data['grade'] = $_POST['grade'];
            $data['USEABLE'] = $_POST['USEABLE'];
            $data['PRIMARY_PERSON'] = $_POST['PRIMARY_PERSON'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['address'] = $_POST['address'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['zip_code'] = $_POST['zip_code'];
            $data['master'] = $_POST['master'];
            $data['phone'] = $_POST['phone'];
            $data['fax'] = $_POST['fax'];
            $data['email'] = $_POST['email'];
            $data['remarks'] = $_POST['remarks'];
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = get_current_admin_id();
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('sys_office')->where(array('id'=>trim($_POST['id'])))->save($data)){
                $this->success('修改成功',U('Organizational/index'));
            } else {
                $this->error('修改失败');
            }
        }
    }
    // 后台机构删除
    public function org_del() {
        $id = I("get.id");
        $count = $this->sys_office_model->where(array("parent_id" => $id))->count();
        if ($count > 0) {
            $this->error("该机构下还有子机构，无法删除！");
        }
        if ($this->sys_office_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    //添加下级机构
    public function org_next_add(){
        $tree = new \Off_Tree();
        $parentid = I('get.id');
        //上级机构
        /*$result = $this->sys_office_model->order(array("sort" => "ASC"))->select();
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign("select_categorys", $select_categorys);*/
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            //上级机构
            $result = $this->sys_office_model->order(array("sort" => "ASC"))->select();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            //主副责任人
            $name = M('sys_user')->where(array('del_flag'=>0))->select();
            $this->assign('name',$name);
        } else {
            //主副责任人
            $name = M('sys_user')->where(array('del_flag'=>0,'office_id'=>get_current_office_id()))->select();
            $this->assign('name',$name);
            $shang = $this->sys_office_model->where(array('id'=>get_current_office_id()))->find();
            $select_categorys = $this->sys_office_model->where(array('id'=>$shang['parent_id']))->find();
            $this->assign("select_categorys", $select_categorys);
        }
        //归属地区
        $area = M('sys_area')->order(array("sort" => "ASC"))->select();
        foreach ($area as $r) {
            $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
            $array1[] = $r;
        }
        $str1 = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $tree->init($array1);
        $sys_area = $tree->get_tree(0, $str1);
        $this->assign('sys_area',$sys_area);
        $this->display();
    }
    //添加下级机构ajax
    public function next_add_post() {
        if (IS_POST) {
            //找出上级机构
            $office = M('sys_office')->where(array('id'=>$_POST['parent_id']))->find();
            $data['parent_ids'] = $office['parent_ids'].$office['id'].',';
            $data['id'] = generateNum();
            $data['parent_id'] = $_POST['parent_id'];
            $data['area_id'] = $_POST['area_id'];
            $data['name'] = $_POST['name'];
            $data['code'] = $_POST['code'];
            $data['type'] = $_POST['type'];
            $data['grade'] = $_POST['grade'];
            $data['USEABLE'] = $_POST['USEABLE'];
            $data['PRIMARY_PERSON'] = $_POST['PRIMARY_PERSON'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['address'] = $_POST['address'];
            $data['DEPUTY_PERSON'] = $_POST['DEPUTY_PERSON'];
            $data['zip_code'] = $_POST['zip_code'];
            $data['master'] = $_POST['master'];
            $data['phone'] = $_POST['phone'];
            $data['fax'] = $_POST['fax'];
            $data['email'] = $_POST['email'];
            $data['remarks'] = $_POST['remarks'];
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = get_current_admin_id();
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('sys_office')->add($data)){
                $this->success('添加成功',U('Organizational/index'));
            } else {
                $this->error('添加失败');
            }
        }
    }
}
