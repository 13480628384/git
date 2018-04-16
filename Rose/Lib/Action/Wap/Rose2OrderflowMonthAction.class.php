<?php
class Rose2OrderflowMonthAction extends Rose2BaseAction {
    public function index(){
        if(IS_POST){die;
            $n=$_POST['n']*10;
            $model = M('device_consume_rec');
            $index = $model->where(array(
                'create_by'=>$this->user_id,
                'del_flag'=>0
            ))->field("year(create_date) year ,sum(consume_account) count,month(create_date) month")
                ->group("year(create_date),month(create_date)")
                ->order('create_date desc')
                ->limit($n,10)
                ->select();
            $this->assign('index',$index);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2OrderflowMonth_month_index_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $balance = $model->where(array(
                'create_by'=>$this->user_id,
                'consume_status'=>'1',
                'command_status'=>'2',
                'transfer_status'=>'0',
                'del_flag'=>0
            ))->field("year(create_date) year ,sum(consume_account) count,month(create_date) month")
                ->group("year(create_date),month(create_date)")
                ->order('create_date desc')
                ->limit(5)
                ->select();
            $this->assign('openid', $this->openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //每月查看群组的所有设备号
    public function group_details_list(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $month = trim($_POST['month']);
            $model = M('device_consume_rec');
            $index = $model->query("SELECT `deivce_command`,`consume_account`,`type`,`command_status`,`create_date` FROM
    `device_consume_rec` WHERE ( `create_by` = '$this->user_id' ) and consume_status=1 and command_status=2  and transfer_status=0 AND ( `del_flag` = 0 ) AND month(create_date)='$month' ORDER BY create_date desc LIMIT $n,10");
            $this->assign('balance',$index);
            $this->assign('month',$month);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2OrderflowMonth_month_group_details_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $month = trim($_GET['month']);
            $balance = $model->query("SELECT `deivce_command`,`consume_account`,`type`,`command_status`,`create_date` FROM
    `device_consume_rec` WHERE ( `create_by` = '$this->user_id' ) and consume_status=1 and command_status=2  and transfer_status=0 AND ( `del_flag` = 0 ) AND month(create_date)='$month' ORDER BY create_date desc LIMIT 10");
            $this->assign('openid', $this->openid);
            $this->assign('month', $month);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //每月查看设备列表所有的信息
    public function month_group_device_deteil_id(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $group_id = trim($_POST['group_id']);
            $month = trim($_POST['month']);
            $model = M('device_consume_rec');
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dcr.del_flag'] = 0;
            $where['dgi.id'] = $group_id;
            $where['month(dcr.create_date)'] = $month;
            $index = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,dcr.create_date,sum(dcr.consume_account) consume_account,di.device_type,drg.group_word")
                ->group("dcr.create_date,dcr.cmd_uuid")
                ->order("dcr.create_date DESC")
                ->limit($n,"20")
                ->select();
            foreach($index as $key=>$v){
                //按摩椅
                if($v['device_type'] == 4){
                    $index[$key]['code'] = preg_replace('/0/','6',substr($v['scan_code'],6,5),1);
                }
                //洗衣机
                elseif($v['device_type'] == 5){
                    $index[$key]['code'] = preg_replace('/0/','7',substr($v['scan_code'],6,5),1);
                }
                //娃娃机
                elseif($v['device_type'] == 1){
                    $index[$key]['code'] = preg_replace('/0/','8',substr($v['scan_code'],6,5),1);
                }else {
                    $index[$key]['code'] = preg_replace('/0/','1',substr($v['scan_code'],6,5),1);
                }
            }
            $this->assign('balance',$index);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2OrderflowMonth_month_group_details_id_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $group_id = trim($_GET['group_id']);
            $month = trim($_GET['month']);
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['dcr.del_flag'] = 0;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dgi.id'] = $group_id;
            $where['month(dcr.create_date)'] = $month;
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,dcr.create_date,sum(dcr.consume_account) consume_account,di.device_type,drg.group_word")
                ->group("dcr.create_date,dcr.cmd_uuid")
                ->order("dcr.create_date DESC")
                ->limit(20)
                ->select();
            foreach($balance as $key=>$v){
                //按摩椅
                if($v['device_type'] == 4){
                    $balance[$key]['code'] = preg_replace('/0/','6',substr($v['scan_code'],6,5),1);
                }
                //洗衣机
                elseif($v['device_type'] == 5){
                    $balance[$key]['code'] = preg_replace('/0/','7',substr($v['scan_code'],6,5),1);
                }
                //娃娃机
                elseif($v['device_type'] == 1){
                    $balance[$key]['code'] = preg_replace('/0/','8',substr($v['scan_code'],6,5),1);
                }else {
                    $balance[$key]['code'] = preg_replace('/0/','1',substr($v['scan_code'],6,5),1);
                }
            }
            $this->assign('openid', $this->openid);
            $this->assign('month', $month);
            $this->assign('group_id', $group_id);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
}