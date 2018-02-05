<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class RoseController extends AdminbaseController{

    protected $Rose_user_info;
    protected $rose_eco_business_recharge_record;
    public function _initialize() {
        parent::_initialize();
        $this->Rose_user_info = D("Common/Rose_user_info");
        $this->rose_eco_business_recharge_record = D("Common/Rose_eco_business_recharge_record");
    }
    public function index(){
        //超级用户管理权限
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['nickname']  = array('like', "%$keyword%");
            $keyword_complex['rose_id']  = array('like', "%$keyword%");
            $keyword_complex['email']  = array('like', "%$keyword%");
            $keyword_complex['phone']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['del_flag']=0;
        $device=$this->Rose_user_info
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->Rose_user_info
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //玫瑰用户删除
    public function del(){
        $id = trim($_GET['id']);
        $data['del_flag']=1;
        if($this->Rose_user_info->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    //玫瑰充值记录
    public function record(){
        $request=I('request.');
        if(!empty($request['type'])){
            $where['re.type']=$request['type'];
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['re.transaction_id']  = array('like', "%$keyword%");
            $keyword_complex['re.account']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $where['re.del_flag']=0;
        $device=$this->rose_eco_business_recharge_record
            ->alias('re')
            ->join('left join rose_user_info ru on ru.id=re.quotient_id')
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->rose_eco_business_recharge_record
            ->alias('re')
            ->join('left join rose_user_info ru on ru.id=re.quotient_id')
            ->field('re.id,re.type,re.account,re.pay_status,re.transaction_id,re.price,re.create_date,ru.nickname')
            ->where($where)
            ->order("re.pay_status desc,re.create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //玫瑰充值记录删除
    public function record_del(){
        $id = trim($_GET['id']);
        $data['del_flag']=1;
        if($this->rose_eco_business_recharge_record->where(array('del_flag'=>0,'id'=>$id))->save($data)){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}