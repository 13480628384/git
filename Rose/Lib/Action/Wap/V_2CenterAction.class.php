<?php
/*
 * author chw
 * date 2016-11-28
 * 玫瑰会员中心
 * */
class V_2CenterAction extends V_2BaseAction{
    public function _initialize()
    {
        parent::_initialize();
        //判断是否会员
        $userModel = M('rose_user_info');
        $where['openid'] = $this->user_id;
        $where['_logic'] = 'or';
        $where['buyer_id'] = $this->user_id;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $userinfoid = $userModel->where($map)->find();
        if($userinfoid){
            //更新用户信息
            if($this->borwser == 'wechat'){
                $bor['openid'] = $this->user_id;
                $bor['update_date'] = date('Y-m-d H:i:s');
                $weiopenid = $userModel->where($map)->save($bor);
            } else {
                $bor['buyer_id'] = $this->user_id;
                $bor['update_date'] = date('Y-m-d H:i:s');
                $weibuy_id = $userModel->where($map)->save($bor);
            }
            //存在用户
            session('user_id',$userinfoid['id']);
        } else {
            header('Location:'.U('Bind/binding'));
            exit();
        }
        //用户是否注册成为生态商
        //用户还没有注册成为生态商，跳转到注册页面

    }
    /*
     * 会员中心页
     * */
    public function index(){

        $this->display();
    }
    /*
     *
     * */
}
?>