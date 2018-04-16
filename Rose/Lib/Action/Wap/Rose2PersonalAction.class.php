<?php
class Rose2PersonalAction extends Rose2BaseAction {
    public function index(){
        if(empty($this->user_id)){
            exit('请重新进入');
        }
        $model = M('device_consume_rec');
        $where['command_status'] = '2';
        $where['del_flag'] = '0';
        $where['type'] = array('in','1,3,5,9,11,13,15,17,19');
        $where['transfer_status'] = '0';
        $where['consume_status'] = array('in','1,2');
        $where['create_by'] = $this->user_id;
        $balance = $model->where($where)->sum('consume_account');
        //支付宝收益
        $where['command_status'] = '2';
        $where['del_flag'] = '0';
        $where['type'] = array('in','2,4,6,10,12,14,16,18');
        $where['transfer_status'] = '0';
        $where['consume_status'] = array('in','1,2');
        $where['create_by'] = $this->user_id;
        $alipay_count = $model->where($where)->sum('consume_account');
        $lop = M('device_info')->query("SELECT sum(dw.LOC_OP) c from (SELECT * from (SELECT dr.* FROM `device_info` LEFT JOIN device_record
 dr on dr.dev_id=device_info.device_command WHERE ( device_info.del_flag = 0 )
 AND ( device_info.office_id = '$this->office_id' ) ORDER BY dr.LOC_OP desc)
 da GROUP BY da.dev_id ORDER BY da.LOC_OP desc) dw");
        if(empty($lop[0]['c'])){
            $lop[0]['c'] = 0;
        }
        $office = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        if(intval($balance)<=0){
            $w = 0;
        }else {
            $w = $balance;
        }
        if(intval($alipay_count) <= 0){
            $p = 0;
        } else {
            $p = $alipay_count;
        }
        $to['totals'] = $w+$p;
        M('sys_user')
            ->where(array('del_flag'=>0,'id'=>$this->user_id))
            ->save($to);
        $ban_tol = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->sum('totals-consume');
        $sums='';
        if($ban_tol<=0){
            $sums = 0;
        }else{
            $sums = $ban_tol;
        }
        //洗车分成
        $car_pay = M('car_pay')->where(array('create_by'=>$this->user_id))->find();
        //查询分成总余额
        $model = M('car_pay');
        $where['status'] = '1';
        $where['create_by'] = $this->user_id;
        $car_account = $model->where($where)->sum('account');
        $this->assign('lop_count',$lop[0]['c']);//线下收益
        $this->assign('sums',round($sums,2));//可提现
        $this->assign('alipay_count',$p);//支付宝收益
        $this->assign('office',$office);//支付宝收益
        $this->assign('balance',$w);//微信收益
        $this->assign('user_id',$this->user_id);
        $this->assign('car_account',round($car_account,2));
        $this->assign('car_pay',$car_pay);
        $this->display();
    }
    /*=======个人信息 [[====*/
    public function personel_information(){
        $group_name = M('deivce_group_info')
            ->where(array('del_flag'=>0,'office_id'=>$this->office_id))
            ->order('create_date desc')
            ->select();
        $this->assign('group_name',$group_name);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    public function presonal_new(){
        if(IS_POST){
            /*$openid = trim($_POST['openid']);
            $email = trim($_POST['email']);
            $mobile = trim($_POST['mobile']);
            $phone = trim($_POST['phone']);
            $data['email'] = $email;
            $data['mobile'] = $mobile;
            $data['phone'] = $phone;*/
            $title = trim($_POST['title']);
            $data['title'] = $title;
            $data['update_date'] = date('Y-m-d H:i:s',time());
            if(M('sys_user')->where(array('del_flag'=>0,'id'=>$this->user_id))->save($data)){
                echo json_encode(array('code'=>200));
            } else {
                echo json_encode(array('code'=>500));
            }
        } else {
            $sys_user = M('sys_user');
            $office = $sys_user->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->find();
            $office_name = M('sys_office')->where(array('del_flag'=>0,'id'=>trim($this->office_id)))->getField('name');
            //今日收入
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
            $where['create_date'] = array('egt', $today);
            $where['command_status'] = 2;
            $where['consume_status'] = 1;
            $where['del_flag'] = 0;
            $where['transfer_status'] = 0;
            $where['create_by'] = $office['id'];
            $today_total = $model->where($where)->sum('consume_account');
            //本月收入
            $m['create_date'] = array(
                array('egt',date('Y-m',time()))
            );
            $m['command_status'] = 2;
            $m['consume_status'] = 1;
            $m['del_flag'] = 0;
            $m['transfer_status'] = 0;
            $m['create_by'] = $office['id'];
            $month_total = $model->where($m)->sum('consume_account');
            //我的余额
            $my_total = $sys_user->where(array('del_flag'=>0,'openid'=>$this->openid))->sum('totals-consume');
            if($my_total <=0){
                $my_totals = 0;
            }else {
                $my_totals = $my_total;
            }
            //单个设备收入
            $one['command_status'] = 2;
            $one['consume_status'] = 1;
            $one['del_flag'] = 0;
            $one['transfer_status'] = 0;
            $one['create_by'] = $office['id'];
            $one_device = $model->where($one)->field('sum(consume_account) as count ,deivce_command')
                ->group('deivce_command')->order('count desc')->find();
            $scan_code = substr(M('device_info')->where(array('del_flag'=>0,
                'device_command'=>$one_device['deivce_command'],'owner_id'=>$office['id']
            ))->getField('scan_code'),-7);
            //在线设备和离线设备
            $on['di.del_flag'] = 0;
            $on['drg.del_flag'] = 0;
            $on['di.owner_id'] = $office['id'];
            $online = M('device_info')->alias('di')
                    ->join("LEFT JOIN device_relation_group drg ON drg.di_id = di.id")
                    ->where($on)
                    ->group('drg.online_status')
                    ->order('drg.online_status asc')
                    ->field('sum(1) as count,drg.online_status')
                    ->select();
            //申请退款
            $userfund = M('userefund')->where(array('owner_id'=>$office['id']))->count();
            $this->assign('openid',$this->openid);
            $this->assign('today_total',$today_total);
            $this->assign('userfund',$userfund);
            $this->assign('online',$online);
            $this->assign('scan_code',$scan_code);
            $this->assign('month_total',$month_total);
            $this->assign('my_total',intval($my_totals));
            $this->assign('one_device',$one_device);
            $this->assign('rose',$office);
            $this->assign('office_name',$office_name);
            $this->display();
        }
    }
    //设备排行榜
    public function panding(){
        $model = M('device_consume_rec');
        $office = M('sys_user')->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->find();
        $m['dsc.command_status'] = 2;
        $m['dsc.consume_status'] = 1;
        $m['dsc.del_flag'] = 0;
        $m['dsc.transfer_status'] = 0;
        $m['di.del_flag'] = 0;
        $m['dsc.create_by'] = $office['id'];
        $panding = $model->alias('dsc')
                    ->join("left join device_info as di on di.device_command=dsc.deivce_command")
                    ->where($m)
                    ->field('di.device_code,dsc.consume_status,dsc.deivce_command,sum(dsc.consume_account) as count')
                    ->group('dsc.deivce_command')
                    ->order('count desc')
                    ->select();
        $this->assign('openid',$this->openid);
        $this->assign('panding',$panding);
        $this->display();
    }
    //支付订单数
    public function order(){
        $office = M('sys_user')->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->find();
        $model = M('weixin_pay_rec');
        //找出我的所有用户
        $order = $model
                ->alias('wp')
                ->field('wp.*')
                ->join('left join device_consume_rec dc ON dc.from_username = wp.create_by')
                ->where(array('wp.create_date'=>array('egt',date('Y-m',time())),'wp.pay_status'=>1,'wp.del_flag'=>0))
                ->order('wp.create_date desc')
                ->limit('30')
                ->select();
        $this->assign('openid',$this->openid);
        $this->assign('order',$order);
        $this->display();
    }
    //退款处理
    public function refund(){
        $office = M('sys_user')->where(array('del_flag'=>0,'openid'=>trim($this->openid)))->find();
        $userfund = M('userefund')->where(array('owner_id'=>$office['id']))->order('apple_time desc')->select();
        $this->assign('openid',$this->openid);
        $this->assign('userfund',$userfund);
        $this->display();
    }
    //获取真实的退款余额
    public function real(){
        if(IS_POST){
            $id = trim($_POST['id']);
            if(empty($id)) exit;
            $res = M('userefund')->where(array('id'=>$id))->find();
            $total1 = M('weixin_pay_rec')->where(array('from_username'=>trim($res['openid']),
                'pay_status'=>'1','is_close'=>0,'del_flag'=>0))->sum('pay_account');
            $total2 = M('device_consume_rec')->where(array('from_username'=>trim($res['openid']),
                'is_close'=>0,'del_flag'=>0,'command_status'=>array('in','1,2')))->sum('consume_account');
            $count = $total1-$total2;
            if($count<=0){
                $pay = 0;
            } else {
                $pay = trim(intval($count));
            }
            echo json_encode(array('msg'=>$pay));
        }
    }
    //退款审核
    public function check_status(){
        if(IS_POST){
            $id = $_POST['id'];
            $openid = $_POST['openid'];
            $status = $_POST['status'];
            $pay = $_POST['pay'];//实际退款金额
            if($status == 2){
                $resd['status'] = '2';
                $resd['update_date'] = date('Y-m-d H:i:s',time());
                $resd['payment_no'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
                $resu = M('userefund')->where(array('id'=>$id,'openid'=>$openid))->save($resd);
                if($resu){
                    echo json_encode(array('code'=>200,'msg'=>'确认成功'));
                }else{
                    echo json_encode(array('code'=>200,'msg'=>'确认失败'));
                }
            } else {
                $resd['status'] = '1';
                $resd['update_date'] = date('Y-m-d H:i:s',time());
                $resd['payment_no'] = md5(uniqid());
                $resd['arrival'] = $pay;
                M('userefund')->where(array('id'=>$id,'openid'=>$openid))->save($resd);
                $dataed['id'] = generateNum();
                $dataed['type'] = '1';
                $dataed['from_username'] = $openid;
                $dataed['command_status'] = 2;
                $dataed['consume_account'] = $pay;
                $dataed['consume_status'] = 1;
                $dataed['transfer_status'] = 1;
                $dataed['create_by'] = $openid;
                $dataed['create_date'] = date('Y-m-d H:i:s',time());
                $dataed['create_by'] = $openid;
                $dataed['remarks'] = '用户退款';
                M('device_consume_rec')->add($dataed);
                echo json_encode(array('code'=>200,'msg'=>'确认成功'));
            }
        }
    }
    public function device_code_exists_or(){
        if(!isset($this->office_id)){
            exit;
        }
        $hard_device_code = $_POST['hard_device_code'];
        $where['device_command'] = $hard_device_code;
        $where['_logic'] = 'or';
        $where['device_code'] = $hard_device_code;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $map['create_by'] = array('eq',$this->office_id);
        $res = M('device_relation_group')->where($map)->find();
        if($res){
            $group_name = M('deivce_group_info')
                ->where(array('del_flag'=>0,'office_id'=>$this->office_id,'id'=>$res['dgi_id']))
                ->getField('group_name');
            echo json_encode(array('msg'=>1,'datas'=>$res,'group_names'=>$group_name));
        }else{
            echo json_encode(array('msg'=>2));
        }
    }
    public function update_device_grouped(){
        $device_cod = $_POST['device_cod'];
        $device_group_id = $_POST['device_group_id'];
        $where['device_command'] = $device_cod;
        $where['_logic'] = 'or';
        $where['device_code'] = $device_cod;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $map['create_by'] = array('eq',$this->office_id);
        $data['dgi_id'] = $device_group_id;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $res = M('device_relation_group')->where($map)->save($data);
        if($res){
            echo json_encode(array('msg'=>200));
        }else{
            echo json_encode(array('msg'=>500));
        }
    }
    /*=======个人信息 ]]====*/
    /*======设备安装  [[===*/
    public function package(){
        $query_group = M('deivce_group_info')
            ->where(array('office_id'=>$this->office_id,'del_flag'=>0))
            ->order('create_date desc')
            ->select();
        $Capital = array(
            array('A'),array('B'),array('C'),array('D'), array('E'),array('F'),
            array('G'),array('H'),array('I'),array('J'), array('K'),array('L'),
            array('M'),array('N'),array('O'),array('P'), array('Q'),array('R'),
            array('S'),array('T'),array('U'),array('V'), array('W'),array('X'),
            array('Y'),array('Z')
        );
        $this->assign('Capital',$Capital);
        $this->assign("openid",$this->openid);
        $this->assign('query_group',$query_group);
        $this->display();
    }
    /*======设备安装  ]]===*/
    //我的二维码
    public function my_qroce(){
        $this->assign("openid",$this->openid);
        $this->display();
    }
    public function chengxu(){
        echo json_encode(array('msg'=>200));
    }
    /*
     * 用户添加
     * author chw
     * date 2017-5-19
     * */
    public function users_add(){
        $this->assign("openid",$this->openid);
        $this->display();
    }
    /*
     * 用户添加提交
     * author chw
     * date 2017-5-19
     * */
    public function add_post_user(){
        if(IS_POST){
            $model = M('sys_user');
            $openid = trim($_POST['openid']);
            $login_name = trim($_POST['login_name']);
            $phone = trim($_POST['phone']);
            $remarks = trim($_POST['remarks']);
            $is_fencheng = trim($_POST['is_fencheng']);
            $name = trim($_POST['name']);
            $password = trim($_POST['password']);
            $user = $model->where(array('del_flag'=>0,'openid'=>trim($openid)))->find();
            $user_id_resu = $model
                ->where(array('del_flag'=>0,'phone'=>$_POST['phone']))->find();
            $user_id_resu1 = $model
                ->where(array('del_flag'=>0,'login_name'=>$_POST['login_name']))->find();
            if($user_id_resu){
                echo json_encode(array('code'=>500,'message'=>'电话已被占用!'));
                exit();
            }
            if($user_id_resu1){
                echo json_encode(array('code'=>500,'message'=>'登录名已被占用!'));
                exit();
            }
            //1代表分成0不分成
            if($is_fencheng == '1'){
                $data['is_into'] = 1;
            }else{
                $data['is_into'] = 0;
            }
            $data['pre_userid'] = $user['id'];
            $data['company_id'] = $user['company_id'];
            $data['office_id'] = $user['office_id'];
            $data['login_name'] = $login_name;
            $data['name'] = $name;
            $data['password'] = "###".md5(md5('lRRTx9DRZYZOq1k7Yk'.$password));
            $data['user_pass'] = "###".md5(md5('lRRTx9DRZYZOq1k7Yk'.$password));
            $data['phone'] = $phone;
            $data['mobile'] = $phone;
            $data['remarks'] = $remarks;
            $data['login_flag'] = 1;
            $data['user_type'] = 1;
            $data['id'] = generateNum();
            $data['no'] = rand(100,10000);
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $resu = $model->add($data);
            if($resu){
                $user_id = $model
                    ->where(array('del_flag'=>0,'phone'=>$_POST['phone'],'login_name'=>$_POST['login_name']))->find();
                //数据表没有就添加角色
                $role_id = M('sys_user_role')->where(array('user_id'=>$user['id']))->getField('role_id');
                $rose['user_id'] = $user_id['id'];
                $rose['role_id'] = $role_id;
                M('sys_user_role')->add($rose);
                $wher['del_flag'] = '0';
                $wher['phone'] =$_POST['phone'];
                $wher['login_name'] =$_POST['login_name'];

                if(empty($user['prev_user'])){
                    $dar['prev_user'] = $user['id'].",";
                } else {
                    $dar['prev_user']=$user['prev_user'].$user['id'].",";
                }
                $model->where($wher)->save($dar);
                echo json_encode(array('code'=>200,'message'=>'添加成功!'));
            } else {
                echo json_encode(array('code'=>500,'message'=>'添加失败!'));
            }
        }
    }
    //用户列表
    public function users_list(){
        $users = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        $list = M('sys_user')->where(array('del_flag'=>0,'pre_userid'=>$users['id']))->select();
        //echo M('sys_user')->getLastSql();die;
        $this->assign("openid",$this->openid);
        $this->assign("list",$list);
        $this->display();
    }
    //上传用户头像
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
            //删除以前的图片文件
            $photo = M('sys_user')->where(array('openid'=>$id,'del_flag'=>0))->getField('photo');
            $str = strstr($photo,'./');
            @unlink($str);
            $urls=array();
            foreach($img as $v){
                $mediaid=$v;
                $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
                $fileInfo = downloadWeixinFile($url);
                $filename = $dir."/".getSn().".jpg";//取名字
                saveWeixinFile($filename, $fileInfo["body"]);
                $dataed['photo'] = C('site_url').$filename;
                $uid = M('sys_user')->where(array('openid'=>$id,'del_flag'=>0))
                    ->save($dataed);
            }
            $uid = M('sys_user')->where(array('openid'=>$id,'del_flag'=>0))
                ->find();
            echo $uid['photo'];
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
}