<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class MaterialController extends AdminbaseController{

    protected $Material;
    protected $Meter_descime;

    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->Material = D("Common/Material");
        $this->Meter_descime = D("Common/Meter_descime");
    }

    // 物料信息管理列表
    public function index(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['di.device_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $count=$this->Material
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->field('ma.area_id')
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
        $list = $this->Material
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->where($where)
            ->field('ma.*,di.device_command')
            ->order("ma.create_date DESC")
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
     * 物料消耗管理列表
     * ==========================
     */
    // 物料信息管理列表
    public function consume(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['di.device_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $count=$this->Meter_descime
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->field('ma.area_id')
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
        $list = $this->Meter_descime
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->where($where)
            ->field('ma.*,di.device_command')
            ->order("ma.create_date DESC")
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
     * 物料消耗总数列表
     * ==========================
     */
    public function sum_consume(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['di.device_command']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $cur_date = date('Y-m-d 00:00:00',time());
        $where['ma.create_date'] = array('egt',$cur_date);
        $count=$this->Meter_descime
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->join('left join ju_area as ja on ja.id=ma.area_id')
            ->field('ma.*,ja.name,count(1) as all_beishu, sum(ma.orange) as all_orange')
            ->where($where)
            ->group('ma.area_id')
            ->select();
        //p($count);die;
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
        $list = $this->Meter_descime
            ->alias('ma')
            ->join('left join ju_device_info_detail as di on di.id=ma.di_id')
            ->join('left join ju_area as ja on ja.id=ma.area_id')
            ->field('ma.*,ja.name,di.device_command,count(1) as all_beishu, sum(ma.orange) as all_orange')
            ->where($where)
            ->group('ma.area_id')
            ->limit($page->firstRow, $page->listRows)
            ->select();
        foreach($list as $k=>$v){
            $list[$k]['one_orange'] = round($v['all_orange']/$v['all_beishu'],1);
        }
        //p($list);die;
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