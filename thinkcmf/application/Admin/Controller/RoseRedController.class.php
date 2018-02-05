<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class RoseRedController extends AdminbaseController{

    protected $rose_gift_of_rose;
    public function _initialize() {
        parent::_initialize();
        $this->rose_gift_of_rose = D("Common/rose_gift_of_rose");
    }
    public function index(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['rg.content']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['rg.del_flag']=0;
        $where['ru.del_flag']=0;
        $where['rus.del_flag']=0;
        $device=$this->rose_gift_of_rose
            ->alias('rg')
            ->join("left join rose_user_info ru on ru.id=rg.quotient_id")
            ->join("left join rose_user_info rus on rus.id=rg.give_quotient_id")
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->rose_gift_of_rose
            ->alias('rg')
            ->join("left join rose_user_info ru on ru.id=rg.quotient_id")
            ->join("left join rose_user_info rus on rus.id=rg.give_quotient_id")
            ->field('ru.nickname,rus.nickname as givename,rg.total,rg.content,rg.create_date,rg.update_date,rg.id')
            ->where($where)
            ->order("rg.create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    public function del(){
        $id = trim($_GET['id']);
        $data['del_flag']=1;
        if($this->rose_gift_of_rose->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}