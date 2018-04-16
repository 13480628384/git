<?php
class V_2RoseAction extends V_2RoseBaseAction {
    //注册进来到会员个人资料
    public function vip_personal(){
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
        $this->display();
    }
    //云网余额
    public function yuncount(){
        $this->display();
    }
    //充值
    public function pay(){

    }
    //玫瑰空间
    public function space(){

    }

    //生态商
    public function quotient(){
        $this->display();
    }
    //更改会员信息
    public function update_vip(){
        $this->display();
    }
    //赠送列表
    public function give_list(){
        $model = M('rose_user_info');
        $where['buyer_id'] = $this->user_id;
        $where['_logic'] = 'or';
        $where['openid'] = $this->user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $rose = $model->where($map)->find();
        if(IS_POST){
            $n=$_POST['n']*20;
            $give_list = M('rose_user_info')->alias('rui')
                ->join('rose_gift_of_rose rgor on rgor.give_quotient_id=rui.id')
                ->where(array('rui.del_flag'=>0,'rgor.del_flag'=>0,'rgor.quotient_id'=>$rose['id']))
                ->field('rui.nickname,rgor.total,rgor.create_date,rgor.content')
                ->order('rgor.create_date desc')
                ->limit($n,20)
                ->select();
            $this->assign('give_list',$give_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2_give_list_page.html');
            exit($html);
        } else {
            $give_list = M('rose_user_info')->alias('rui')
                ->join('rose_gift_of_rose rgor on rgor.give_quotient_id=rui.id')
                ->where(array('rui.del_flag'=>0,'rgor.del_flag'=>0,'rgor.quotient_id'=>$rose['id']))
                ->field('rui.nickname,rgor.total,rgor.create_date,rgor.content')
                ->order('rgor.create_date desc')
                ->limit(20)
                ->select();
            $this->assign('give_list',$give_list);
            $this->display();
        }
    }
    //我的玫瑰花瓶
    public function vash(){
        $id = trim($_GET['id']);
        $rose = M('rose_user_info')->where(array('del_flag'=>0,'id'=>$id))->find();
        $url = 'http://'.$_SERVER['HTTP_HOST'];
        $this->assign('rose',$rose);
        $this->assign('id',$id);
        $this->assign('url',$url);
        $this->display();
    }
    //我的玫瑰花瓶
    public function my(){
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        $send_id = '';
        $weixin_alipay_type = '';
        if( strpos($typesd,'MicroMessenger')>0 ){
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $send_id = $openid;
            $weixin_alipay_type = 'wechat';
        } elseif(strpos($typesd,'AlipayClient') > 0) {
            $buyer_id = get_user_info();
            $send_id = $buyer_id;
            $weixin_alipay_type = 'alipay';
        } else {
            exit();
        }
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
        $scan_code = $_GET['scan_code'];
        //$send_id = '2088802658990276';
        $this->assign('send_id',$send_id);
        $this->assign('weixin_alipay_type_al',$weixin_alipay_type);
        $this->assign('scan_code',$scan_code);
        $this->display();
    }
    //收到的红玫瑰
    public function get_red(){
        $id = trim($_GET['id']);
        if(IS_POST){
            $n=$_POST['n']*20;
            $id = $_POST['id'];
            $give_list = M('rose_user_info')->alias('rui')
                ->join('rose_gift_of_rose rgor on rgor.quotient_id=rui.id')
                ->where(array('rui.del_flag'=>0,'rgor.del_flag'=>0,'rgor.give_quotient_id'=>$id))
                ->field('rui.nickname,rgor.total,rgor.create_date,rgor.content')
                ->order('rgor.create_date desc')
                ->limit($n,20)
                ->select();
            $this->assign('give_list',$give_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2_get_red_list_page.html');
            exit($html);
        } else {
            $give_list = M('rose_user_info')->alias('rui')
                ->join('rose_gift_of_rose rgor on rgor.quotient_id=rui.id')
                ->where(array('rui.del_flag'=>0,'rgor.del_flag'=>0,'rgor.give_quotient_id'=>$id))
                ->field('rui.nickname,rgor.total,rgor.create_date,rgor.content')
                ->order('rgor.create_date desc')
                ->limit(20)
                ->select();
            $this->assign('give_list',$give_list);
            $this->assign('id',$id);
            $this->display();
        }
    }
}