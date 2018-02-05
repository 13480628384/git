<?php
namespace app\index\controller;
use app\index\controller\Base;
use think\Db;
use think\Request;
use think\view\driver\Think;

class Center extends Base
{
    /*
     * ====================
     * 下单预定
     * 2018-1-16
     * ====================
     * */
    public function reserve()
    {
        $order_id = order_id();
        //判断用户是否点击返回
       /* if(!empty(session('ip'))){
            $this->redirect('customer');
            die;
        }*/
        if($_POST){
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
        }else{
            $request = Request::instance();
            $id = $drg_id = $request->param('id');
            $res = Db::table('shop')->where(array('id'=>$id,'status'=>'1','online'=>'1'))->find();
            //服务地址
            $house = Db::table('address')->where(['create_by'=>$this->openid])->order('create_date desc')->select();
            $one_array = array();
            foreach ($house as $v){
                $one_array[]['id'] = $v['id'];
                $one_array[]['details'] = $v['details'];
            }
            //p($one_array);die;
            $this->assign([
                'res'=>$res,
                'house'=>$house,
            ]);
            return $this->fetch('reserve');
        }
    }
    /*
    * ====================
    * 下单预定
    * 2018-1-16
    * ====================
    * */
    public function select_time()
    {
            $request = Request::instance();
            $id = $drg_id = $request->param('id');
            $res = Db::table('shop')->where(array('id' => $id, 'status' => '1', 'online' => '1'))->find();

            //print_r(count($day));die;
            //把所有的服务时间选择出来，不能选择
            $where ['status'] = array('neq','5');
            $time = Db::table('place')->field('servicetime')->where($where)->order('servicetime desc')->select();
            $tims = array();
            foreach ($time as $k =>$v){
                $string = substr($v['servicetime'],0,10);
                $zero = substr($string,5,1);
                if($zero == '0'){
                    $tims[$k]['servicetime'] = substr_replace($string,'',5,1);
                    $tims[$k]['hour'] = substr_replace($string,'',5,1).' '.substr($v['servicetime'],11,5);
                } else {
                    $tims[$k]['servicetime'] = $string;
                    $tims[$k]['hour'] = $string.' '.substr($v['servicetime'],11,5);
                }
            }
            //p(array_unique_fb($tims));die;
            $this->assign([
                'res' => $res,
                'time' => $tims,
            ]);
            return  $this->fetch('select_time');
    }
    /*
     * ==========================
     * 添加服务地址
     * 2018-1-19
     * ==========================
     * */
    public function address(){
        if($_POST){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $servicearea = $_POST['servicearea'];
            $data['name'] = $name;
            $data['phone'] = $phone;
            $data['details'] = $address;
            $data['servicearea'] = $servicearea;
            $data['create_by'] = $this->openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $result = Db::table('address')->insert($data);
            if($result){
                $add_id = Db::table('address')->where(['servicearea'=>$servicearea,
                    'phone'=>$phone,'name'=>$name,'create_by'=>$this->openid])->column('id');
                echo json_encode(array('code'=>200,'msg'=>'添加成功','add_id'=>$add_id));
            } else {
                echo json_encode(array('code'=>500,'msg'=>'添加失败'));
            }
        } else {
            $request = Request::instance();
            $id = $drg_id = $request->param('id');
            $this->assign([
                'id' => $id,
            ]);
            return  $this->fetch('address');
        }
    }
    /*
     * ======================
     * 结算中心
     * 2018-1-19
     * ======================
     * */
    public function accounting(){
        $request = Request::instance();
        $id = $request->param('id');
        $add_id = $request->param('add_id');
        $order_id = $request->param('order_id');
        //$order_id = order_id();
        //先判断是否已经存储
        $res = Db::table('place')->where(array('openid'=>$this->openid,
            'out_trade_no'=>$order_id,'address_id'=>$add_id))->find();
        $resu = Db::table('shop')->where(array('id' => $id, 'status' => '1', 'online' => '1'))->find();
        $this->assign([
            'res' => $res,
            'shop' => $resu,
            'order_id'=>$order_id
        ]);
        return $this->fetch('accounting');
    }
    /*
     * ==================
     * 订单
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
        $this->assign([
            'res' => $place,
        ]);
        return $this->fetch('customer');
    }
    /*
     * =====================
     * 订单详情
     * 2018-1-23
     * $param id 订单id
     * =====================
     * */
    public function detail(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        $place = Db::table('place')
            ->where(array('openid'=>$this->openid,'id'=>$id))
            ->find();
        //地址信息
        $add = Db::table('address')->where(['id'=>$place['address_id']])->find();
        $this->assign([
            'res' => $place,
            'add' => $add,
        ]);
        return $this->fetch('detail');
    }
    /*
     * ========================
     * 下单支付
     * 2018-1-22
     * ========================
     * */
    public function weixin_place_pay(){
       if($_POST){
           $price = $_POST['price'];
           $openid = trim($_POST['openid']);
           $out_trade_no = trim($_POST['order_id']);
           $jsapi = $this->Weixin_Pay_Result($openid,$price*100,
               "http://m.roseo2o.com/homewap/public/index/center/WeiXinPay/notify",$out_trade_no);
           $now = date("Y-m-d H:i:s");
           if($jsapi){
               echo json_encode(array('jsapi'=>$jsapi,'out_trade_no'=>$out_trade_no,'code'=>200));
           } else {
               echo json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
           }
       }
    }
    /*
    * ==================
    * 个人中心
    * 2018-1-19
    * ==================
    * */
    public function index(){
        $user = Db::table('weixin_userinfo')
            ->where(array('openid'=>$this->openid,'status'=>'1'))
            ->field('id,city,openid,nickname,headimgurl,phone')
            ->find();
        $this->assign([
            'res' => $user,
        ]);
        return $this->fetch('index');
    }
    /*
     * ====================
     * 家庭地址列表
     * 2018-1-22
     * ====================
     * */
    public function add_list(){
        $home = Db::table('address')->where(['create_by'=>$this->openid])
            ->order('create_date','desc')->select();
        $this->assign([
            'home' => $home
        ]);
        return $this->fetch('add_list');
    }
    /*
     * ====================
     * 家庭地址添加
     * 2018-1-22
     * ====================
     * */
    public function add_html(){
        if($_POST){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $servicearea = $_POST['servicearea'];
            $is= $_POST['is'];
            if($is == '1'){
                $data['name'] = $name;
                $data['phone'] = $phone;
                $data['details'] = $address;
                $data['servicearea'] = $servicearea;
                $data['is_default'] = $is;
                $data['create_by'] = $this->openid;
                $data['create_date'] = date('Y-m-d H:i:s',time());
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $uid = Db::table('address')->insertGetId($data);
                $da['is_default'] = 0;
                $where['id'] = array('neq',$uid);
                $where['create_by'] = $this->openid;
                $result = Db::table('address')->where($where)->update($da);
            } else {
                $data['name'] = $name;
                $data['phone'] = $phone;
                $data['details'] = $address;
                $data['servicearea'] = $servicearea;
                $data['is_default'] = 0;
                $data['create_by'] = $this->openid;
                $data['create_date'] = date('Y-m-d H:i:s',time());
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $result = Db::table('address')->insertGetId($data);
            }

            if($result){
                echo json_encode(array('code'=>200,'msg'=>'添加成功','url'=>url('add_list')));
            } else {
                echo json_encode(array('code'=>500,'msg'=>'添加失败'));
            }
        } else {
            return $this->fetch('add_html');
        }
    }
    /*
    * ====================
    * 家庭地址列表编辑
    * 2018-1-22
    * ====================
    * */
    public function add_edit(){
        $request = Request::instance();
        $id = $drg_id = $request->param('id');
        if($id){
            $home = Db::table('address')->where(['create_by'=>$this->openid,'id'=>$id])
                ->find();
            $this->assign([
                'home' => $home
            ]);
        }
        return $this->fetch('add_edit');
    }
    public function add_save(){
        if($_POST){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $id = $_POST['id'];
            $address = $_POST['address'];
            $servicearea = $_POST['servicearea'];
            $is = $_POST['is'];
            if($is == '1'){
                $data['name'] = $name;
                $data['phone'] = $phone;
                $data['details'] = $address;
                $data['is_default'] = $is;
                $data['servicearea'] = $servicearea;
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $result = Db::table('address')->where(['id'=>$id])->update($data);
                $da['is_default'] = 0;
                $where['id'] = array('neq',$id);
                $where['create_by'] = $this->openid;
                Db::table('address')->where($where)->update($da);
            } else {
                $data['name'] = $name;
                $data['phone'] = $phone;
                $data['details'] = $address;
                $data['is_default'] = $is;
                $data['servicearea'] = $servicearea;
                $data['update_date'] = date('Y-m-d H:i:s',time());
                $result = Db::table('address')->where(['id'=>$id,'create_by'=>$this->openid])->update($data);
            }
            if($result){
                echo json_encode(array('code'=>200,'msg'=>'保存成功','url'=>url('add_list')));
            } else {
                echo json_encode(array('code'=>500,'msg'=>'添加失败'));
            }
        }
    }
    /*
     * ===========================
     * 退出登录
     * 2018-1-22
     * ===========================
     * */
    public function logout(){
        $res = session('openid',null);
        $this->redirect('Login/index');
    }
}
