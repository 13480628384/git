<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ComminfoController extends AdminbaseController{
    protected $command_info_model;
    protected $office_id;
    protected $weixin_enterprise_payment;
    public function _initialize() {
        parent::_initialize();
        $this->command_info_model = D("Common/Command_info");
    }
    // 后台指令列表
    public function index(){
        //超级管理员
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['deivce_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['del_flag'] = 0;
        $device=$this->command_info_model
           ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->command_info_model
            ->where($where)
            ->group('create_date')
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));
        $this->assign("page", $page->show('index'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //用户信息删除
    public function del(){
        if(get_current_admin_id() != 1){
            $this->error('普通管理员不允许执行删除操作');
        }
        $id = I('get.id');
        $relation = $this->command_info_model
            ->where(array('id'=>$id,'del_flag'=>0))
            ->save(array('del_flag'=>1));
        if($relation){
            $this->success('删除成功',U('Comminfo/index'));
        } else {
            $this->error('删除失败');
        }
    }

}