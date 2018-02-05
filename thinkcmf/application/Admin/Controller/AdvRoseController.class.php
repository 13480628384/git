<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class AdvRoseController extends AdminbaseController{

    protected $rose_eco_advertising_info;
    public function _initialize() {
        parent::_initialize();
        $this->rose_eco_advertising_info = D("Common/rose_eco_advertising_info");
    }
    public function index(){
        $request=I('request.');
        if(!empty($request['audit_status']) ||  $request['audit_status'] == '0'){
            $where['re.audit_status']=$request['audit_status'];
        }
        if(!empty($request['online']) || $request['online'] == '0'){
            $where['re.online']=$request['online'];
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['ru.nickname']  = array('like', "%$keyword%");
            $keyword_complex['re.title']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['re.del_flag']=0;
        $where['ru.del_flag']=0;
        $device=$this->rose_eco_advertising_info
            ->alias('re')
            ->join("left join rose_user_info ru on ru.id=re.quotient_id")
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->rose_eco_advertising_info
            ->alias('re')
            ->join("left join rose_user_info ru on ru.id=re.quotient_id")
            ->field('ru.nickname,re.title,re.image,re.audit_status,re.no_by_desc,
            re.online,re.show_number,re.click_number,re.consume_number,re.create_date,re.one_number,re.id')
            ->where($where)
            ->order("re.create_date DESC,re.update_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    public function del(){
        $id = trim($_GET['id']);
        $data['del_flag']=1;
        if($this->rose_eco_advertising_info->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    //审核广告
    public function check(){
        //通过审核
        if(isset($_POST['ids']) && $_GET["check"]){
            $ids = I('post.ids/a');
            if ( $this->rose_eco_advertising_info
                    ->where(array('id'=>array('in',$ids)))->save(array('audit_status'=>1)) !== false ) {
                $this->success("审核成功！");
            } else {
                $this->error("审核失败！");
            }
        }
        //不通过审核
        if(isset($_POST['ids']) && $_GET["uncheck"]){
            $ids = I('post.ids/a');
            if ( $this->rose_eco_advertising_info
                    ->where(array('id'=>array('in',$ids)))->save(array('audit_status'=>0)) !== false) {
                $this->success("取消审核成功！");
            } else {
                $this->error("取消审核失败！");
            }
        }
    }
    //上线
    public function online(){
        //通过审核
        if(isset($_POST['ids']) && $_GET["line"]){
            $ids = I('post.ids/a');
            if ( $this->rose_eco_advertising_info
                    ->where(array('id'=>array('in',$ids)))->save(array('online'=>1)) !== false ) {
                $this->success("上线成功！");
            } else {
                $this->error("上线失败！");
            }
        }
        //不通过审核
        if(isset($_POST['ids']) && $_GET["unline"]){
            $ids = I('post.ids/a');
            if ( $this->rose_eco_advertising_info
                    ->where(array('id'=>array('in',$ids)))->save(array('online'=>0)) !== false) {
                $this->success("下线成功！");
            } else {
                $this->error("下线失败！");
            }
        }
    }
    //修改广告
    public function edit(){
        $id = I('id');
        $va = $this->rose_eco_advertising_info->where(array('del_flag'=>0,'id'=>$id))->find();
        $this->assign('v',$va);
        $this->display();
    }
    //修改广告提交
    public function edit_post(){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $audit_status = $_POST['audit_status'];
        $no_by_desc = $_POST['no_by_desc'];
        $online = $_POST['online'];
        $consume_number = $_POST['consume_number'];
        $one_number = $_POST['one_number'];
        $data['title'] = $title;
        $data['audit_status'] = $audit_status;
        $data['no_by_desc'] = $no_by_desc;
        $data['online'] = $online;
        $data['consume_number'] = $consume_number;
        $data['one_number'] = $one_number;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $this->rose_eco_advertising_info->where(array('del_flag'=>0,'id'=>$id))->save($data);
        if($uid){
            $this->success('修改成功',U('AdvRose/index'));
        } else {
            $this->error('修改失败');
        }
    }
}