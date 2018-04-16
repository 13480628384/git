<?php
require_once "lib/WxPay.Config.php";
require_once "Alipay/function.php";
class V_2RoseAjaxAction extends Action {
    //注册提醒
    public function p_my(){
        $total_counts = '';
        $user_id = $_GET['user_id'];
        $weixin_alipay_type = $_GET['weixin_alipay_type'];
        $scan_code = $_GET['scan_code'];
        if($weixin_alipay_type == 'wechat'){
            $count = M('weixin_userinfo')->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>WxPayConfig::APPID,
                'from_username'=>$user_id
            ))->find();
            $total_count = intval($count['pay_total_account']-$count['consume_total_account']);
        } else {
            $alipay_userinfo = M('alipay_userinfo');
            $total_count = $alipay_userinfo->where(array(
                'status'=>1,
                'del_flag'=>0,
                'app_id'=>AlipayConfig::APPID,
                'buyer_id'=>$user_id
            ))->sum("pay_total_account-consume_total_account");
        }
        if( intval($total_count)<=0 ) {
            $total_counts = 0;
        } else {
            $total_counts = $total_count;
        }
        $this->assign('all',$total_counts);
        /*==========================导流广告 [[==========================*/
        $rose_adv = M('rose_eco_advertising_info')->where(array(
            'del_flag'=>0,
            'audit_status'=>1,
            'online'=>1
        ))->order('rand()')->limit(2)->select();
        //消耗黄玫瑰和添加展示数
        foreach($rose_adv as $key=>$v){
            M('rose_eco_advertising_info')->where(array('id'=>$v['id']))->setInc('show_number');
            $quoention = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->find();
            if(intval($quoention['yellow_rose'])>0){
                M('rose_user_info')->where(array('del_flag'=>0,'id'=>$v['quotient_id']))->setDec('yellow_rose');
                $rose_adv[$key]['count'] = 2;
            }
        }
        $this->assign('rose_adv',$rose_adv);
        /*==========================导流广告 ]]==========================*/
        $this->assign('scan_code',$scan_code);
        $this->display();
    }
    //赠送生态红玫瑰
    public function send_rose(){
        $content = trim($_POST['content']);
        $total = trim($_POST['total']);
        $scan_code = trim($_POST['scan_code']);
        $nickname = trim($_POST['nickname']);
        $quotient_id = trim($_POST['quotient_id']);
        $user_id = trim($_POST['user_id']);
        $weixin_alipay_type = trim($_POST['weixin_alipay_type']);
        $ifnic = M('rose_user_info')->where(array('del_falg'=>0,'nickname'=>$nickname))->find();
        if(!$ifnic){
            exit(json_encode(array('code'=>500,'error'=>'对方昵称不存在')));
        }
        if($ifnic['id'] == $quotient_id){
            exit(json_encode(array('code'=>500,'error'=>'不能送给自己')));
        }
        //检查生态商玫瑰是否足够
        $ifrose = M('rose_user_info')->where(array('del_falg'=>0,'id'=>$quotient_id))->find();
        if(intval($ifrose['ecological_red_rose']) < intval($total)){
            exit(json_encode(array('code'=>500,'error'=>'生态红玫瑰不足')));
        }
        $model = M('rose_gift_of_rose');
        $model->startTrans();
        if($weixin_alipay_type == 'wechat'){
            $data['openid'] = $user_id;
        } else {
            $data['buyer_id'] = $user_id;
        }
        $data['id'] = generateNum();
        $data['give_quotient_id'] = $ifnic['id'];
        $data['total'] = $total;
        $data['content'] = $content;
        $data['quotient_id'] = $quotient_id;
        $data['create_by'] = $quotient_id;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->add($data);
        $cid = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$ifnic['id']))->setInc('red_rose',$total);
        $myrose = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$quotient_id))->setDec('ecological_red_rose',$total);
        if($uid && $cid && $myrose){
            $model->commit();
            exit(json_encode(array('code'=>200,'url'=>U('V_2Rose/give_list',
                array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code)))));
        } else {
            exit(json_encode(array('code'=>500,'error'=>'赠送失败，请重新赠送')));
        }
    }
    //绑定用户信息
    public function update_vip(){
        $nickname = trim($_POST['nickname']);
        $wechat_number = trim($_POST['wechat_number']);
        $email = trim($_POST['email']);
        $scan_code = trim($_POST['scan_code']);
        $alipay_number = trim($_POST['alipay_number']);
        $phone = trim($_POST['phone']);
        $uid = trim($_POST['uid']);
        $weixin_alipay_type = trim($_POST['weixin_alipay_type']);
        $user_id = trim($_POST['user_id']);
        //判断用户昵称是否存在
        $ifnic = M('rose_user_info')->where(array('del_falg'=>0,'nickname'=>$nickname))
            ->group('nickname')->having('count(nickname)>1')->find();
        if($ifnic){
            exit(json_encode(array('code'=>500,'error'=>'用户昵称已被使用')));
        }
        $ifphone = M('rose_user_info')->where(array('del_falg'=>0,'phone'=>$phone))
            ->group('phone')->having('count(phone)>1')->find();
        if($ifphone){
            exit(json_encode(array('code'=>500,'error'=>'手机号码已被使用')));
        }
        $data['nickname'] = $nickname;
        $data['email'] = $email;
        $data['alipay_number'] = $alipay_number;
        $data['phone'] = $phone;
        $data['wechat_number'] = $wechat_number;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $cid = M('rose_user_info')->where(array('id'=>$uid,'del_flag'=>0))->save($data);
        if($cid){
            echo json_encode(array('code'=>200,'url'=>U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code))));
        } else {
            echo json_encode(array('code'=>300));
        }
    }
    //用户头像信息修改
    public function update_img(){
        $_POST['imgs'] = urldecode($_POST['imgs']);
        $id = trim($_POST['id']);
        $img=explode(',',$_POST['imgs']);
        $access_token  = $this->getAccessToken();
        //目录
        $dir="./upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
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
            //$urls['imgs'][]=C('site_url').$filename;
            $dataed['headimgurl'] = C('site_url').$filename;
            $uid = M('rose_user_info')->where(array('id'=>$id,'del_flag'=>0))
                ->save($dataed);
        }
        $uid = M('rose_user_info')->where(array('id'=>$id,'del_flag'=>0))
            ->find();
        echo $uid['headimgurl'];

    }
    public function getAccessToken(){
        $appid = WxPayConfig::APPID;
        $appsecret = WxPayConfig::APPSECRET;
        $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
        $json=json_decode(file_get_contents($url_get));
        if(!isset($json->access_token)){
            return false;
        }else{
            return $json->access_token;
        }
    }
    //点击扫码获取用户昵称
    public function get_name(){
        $url = trim($_POST['url']);
        $arr = parse_url($url);
        $arr_query = convertUrlQuery($arr['query']);
        $cuid = trim($arr_query['amp;id']);
        $rose = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$cuid))->find();
        if($rose){
            echo $rose['nickname'];
        } else {
            echo 2;
        }
    }
    //导流广告上传图片
    public function adv_update_img(){
        $_POST['imgs'] = urldecode($_POST['imgs']);
        $id = trim($_POST['id']);
        $img=explode(',',$_POST['imgs']);
        $access_token  = $this->getAccessToken();
        //目录
        $dir="./upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $urls = '';
        foreach($img as $v){
            $mediaid=$v;
            $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
            $fileInfo = downloadWeixinFile($url);
            $filename = $dir."/".getSn().".jpg";//取名字
            saveWeixinFile($filename, $fileInfo["body"]);
            $urls = C('site_url').$filename;
        }
        echo $urls;
    }
    //消耗导流广告数
    public function add_adv(){
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $ro = M('rose_eco_advertising_info')->where(array('id'=>$id,'del_flag'=>0))->find();
        $all = M('rose_user_info')->where(array('id'=>$ro['quotient_id'],'del_falg'=>0))->find();
        if($all['yellow_rose']<$ro['one_number']){
            echo json_encode(array('code'=>201));
            exit;
        }
        //判断是否是会员
        $model = M('rose_user_info');
        $where['buyer_id'] = $user_id;
        $where['_logic'] = 'or';
        $where['openid'] = $user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $rose = $model->where($map)->find();
        if($rose){
            $roseed = $model->where($map)->setInc('red_rose',$ro['consume_number']);//会员增加5个红玫瑰
        }
        $data['id'] = generateNum();
        $data['user_id'] = $user_id;
        $data['total'] = $ro['one_number'];
        $data['reai_id'] = $id;
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['quotient_id'] = $ro['quotient_id'];
        $uid = M('rose_give_yellow')->add($data);
        $cid = M('rose_user_info')->where(array('id'=>$ro['quotient_id'],'del_falg'=>0))->setDec('yellow_rose',$ro['one_number']);
        $idc = M('rose_eco_advertising_info')->where(array('id'=>$id,'del_flag'=>0))->setInc('click_number');
        if($uid && $cid && $idc){
            echo json_encode(array('code'=>200));
        }
    }
    //用户头像信息修改
    public function alipay_update_img(){
        $img = $_POST['img'];
        $id = $_POST['id'];
        $dir="./upload/weixin_imgs/".date('Y',time())."/".date('m',time())."/".date('d',time());
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $filename = getSn();//取名字
        $dataed['headimgurl'] = C('site_url').base64DecImg($img,$dir,$filename);
        $uid = M('rose_user_info')->where(array('id'=>$id,'del_flag'=>0))
            ->save($dataed);
        echo C('site_url').base64DecImg($img,$dir,$filename);
    }
}