<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DeviceIncomeController extends AdminbaseController{

    protected $Device_consume_weixin_rec;

    public function _initialize() {
        parent::_initialize();
        $this->Device_consume_weixin_rec = D("Common/Device_consume_weixin_rec");
    }

    // 设备收益列表
    public function index(){
        $where['del_flag'] = 0;
        $where['type'] = 0;
        $where['status'] = 1;
        $cur_date = date('Y-m-d 00:00:00',time());
        $where['create_date'] = array('egt',$cur_date);
        $Area = $this->Device_consume_weixin_rec
            ->where($where)
            ->field("device_command,sum(consume_account) as count,create_date")
            ->group("device_command")
            ->order("count DESC")
            ->select();
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($Area as $k => $v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($Area[$k]);
                }
            }
        }
        //判断今天是否已经插入设备的总收益
        foreach($Area as $k => $v){
            $cur_date = date('Y-m-d 00:00:00',time());
            $wheres['create_date'] = array('egt',$cur_date);
            $wheres['device_command'] = $v['device_command'];
            if(M('device_today_income')->where($wheres)->find()){
                $datas['count'] = $v['count'];
                M('device_today_income')->
                where(array('device_command'=>$v['device_command']))->save($datas);
            } else {
                $datas['id'] = generateNum();
                $datas['device_command'] = $v['device_command'];
                $datas['count'] = $v['count'];
                $datas['create_date'] = $v['create_date'];
                M('device_today_income')->add($datas);
            }

        }
        $this->assign("list",$Area);
        $this->display();
    }
    //微信区域排名
    public function area(){
        $count=$this->Device_consume_weixin_rec
            ->alias('dcw')
            ->join('left join ju_area ja on ja.id=dcw.area_id')
            ->where(array('dcw.type'=>'0','dcw.command_status'=>'2','dcw.status'=>'1'))
            ->field('sum(dcw.consume_account) as count,dcw.id,dcw.create_date,
            ja.name')
            ->group("ja.name")
            ->order('count desc')
            ->select();
        //市区或省份显示
        if($this->area_type == '2' || $this->area_type == '4'){
            foreach($count as $k=>$v){
                $parent_ids = M('area')->where(array('id'=>$v['area_id']))->getField('parent_ids');
                $array_parent = explode(',',$parent_ids);
                if(!in_array(session('area_id'),$array_parent)){
                    unset($count[$k]);
                }
            }
        }
        $this->assign('list',$count);
        $this->display();
    }
}