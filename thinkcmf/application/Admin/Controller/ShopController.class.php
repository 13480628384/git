<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ShopController extends AdminbaseController{

    protected $Goods_shop;
    protected $owner_id;
    public function _initialize() {
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'no'=>'售货机'))->select();
        } else {
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'id'=>get_current_admin_id(),'no'=>'售货机'))->select();
        }
        $this->assign('owner_id',$this->owner_id);
        $this->Goods_shop = D("Common/Goods_shop");
    }
    // 后台设备信息列表
    public function index(){
        //超级用户管理权限
        $request=I('request.');
        if(!empty($request['status'])){
            $where['gv.status']=$request['status'];
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['gv.name']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $device=$this->Goods_shop
                ->alias('gv')
                ->join('sys_user su on su.id=gv.owner_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->Goods_shop
                ->alias('gv')
                ->join('sys_user su on su.id=gv.owner_id')
                ->where($where)
                ->field('gv.id,gv.ords,gv.name as names,gv.image,
                gv.status,gv.create_date,su.name')
                ->order("gv.ords DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        } else {
            $where['gv.owner_id'] = get_current_admin_id();
            $device=$this->Goods_shop
                ->alias('gv')
                ->join('sys_user su on su.id=gv.owner_id')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->Goods_shop
                ->alias('gv')
                ->join('sys_user su on su.id=gv.owner_id')
                ->where($where)
                ->field('gv.id,gv.ords,gv.name as names,gv.image,
                gv.status,gv.create_date,su.name')
                ->order("gv.ords DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }

    //添加售货机
    public function add(){
        $this->display();
    }
    //添加售货机提交
    public function add_post(){
        if(IS_POST){
            if(empty($_POST['name']) || empty($_POST['ords'])){
                $this->error('请检查数据是否为空！');
            }
            if($this->Goods_shop->where(array('name'=>$_POST['name']))->find()){
                $this->error('商品名称'.$_POST['name'].'已存在');
            }
            if(!is_numeric($_POST['ords'])){
                $this->error('请输入排序为数字');
            }
            if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
                sp_asset_relative_url($_POST['photos_url']);
            }
            $data['name'] = trim($_POST['name']);
            $data['image'] = 'http://'.$_SERVER['HTTP_HOST'].C("TMPL_PARSE_STRING.__UPLOAD__").$_POST['photos_url'];
            $data['ords'] = trim($_POST['ords']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['id'] = generateNum();
            $data['status'] = trim($_POST['status']);
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $result = $this->Goods_shop->add($data);
            if($result){
                $this->success('添加成功',U('Shop/index'));
            } else {
                $this->error('添加失败，请检查数据是否正确');
            }
        }
    }

    //修改售货机
    public function edit(){
        $id = trim($_GET['id']);
        $res = $this->Goods_shop->where(array('id'=>$id))->find();
        $this->assign('res',$res);
        $this->display();
    }
    //修改商品
    public function edit_post(){
        if(IS_POST){
            if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
                sp_asset_relative_url($_POST['photos_url']);
                $img = $this->Goods_shop->where(array('id'=>trim($_POST['id'])))->find();
                $file = str_replace('/thinkcmf','.',$img['image']);
                unlink($file);
                $data['owner_id'] = trim($_POST['owner_id']);
                $data['name'] = trim($_POST['name']);
                $data['ords'] = trim($_POST['ords']);
                $data['image'] = C("TMPL_PARSE_STRING.__UPLOAD__").$_POST['photos_url'];
                $data['remarks'] = trim($_POST['remarks']);
                $data['status'] = trim($_POST['status']);
                $data['update_date'] = date('Y-m-d H:i:s',time());
                if($this->Goods_shop->where(array('id'=>trim($_POST['id'])))->save($data)){
                    $this->success('修改成功',U('Shop/index'));
                } else {
                    $this->error('修改失败');
                }
            } else {
                $data['owner_id'] = trim($_POST['owner_id']);
                $data['name'] = trim($_POST['name']);
                $data['ords'] = trim($_POST['ords']);
                $data['remarks'] = trim($_POST['remarks']);
                $data['status'] = trim($_POST['status']);
                $data['update_date'] = date('Y-m-d H:i:s',time());
                if($this->Goods_shop->where(array('id'=>trim($_POST['id'])))->save($data)){
                    $this->success('修改成功',U('Shop/index'));
                } else {
                    $this->error('修改失败');
                }
            }
        }
    }
    //删除设备
    public function del(){
        $id = trim($_GET['id']);
        $res = $this->Goods_shop->where(array('id'=>$id))->delete();
        if($res){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    //问题反馈
    public function feedback(){
        $device=M('goods_view')
            ->count();
        $page = $this->page($device,30);
        $list = M('goods_view')
            ->order("create_date DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //删除就数据
    public function del_images(){
        $id = trim($_GET['id']);
        $result = M('goods_view')->where(array('id'=>$id))->find();
        $img = explode(',',$result['images']);
        //删除图片
        if(!empty($img[0])){
            $imgs = str_replace('http://wxpay.roseo2o.com/../thinkcmf','.',$img[0]);
            unlink($imgs);
        }
        if(!empty($img[1])){
            $imgs1 = str_replace('http://wxpay.roseo2o.com/../thinkcmf','.',$img[1]);
            unlink($imgs1);
        }
        $res = M('goods_view')->where(array('id'=>$id))->delete();
        if($res){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}