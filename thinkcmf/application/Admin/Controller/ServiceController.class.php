<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class ServiceController extends AdminbaseController{

    protected $pay_weixin_config;
    public function _initialize() {
        parent::_initialize();
        $this->pay_weixin_config = D("Common/pay_weixin_config");
    }
    public function index(){
        /*$request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['ru.nickname']  = array('like', "%$keyword%");
            $keyword_complex['re.title']  = array('like', "%$keyword%");
            $keyword_complex['res.nickname']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }*/
        $where['pwc.del_flag']=0;
        $where['so.del_flag']=0;
        $where['su.del_flag']=0;
        $device=$this->pay_weixin_config
            ->alias('pwc')
            ->join("left join sys_office so on so.id=pwc.company_id")
            ->join("left join sys_user su on su.id=pwc.user_id")
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->pay_weixin_config
            ->alias('pwc')
            ->join("left join sys_office so on so.id=pwc.company_id")
            ->join("left join sys_user su on su.id=pwc.user_id")
            ->field('so.name,pwc.*,su.name as c_name')
            ->where($where)
            ->order("pwc.create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    public function del(){
        $id = trim($_GET['id']);
        $data['del_flag']=1;
        if($this->pay_weixin_config->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    public function add(){
        $office = M('sys_office')->where(array('del_flag'=>0,'type'=>'1'))->order('create_date desc')->select();
        $user = M('sys_user')->where(array('del_flag'=>0))->order('create_date desc')->select();
        $this->assign("office",$office);
        $this->assign("user",$user);
        $this->display();
    }
    public function add_post(){
        if(IS_POST){
            $data['company_id'] = trim($_POST['company_id']);
            $data['user_id'] = trim($_POST['user_id']);
            $data['config_name'] = trim($_POST['config_name']);
            $data['mchid'] = trim($_POST['mchid']);
            $data['pay_type'] = trim($_POST['pay_type']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = get_current_admin_id();
            $data['update_by'] = get_current_admin_id();
            $data['id'] = generateNum();
            if($this->pay_weixin_config->add($data)){
                $this->success('添加成功',U('Service/index'));
            } else {
                $this->error('添加失败');
            }
        }
    }
    public function edit(){
        $id = trim($_GET['id']);
        $info = $this->pay_weixin_config->where(array('del_flag'=>0,'id'=>$id))->find();
        $office = M('sys_office')->where(array('del_flag'=>0,'type'=>'1'))->order('create_date desc')->select();
        $user = M('sys_user')->where(array('del_flag'=>0))->order('create_date desc')->select();
        $this->assign("info",$info);
        $this->assign("office",$office);
        $this->assign("user",$user);
        $this->display();
    }
    public function edit_post(){
        if(IS_POST){
            $id = trim($_POST['id']);
            $data['company_id'] = trim($_POST['company_id']);
            $data['user_id'] = trim($_POST['user_id']);
            $data['config_name'] = trim($_POST['config_name']);
            $data['mchid'] = trim($_POST['mchid']);
            $data['pay_type'] = trim($_POST['pay_type']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['update_by'] = get_current_admin_id();
            if($this->pay_weixin_config->where(array('del_flag'=>0,'id'=>$id))->save($data)){
                $this->success('修改成功',U('Service/index'));
            } else {
                $this->error('修改失败');
            }
        }
    }
}