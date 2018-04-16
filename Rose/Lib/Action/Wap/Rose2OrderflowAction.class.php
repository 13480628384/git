<?php
class Rose2OrderflowAction extends Rose2BaseAction {
    //今天消费记录流水
    public function index(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
            /*$where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dcr.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dcr.create_date'] = array('egt', $today);
            $index = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("dgi.group_name,dcr.deivce_command,sum(dcr.consume_account) count,dgi.id")
                ->group("dgi.group_name")
                ->order("dcr.create_date DESC")
                ->limit($n,"10")
                ->select();*/
            $index = $model->where(array(
                'create_by'=>$this->user_id,
                'consume_status'=>'1',
                'command_status'=>'2',
                'transfer_status'=>'0',
                'del_flag'=>0,
                'create_date'=>array('egt', $today)
            ))->field("deivce_command,consume_account,type,command_status,create_date")
                ->order('create_date desc')
                ->limit($n,10)
                ->select();
            $this->assign('index',$index);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_index_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
            $balance = $model->where(array(
                'create_by'=>$this->user_id,
                'consume_status'=>'1',
                'command_status'=>'2',
                'transfer_status'=>'0',
                'del_flag'=>0,
                'create_date'=>array('egt', $today)
            ))->field("deivce_command,consume_account,type,command_status,create_date")
                ->order('create_date desc')
                ->limit(10)
                ->select();
            //echo $model->getLastSql();die;
            /*$where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['dcr.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dcr.create_date'] = array('egt', $today);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("dgi.group_name,dcr.deivce_command,sum(dcr.consume_account) count,dgi.id")
                ->group("dgi.group_name")
                ->order("dcr.create_date DESC")
                ->limit(10)
                ->select();*/
            $this->assign('openid', $this->openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //每日查看群组的所有设备号
    public function today_group_device_ids(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $group_id=$_POST['group_id'];
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dcr.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dgi.id'] = $group_id;
            $where['dcr.create_date'] = array('egt', $today);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,sum(dcr.consume_account) count,di.id,di.device_type,drg.group_word")
                ->group("dcr.deivce_command")
                ->order("dcr.create_date DESC")
                ->limit($n,'10')
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
            $this->assign('balance', $balance);
            $this->assign('group_id', $group_id);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_device_ids_page.html');
            exit($html);
        } else {
            $group_id = trim($_GET['group_id']);
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.del_flag'] = 0;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dgi.id'] = $group_id;
            $where['dcr.create_date'] = array('egt', $today);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,sum(dcr.consume_account) count,di.id,di.device_type,drg.group_word")
                ->group("dcr.deivce_command")
                ->order("dcr.create_date DESC")
                ->limit(10)
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
            $group_name = M('deivce_group_info')->where(array('del_falg'=>0,'office_id'=>$this->office_id,'id'=>$group_id))
                ->getField('group_name');
            $this->assign('group_name', $group_name);
            $this->assign('openid', $this->openid);
            $this->assign('balance', $balance);
            $this->assign('group_id', $group_id);
            $this->display();
        }
    }
    //每日查看设备列表所有的信息
    public function today_group_device_deteil(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $di_id = trim($_POST['di_id']);
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
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
            $where['dcr.di_id'] = $di_id;
            $where['dcr.create_date'] = array('egt', $today);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("drg.group_word,di.scan_code,dcr.create_date,dcr.consume_status,sum(dcr.consume_account) consume_account,di.device_type")
                ->group('dcr.create_date,dcr.cmd_uuid')
                ->order("dcr.create_date DESC")
                ->limit($n,"20")
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
            $this->assign('balance', $balance);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_deteil_page.html');
            exit($html);
        } else {
            $di_id = trim($_GET['di_id']);
            $model = M('device_consume_rec');
            $today = date('Y-m-d 00:00:00');
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
            $where['dcr.di_id'] = $di_id;
            $where['dcr.create_date'] = array('egt', $today);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("drg.group_word,di.scan_code,dcr.create_date,dcr.consume_status,sum(dcr.consume_account) consume_account,di.device_type")
                ->group('dcr.create_date,dcr.cmd_uuid')
                ->order("dcr.create_date DESC")
                ->limit("20")
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
            $this->assign('balance', $balance);
            $this->assign('di_id', $di_id);
            $this->display();
        }
    }
    /*========每周流水 [[=======*/
    public function week_index(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $model = M('device_consume_rec');
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $index = $model->where(array(
                'create_by'=>$this->user_id,
                'consume_status'=>'1',
                'command_status'=>'2',
                'transfer_status'=>'0',
                'del_flag'=>0,
                'create_date'=>array('egt', $serven)
            ))->field("deivce_command,consume_account,type,command_status,create_date")
                ->order('create_date desc')
                ->limit($n,10)
                ->select();
            $this->assign('index',$index);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_week_index_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $balance = $model->where(array(
                'create_by'=>$this->user_id,
                'consume_status'=>'1',
                'command_status'=>'2',
                'transfer_status'=>'0',
                'del_flag'=>0,
                'create_date'=>array('egt', $serven)
            ))->field("deivce_command,consume_account,type,command_status,create_date")
                ->order('create_date desc')
                ->limit(10)
                ->select();
            $this->assign('openid', $this->openid);
            $this->assign('balance', $balance);
            $this->display();
        }
    }
    //群组设备流水
    public function serven_group_device_ids(){
        if(IS_POST){
            $n=$_POST['n']*10;
            $group_id=$_POST['group_id'];
            $model = M('device_consume_rec');
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
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $where['dcr.create_date'] = array('egt', $serven);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,sum(dcr.consume_account) count,di.id,di.device_type,drg.group_word")
                ->group("dcr.deivce_command")
                ->order("dcr.create_date DESC")
                ->limit($n,'10')
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
            $this->assign('balance', $balance);
            $this->assign('group_id', $group_id);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_week_device_ids_page.html');
            exit($html);
        } else {
            $group_id = trim($_GET['group_id']);
            $model = M('device_consume_rec');
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.del_flag'] = 0;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dgi.id'] = $group_id;
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $where['dcr.create_date'] = array('egt', $serven);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,sum(dcr.consume_account) count,di.id,di.device_type,drg.group_word")
                ->group("dcr.deivce_command")
                ->order("dcr.create_date DESC")
                ->limit(10)
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
            $group_name = M('deivce_group_info')->where(array('del_falg'=>0,'office_id'=>$this->office_id,'id'=>$group_id))
                ->getField('group_name');
            $this->assign('group_name', $group_name);
            $this->assign('openid', $this->openid);
            $this->assign('balance', $balance);
            $this->assign('group_id', $group_id);
            $this->display();
        }
    }
    public function week_group_device_deteil(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $di_id = trim($_POST['di_id']);
            $model = M('device_consume_rec');
            $where['di.office_id'] = $this->office_id;
            $where['di.del_flag'] = 0;
            $where['dgi.office_id'] = $this->office_id;
            $where['dcr.consume_status'] = 1;
            $where['dcr.command_status'] = 2;
            $where['dcr.create_by'] = $this->user_id;
            $where['drg.status'] = 1;
            $where['drg.del_flag'] = 0;
            $where['dcr.del_flag'] = 0;
            $where['drg.create_by'] = $this->office_id;
            $where['dgi.del_flag'] = 0;
            $where['dcr.di_id'] = $di_id;
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $where['dcr.create_date'] = array('egt', $serven);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,dcr.create_date,dcr.consume_status,sum(dcr.consume_account) consume_account,di.device_type,drg.group_word")
                ->group("dcr.create_date,dcr.cmd_uuid")
                ->order("dcr.create_date DESC")
                ->limit($n,"20")
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
            $this->assign('balance', $balance);
            $this->assign('openid', $this->openid);
            $html=$this->fetch('./tpl/Wap/default/Rose2Orderflow_week_deteil_page.html');
            exit($html);
        } else {
            $di_id = trim($_GET['di_id']);
            $model = M('device_consume_rec');
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
            $where['dcr.di_id'] = $di_id;
            $yesterday = strtotime(date('Ymd',strtotime('-7 day')));
            $serven = date('Y-m-d H:i:s',$yesterday);
            $where['dcr.create_date'] = array('egt', $serven);
            $balance = $model->alias('dcr')
                ->join("LEFT JOIN device_relation_group drg ON drg.di_id = dcr.di_id")
                ->join("LEFT JOIN device_info di ON di.id = drg.di_id")
                ->join("LEFT JOIN deivce_group_info dgi ON dgi.id = drg.dgi_id")
                ->where($where)
                ->field("di.scan_code,dcr.create_date,dcr.consume_status,sum(dcr.consume_account) consume_account,di.device_type,drg.group_word")
                ->group("dcr.create_date,dcr.cmd_uuid")
                ->order("dcr.create_date DESC")
                ->limit("20")
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
            $this->assign('balance', $balance);
            $this->assign('di_id', $di_id);
            $this->display();
        }
    }
    /*========每周流水 ]]=======*/
}