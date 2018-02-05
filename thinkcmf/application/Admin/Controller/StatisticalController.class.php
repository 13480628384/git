<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class StatisticalController extends AdminbaseController{

    protected $Goods_vending;
    protected $owner_id;
    public function _initialize() {
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'no'=>'售货机'))->select();
        } else {
            $this->owner_id = M('sys_user')->where(array('del_flag'=>0,'id'=>get_current_admin_id(),'no'=>'售货机'))->select();
        }
        $this->assign('owner_id',$this->owner_id);
        $this->Goods_vending = D("Common/Goods_vending");
    }
    // 营收排行榜
    public function index(){
        //超级用户管理权限
        $where['gv.del_flag'] = 0;
        $where['gc.del_flag'] = 0;
        //超级管理员
        if(get_current_admin_id() == 1 || $this->role_type == 2){
            $device=$this->Goods_vending
                ->alias('gv')
                ->join('goods_consume_rec gc on gc.deivce_code=gv.device_code')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $where['gc.command_status'] = '2';
            $where['gc.is_close'] = '0';
            $list = $this->Goods_vending
                ->alias('gv')
                ->join('goods_consume_rec gc on gc.deivce_code=gv.device_code')
                ->where($where)
                ->field('gv.address,gv.device_code,gv.device_command,sum(gc.consume_account) counts')
                ->order("gv.create_date DESC")
                ->group('gc.deivce_code')
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        } else {
            $where['gv.owner_id'] = get_current_admin_id();
            $where['gc.owner_id'] = get_current_admin_id();
            $device=$this->Goods_vending
                ->alias('gv')
                ->join('goods_consume_rec gc on gc.deivce_code=gv.device_code')
                ->where($where)
                ->count();
            $page = $this->page($device,30);
            $list = $this->Goods_vending
                ->alias('gv')
                ->join('goods_consume_rec gc on gc.deivce_code=gv.device_code')
                ->where($where)
                ->field('gv.address,gv.device_code,gv.device_command,sum(gc.consume_account) counts')
                ->order("gv.create_date DESC")
                ->group('gc.deivce_code')
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->assign("device",$device);
        $this->display();
    }
    //转移设备
    public function change(){
        if(isset($_POST['ids']) && $_GET['chan'] == '1'){
            $ids = I('post.ids/a');
            $user_id = $_POST['user_id'];
            if(empty($user_id)){
                $this->error("请选择转移用户！");exit;
            }
            $user = M('sys_user')->where(array('id'=>$user_id,'del_flag'=>0))->find();
            if(empty($user)){
                $this->error("用户不存在,请检查是否有该用户");exit;
            }
            $model =  M('goods_vending');
            $model->startTrans();
            //商品属性更改
            /*$shop['owner_id'] = $user_id;
            $shop['update_date'] = date('Y-m-d H:i:s',time());
            $res_shop = M('goods_shop')->where(array('owner_id'=>array('in',$ids)))->save($shop);*/
            //设备属性更改
            $device['owner_id'] = $user_id;
            $device['update_date'] = date('Y-m-d H:i:s',time());
            $res_device = M('goods_vending')->where(array('device_command'=>array('in',$ids)))->save($device);
            if($res_device){
                $model->commit();
                $this->success('设备转移成功',U('index'));
            } else {
                $model->rollback();
                $this->error('网络错误，请重试');
            }
        }
    }
    //添加售货机
    public function add(){
        $this->display();
    }
    //添加售货机提交
    public function add_post(){
        if(IS_POST){
            if(empty($_POST['device_code']) || empty($_POST['device_command']) ||
                empty($_POST['owner_id'])|| empty($_POST['number_routes']) ||empty( $_POST['address']) ||empty($_POST['pay_price'])){
                $this->error('请检查数据是否为空！');
            }
            if($this->Goods_vending->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->find()){
                $this->error('编号'.$_POST['device_code'].'已存在');
            }
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['address'] = trim($_POST['address']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['device_type'] = trim($_POST['device_type']);
            $data['number_routes'] = trim($_POST['number_routes']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['id'] = generateNum();
            $data['status'] = 0;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $result = $this->Goods_vending->add($data);
            if($result){
                //找出刚刚添加的设备
                $reid = $this->Goods_vending->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->find();
                if($reid){
                    //添加货道数目
                    for($i=1;$i<=$_POST['number_routes'];$i++){
                        $huo['id'] = generateNum();
                        $huo['device_code'] = trim($_POST['device_code']);
                        $huo['number'] = $i;
                        $huo['number_order'] = '10'.$i;
                        $huo['create_date'] = date('Y-m-d H:i:s',time());
                        $huo['update_date'] = date('Y-m-d H:i:s',time());
                        $huo['shipment_id'] = $reid['id'];
                        M('goods_huodao')->add($huo);
                    }
                } else {
                    $this->Goods_vending->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->delete();
                    $this->error('添加失败，请检查数据是否正确');
                }
                $this->success('添加成功',U('Vending/index'));
            } else {
                $this->error('添加失败，请检查数据是否正确');
            }
        }
    }

    //修改售货机
    public function edit(){
        $id = trim($_GET['id']);
        $res = $this->Goods_vending->where(array('id'=>$id,'del_flag'=>0))->find();
        $this->assign('res',$res);
        $this->display();
    }
    //修改售货机
    public function edit_post(){
        if(IS_POST){
            if(empty($_POST['device_code']) || empty($_POST['device_command']) ||
                empty($_POST['owner_id'])|| empty($_POST['number_routes']) ||empty( $_POST['address']) ||empty($_POST['pay_price'])){
                $this->error('请检查数据是否为空！');
            }
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['address'] = trim($_POST['address']);
            $data['device_type'] = trim($_POST['device_type']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['number_routes'] = trim($_POST['number_routes']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $number = trim($_POST['number']);
            if($this->Goods_vending->where(array('id'=>trim($_POST['id']),'del_flag'=>0))->save($data)){
                //更改货道数目
                if($_POST['number_routes'] < $number){
                    for($i=$number;$i>$_POST['number_routes'];$i--){
                        M('goods_huodao')->where(array('shipment_id'=>trim($_POST['id']),'number'=>$i))->delete();
                    }
                }elseif($_POST['number_routes'] > $number){
                    //添加货道数目
                    for($i=$number;$i<$_POST['number_routes'];$i++){
                        $huo['id'] = generateNum();
                        $huo['device_code'] = trim($_POST['device_code']);
                        $huo['number'] = $i+1;
                        $huo['create_date'] = date('Y-m-d H:i:s',time());
                        $huo['update_date'] = date('Y-m-d H:i:s',time());
                        $huo['shipment_id'] = trim($_POST['id']);
                        M('goods_huodao')->add($huo);
                    }
                }
                $this->success('修改成功',U('Vending/index'));
            } else {
                $this->error('修改失败');
            }
        }
    }
    //上下货管理
    public function management(){
        if(IS_POST){
            $shop_number = trim($_POST['shop_number']);
            $shop_price = trim($_POST['shop_price']);
            $toubi_price = trim($_POST['toubi_price']);
            $number_order = trim($_POST['number_order']);
            $status = trim($_POST['status']);
            $shop_id = trim($_POST['shop_id']);
            $id = trim($_POST['id']);
            $data['shop_number'] = $shop_number;
            $data['number_order'] = $number_order;
            $data['shop_price'] = $shop_price;
            $data['toubi_price'] = $toubi_price;
            $data['status'] = $status;
            $data['shop_id'] = $shop_id;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $model = M('goods_huodao');
            $date = date('Y-m-d H:i:s',time());
            $result = $model->execute("update goods_huodao set shop_number='$shop_number',number_order='$number_order',shop_price='$shop_price',toubi_price='$toubi_price',
          status=$status,shop_id='$shop_id',update_date='$date' where id='$id'");
            //$result = M('goods_huodao')->where(array('id'=>$id))->save($data);
            if($result){
                echo json_encode(array('msg'=>200));
            } else {
                echo json_encode(array('msg'=>500));
            }
        } else {
            $id = trim($_GET['id']);
            $res = M('goods_huodao')->where(array('shipment_id'=>$id))->order('number asc')->select();
            if(get_current_admin_id()==1 || $this->type==2){
                $shop = M('goods_shop')->select();
            }else{
                $shop = M('goods_shop')->where(array('owner_id'=>get_current_admin_id()))->select();
            }
            $this->assign('list',$res);
            $this->assign('shop',$shop);
            $this->display();
        }
    }
    //删除设备
    public function del(){
        $id = trim($_GET['id']);
        $res = $this->Goods_vending->where(array('id'=>$id,'del_flag'=>0))->delete();
        $result = M('goods_huodao')->where(array('shipment_id'=>$id))->delete();
        if($result && $res){
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}