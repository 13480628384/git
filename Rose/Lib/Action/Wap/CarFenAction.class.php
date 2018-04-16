<?php
class CarFenAction extends Rose2BaseAction {
    //日和月流水报表
    public function index(){
        //今日报表
        $today = date('Y-m-d 00:00:00');
        $one = M('car_pay')->where(array(
            'create_by'=>$this->user_id,
            'status'=>'1',
            'create_date'=>array('egt', $today)
        ))->field("device_command,account,create_date")
            ->order('create_date desc')
            ->select();
        $week = M('car_pay')->where(array(
            'create_by'=>$this->user_id,
            'status'=>'1',
        ))->field("year(create_date) year ,sum(account) count,month(create_date) month")
            ->group("year(create_date),month(create_date)")
            ->order('create_date desc')
            ->select();
        $this->assign('one',$one);
        $this->assign('week',$week);
        $this->display();
    }
}