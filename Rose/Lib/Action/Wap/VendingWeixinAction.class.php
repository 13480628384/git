<?php
require_once("WxPay.JsApiPay.php");
//售货机微信页面
class VendingWeixinAction extends BackAction{
    public $scan_code = null;
    public $type = null;
    protected function _initialize(){
        parent::_initialize();
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
        Vendor('weixin.jssdk');
        $jssdk = new JSSDK(WxPayConfig::APPID, WxPayConfig::APPSECRET);
        $this->signPackage = $signPackage = $jssdk->GetSignPackage($this->token);
        $this->assign('signPackage',$signPackage);
    }
    public function index(){
        //$tools = new JsApiPay();
        //$user_id = $tools->GetOpenid();
        $get_user_id = $_GET['openid'];
        if(empty($user_id)){
            $openid = $get_user_id;
        }elseif(empty($get_user_id)){
            $openid = $user_id;
        }else{
            $openid = $user_id;
        }
        //先获取设备编号
        $device_code = isset($_GET['device_code'])?$_GET['device_code']:null;
        if(is_null($device_code) ){
            exit('页面参数错误');
        }
        if(empty($openid)) exit('请重新扫码');
        //查出余额:支付减去消费=余额
        $pay_count = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$openid,
                'pay_status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('pay_account');
        $consume_count = M('goods_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $count = $pay_count-$consume_count;
        if($count<=0){
            $all = 0;
        } else {
            $all = $count;
        }
        //查出玫瑰币余额
        $rose_count = M('goods_rose')
            ->where(array('wechat_alipay_id'=>$openid,
                'status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('account');
        $consume_rose_count = M('goods_rose_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $rose_count1 = $rose_count-$consume_rose_count;
        if($rose_count1<=0){
            $rose = 0;
        } else {
            $rose = $rose_count1;
        }
        //查出玫瑰币余额
        $this->assign('count',$all);
        $this->assign('rose',$rose);
        $this->assign('openid',$openid);
        //找出设备
        $result = M('goods_vending')->where(array('device_code'=>$device_code,'del_flag'=>'0'))->find();
        //找出货道
        $shop = M('goods_huodao')->alias('gh')
            ->join('LEFT JOIN goods_shop gs on gh.shop_id=gs.id')
            ->where(array('gh.shipment_id'=>$result['id']))
            ->field('gh.number,gh.id as gid,gh.number_order,gs.id,gh.shop_number,gh.shop_price,gh.toubi_price,gh.status,gs.name,gs.image')
            ->order('gh.number asc')
            ->select();
        $this->assign('res',$result);
        $this->assign('shop',$shop);
        $this->display();
    }
    //充值页面
    public function balance_recharge(){
        $openid = trim($_GET['openid']);
        $count = trim($_GET['count']);
        $device_code = trim($_GET['device_code']);
        $owner_id = trim($_GET['owner_id']);
        if(empty($openid) || empty($device_code) || empty($owner_id)) exit('缺少参数，请重新扫码');
        $rose_count = M('goods_rose')
            ->where(array('wechat_alipay_id'=>$openid,
                'status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('account');
        $consume_rose_count = M('goods_rose_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $rose_count1 = $rose_count-$consume_rose_count;
        if($rose_count1<=0){
            $rose = 0;
        } else {
            $rose = $rose_count1;
        }
        //充值价格
        $pay_price = M('goods_vending')->where(array('del_flag'=>'0','device_code'=>$device_code))->getField('pay_price');
        $array = explode(',',$pay_price);
        $array2 = array();
        foreach ($array as $key => $value) {
            $array2[] = explode('-',$value);
        }
        $array2 = array_slice($array2,0,6);//截取数组的前六个
        $this->assign('rose',$rose);
        $this->assign('array2',$array2);
        $this->assign('count',$count);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->assign('owner_id',$owner_id);
        $this->display();
    }
    //购买商品
    public function buy_goods(){
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $device_code = trim($_POST['device_code']);
        $gid = trim($_POST['gid']);//货道id
        $price = trim($_POST['price']);
        $shopid = trim($_POST['shopid']);
        $number_order= trim($_POST['number_order']);
        //判断是否空值
        if(empty($openid) || empty($owner_id) || empty($device_code)){
            exit(json_encode(array('code'=>500,'msg'=>'缺少参数，请重新扫码购买')));
        }
        //判断余额是否足够
        $pay_count = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$openid,
                'pay_status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('pay_account');
        $consume_count = M('goods_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $count = $pay_count-$consume_count;
        if($count<=0){
            exit(json_encode(array('code'=>500,'msg'=>'余额不足，请先充值再购买')));
        }
        //判断设备是否有问题，设备是否在忙，设备是否不在线
        $result_ven = M('goods_vending')->where(array('device_code'=>$device_code,'del_flag'=>'0'))->find();
        if($result_ven['online_status'] == '0'){
            exit(json_encode(array('code'=>500,'msg'=>'售货机已经断电了，请到别的售货机上购买')));
        }
        //查出货道
        $resu = M('goods_huodao')->where(array('id'=>$gid))->find();
        //确认购买商品
        //$huodaohao = '10'.$resu['number'];
        $qos = '1'; //1需要响应  0 不需要响应
        $timeout = '0';//为“秒”，默认“0”
        $order_only = only_order();
        $sms = array("OP_HD"=>intval($number_order),"TG_NUM"=>intval(1),"TG_MES"=>$order_only);
        $result = $this->vending_start->send_data_to_edp($result_ven['device_command'], $qos, $timeout, $sms);
        $return_result = 0;
        if (empty($result)) {
            $return_result = 0;
            $error_code = $this->vending_start->error_no();
            $error = $this->vending_start->error();
            echo json_encode(array('code'=>500,'msg'=>'出货失败，请购买其他商品'));
            exit;
        } else {
            $return_result = $result['cmd_uuid'];
        }
        sleep(2);
        $get_dev_status_resp = $this->vending_start->get_http($return_result);
        $res1 = json_decode($get_dev_status_resp);
        $res = object_array($res1);
        $command_info = array(
            'id' => generateNum(),
            'cmd_id' => $return_result,
            'di_id' => $result_ven['id'],
            'deivce_command' =>$result_ven['device_command'],
            'status' => '1',
            'resp_status'=>'100',
            'create_date'=>date('Y-m-d H:i:s',time()),
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time()),
        );
        M('vending_command_info')->add($command_info);
        //成功购买添加消费记录
        $data['id'] = generateId();
        $data['type'] = '1';
        $data['cmd_uuid'] = $return_result;
        $data['transaction'] = $order_only;
        $data['wechat_alipay_id'] = $openid;
        $data['app_id'] = WxPayConfig::APPID;
        if(isset($res['TP'])){
            if($res['TP'] == '1'){
                $data['command_status'] = '1';
            }else{
                $data['command_status'] = '3';
            }
        }else{
            if($res['CMD_RES'] == '0'){//正常出货
                $data['command_status'] = '1';
            }else{
                $data['command_status'] = '3';
            }
        }
        $data['consume_account'] = $price;
        $data['is_close'] = '0';
        $data['shop_id'] = $shopid;
        $data['deivce_code'] = $device_code;
        $data['owner_id'] = $owner_id;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['create_by'] = $openid;
        $data['remarks'] = '微信购买';
        $data['del_flag'] = '0';
        $result_consume = M('goods_consume_rec')->add($data);
        M('goods_huodao')->where(array('id'=>$gid))->setDec('shop_number',1);
        M('goods_huodao')->where(array('id'=>$gid))->setInc('alls',1);
        if($result_consume){
            exit(json_encode(array('code'=>200,'msg'=>'购买成功，请取商品'
            ,'url'=>U('weixin_consume',array('openid'=>$openid,'device_code'=>$device_code)))));
        } else {
            exit(json_encode(array('code'=>500,'msg'=>'购买失败')));
        }
        //减去余额
    }
    /*==================================================玫瑰币===========================================================*/
    //玫瑰币购买 START
    public function rose_goods(){
        $openid = trim($_POST['openid']);
        $owner_id = trim($_POST['owner_id']);
        $device_code = trim($_POST['device_code']);
        $gid = trim($_POST['gid']);//货道id
        $price = trim($_POST['price']);
        $shopid = trim($_POST['shopid']);
        $number_order= trim($_POST['number_order']);
        //判断是否空值
        if(empty($openid) || empty($owner_id) || empty($device_code)){
            exit(json_encode(array('code'=>500,'msg'=>'缺少参数，请重新扫码购买')));
        }
        //判断余额是否足够
        $pay_count = M('goods_rose')
            ->where(array('wechat_alipay_id'=>$openid,
                'status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('account');
        $consume_count = M('goods_rose_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $count = $pay_count-$consume_count;
        if($count<=0){
            exit(json_encode(array('code'=>500,'msg'=>'玫瑰币不足，请先充值再购买')));
        }
        //判断设备是否有问题，设备是否在忙，设备是否不在线
        $result_ven = M('goods_vending')->where(array('device_code'=>$device_code,'del_flag'=>'0'))->find();
        if($result_ven['online_status'] == '0'){
            exit(json_encode(array('code'=>500,'msg'=>'售货机已经断电了，请到别的售货机上购买')));
        }
        //查出货道
        $resu = M('goods_huodao')->where(array('id'=>$gid))->find();
        //确认购买商品
        //$huodaohao = '10'.$resu['number'];
        $qos = '1'; //1需要响应  0 不需要响应
        $timeout = '0';//为“秒”，默认“0”
        $order_only = only_order();
        $sms = array("OP_HD"=>intval($number_order),"TG_NUM"=>intval(1),"TG_MES"=>$order_only);
        $result = $this->vending_start->send_data_to_edp($result_ven['device_command'], $qos, $timeout, $sms);
        $return_result = 0;
        if (empty($result)) {
            $return_result = 0;
            $error_code = $this->vending_start->error_no();
            $error = $this->vending_start->error();
            echo json_encode(array('code'=>500,'msg'=>'出货失败，请购买其他商品'));
            exit;
        } else {
            $return_result = $result['cmd_uuid'];
        }
        sleep(2);
        $get_dev_status_resp = $this->vending_start->get_http($return_result);
        $res1 = json_decode($get_dev_status_resp);
        $res = object_array($res1);
        $command_info = array(
            'id' => generateNum(),
            'cmd_id' => $return_result,
            'di_id' => $result_ven['id'],
            'deivce_command' =>$result_ven['device_command'],
            'status' => '1',
            'resp_status'=>'100',
            'create_date'=>date('Y-m-d H:i:s',time()),
            'update_by'=>'1',
            'update_date'=>date('Y-m-d H:i:s',time()),
        );
        M('vending_command_info')->add($command_info);
        //成功购买添加消费记录
        $data['id'] = generateId();
        $data['type'] = '1';
        $data['cmd_uuid'] = $return_result;
        $data['transaction'] = $order_only;
        $data['wechat_alipay_id'] = $openid;
        if(isset($res['TP'])){
            if($res['TP'] == '1'){
                $data['command_status'] = '1';
            }else{
                $data['command_status'] = '3';
            }
        }else{
            if($res['CMD_RES'] == '0'){//正常出货
                $data['command_status'] = '1';
            }else{
                $data['command_status'] = '3';
            }
        }
        $data['consume_account'] = $price;
        $data['is_close'] = '0';
        $data['shop_id'] = $shopid;
        $data['deivce_code'] = $device_code;
        $data['owner_id'] = $owner_id;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['create_by'] = $openid;
        $data['remarks'] = '玫瑰币购买';
        $data['del_flag'] = '0';
        $result_consume = M('goods_rose_consume_rec')->add($data);
        M('goods_huodao')->where(array('id'=>$gid))->setDec('shop_number',1);
        M('goods_huodao')->where(array('id'=>$gid))->setInc('alls',1);
        if($result_consume){
            exit(json_encode(array('code'=>200,'msg'=>'购买成功，请取商品'
            ,'url'=>U('weixin_consume',array('openid'=>$openid,'device_code'=>$device_code)))));
        } else {
            exit(json_encode(array('code'=>500,'msg'=>'购买失败')));
        }
        //减去余额
    }
    //玫瑰币购买 END
    //我的支付记录
    public function weixin_pay(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $pay = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$openid,
                'is_close'=>'0','del_flag'=>'0','type'=>'1'))->order('create_date desc')->limit(30)->select();
        $this->assign('pay',$pay);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //我的购买记录
    public function weixin_consume(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $consume = M('goods_consume_rec')->alias('gw')
            ->join('left join goods_shop as gs on gs.id=gw.shop_id')
            ->field('gw.consume_account,gw.command_status,gw.create_date,gs.name')
            ->where(array('gw.type'=>'1','gw.wechat_alipay_id'=>$openid
        ,'gw.is_close'=>'0','gw.del_flag'=>'0'))->order('gw.create_date desc')->limit(30)->select();
        $this->assign('consume',$consume);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //我得到的玫瑰币
    public function rose_pay(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $pay = M('goods_rose')
            ->where(array('wechat_alipay_id'=>$openid,
                'is_close'=>'0','del_flag'=>'0','type'=>'1'))->order('create_date desc')->limit(30)->select();
        $this->assign('pay',$pay);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //玫瑰消费
    public function rose_consume(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $consume = M('goods_rose_consume_rec')->alias('gw')
            ->join('left join goods_shop as gs on gs.id=gw.shop_id')
            ->field('gw.consume_account,gw.command_status,gw.create_date,gs.name')
            ->where(array('gw.type'=>'1','gw.wechat_alipay_id'=>$openid
            ,'gw.is_close'=>'0','gw.del_flag'=>'0'))->order('gw.create_date desc')->limit(30)->select();
        $this->assign('consume',$consume);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //微信申请退款到红包
    public function weixin_refund(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        //判断余额是否足够
        $pay_count = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$openid,
                'pay_status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('pay_account');
        $consume_count = M('goods_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $counts = $pay_count-$consume_count;
        if($counts<=0){
            $count = 0;
        } else {
            $count = $counts;
        }
        $this->assign('openid',$openid);
        $this->assign('count',$count);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //手动申请退款
    public function Manual_application(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        //判断余额是否足够
        $pay_count = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$openid,
                'pay_status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('pay_account');
        $consume_count = M('goods_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$openid
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $counts = $pay_count-$consume_count;
        if($counts<=0){
            $count = 0;
        } else {
            $count = $counts;
        }
        $this->assign('openid',$openid);
        $this->assign('count',$count);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //申请退款审核
    public function check_total(){
        $openid = trim($_POST['openid']);
        $today = date('Y-m-d 00:00:00');
        if(empty($openid)) exit('缺少参数，请重新扫码');
        if(M('vending_userefund')->where(array('openid'=>$openid,
            'apple_time'=>array('egt',$today)))->find()){
            echo json_encode( array('code' => '201', 'msg' => '您每天只能申请一次') );
            exit;
        }
        $total = $_POST['total'];
        $remarks = $_POST['remarks'];
        $name = $_POST['name'];
        $wechatid = $_POST['wechatid'];
        $phone = $_POST['phone'];
        $data['id'] = generateNum();
        $data['openid'] = $openid;
        $data['partner_trade_no'] = only_order();
        $data['name'] = $name;
        $data['wechatid'] = $wechatid;
        $data['phone'] = $phone;
        $data['total'] = $total;
        $data['remarks'] = $remarks;
        $data['status'] = '0';
        $data['apple_time'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        if(M('vending_userefund')->add($data)){
            echo json_encode( array('code' => '200', 'msg' => '提交成功，请留意您的账号信息') );
        }else{
            echo json_encode( array('code' => '201', 'msg' => '网络错误，请重新提交') );
        }
    }
    //退款记录
    public function Present(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $present_list = M('vending_userefund')->where(array('openid'=>$openid))->order('apple_time desc')->select();
        $this->assign('present_list',$present_list);
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
        $this->display();
    }
    //用户问题反馈
    public function feedback(){
        $openid = trim($_GET['openid']);
        $device_code = trim($_GET['device_code']);
        if(empty($openid)) exit('缺少参数，请重新扫码');
        $this->assign('openid',$openid);
        $this->assign('device_code',$device_code);
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
            $dir="../thinkcmf/upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
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
    //提交建议
    public function submit_content(){
        if(IS_POST){
            $content =trim($_POST['content']);//建议内容
            $select_id =trim($_POST['select_id']);//图片id
            $openid =trim($_POST['openid']);
            $data['id'] = generateId();
            $data['images'] = $select_id;
            $data['content'] = $content;
            $data['openid'] = $openid;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('goods_view')->add($data)){
                echo json_encode( array('code' => '200', 'msg' => '提交成功，感谢您的建议') );
            } else {
                echo json_encode( array('code' => '500', 'msg' => '网络错误，请重新提交') );
            }
        }
    }
}