<?php
//小程序接口
namespace app\index\controller;
use think\Db;
use think\Request;
class Xiao extends \think\Controller {
    protected $openid = null;
    protected function _initialize(){
        $this->openid='odOIPv0FULcQz0pfsKpnf88N9NXU';
    }
    //社区家园列表信息
    public function Community_home_list(){
        if($_POST){
            //获取分页的数据
            if(isset($_POST['n']) && !empty($_POST['n'])){
                $n=$_POST['n']*10;
                //首页页面数据的直接显示加载
                $where['status'] = '1';
                $where['online'] = '1';
                $shop = Db::table('shop')->where($where)->order('ords','desc')->limit($n,10)->select();
                if($shop){
                    echo json_encode(array('code'=>200,'msg'=>$shop));
                }else{
                    echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
                }
            }else{
                //首页页面数据的直接显示加载
                $where['status'] = '1';
                $where['online'] = '1';
                $shop = Db::table('shop')->where($where)->order('ords','desc')->limit(10)->select();
                if($shop){
                    echo json_encode(array('code'=>200,'msg'=>$shop));
                }else{
                    echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
                }
            }
        }
    }
    /*
      * ==================
      * 我的订单
      * 2018-1-19
      * ==================
      * */
    public function customer(){
        //超过30分钟未付款，订单取消
        $uid = Db::query("SELECT	id FROM place t WHERE t.create_date IS NOT NULL
		AND t.create_date < DATE_ADD(NOW(), INTERVAL - 30 MINUTE) and openid='$this->openid'");
        $array = array();
        foreach ($uid as $k=> $v){
            $array[] = $v['id'];
        }
        $in = implode("','",$array);
        $query = Db::execute("update place set status=5 where id in('$in') and openid='$this->openid'");
        //end
        $where['openid'] = $this->openid;
        $where['create_date'] = array('> time',date('Y-m-d H:i:s',time()));
        $close = Db::table('place')->where($where)->select();
        $place = Db::table('place')->alias('p')
            ->join('shop s ','p.shop_id=s.id','left')
            ->where(array('p.openid'=>$this->openid))->order('p.create_date desc')
            ->field('p.id,s.title,s.img,p.status,p.servicetime,p.create_date,p.price')
            ->select();
        if($place){
            echo json_encode(array('code'=>200,'msg'=>$place));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
        }
    }
    /*
   * =====================
   * 订单详情
   * 2018-1-23
   * $param id 订单id
   * =====================
   * */
    public function details(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $place = Db::table('place')
            ->where(array('openid'=>$this->openid,'id'=>$id))
            ->find();
        //地址信息
        $add = Db::table('address')->where(['id'=>$place['address_id']])->find();
        if($place){
            echo json_encode(array('code'=>200,'msg'=>$place,'add'=>$add));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
        }
    }
    /*
     * =========================
     * 服务明细
     * 2018-1-16
     * =========================
     * */
    public function yuding(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $res = Db::table('shop')->where(array('id'=>$id,'status'=>'1','online'=>'1'))->find();
        if($res){
            echo json_encode(array('code'=>200,'msg'=>$res));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
        }
    }
    /*
     * ===========================
     * 预定
     * 2018-1-30
     * ===========================
     * */
    public function reserve(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $res = Db::table('shop')->where(array('id'=>$id,'status'=>'1','online'=>'1'))->find();
        //服务地址
        $house = Db::table('address')->where(['create_by'=>$this->openid])->order('create_date desc')->select();

        //预约已满
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $res = Db::table('shop')->where(array('id' => $id, 'status' => '1', 'online' => '1'))->find();

        //print_r(count($day));die;
        //把所有的服务时间选择出来，不能选择
        $where ['status'] = array('neq','5');
        $time = Db::table('place')->field('servicetime')->where($where)->order('servicetime desc')->select();
        $tims = array();
        foreach ($time as $k =>$v){
            /*$string = substr($v['servicetime'],0,10);
            $zero = substr($string,5,1);
            if($zero == '0'){
                $tims[$k]['servicetime'] = substr_replace($string,'',5,1);
                $tims[$k]['hour'] = substr_replace($string,'',5,1).' '.substr($v['servicetime'],11,5);
            } else {
                $tims[$k]['servicetime'] = $string;
                $tims[$k]['hour'] = $string.' '.substr($v['servicetime'],11,5);
            }*/
            $tims[$k]['servicetime'] = $v['servicetime'];
            $tims[$k]['hour'] = $v['servicetime'];
        }
        if($res){
            echo json_encode(array('code'=>200,'msg'=>$res,'house'=>$house,'hour'=>array_unique_fb($tims)));
        }else{
            echo json_encode(array('code'=>500,'msg'=>'网络错误，请重新进入'));
        }
    }
    /*
     * ====================
     * 订单提交
     * 2018-1-31
     * ====================
     * */
    public function reser_submit(){
        if($_POST){
            $order_id = order_id();
            $select_time = $_POST['select_time'];
            $add_id = $_POST['add_id'];
            $customer_mark = $_POST['customer_mark'];
            $id = $_POST['id'];
            $price = $_POST['price'];
            $resu = Db::table('place')->where(array('openid'=>$this->openid,
                'out_trade_no'=>$order_id))->find();
            if(!$resu){
                $data = array(
                    'shop_id' => $id,
                    'address_id'=>$add_id,
                    'openid'=>$this->openid,
                    'status'=>'0',
                    'price'=>$price,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'out_trade_no'=>$order_id,
                    'servicetime' => $select_time,
                    'remarks' => $customer_mark,
                    'create_date' => date('Y-m-d H:i:s',time()),
                    'update_date' => date('Y-m-d H:i:s',time())
                );
                $result = Db::table('place')->insert($data);
                session('ip',$_SERVER['REMOTE_ADDR']);
            }
            $url = url('accounting',array('id'=>$id,
                'add_id'=>$add_id,'order_id'=>$order_id));
            echo json_encode(array('url'=>$url));
        }
    }
}