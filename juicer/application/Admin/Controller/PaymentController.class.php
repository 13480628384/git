<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class PaymentController extends AdminbaseController{

    protected $Device_consume_weixin_rec;
    protected $Device_consume_alipay_rec;

    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->Device_consume_weixin_rec = D("Common/Device_consume_weixin_rec");
        $this->Device_consume_alipay_rec = D("Common/Device_consume_alipay_rec");
    }

    // 微信管理列表
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
            $keyword_complex['out_trade_no']  = array('like', "%$keyword%");
            $keyword_complex['transaction_id']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }

        $where['del_flag'] = 0;
        $where['type'] = '1';
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
     * 微信支付管理删除
     * ==========================
     */
    public function delete(){
        $id = $_GET['id'];
        if(empty($id)){
            exit;
        }
        $data['del_flag'] = 1;
        if ($this->Device_consume_weixin_rec->where(array('id'=>$id,'del_flag'=>0))->save($data)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    /*
     * ==========================
     * 支付宝支付管理列表
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
            $keyword_complex['buyer_id']  = array('like', "%$keyword%");
            $keyword_complex['out_trade_no']  = array('like', "%$keyword%");
            $keyword_complex['transaction_id']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }

        $where['del_flag'] = 0;
        $where['type'] = '1';
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
}