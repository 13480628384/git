<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DaoController extends AdminbaseController{

    protected $rose_give_yellow;
    public function _initialize() {
        parent::_initialize();
        $this->rose_give_yellow = D("Common/rose_give_yellow");
    }
    public function index(){
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['ru.nickname']  = array('like', "%$keyword%");
            $keyword_complex['re.title']  = array('like', "%$keyword%");
            $keyword_complex['res.nickname']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['rg.del_flag']=0;
        $where['re.del_flag']=0;
        $where['res.del_flag']=0;
        $device=$this->rose_give_yellow
            ->alias('rg')
            ->join("left join rose_user_info ru on ru.id=rg.user_id")
            ->join("left join rose_eco_advertising_info re on re.id=rg.reai_id")
            ->join("left join rose_user_info res on res.id=rg.quotient_id")
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->rose_give_yellow
            ->alias('rg')
            ->join("left join rose_user_info ru on ru.openid=rg.user_id")
            ->join("left join rose_eco_advertising_info re on re.id=rg.reai_id")
            ->join("left join rose_user_info res on res.id=rg.quotient_id")
            ->field('ru.nickname,re.title,res.nickname as quename,rg.total,rg.create_date,rg.id,rg.update_date')
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
        if($this->rose_give_yellow->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}