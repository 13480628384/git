<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class WeixinBillController extends AdminbaseController{
    protected $Wx_bill;
    public function _initialize() {
        parent::_initialize();
        $this->Wx_bill = D("Common/Wx_bill");
    }
    // 微信对账列表
    public function index(){
        $request=I('request.');
        $start_time=I('request.start_time');
        if(!empty($start_time)){
            $where['tradetime']=array(
                array('EGT',$start_time)
            );
        }
        $end_time=I('request.end_time');
        if(!empty($end_time)){
            if(empty($where['tradetime'])){
                $where['tradetime']=array();
            }
            array_push($where['tradetime'], array('ELT',$end_time));
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['wxorder']  = array('like', "%$keyword%");
            $keyword_complex['bzorder']  = array('like',"%$keyword%");
            $keyword_complex['openid']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $device=$this->Wx_bill
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->Wx_bill
            ->where($where)
            ->order("tradetime DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //审核

}