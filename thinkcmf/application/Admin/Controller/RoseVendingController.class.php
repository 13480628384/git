<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class RoseVendingController extends AdminbaseController{

    protected $goods_rose;
    protected $goods_rose_consume_rec;
    protected $owner_id;
    public function _initialize() {
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'no'=>'售货机'))->select();
        } else {
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'id'=>get_current_admin_id(),'no'=>'售货机'))->select();
        }
        $this->assign('owner_id',$this->owner_id);
        $this->goods_rose = D("Common/goods_rose");
        $this->goods_rose_consume_rec = D("Common/goods_rose_consume_rec");
    }
    // 支付信息管理
    public function index(){
        //超级用户管理权限
        $request=I('request.');
        if(!empty($request['pay_status'])){
            $where['pay_status']=$request['pay_status'];
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['out_trade_no']  = array('like',"%$keyword%");
            $keyword_complex['transaction_id']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $device=$this->goods_rose
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->goods_rose
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //消费信息
    public function consume(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['deivce_code']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $device=$this->goods_rose_consume_rec
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->goods_rose_consume_rec
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
}