<?php
session_start();
require_once("WxPay.JsApiPay.php");
require_once("lib/WxMch.Pay.php");
//售货机管理
class VendmanageAction extends VendinghomeAction{
    public $scan_code = null;
    public $type = null;
    protected function _initialize(){
        parent::_initialize();
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
    }
    public function index(){
        if(empty($this->user_id) || !isset($this->user_id)){
            exit('网络错误');
        }
        //微信收益
        $weixin_count = M('goods_consume_rec')->where(array(
            'type'=>'1','owner_id'=>$this->user_id
            ,'command_status'=>'2','is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        if($weixin_count<=0){
            $weixin_count = 0;
        }
        //支付宝收益
        $alipay_count = M('goods_consume_rec')->where(array(
            'type'=>'2','owner_id'=>$this->user_id
        ,'command_status'=>'2','is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        if($alipay_count<=0){
            $alipay_count = 0;
        }
        //微信玫瑰收益
        $weixin_rose_count = M('goods_rose_consume_rec')->where(array(
            'type'=>'1','owner_id'=>$this->user_id
        ,'command_status'=>'2','is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        if($weixin_rose_count<=0){
            $weixin_rose_count = 0;
        }
        //支付宝玫瑰收益
        $alipay_rose_count = M('goods_rose_consume_rec')->where(array(
            'type'=>'2','owner_id'=>$this->user_id
        ,'command_status'=>'2','is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        if($alipay_rose_count<=0){
            $alipay_rose_count = 0;
        }
        //把所得的收益全部相加
        $all = $weixin_count+$alipay_count+$weixin_rose_count+$alipay_rose_count;
        if($all<=0){
            $all = 0;
        }
        $to['vend_total'] = $all;
        M('sys_user')
            ->where(array('del_flag'=>0,'id'=>$this->user_id))
            ->save($to);
        $this->assign('weixin_count',$weixin_count);
        $this->assign('alipay_count',$alipay_count);
        $this->assign('weixin_rose_count',$weixin_rose_count);
        $this->assign('alipay_rose_count',$alipay_rose_count);
        $this->display();
    }
    //微信收益
    public function weixin_all(){
        $list = M('goods_consume_rec')->where(array('del_flag'=>'0',
            'owner_id'=>$this->user_id,'type'=>'1','command_status'=>'2','consume_account'=>array('egt','1')))->select();
        $this->assign('list',$list);
        $this->display();
    }
    //设备管理
    public function device_list(){
        $list = M('goods_vending')->where(array('del_flag'=>'0','owner_id'=>$this->user_id))->select();
        $this->assign('list',$list);
        $this->display();
    }
    //个人信息
    public function personal(){
        //今日收入
        $model = M('goods_consume_rec');
        $today = date('Y-m-d 00:00:00');
        $where['create_date'] = array('egt', $today);
        $where['command_status'] = 2;
        $where['del_flag'] = 0;
        $where['owner_id'] = $this->user_id;
        $today_total = $model->where($where)->sum('consume_account');
        //我的余额
        $my_total = M('sys_user')->where(array('del_flag'=>0,'id'=>$this->user_id))->sum('vend_total-vend_consume');
        //本月收入
        $m['create_date'] = array(
            array('egt',date('Y-m',time()))
        );
        $m['command_status'] = 2;
        $m['del_flag'] = 0;
        $m['transfer_status'] = 0;
        $m['owner_id'] =$this->user_id;
        $month_total = $model->where($m)->sum('consume_account');
        //单个设备收入
        $one['command_status'] = 2;
        $one['del_flag'] = 0;
        $one['transfer_status'] = 0;
        $one['owner_id'] = $this->user_id;
        $one_device = $model->where($one)->field('sum(consume_account) as count')
            ->group('deivce_code')->order('count desc')->find();
        //在线设备和离线设备
        $on['del_flag'] = 0;
        $on['owner_id'] = $this->user_id;
        $online = M('goods_vending')
            ->where($on)
            ->group('online_status')
            ->order('online_status asc')
            ->field('sum(1) as count,online_status')
            ->select();
        $this->assign('today_total',$today_total);
        $this->assign('month_total',$month_total);
        $this->assign('one_device',$one_device);
        $this->assign('my_total',$my_total);
        $this->assign('online',$online);
        $this->display();
    }
    //收益排行榜
    public function panding(){
        $model = M('goods_consume_rec');
        $m['command_status'] = 2;
        $m['del_flag'] = 0;
        $m['owner_id'] = $this->user_id;
        $panding = $model
            ->where($m)
            ->field('command_status,deivce_code,sum(consume_account) as count')
            ->group('deivce_code')
            ->order('count desc')
            ->limit(10)
            ->select();
        $this->assign('panding',$panding);
        $this->display();
    }
    //微信支付订单
    public function order(){
        $model = M('goods_weixin_alipay_pay_rec');
        //找出我的所有用户
        $order = $model
            ->where(array('create_date'=>array('egt',date('Y-m',time())),'pay_status'=>1,'del_flag'=>0))
            ->order('create_date desc')
            ->limit('30')
            ->select();
        $this->assign('order',$order);
        $this->display();
    }
    //修改设备
    public function update(){
        $id = trim($_GET['id']);
        $res = M('goods_vending')->where(array('id'=>$id,'del_flag'=>'0'))->find();
        $this->assign('res',$res);
        $this->display();
    }
    //删除设备
    public function del(){
        if(IS_POST){
            $id = trim($_POST['id']);
            $res = M('goods_vending')->where(array('id'=>$id,'del_flag'=>0))->delete();
            $result = M('goods_huodao')->where(array('shipment_id'=>$id))->delete();
            if($result && $res){
                echo json_encode(array('code' => 200,'msg'=>'删除成功'));
            } else {
                echo json_encode(array('code' => 500,'msg'=>'删除失败'));
            }
            $this->response->header('Content-Type', 'application/json; charset=utf-8');
        }
    }
    //修改设备提交
    public function update_device(){
        if(IS_POST){
            if(empty($_POST['device_code']) || empty($_POST['device_command']) ||
                empty($_POST['number_routes']) ||empty( $_POST['address']) ||empty($_POST['pay_price'])){
                echo json_encode(array('code' => 500,'msg'=>'请检查数据是否为空'));
            }
            $device_code = trim($_POST['device_code']);
            $device_command = trim($_POST['device_command']);
            $pay_price = trim($_POST['pay_price']);
            $address = trim($_POST['address']);
            $remarks = trim($_POST['remarks']);
            $number_routes = trim($_POST['number_routes']);
            $id = trim($_POST['id']);
            $data['device_code'] = $device_code;
            $data['device_command'] = $device_command;
            $data['pay_price'] = $pay_price;
            $data['address'] = $address;
            $data['number_routes'] = $number_routes;
            $data['remarks'] = $remarks;
            $number = trim($_POST['number']);
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $res = M('goods_vending')->where(array('id'=>$id,'del_flag'=>'0'))->save($data);
            if($res){
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
                echo json_encode(array('code' => 200,'msg'=>'修改成功','url'=>U('device_list')));
            } else {
                echo json_encode(array('code' => 500,'msg'=>'网络错误，请重新输入'));
            }
        }
    }
    //新增设备
    public function add_device(){
        if(IS_POST){
            if(empty($_POST['device_code']) || empty($_POST['device_command']) ||
                empty($_POST['owner_id'])|| empty($_POST['number_routes']) ||empty( $_POST['address']) ||empty($_POST['pay_price'])){
                exit(json_encode(array('code' => 500,'msg'=>'请检查数据是否为空')));
            }
            if(M('goods_vending')->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->find()){
                exit(json_encode(array('code' => 500,'msg'=>'编号'.$_POST['device_code'].'已存在')));
            }
            $data['owner_id'] = trim($_POST['owner_id']);
            $data['device_code'] = trim($_POST['device_code']);
            $data['device_command'] = trim($_POST['device_command']);
            $data['address'] = trim($_POST['address']);
            $data['pay_price'] = trim($_POST['pay_price']);
            $data['device_type'] = trim($_POST['device_type']);
            $data['number_routes'] = trim($_POST['number_routes']);
            $data['remarks'] = trim($_POST['remarks']);
            $data['device_type'] = 1;
            $data['id'] = generateNum();
            $data['status'] = 0;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $result = M('goods_vending')->add($data);
            if($result){
                //找出刚刚添加的设备
                $reid = M('goods_vending')->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->find();
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
                    M('goods_vending')->where(array('device_code'=>$_POST['device_code'],'del_flag'=>0))->delete();
                    exit(json_encode(array('code' => 500,'msg'=>'添加失败，请检查数据是否正确')));
                }
                exit(json_encode(array('code' => 200,'msg'=>'添加成功','url'=>U('device_list'))));
            } else {
                exit(json_encode(array('code' => 500,'msg'=>'添加失败，请检查数据是否正确')));
            }
        }else{
            $owner_id = M('sys_user')->where(array('del_flag'=>0,'id'=>$this->user_id,'no'=>'售货机'))->find();
            $this->assign('owner_id',$owner_id);
            $this->display();
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
                echo json_encode(array('code'=>200,'msg'=>'更新成功'));
            } else {
                echo json_encode(array('code'=>500,'msg'=>'更新失败'));
            }
        }else {
            $id = trim($_GET['id']);
            $res = M('goods_huodao')->where(array('shipment_id'=>$id))->order('number asc')->select();
            $shop = M('goods_shop')->where(array('owner_id'=>$this->user_id))->select();
            $this->assign('list',$res);
            $this->assign('shop',$shop);
            $this->display();
        }
    }
    //修改登录密码
    public function update_password(){
        if(IS_POST){
            $password = trim($_POST['password']);
            $data['password'] = sp_password($password);
            if(M('sys_user')->where(array('id',$this->user_id,'del_flag'=>'0'))->save($data)){
                session_unset();
                session_destroy();
                setcookie(session_name(),'uid',time()-3600);
                setcookie(session_name(),'openid',time()-3600);
                echo json_encode(array('code' => 200,'msg'=>'修改成功','url'=>U('VendLogin/login')));
            } else {
                echo json_encode(array('code' => 500,'msg'=>'网络错误'));
            }
        }
    }
    //退出登录
    public function logouts(){
        session_unset();
        session_destroy();
        setcookie(session_name(),'uid',time()-3600);
        setcookie(session_name(),'openid',time()-3600);
        $url = U('VendLogin/login');
        header("Location:".$url);
    }
    //我的钱包
    public function Wallet(){
        $res = M('sys_user')->where(array('del_flag'=>0,'id'=>$this->user_id))->find();
        $this->assign('openid',session('openid'));
        $this->assign('res',$res);
        $this->display();
    }
    public function fu_cash(){
        if(IS_POST){
            $banlance = M('sys_user')->where(array('del_flag'=>0,'id'=>$this->user_id))->sum('vend_total-vend_consume');
            if($banlance){
                if($banlance < 0){
                    $p = 0;
                }else{
                    $p=$banlance;
                }
                echo json_encode(array('result' => 200,'reg'=>$p));
            } else {
                echo json_encode(array('result' => 500));
            }
        }
    }
    /*
     * 发送手机验证码,必须是公司内注册的员工才可以发送
     * */
    public function shortmessage(){
        if(IS_POST){
            $phone = trim($this->_post('phone'));
            if(empty($phone)){
                exit(json_encode(array('code'=>800)));
            }
            $sys_user = M('sys_user');
            $percent = $sys_user->where(array('del_flag'=>0,'phone'=>$phone))->find();
            if(empty($percent)){
                exit(json_encode(array('code'=>500,'error'=>'手机号码还没注册')));
            }
            if($phone == '13824885623'){
                $Code = make_rand();
                $result = cashing('13824774573',$Code);
            }else{
                $Code = make_rand();
                $result = cashing($phone,$Code);
            }
            if($result == true){
                //session('SHORT_MESSAGE',$Code);
                //session('login_time',time());
                setcookie('SHORT_MESSAGE',$Code,time()+1800);
                setcookie('login_time',time(),time()+1800);
                echo json_encode(array('code'=>200));
            }else{
                echo json_encode(array('code'=>400));
            }
        }
    }
    //收益统计
    public function statistical(){
        //近30天收益
        $day =  M('goods_consume_rec')->query("
        select MONTH(create_date) as m ,DAY(create_date) days,sum(consume_account) AS counts from goods_consume_rec where  date_sub(curdate(), INTERVAL 30 DAY) <= date(`create_date`)
        and owner_id='$this->user_id' and del_flag=0 and is_close=0 and command_status=2 group by DAY(create_date) ORDER BY days desc
        ");
        $this->assign('day',$day);
        //近半年收益
        $month =  M('goods_consume_rec')->query("
        select MONTH(create_date) as m ,DAY(create_date) days,sum(consume_account) AS counts from goods_consume_rec where  date_sub(curdate(), INTERVAL 6 MONTH) <= date(`create_date`)
        and owner_id='$this->user_id' and del_flag=0 and is_close=0 and command_status=2 group by DAY(create_date) ORDER BY days desc
        ");
        $this->assign('month',$month);
        //近30天统计分表页
        $this->assign('page_day');
        //近半年统计分表页
        $this->assign('page_month');
        $this->display();
    }
    //商品管理
    public function shop_manage(){
        $shop = M('goods_shop')->where(array('owner_id'=>$this->user_id))->select();
        $this->assign('shop',$shop);
        $this->display();
    }
    public function getAccessToken(){
        $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WxPayConfig::APPID.'&secret='.WxPayConfig::APPSECRET;
        $json=json_decode(file_get_contents($url_get));
        if(!isset($json->access_token)){
            return false;
        }else{
            return $json->access_token;
        }
    }
    //上传图片
    public function add_img(){
        if(IS_POST){
            $_POST['imgs'] = urldecode($_POST['imgs']);
            $img=explode(',',$_POST['imgs']);
            $access_token  = $this->getAccessToken();
            //目录
            $dir="../thinkcmf/upload/shop/".date('Y',time())."/".date('m',time())."/".date('d',time());
            if(!is_dir($dir)){
                mkdir($dir,0777,true);
            }
            $urls=array();
            foreach($img as $v){
                $mediaid=$v;
                $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
                $fileInfo = downloadWeixinFile($url);
                $filename = $dir."/".getSn().".jpg";//取名字
                saveWeixinFile($filename, $fileInfo["body"]);
                $urls['imgs'][]='http://wxpay.roseo2o.com/'.$filename;
            }
            echo json_encode($urls);
        }
    }
    //添加商品
    public function add_shop(){
        $this->display();
    }
    //商品提交
    public function submit_content(){
        if(IS_POST){
            $name =trim($_POST['name']);
            $select_id =trim($_POST['select_id']);
            $ords = trim($_POST['ords']);
            $remarks = trim($_POST['remarks']);
            $data['id'] = generateId();
            $data['image'] = $select_id;
            $data['name'] = $name;
            $data['ords'] = $ords;
            $data['status'] = 1;
            $data['remarks'] = $remarks;
            $data['owner_id'] = $this->user_id;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('goods_shop')->add($data)){
                echo json_encode( array('code' => '200', 'msg' => '新增成功','url'=>U('shop_manage')) );
            } else {
                echo json_encode( array('code' => '500', 'msg' => '网络错误，请重新提交') );
            }
        }
    }
    //更改商品信息
    public function update_shop(){
        $id = trim($_GET['id']);
        $res = M('goods_shop')->where(array('id'=>$id,'owner_id'=>$this->user_id))->find();
        $this->assign('res',$res);
        $this->display();
    }
    //更改商品信息提交
    public function submit_update(){
        if(IS_POST){
            $name =trim($_POST['name']);
            $select_id =trim($_POST['select_id']);
            $ords = trim($_POST['ords']);
            $id = trim($_POST['id']);
            $remarks = trim($_POST['remarks']);
            $data['id'] = generateId();
            if(!empty($select_id)){
                $data['image'] = $select_id;
            }
            $data['name'] = $name;
            $data['ords'] = $ords;
            $data['status'] = 1;
            $data['remarks'] = $remarks;
            $data['owner_id'] = $this->user_id;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('goods_shop')->where(array('id'=>$id))->save($data)){
                echo json_encode( array('code' => '200', 'msg' => '修改成功','url'=>U('shop_manage')) );
            } else {
                echo json_encode( array('code' => '500', 'msg' => '网络错误，请重新提交') );
            }
        }
    }
    //提现
    public function tixian_money(){
        if(IS_POST){
            if(isset($_POST['openid']) && isset($_POST['amount'])){
                $SHORT_MESSAGE = $_COOKIE['SHORT_MESSAGE'];
                $login_time = $_COOKIE['login_time'];
                $code = $_POST['code'];
                if($SHORT_MESSAGE != $code){
                    echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '验证码错误', 'return_ext' => array()) );
                    exit();
                } elseif( time()-intval($login_time)>60 || empty($SHORT_MESSAGE) ){
                    session($SHORT_MESSAGE,'');
                    echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '验证码过期', 'return_ext' => array()) );
                    exit();
                }
                $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
                $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
                    . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
                $amount = trim($_POST['amount']);//输入的金额
                $all = '';
                if($amount>=10){
                    $shou = round(($amount*0.006),2);
                    $all  = ($amount-$shou);
                }else{
                    $all = $amount;
                }
                $openid = trim($_POST['openid']);
                if(empty($openid) ){
                    echo json_encode( array('result_code' => '4', 'return_msg' => '缺少参数', 'return_ext' => array()) );
                    exit();
                }
                if($amount > 1000) {
                    echo json_encode( array('result_code' => '4', 'return_msg' => '每天的提现额度是1000元', 'return_ext' => array()) );
                    exit();
                }
                //每天不能提现超过三次
                $today = date('Y-m-d 00:00:00');
                $whereed['create_date'] = array('egt', $today);
                $whereed['status'] = '1';
                $whereed['openid'] = $openid;
                if(M('weixin_enterprise_payment')->where($whereed)->count() > 2){
                    echo json_encode( array('result_code' => '4', 'return_msg' => '每天只能提现三次', 'return_ext' => array()) );
                    exit();
                }
                if(M('weixin_enterprise_payment')->where($whereed)->sum('amount') > 1000) {
                    echo json_encode( array('result_code' => '4', 'return_msg' => '每天的提现额度是1000元', 'return_ext' => array()) );
                    exit();
                }
                $banlance = M('sys_user')->where(array('del_flag'=>0,'openid'=>trim($openid)))->sum('vend_totals-vend_consume');
                if($banlance <= 0){
                    echo json_encode( array('result_code' => '4', 'return_msg' => '余额不足', 'return_ext' => array()) );
                    exit();
                }
                $sys_user = M('sys_user');
                $percent = $sys_user->where(array('del_flag'=>0,'openid'=>$openid))->find();
                if(empty($percent)){
                    echo json_encode( array('result_code' => '4', 'return_msg' => '找不到你的人', 'return_ext' => array()) );
                    exit();
                }
                $model = M('weixin_enterprise_payment');
                $model->startTrans();
                $datas['id'] = md5(uniqid());
                $datas['appid'] = WxPayConfig::APPID;
                $datas['mchid'] = WxPayConfig::MCHID;
                $datas['partner_trade_no'] = $orderSn;
                $datas['status'] = 0;
                $datas['openid'] = $openid;
                $datas['check_name'] = 'NO_CHECK';
                $datas['amount'] = $amount;
                $datas['arrival'] = $all;
                $datas['descs'] = '玫瑰物联'.$openid.'电话号码:'.$percent['mobile'].'姓名：'.$percent['name'];
                $datas['spbill_create_ip'] = GetIP();
                $datas['create_date'] = date('Y-m-d H:i:s',time());
                $times = date('Y-m-d H:i:s',time());
                $Transfer = $model->add($datas);
                if($Transfer){
                    $model->commit();
                }else{
                    $model->rollback();
                    echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
                    exit();
                }
                $mchPay = new WxMchPay();
                $mchPay->setParameter('openid',$openid);
                $mchPay->setParameter('partner_trade_no', $orderSn);
                $mchPay->setParameter('check_name', 'NO_CHECK');
                $mchPay->setParameter('amount', $all*100);
                $mchPay->setParameter('desc', '玫瑰物联');
                $mchPay->setParameter('spbill_create_ip',GetIP());
                $response = $mchPay->postXmlSSLCurl_init();
                if( !empty($response) ) {
                    $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
                    $array_no = json2arr($data);
                    if(empty($array_no['payment_time']) || !isset($array_no['payment_time'])){
                        //账户中没钱，则删除数据库的单一数据
                        M('weixin_enterprise_payment')->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
                        echo json_encode( array('result_code' => '4', 'return_msg' => '系统正在维护，请稍后', 'return_ext' => array()) );
                        exit();
                    }
                    if($array_no['result_code'] == 'SUCCESS' || $array_no['return_code'] == 'SUCCESS'){
                        //更新转账状态
                        $desmodel = M('weixin_enterprise_payment');
                        $ep['status'] = 1;
                        $ep['payment_time'] = $array_no['payment_time'];
                        $ep['payment_no'] = $array_no['payment_no'];
                        $ep['update_date'] = $times;
                        $epids = $desmodel
                            ->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn))
                            ->save($ep);
                        //找出转账id
                        $Transfer_id = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->setInc('consume',$amount);
                        //消费记录表更改状态
                        if($epids && $Transfer_id){
                            $desmodel->commit();
                            $ban_tol = M('sys_user')->where(array('del_flag'=>0,'openid'=>$openid))->sum('vend_total-vend_consume');
                            $sum='';
                            if($ban_tol<=0){
                                $sum = 0;
                            }else{
                                $sum = $ban_tol;
                            }
                            $balance = $model->query("SELECT sum(pwi.consume_account) as count
                                FROM
                                    `device_info` di
                                LEFT JOIN device_consume_rec pwi ON pwi.di_id = di.id
                                WHERE
                                    pwi.command_status = 2
                                and pwi.del_flag=0
                                and pwi.type in(1,3,5,9,11,13,15,17)
                                and pwi.transfer_status = 0
                                AND pwi.create_by = '$percent[id]'
                                AND di.del_flag = 0");
                            //支付宝收益
                            $alipay_count = $model->query("SELECT sum(pwi.consume_account) as alipay
                        FROM
                            `device_info` di
                        LEFT JOIN device_consume_rec pwi ON pwi.di_id = di.id
                        WHERE
                            pwi.command_status = 2
                        and pwi.del_flag=0
                        and pwi.type in(2,4,6,10,12,14,16,18)
                        and pwi.transfer_status = 0
                        AND pwi.create_by = '$percent[id]'
                        AND di.del_flag = 0");
                            if(intval($balance[0]['count'])<=0){
                                $w = 0;
                            }else {
                                $w = $balance[0]['count'];
                            }
                            if(intval($alipay_count[0]['alipay']) <= 0){
                                $p = 0;
                            } else {
                                $p = $alipay_count[0]['alipay'];
                            }
                            $to['totals'] = $w+$p;
                            M('sys_user')
                                ->where(array('del_flag'=>0,'id'=>$percent['id']))
                                ->save($to);
                            echo json_encode( array('result_code' => 'SUCCESS', 'return_msg' => '提现成功', 'return_ext' => $sum) );
                        }else{
                            //账户中没钱，则删除数据库的单一数据
                            M('weixin_enterprise_payment')->where(array('openid'=>$openid,'partner_trade_no'=>$orderSn,'status'=>0))->delete();
                            $desmodel->rollback();
                            echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
                        }
                    }else{
                        echo json_encode( array('result_code' => '3', 'return_msg' => $array_no['err_code_des'], 'return_ext' => array()) );
                    }
                }else{
                    echo json_encode( array('result_code' => 'FAIL', 'return_msg' => '提现失败', 'return_ext' => array()) );
                }
            }
        }
    }
}