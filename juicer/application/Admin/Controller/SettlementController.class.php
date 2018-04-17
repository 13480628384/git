<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SettlementController extends AdminbaseController{

    protected $Device_consume_weixin_rec;
    protected $Device_consume_alipay_rec;

    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->Device_consume_weixin_rec = D("Common/Device_consume_weixin_rec");
        $this->Device_consume_alipay_rec = D("Common/Device_consume_alipay_rec");
    }

    // 微信收益管理列表
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
        if(!empty($request['area_id'])){
            $where['area_id']=$request['area_id'];
        }if(!empty($request['status'])){
            $where['status']=$request['status'];
        }if($request['status'] == '0'){
            $where['status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['openid']  = array('like', "%$keyword%");
            $keyword_complex['device_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }

        $where['del_flag'] = 0;
        $where['type'] = '0';
        $count=$this->Device_consume_weixin_rec
            ->where($where)->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $page = $this->page(count($count), 20);
        $list = $this->Device_consume_weixin_rec
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($list as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($list[$k]);
                }
            }
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    /*
     * ==========================
     * 支付宝收益管理列表
     * ==========================
     */
    public function alipay(){
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
        if(!empty($request['area_id'])){
            $where['area_id']=$request['area_id'];
        }if(!empty($request['status'])){
            $where['status']=$request['status'];
        }if($request['status'] == '0'){
            $where['status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['openid']  = array('like', "%$keyword%");
            $keyword_complex['device_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }

        $where['del_flag'] = 0;
        $where['type'] = '0';
        $count=$this->Device_consume_alipay_rec
            ->where($where)->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $page = $this->page(count($count), 20);
        $list = $this->Device_consume_alipay_rec
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($list as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($list[$k]);
                }
            }
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //微信收益报表
    public function weixin_get(){
        $today = date('Y-m-d 00:00:00');
        $count=$this->Device_consume_weixin_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1','dcw.create_date'=>array('egt', $today)))
            ->field('dcw.device_command,dcw.area_id,ja.name,sum(dcw.consume_account) as count,dcw.id,dcw.create_date')
            ->group('dcw.area_id')
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
    //支付宝收益报表
    public function alipay_get(){
        $today = date('Y-m-d 00:00:00');
        $count=$this->Device_consume_alipay_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1','dcw.create_date'=>array('egt', $today)))
            ->field('dcw.device_command,dcw.area_id,ja.name,sum(dcw.consume_account) as count,dcw.id,dcw.create_date')
            ->group('dcw.area_id')
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
    //支付宝今日收益设备列表
    public function alipay_deivce_list(){
        $area_id = trim($_GET['area_id']);
        $name = M('area')->where(array('id'=>$area_id))->getField('name');
        $list = $this->Device_consume_alipay_rec->
        where(array('type'=>'0','command_status'=>'2','status'=>'1','area_id'=>$area_id))->
        select();
        $this->assign('list',$list);
        $this->assign('name',$name);
        $this->display();
    }
    //今日收益设备列表
    public function deivce_list(){
        $area_id = trim($_GET['area_id']);
        $name = M('area')->where(array('id'=>$area_id))->getField('name');
        $list = $this->Device_consume_weixin_rec->
            where(array('type'=>'0','command_status'=>'2','status'=>'1','area_id'=>$area_id))->
            select();
        $this->assign('list',$list);
        $this->assign('name',$name);
        $this->display();
    }
    //支付宝设备月收益报表
    public function alipay_month(){
        $count=$this->Device_consume_alipay_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1'))
            ->field('sum(dcw.consume_account) as count,dcw.id,dcw.create_date,
            month(dcw.create_date) month,year(dcw.create_date) year')
            ->group("year(dcw.create_date),month(dcw.create_date)")
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
    //设备月收益报表
    public function weixin_month(){
        $count=$this->Device_consume_weixin_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1'))
            ->field('sum(dcw.consume_account) as count,dcw.id,dcw.create_date,
            month(dcw.create_date) month,year(dcw.create_date) year')
            ->group("year(dcw.create_date),month(dcw.create_date)")
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
    //设备每月的所以设备
    public function deivce_month(){
        $month = trim($_GET['month']);
        $count=$this->Device_consume_weixin_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1','month(dcw.create_date)'=>$month))
            ->field('dcw.device_command,ja.name,dcw.consume_account,dcw.id,dcw.create_date')
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
    //支付宝设备每月的所以设备
    public function alipay_deivce_month(){
        $month = trim($_GET['month']);
        $count=$this->Device_consume_alipay_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1','month(dcw.create_date)'=>$month))
            ->field('dcw.device_command,ja.name,dcw.consume_account,dcw.id,dcw.create_date')
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
}