<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SettlementController extends AdminbaseController{
    protected $device_consume_rec_model;
    protected $office_id;
    protected $weixin_enterprise_payment;
    public function _initialize() {
        parent::_initialize();
        $this->device_consume_rec_model = D("Common/Device_consume_rec");
        $this->weixin_enterprise_payment = D("Common/Weixin_enterprise_payment");
        $this->office_id = M('sys_user')
            ->where(array('del_flag'=>0,'id'=>get_current_admin_id()))->getField('office_id');
    }
    // 后台微信支付列表
    public function index(){
        if(get_current_admin_id() != 1){
            $request=I('request.');
            if(!empty($request['keyword'])){
                $keyword=$request['keyword'];
                $keyword_complex=array();
                $keyword_complex['dcr.from_username']  = array('like', "%$keyword%");
                $keyword_complex['dcr.deivce_command']  = array('like', "%$keyword%");
                $keyword_complex['dcr.create_by']  = array('like', "%$keyword%");
                $keyword_complex['_logic'] = 'or';
                $where['_complex'] = $keyword_complex;
            }

            $where['dcr.del_flag'] = 0;
            $where['di.office_id'] = $this->office_id;
            $device=$this->device_consume_rec_model
                ->query("SELECT count(1) count from (SELECT count(1) FROM device_consume_rec dcr
          LEFT JOIN device_info di on di.id=dcr.di_id WHERE dcr.del_flag = 0 and di.office_id = '$this->office_id'
           GROUP BY dcr.create_date) a");
            $page = $this->page($device[0]['count'],30);
            $list = $this->device_consume_rec_model
                ->alias('dcr')
                ->join("left join device_info di on dcr.di_id=di.id")
                ->field('dcr.type,sum(dcr.consume_account) as consume_account,dcr.consume_status,dcr.create_date,
            dcr.from_username,dcr.command_status,dcr.consume_status,dcr.is_close,dcr.transfer_status,dcr.deivce_command')
                ->where($where)
                ->group('dcr.create_date')
                ->order("dcr.create_date DESC")
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
        } else {
            //超级管理员
            $request=I('request.');
            if(!empty($request['keyword'])){
                $keyword=$request['keyword'];
                $keyword_complex=array();
                $keyword_complex['from_username']  = array('like', "%$keyword%");
                $keyword_complex['deivce_command']  = array('like', "%$keyword%");
                $keyword_complex['create_by']  = array('like', "%$keyword%");
                $keyword_complex['_logic'] = 'or';
                $where['_complex'] = $keyword_complex;
                $device=$this->device_consume_rec_model
                    ->query("SELECT count(1) count from (SELECT count(1) FROM 	`device_consume_rec`
            WHERE `del_flag` = 0 and from_username like '%$keyword%' or deivce_command like '%$keyword%' GROUP BY create_date) a");
            }else{
                $device=$this->device_consume_rec_model
                    ->query("SELECT count(1) count from (SELECT count(1) FROM 	`device_consume_rec`
            WHERE `del_flag` = 0 GROUP BY create_date) a");
            }
            if(!empty($request['type'])){
                $where['type']=$request['type'];
                $device=$this->device_consume_rec_model
                    ->query("SELECT count(1) count from (SELECT count(1) FROM 	`device_consume_rec`
            WHERE type='$request[type]' and `del_flag` = 0 GROUP BY create_date) a");
            }

            $where['del_flag'] = 0;
            $where['status'] = 1;
            $page = $this->page($device[0]['count'],30);
            $list = $this->device_consume_rec_model
                ->field('type,sum(consume_account) as consume_account,consume_status,create_date,
            from_username,command_status,consume_status,is_close,transfer_status,deivce_command')
                ->where($where)
                ->group('create_date')
                ->order("create_date DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            //消费类型
            $this->assign("page", $page->show('Admin'));
            $this->assign("list",$list);
            $this->assign("device",$device);
            $this->display();
        }
    }
    //用户信息删除
    public function del(){
        if(get_current_admin_id() != 1){
            $this->error('普通管理员不允许执行删除操作');
        }
        $id = I('get.id');
        $relation = $this->device_consume_rec_model
            ->where(array('id'=>$id,'del_flag'=>0))
            ->save(array('del_flag'=>1));
        if($relation){
            $this->success('删除成功',U('Settlement/index'));
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
        if(get_current_admin_id() != 1){
            $this->error('普通管理员不允许执行删除操作');
        }
        $id = I('get.id');
        $relation = $this->alipay_pay_rec_model
            ->where(array('id'=>$id,'del_flag'=>0))
            ->save(array('del_flag'=>1));
        if($relation){
            $this->success('删除成功',U('Settlement/alipay'));
        } else {
            $this->error('删除失败');
        }
    }

    //提现记录
    public function draw(){
        $request=I('request.');
        if(!empty($request['status'])){
            $where['status']=$request['status'];
        }if($request['status'] == '0'){
            $where['status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['payment_no']  = array('like', "%$keyword%");
            $keyword_complex['openid']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $device=$this->weixin_enterprise_payment
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->weixin_enterprise_payment
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        /*$page->SetPager('index','<div class="newpager">共有{recordcount} 个条&nbsp;&nbsp;
        当前第&nbsp;{pageindex}&nbsp;页&nbsp;/&nbsp;共&nbsp;{pagecount}&nbsp;
        页&nbsp;分页：&nbsp;{first}{prev}&nbsp;&nbsp;{list}&nbsp;&nbsp;{next}{last}&nbsp;&nbsp;
        转到&nbsp;{jump}&nbsp;页</div>',
            array("listlong"=>"6","first"=>"首页","prev"=>"上一页",
                "next"=>"下一页","list"=>"第*页","jump"=>"select"));*/
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    public function dr_del(){
        if(get_current_admin_id() != 1){
            $this->error('普通管理员不允许执行删除操作');
        }
        $id = I('get.id');
        $relation = $this->weixin_enterprise_payment
            ->where(array('id'=>$id))
            ->delete();
        if($relation){
            $this->success('删除成功',U('Settlement/draw'));
        } else {
            $this->error('删除失败');
        }
    }
}