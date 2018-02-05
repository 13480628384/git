<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Db;
use think\Request;
use think\view\driver\Think;

class Index extends \think\Controller
{
    public function index()
    {
        // 所有的商品
        $where['status'] = '1';
        $where['online'] = '1';
        $shop = Db::table('shop')->where($where)->order('ords','desc')->select();
        $openid = session('openid');
        //$openid = 'odOIPv0FULcQz0pfsKpnf88N9NXU';
        $user_result = Db::table('weixin_userinfo')->where(array('openid'=>$openid))->find();
        $this->assign([
            'shop' => $shop,
            'user_result'=>$user_result
        ]);
        return $this->fetch('index');
    }
    /*
     * =========================
     * 服务明细
     * 2018-1-16
     * =========================
     * */
    public function details(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $res = Db::table('shop')->where(array('id'=>$id,'status'=>'1','online'=>'1'))->find();
        $this->assign([
            'res'=>$res
        ]);
        return $this->fetch('details');
    }
    /*
     * ========================
     * 设备列表
     * 2017-12-22
     * ========================
     * */
    public function device_list(){
        $result = '';
        $this->assign([
            'result'=>$result
        ]);
        return $this->fetch('device_list');
    }
    /*
     *=======================
     * 更新设备信息
     * 2017-12-25
     *=======================
     * */
    public function update_list(){
        $request = Request::instance();
        $drg_id = $request->param('drg_id');
        $di_id = $request->param('di_id');
        $result = Db::table('device_relation_group')->where(['id'=>$drg_id])->find();
        $this->assign([
            'di_id' => $di_id,
            'drg_id' => $drg_id,
            'result' => $result
        ]);
        return $this->fetch('update_list');
    }
    /*
     * ===========================
     * 设备指令修改提交
     * 2017-12-25
     * ===========================
     * */
    public function update_check(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(!empty($this->user_id)){
                $di_id = trim($_POST['di_id']);
                $drg_id = trim($_POST['drg_id']);
                $device_code = trim($_POST['device_code']);
                $device_command = trim($_POST['device_command']);
                $charger = trim($_POST['charger']);
                Db::startTrans();
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $data['device_code'] = $device_code;
                $data['device_command'] = $device_command;
                $data['charger'] = $charger;
                $code['update_date'] = date('Y-m-d H:i:s',time());
                $code['device_code'] = $device_code;
                $code['device_command'] = $device_command;
                $result1 = Db::table('device_relation_group')->where(['id'=>$drg_id,'del_flag'=>'0'])->update($data);
                $result2 = Db::table('device_info')->where(['id'=>$di_id,'del_flag'=>'0'])->update($code);
                if($result1 && $result2){
                    Db::commit();
                    return ['code'=>200,'msg'=>'修改成功','url'=>url('Index/device_list')];
                } else {
                    Db::rollback();
                    return ['code'=>201,'msg'=>'修改失败'];
                }
            }
        }
    }
    /*
     * =======================
     * 退出登录
     * 2017-12-25
     * =======================
     * */
    public function logout(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->delete('openid');
        $this->redirect('Login/index');
    }
    /*
     * ======================
     * vip 价格修改
     * 2017-12-26
     * ======================
     * */
    public function vip_update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(!empty($this->user_id)){
                $glass_price = trim($_POST['glass_price']);
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $data['glass_price'] = $glass_price;
                $result = Db::table('sys_user')->where(['id'=>$this->user_id,'del_flag'=>'0'])->update($data);
                if($result){
                    return ['code'=>200,'msg'=>'修改成功','url'=>url('Index/index')];
                } else {
                    return ['code'=>201,'msg'=>'修改失败'];
                }
            }
        }
    }
}
