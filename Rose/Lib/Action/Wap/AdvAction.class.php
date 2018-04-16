<?php
class AdvAction extends V_2RoseBaseAction {
    //广告商
    public function adv(){
        if($this->type == 1){
            echo '<script>alert("你还不是广告商");</script>';die;
        }
        $scan_code = $_GET['scan_code'];
        $this->assign('scan_code',$scan_code);
        $this->display();
    }
    //添加广告
    public function adv_ajax(){
        if(IS_POST){
            $title = trim($_POST['title']);
            if(empty($title))exit;
            $image = trim($_POST['image']);
            $scan_code = trim($_POST['scan_code']);
            $quotient_id = trim($_POST['quotient_id']);
            $user_id = trim($_POST['user_id']);
            $weixin_alipay_type = trim($_POST['weixin_alipay_type']);
            $url = trim($_POST['url']);
            //判断黄玫瑰金额是否足够
            $yellow_count = M('rose_user_info')->where(array('id'=>$quotient_id,'del_flag'=>0))->sum('yellow_rose');
            if(intval($yellow_count) <= 0){
                exit(json_encode(array('code'=>201,'msg'=>'黄玫瑰余额不足，请充值')));
            }
            $data['id'] = generateNum();
            $data['title'] = $title;
            $data['url'] = $url;
            $data['image'] = $image;
            $data['audit_status'] = 0;
            $data['online'] = 0;
            $data['show_number'] = 0;
            $data['click_number'] = 0;
            $data['consume_number'] = 0;
            $data['quotient_id'] = $quotient_id;
            $data['create_date'] = date('Y-m-d H:i:s',time());
            $data['update_date'] = date('Y-m-d H:i:s',time());
            $data['create_by'] = $quotient_id;
            $data['one_number'] = 0;
            $cid = M('rose_eco_advertising_info')->add($data);
            if($cid){
                exit(json_encode(array('code'=>200,'msg'=>'提交成功',
                    'url'=>U('adv_list',array(
                        'weixin_alipay_type'=>$weixin_alipay_type,
                        'user_id'=>$user_id,
                        'quotient_id'=>$quotient_id,'scan_code'=>$scan_code)))));
            } else {
                exit(json_encode(array('code'=>202,'msg'=>'提交失败')));
            }
        }
    }
    //导流广告列表
    public function adv_list(){
        $model = M('rose_user_info');
        $where['buyer_id'] = $this->user_id;
        $where['_logic'] = 'or';
        $where['openid'] = $this->user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $rose = $model->where($map)->find();
        if(IS_POST){
            $n=$_POST['n']*20;
            $give_list = M('rose_eco_advertising_info')
                ->where(array('del_flag'=>0,'quotient_id'=>$rose['id']))
                ->order('create_date desc')
                ->limit($n,20)
                ->select();
            $this->assign('give_list',$give_list);
            $html=$this->fetch('./tpl/Wap/default/Adv_adv_list_page.html');
            exit($html);
        } else {
            $give_list = M('rose_eco_advertising_info')
                ->where(array('del_flag'=>0,'quotient_id'=>$rose['id']))
                ->order('create_date desc')
                ->limit(20)
                ->select();
            $this->assign('give_list',$give_list);
            $this->display();
        }
    }
    //广告商个人中心
    public function personal(){
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
}