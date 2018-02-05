<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class GroupDetailController extends AdminbaseController{

    protected $device_relation_group_model;
    protected $owner_id;
    protected $company_id;
    protected $company_ids;
    protected $office_id;
    protected $area;
    public function _initialize() {
        parent::_initialize();
        if(get_current_admin_id() != 1){
            //普通管理员
            $office_id = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('office_id');
            $this->office_id = $office_id;
        }
        $this->device_relation_group_model = D("Common/Device_relation_group");
    }

    // 后台群组详情信息列表
    public function index(){
        $request=I('request.');
        if(!empty($request['online_status'])){
            $where['di.online_status']=$request['online_status'];
        }if($request['online_status'] == '0'){
            $where['di.online_status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['dgi.group_name']  = array('like', "%$keyword%");
            $keyword_complex['dd.scan_code']  = array('like',"%$keyword%");
            $keyword_complex['di.device_command']  = array('like',"%$keyword%");
            $keyword_complex['di.device_code']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        //显示归属用户
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $where['di.del_flag'] = 0;
            $where['dgi.del_flag'] = 0;
            $where['dd.del_flag'] = 0;
            $device=$this->device_relation_group_model
                ->alias('di')
                ->join('deivce_group_info dgi on dgi.id=di.dgi_id')
                ->join('device_info dd on dd.id=di.di_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_relation_group_model
                ->alias('di')
                ->join('deivce_group_info dgi on dgi.id=di.dgi_id')
                ->join('device_info dd on dd.id=di.di_id')
                ->join('device_type dt on dt.device_type=di.device_type')
                ->where($where)
                ->field('di.device_command,di.device_code,dd.scan_code,di.device_type,dt.desc,
                di.id,dgi.group_name,di.group_word,di.ords,di.online_status,di.pay_price,di.update_date,dgi.id as dgi_id')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            $user = M('sys_user')->where(array('del_flag'=>0))->order('create_date desc')->select();
            //echo $this->device_relation_group_model->getlastsql();die;
        } else {//普通管理员
            $where['di.del_flag'] = 0;
            $where['dgi.del_flag'] = 0;
            $where['dd.del_flag'] = 0;
            $where['di.create_by'] = $this->office_id;
            $device=$this->device_relation_group_model
                ->alias('di')
                ->join('deivce_group_info dgi on dgi.id=di.dgi_id')
                ->join('device_info dd on dd.id=di.di_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->device_relation_group_model
                ->alias('di')
                ->join('deivce_group_info dgi on dgi.id=di.dgi_id')
                ->join('device_info dd on dd.id=di.di_id')
                ->join('device_type dt on dt.device_type=di.device_type')
                ->where($where)
                ->field('di.device_command,di.device_code,dd.scan_code,di.id,dgi.group_name,dt.desc,
                di.group_word,di.ords,di.online_status,di.pay_price,di.update_date,di.device_type,dgi.id as dgi_id')
                ->order("di.create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            $company_ids = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->getField('company_id');
            $user = M('sys_user')->where(array('del_flag'=>0,'company_id'=>$company_ids))->select();
        }
        $this->assign('user',$user);
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //转移设备
    public function change(){
        if(isset($_POST['ids']) && $_GET['chan'] == '1'){
            $ids = I('post.ids/a');
            $user_id = $_POST['user_id'];
            if(empty($user_id)){
                $this->error("请选择转移用户！");exit;
            }
            $user = M('sys_user')->where(array('id'=>$user_id,'del_flag'=>0))->find();
            if(empty($user)){
                $this->error("请先添加机构，部门，用户后再操作！");exit;
            }
            if(M('deivce_group_info')->where(array('owner_id'=>$user_id,'del_flag'=>0))->find()){
                $dgi_id = M('deivce_group_info')->where(array('owner_id'=>$user_id,'del_flag'=>0))->getField('id');
            } else {
                $new['id'] = generateNum();
                $new['owner_id'] = $user['id'];
                $new['office_id'] = $user['office_id'];
                $new['group_name'] = $user['name'].'群组';
                $new['ords'] = rand(1,50);
                $new['create_by'] = $user['id'];
                $new['create_date'] = date('Y-m-d H:i:s',time());
                $new['update_by'] = $user['id'];
                $res = M('deivce_group_info')->add($new);
            }
            $dgi_id = M('deivce_group_info')->where(array('owner_id'=>$user_id,'del_flag'=>0))->getField('id');
            $model = M('device_info');
            $model->startTrans();
            $data['owner_id'] = $user['id'];
            $data['office_id'] = $user['office_id'];
            $data['company_id'] = $user['company_id'];
            $data['create_by'] = $user['id'];
            $result = $model->where(array('device_command'=>array('in',$ids)))->save($data);
            $data1['create_by'] = $user['office_id'];
            $data1['update_by'] = $user['office_id'];
            $data1['dgi_id'] = $dgi_id;
            $result3 = M('device_record')->where(array('dev_id'=>array('in',$ids)))->delete();
            $result2 = M('device_relation_group')->where(array('device_command'=>array('in',$ids)))->save($data1);
            if($result && $result2){
                $model->commit();
                $this->success("转移成功！");
            } else {
                $this->error('转移失败');
            }
        }
    }
    //设备信息修改
    public function edit(){
        $id = I('get.id');
        $dgi_id = I('get.dgi_id');
        $device = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->find();
        if(get_current_admin_id() == '1'){
            $deivce_group_info = M('deivce_group_info')
                ->where(array('del_flag'=>0))->select();
        } else {
            $deivce_group_info = M('deivce_group_info')
                ->where(array('del_flag'=>0,'office_id'=>$this->owner_id))->select();
        }
        $this->assign('device',$device);
        $this->assign('dgi_id',$dgi_id);
        $this->assign('deivce_group_info',$deivce_group_info);
        $this->display();
    }
    //设备信息修改ajax
    public function edit_post(){
        if(IS_POST){
            if($_POST['device_type'] == 5 || $_POST['device_type'] == 3
                || $_POST['device_type'] == 2 || $_POST['device_type'] == 6
                || $_POST['device_type'] == 7 || $_POST['device_type'] == 9){
                $data['charger'] = trim($_POST['charger']);
            } elseif($_POST['device_type'] == 4){
                $data['ANM'] = trim($_POST['ANM']);
            }
            $data['group_word'] = trim($_POST['group_word']);
            $data['dgi_id'] = trim($_POST['deivce_group_info']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['online_status'] = trim($_POST['online_status']);
            $data['ords'] = trim($_POST['ords']);
            $data['device_type'] = trim($_POST['device_type_update']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $id = trim($_POST['id']);
            $this->device_relation_group_model->startTrans();
            $res = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->save($data);
            $info['device_code'] = trim($_POST['device_code']);
            $info['device_command'] = trim($_POST['device_command']);
            $info['pay_price'] = trim($_POST['pay_price']);
            $info['device_type'] = trim($_POST['device_type']);
            $info['update_date'] = date('Y-m-d H:i:s',time());
            $result = M('device_info')
                ->where(array('device_command'=>trim($_POST['di_command']),'del_flag'=>0))
                ->save($info);
            if($result && $res){
                $this->device_relation_group_model->commit();
                $this->success('更新成功',U('GroupDetail/index'));
            } else {
                $this->device_relation_group_model->rollback();
                $this->error('更新失败');
            }
        }
    }
    //设备详情信息删除
    public function del(){
        $id = I('get.id');
        $this->device_relation_group_model->startTrans();
        $di_id = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->getField('di_id');
        $relation = M('device_info')->where(array('id'=>$di_id,'del_flag'=>0))->delete();
        $device = $this->device_relation_group_model->where(array('id'=>$id,'del_flag'=>0))->delete();
        if($device && $relation){
            $this->device_relation_group_model->commit();
            $this->success('删除成功',U('GroupDetail/index'));
        } else {
            $this->device_relation_group_model->rollback();
            $this->error('删除失败');
        }
    }

}