<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class PaymentController extends AdminbaseController{
    protected $weixin_pay_rec_model;
    protected $alipay_pay_rec_model;
    public function _initialize() {
        parent::_initialize();
        $this->weixin_pay_rec_model = D("Common/Weixin_pay_rec");
        $this->alipay_pay_rec_model = D("Common/Alipay_pay_rec");
    }

    // 后台微信支付列表
    public function index(){
        $request=I('request.');
        $start_time=I('request.start_time');
        if(!empty($start_time)){
            $where['create_date']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=I('request.end_time');
        if(!empty($end_time)){
            if(empty($where['create_date'])){
                $where['create_date']=array();
            }
            array_push($where['create_date'], array('ELT',$end_time));
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['from_username']  = array('like', "%$keyword%");
            $keyword_complex['out_trade_no']  = array('like', "%$keyword%");
            $keyword_complex['transaction_id']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['del_flag'] = 0;
        $where['status'] = 1;
        $device=$this->weixin_pay_rec_model
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->weixin_pay_rec_model
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","last"=>"尾页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));
        $this->assign("page", $page->show('index'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //用户信息删除
    public function del(){
        $id = I('get.id');
        $relation = $this->weixin_pay_rec_model
            ->where(array('id'=>$id,'del_flag'=>0))
            ->save(array('del_flag'=>1));
        if($relation){
            $this->success('删除成功',U('Payment/index'));
        } else {
            $this->error('删除失败');
        }
    }
    //支付宝会员信息
    public function alipay(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['buyer_id']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['del_flag'] = 0;
        $where['status'] = 1;
        $device=$this->alipay_pay_rec_model
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->alipay_pay_rec_model
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","last"=>"尾页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));
        $this->assign("page", $page->show('index'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //删除支付宝用户信息
    public function ali_del(){
        $id = I('get.id');
        $relation = $this->alipay_pay_rec_model
            ->where(array('id'=>$id,'del_flag'=>0))
            ->save(array('del_flag'=>1));
        if($relation){
            $this->success('删除成功',U('Payment/alipay'));
        } else {
            $this->error('删除失败');
        }
    }
}