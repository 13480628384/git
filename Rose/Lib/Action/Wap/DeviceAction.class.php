<?php
class DeviceAction extends Rose2BaseAction {
    //检查设备是否存在
    public function hard_device_code_exists(){
        if(empty($this->office_id)){
            exit;
        }
        $model = M('device_info');
        $device_command = $_POST['device_command'];
        $where['device_command'] = $device_command;
        $where['_logic'] = 'or';
        $where['device_code'] = $device_command;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $map['office_id'] = array('eq',$this->office_id);
        $res = $model->where($map)->find();
        if($res){
            echo json_encode(array('msg'=>1,'datas'=>$res));
        }else{
            echo json_encode(array('msg'=>2));
        }
    }
    //设备安装
    public function submit_device_infos(){
        if(!isset($this->office_id)){
            exit;
        }
        $hard_device_code 	= $_POST['hard_device_code'];//二维码
        $device_group_id 	= $_POST['device_group_id'];//群组id
        $device_group_code  = trim($_POST['device_group_code']);
        $ords 				= $_POST['ords'];
        $create_date 				= $_POST['create_date'];
        //判断是否重复安装
        $where['device_command'] = $hard_device_code;
        $where['_logic'] = 'or';
        $where['device_code'] = $hard_device_code;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $relation = M('device_relation_group')->where($map)->find();
        if($relation){
            echo json_encode(array('msg'=>4));
            exit;
        }
        //添加默认群组
        $gid_if = M("deivce_group_info")->where(array('office_id'=>$this->office_id))->find();
        if(!$gid_if){
            if($device_group_id == 1) {
                $group_name = '群组1';
            } else {
                $group_name = '群组2';
            }
            $group['id'] = generateNum();
            $group['office_id'] = $this->office_id;
            $group['group_name'] = $group_name;
            $group['ords'] = rand(1,6);
            $group['create_by'] = $this->office_id;
            $group['update_by'] = $this->office_id;
            $group['create_date'] = date('Y-m-d H:i:s',time());
            $group['update_date'] = date('Y-m-d H:i:s',time());
            $group_id = M('deivce_group_info')->add($group);
        }
        $gid = M("deivce_group_info")->where(array('office_id'=>$this->office_id))->find();
        $model = M('device_info');
        $model->startTrans();
        $data['device_status'] = 1;
        //$data['group_word'] = $device_group_code;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['install_type'] = 1;
        $where['device_command'] = $hard_device_code;
        $where['_logic'] = 'or';
        $where['device_code'] = $hard_device_code;
        $map['_complex'] = $where;
        $map['del_flag'] = array('eq',0);
        $rese = $model->where($map)->save($data);
        $device_command = $model->where($map)->find();
        if($device_command){
            $res['id'] = md5(uniqid());
            if(empty( $gid_if )) {
                $res['dgi_id'] = $gid['id'];
            }else{
                $res['dgi_id'] = $device_group_id;
            }
            if($device_command['device_type'] == 5){
                $res['charger'] = '1=5-2=18-3=31-4=43';
            }
            $res['group_word'] = $device_group_code;
            $res['di_id'] = $device_command['id'];
            $res['device_code'] = $device_command['device_code'];
            $res['device_command'] = $device_command['device_command'];;
            $res['device_type'] = $device_command['device_type'];
            $res['ANM'] = $device_command['ANM'];
            $res['update_by'] = $this->office_id;
            $res['create_by'] = $this->office_id;
            $res['ords'] = rand(1,99);
            $res['status'] = 1;
            $res['create_date'] = $create_date.' '.date('H:i:s',time());;
            $res['update_date'] = date('Y-m-d H:i:s',time());
            $id = M('device_relation_group')->add($res);
            if($id && $rese){
                $model->commit();
                echo json_encode(array('msg'=>1));
            }else{
                $model->rollback();
                echo json_encode(array('msg'=>2));
            }
        }else{
            echo json_encode(array('msg'=>3));
        }
    }
    /*=======设备列表 [[====*/
    public function device_list(){
        if(!isset($this->office_id)){
            exit;
        }

       $users = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        if($users['company_id'] == '2017041315_E15FAA1AA148A91061FBA5A92FD89AB5'){
            if($users['is_into'] == '1'){
                //设备转移成功，但是也可以看到下级分成用户的设备
                $userslist = M('sys_user')->where(array('del_flag'=>0,'is_into'=>'1'))->select();
                $is_into = array();
                foreach($userslist as $k => $v){
                    if($v['pre_userid'] == $users['id']){
                        $is_into[] = $v['id'];
                        $is_into[] = $users['id'];
                    }else{
                        $is_into[] = $users['id'];
                    }
                }
                $is_into = array_unique($is_into);
                $where['di.owner_id'] = array('in',$is_into);
                //$where['di.owner_id'] = $this->user_id;
            }   else {
                //$where['di.owner_id'] = array('in',$users['prev_user']);
                $userslist = M('sys_user')->where(array('del_flag'=>0))->select();
                $array = array();
                foreach($userslist as $k=>$v){
                    if($v['pre_userid'] == $users['id'] || $v['id'] == $users['id'] ){
                        $array[$k] = $v['id'];
                    }
                }
                $array_string = implode(',',$array);
                $array_two = array();
                $is_user = M('sys_user')->where(array('id'=>array('in',$array_string),'del_flag'=>0))->select();
                foreach ($is_user as $key => $item) {
                    if($item['is_into'] == '0' && $item['id'] !=$users['id']){
                        unset($array_two[$key]);
                    }else{
                        $array_two[$key] = $item['id'];
                    }
                }
                $where['di.owner_id'] = array('in',$array_two);
            }
        }else{
            $where['di.owner_id'] = $this->user_id;
        }
        $where['dgi.del_flag'] = 0;
        //判断redis里面是否有没有，没有就差数据库
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        if(!$redis->get('res')) {
            $res = M('device_relation_group')
                ->join('deivce_group_info dgi on dgi.id = device_relation_group.dgi_id')
                ->join('device_info di on di.id = device_relation_group.di_id')
                ->field('device_relation_group.*,dgi.group_name,di.scan_code')
                ->where($where)
                ->order('device_relation_group.online_status desc')
                ->select();
            $redis->set('res',serialize($res));
            $redis->expire('res',10);
        }
        $res = unserialize($redis->get('res'));
        //echo M('device_relation_group')->getLastSql();die;
        foreach($res as $key=>$v){
            //按摩椅
            if($v['device_type'] == 4){
                $res[$key]['code'] = substr($v['scan_code'],6);
            }
            //洗衣机
            elseif($v['device_type'] == 5){
                $res[$key]['code'] = substr($v['scan_code'],6);
            }
            //娃娃机
            elseif($v['device_type'] == 1){
                $res[$key]['code'] = substr($v['scan_code'],6);
            }else{
                $res[$key]['code'] = substr($v['scan_code'],6);
            }
        }
        $this->assign('res',$res);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    public function update_price(){
        $price = trim(intval($_POST['price']));
        $id = trim($_POST['id']);
        $di_id = trim($_POST['di_id']);
        $model = M('device_info');
        $data['pay_price'] = $price;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $datas['pay_price'] = $price;
        $datas['update_date'] = date('Y-m-d H:i:s',time());
        $cid = $model->where(array('id'=>$di_id,'del_flag'=>0))->save($data);
        $uid = M('device_relation_group')->where(array('id'=>$id,'del_flag'=>0))->save($datas);
        if($cid && $uid){
            echo json_encode(array('result_code'=>1));
        } else {
            echo json_encode(array('result_code'=>2));
        }
    }
    /*=======设备列表 ]]====*/
    /*=======群组列表 [[====*/
    public function group_list(){
        $users = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        if($users['company_id'] == '2017041315_E15FAA1AA148A91061FBA5A92FD89AB5'){
            if($users['is_into'] == '1'){
                $userslist = M('sys_user')->where(array('del_flag'=>0,'is_into'=>'1'))->select();
                $is_into = array();
                foreach($userslist as $k => $v){
                    if($v['pre_userid'] == $users['id']){
                        $is_into[] = $v['id'];
                        $is_into[] = $users['id'];
                    }else{
                        $is_into[] = $users['id'];
                    }
                }
                $is_into = array_unique($is_into);
                $where['owner_id'] = array('in',$is_into);
                //$where['di.owner_id'] = $this->user_id;
            }   else {
                //$where['di.owner_id'] = array('in',$users['prev_user']);
                $userslist = M('sys_user')->where(array('del_flag'=>0))->select();
                $array = array();
                foreach($userslist as $k=>$v){
                    if($v['pre_userid'] == $users['id'] || $v['id'] == $users['id'] ){
                        $array[$k] = $v['id'];
                    }
                }
                $array_string = implode(',',$array);
                $array_two = array();
                $is_user = M('sys_user')->where(array('id'=>array('in',$array_string),'del_flag'=>0))->select();
                foreach ($is_user as $key => $item) {
                    if($item['is_into'] == '0' && $item['id'] !=$users['id']){
                        unset($array_two[$key]);
                    }else{
                        $array_two[$key] = $item['id'];
                    }
                }
                $where['owner_id'] = array('in',$array_two);
            }
        }else{
            $where['owner_id'] = $this->user_id;
        }
        $where['del_flag'] = 0;
        $res = M('deivce_group_info')
            ->where($where)
            ->order('create_date desc')
            ->select();
        $this->assign('res',$res);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    /*=======群组列表 ]]====*/
    /*=======添加群组 [[====*/
    public function add_group(){
        $this->assign('openid',$this->openid);
        $this->display();
    }
    public function add_device_group_info(){
        if(!isset($this->office_id)){
            exit;
        }
        $gr = M('deivce_group_info')->where(array('group_name'=>$_POST['device_group_name'],'del_flag'=>0))->find();
        if($gr) exit(json_encode(array('msg'=>500)));
        $device_group_name = $_POST['device_group_name'];
        $data['group_name'] = $device_group_name;
        $data['id'] = md5(uniqid());
        $data['ords'] = rand(1,6);
        $data['create_date'] = date('Y-m-d H:i:s',time());
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['create_by'] = $this->office_id;
        $data['owner_id'] = $this->user_id;
        $data['office_id'] = $this->office_id;
        $data['update_by'] = $this->office_id;
        $res = M('deivce_group_info')->add($data);
        if($res){
            echo json_encode(array('msg'=>1,'datas'=>$res));
        }else{
            echo json_encode(array('msg'=>2));
        }
    }
    /*========转移设备【【==========*/
    public function change(){
        /*$res = M('device_relation_group')
            ->where(array('create_by'=>$this->office_id))
            ->order('create_date desc')
            ->select();*/
        $res = M('device_info')
            ->where(array('owner_id'=>$this->user_id,'del_flag'=>0))
            ->order('create_date desc')
            ->select();
        $this->assign('res',$res);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    public function changeing(){
        $ids = $_POST['text1'];
        $user_name = trim($_POST['user_name']);
        $user = M('sys_user')->where(array('phone'=>$user_name,'del_flag'=>'0'))->find();
        if(empty($user)){
            exit(json_encode(array('code'=>201,'msg'=>'用户不存在或请先添加机构，部门，用户后再操作！')));
        }
        if(M('deivce_group_info')->where(array('owner_id'=>$user['id'],'del_flag'=>0))->find()){
            $dgi_id = M('deivce_group_info')->where(array('owner_id'=>$user['id'],'del_flag'=>0))->getField('id');
        } else {
            $new['id'] = generateNum();
            $new['owner_id'] = $user['id'];
            $new['office_id'] = $user['office_id'];
            $new['group_name'] = $user['name'].'群组';
            $new['ords'] = rand(1,50);
            $new['create_by'] = $user['id'];
            $new['create_date'] = date('Y-m-d H:i:s',time());
            $new['update_by'] = $user['id'];
            $res = M('deivce_group_info')->add($new);
        }
        $dgi_id = M('deivce_group_info')->where(array('owner_id'=>$user['id'],'del_flag'=>0))->getField('id');
        $model = M('device_info');
        $model->startTrans();
        $data['owner_id'] = $user['id'];
        $data['office_id'] = $user['office_id'];
        $data['company_id'] = $user['company_id'];
        $data['create_by'] = $user['id'];
        $result = $model->where(array('device_command'=>array('in',$ids)))->save($data);
        $data1['create_by'] = $user['office_id'];
        $data1['update_by'] = $user['office_id'];
        $data1['dgi_id'] = $dgi_id;
        $result3 = M('device_record')->where(array('dev_id'=>array('in',$ids)))->delete();
        $result2 = M('device_relation_group')->where(array('device_command'=>array('in',$ids)))->save($data1);
        if($result && $result2){
            $model->commit();
            exit(json_encode(array('code'=>200,'msg'=>'转移成功')));
        } else {
            exit(json_encode(array('code'=>201,'msg'=>'转移失败')));
        }
    }
    /*========转移设备 】】==========*/
    /*======添加群组 ]]======*/
    /*======修改群组 [[====*/
    public function edit_group(){
        $id = trim($_GET['id']);
        $group_name = trim($_GET['group_name']);
        $this->assign('openid',$this->openid);
        $this->assign('group_name',$group_name);
        $this->assign('id',$id);
        $this->display();
    }
    public function update_device_group_info(){
        if(!isset($this->office_id)){
            exit;
        }
        $gr = M('deivce_group_info')->where(array('group_name'=>$_POST['device_group_name'],'del_flag'=>0))->find();
        if($gr) exit(json_encode(array('msg'=>500)));
        $device_group_id   = $_POST['uid'];
        $device_group_name = $_POST['device_group_name'];
        $data['group_name'] = $device_group_name;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $res = M('deivce_group_info')->where(array('id'=>$device_group_id,'del_flag'=>0))->save($data);
        if($res){
            echo json_encode(array('msg'=>1,'datas'=>$res));
        }else{
            echo json_encode(array('msg'=>2));
        }
    }
    /*======修改群组 ]]====*/
    /*======群组信息 [[====*/
    public function group_detail_list(){
        $id = trim($_GET['id']);
        $group_name = M('deivce_group_info')->where(array('id'=>$id,'del_flag'=>0))->getField('group_name');
        $res = M('device_relation_group')->where(
            array(
                'del_flag'=>0,
                'status'=>1,
                'dgi_id'=>$id
                //'create_by'=>$this->office_id
            )
        )->order('group_word')->select();

        $this->assign('id',$id);
        $this->assign('group_name',$group_name);
        $this->assign('res',$res);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    /*======群组信息 ]]====*/
    /*======群组信息修改 [[====*/
    public function update_group_detail_list(){
        $id = trim($_GET['id']);
        $device_type= trim($_GET['device_type']);
        $res = M('device_relation_group')->where(
            array(
                'del_flag'=>0,
                'status'=>1,
                'id'=>$id
            )
        )->find();
        $group_name = M('deivce_group_info')
            ->where(array('del_flag'=>0,'owner_id'=>$this->user_id))
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
        $this->assign('id',$id);
        $this->assign('group_name',$group_name);
        $this->assign('device_type',$device_type);
        $this->assign('res',$res);
        $this->assign('openid',$this->openid);
        $this->display();
    }
    public function update_device_group_detail(){
        if(!isset($this->office_id)){
            exit;
        }
        $model = M('device_relation_group');
        $model->startTrans();
        $device_group_code = trim($_POST['device_group_code']);
        $ords = trim($_POST['ords']);
        $device_group_id = trim($_POST['device_group_id']);
        $group_id = trim($_POST['group_id']);
        $ANM = trim($_POST['srdata']);
        $charger = trim($_POST['charger']);
        $ANMD = '';
        $chargered = '';
        if(empty($ANM)){
            $ANMD = 0;
        }else{
            $ANMD = $ANM;
        }
        if(empty($charger)){
            $chargered = 0;
        }else{
            $chargered = $charger;
        }
        $data['group_word'] = $device_group_code;
        $data['dgi_id'] = $device_group_id;
        $data['ords'] = $ords;
        $data['ANM'] = $ANMD;
        $data['charger'] = $chargered;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $res = $model
            ->where(array(
                'id'=>$group_id,
                'del_flag'=>0,
            ))->save($data);
        if($res){
            $model->commit();
            echo json_encode(array('msg'=>1,'datas'=>$res));
        }else{
            $model->rollback();
            echo json_encode(array('msg'=>2));
        }
    }
    /*======群组信息修改 ]]====*/

    //洗衣机价格修改
    public function washing(){
        $device_type = $_GET['device_type'];
        $charger = $_GET['charger'];
        $di_id = $_GET['di_id'];
        $openid = $_GET['openid'];
        if(empty($di_id) || empty($openid)){
            exit('出错了，请重新进入');
        }
        $dgi = M('device_relation_group')->where(array('di_id'=>$di_id,'del_flag'=>0,'device_type'=>5))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('charger',$charger);
        $this->assign('openid',$openid);
        $this->assign('di_id',$di_id);
        $this->assign('out',$out);
        $this->assign('device_type',$device_type);
        $this->display();
    }
    /*
     * ================================
     * 电动车价格修改    START
     * ================================
     * */
    public function veh(){
        $device_type = $_GET['device_type'];
        $charger = $_GET['charger'];
        $di_id = $_GET['di_id'];
        $openid = $_GET['openid'];
        $dgi = M('device_relation_group')->where(array('di_id'=>$di_id,'del_flag'=>0,'device_type'=>6))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('charger',$charger);
        $this->assign('openid',$openid);
        $this->assign('di_id',$di_id);
        $this->assign('out',$out);
        $this->assign('device_type',$device_type);
        $this->display();
    }
    public function update_veh(){
        $di_id = $_POST['di_id'];
        $srdata = $_POST['srdata'];
        $openid = $_POST['openid'];
        if(empty($di_id) || empty($srdata)) exit;
        $model = M('device_relation_group');
        $data['charger'] = $srdata;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->where(array('di_id'=>$di_id,'del_flag'=>0,'create_by'=>$this->office_id))->save($data);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>'恭喜你，修改成功'
            ,'url'=>U('device_list',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'对不起，修改失败'));
        }
    }
    /*
     * ================================
     * 电动车价格修改    END
     * ================================
     * */
    //洗衣机价格修改
    public function update_washing(){
        $di_id = $_POST['di_id'];
        $srdata = $_POST['srdata'];
        $openid = $_POST['openid'];
        if(empty($di_id) || empty($srdata)) exit;
        $model = M('device_relation_group');
        $data['charger'] = $srdata;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->where(array('di_id'=>$di_id,'del_flag'=>0))->save($data);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>'恭喜你，修改成功'
            ,'url'=>U('device_list',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'对不起，修改失败'));
        }
    }
    //按摩椅修改
    public function anm(){
        $device_type = $_GET['device_type'];
        $anm = $_GET['ANM'];
        $di_id = $_GET['di_id'];
        $openid = $_GET['openid'];
        if(empty($openid))exit;
        $dgi = M('device_relation_group')->where(array('di_id'=>$di_id,'del_flag'=>0,'device_type'=>4))->find();
        $new = implode('=',explode('-',$dgi['ANM']));
        $fencheng = M('device_info')->where(array('id'=>$dgi['di_id'],'del_flag'=>0))->getField('fencheng');
        $one_array = explode(',',$fencheng);
        $two_array = array();
        foreach($one_array as $key => $v){
            $two_array[$key] = explode('-',$v);

        }
        foreach($two_array as $k=>$v){
            if($v[0] == '' || $v[1] == '0'){
                unset($two_array[$k]);
            }
        }
        $two_array = $this->arrToOne($two_array);
        $out = explode('=',$new);
        $this->assign('anm',$anm);
        $users = M('sys_user')->where(array('del_flag'=>0,'openid'=>$this->openid))->find();
        $li = M('sys_user')->where(array('del_flag'=>0,'id'=>$users['pre_userid']))->find();
        if(empty($users['pre_userid'])){
            $list = M('sys_user')->where(array('del_flag'=>0,'pre_userid'=>$users['id']))->select();
        }else{
            $list = M('sys_user')->where(array('del_flag'=>0,'pre_userid'=>$users['pre_userid']))->select();
        }
        $this->assign("list",$list);
        $this->assign("li",$li);
        $this->assign("two_array",$two_array);
        $this->assign("users",$users);
        $this->assign('office',$this->office_id);
        $this->assign('openid',$openid);
        $this->assign('di_id',$di_id);
        $this->assign('out',$out);
        $this->assign('device_type',$device_type);
        $this->display();
    }
    //二维数组转一维数组
    public function arrToOne($multi)
    {
        $arr = array();
        foreach ($multi as $key => $val) {
            if( is_array($val) ) {
                $arr = array_merge($arr, $this->arrToOne($val));
            } else {
                $arr[] = $val;
            }
        }
        return $arr;
    }
    public function update_anm(){
        $my_name1 = $_POST['my_name1'];
        $my_name2 = $_POST['my_name2'];
        $my_name3 = $_POST['my_name3'];
        $precent1 = $_POST['precent1'];
        $precent2 = $_POST['precent2'];
        $precent3 = $_POST['precent3'];
        $is_into = $_POST['is_into'];
        $di_id = $_POST['di_id'];
        $srdata = $_POST['srdata'];
        $openid = $_POST['openid'];
        if(empty($di_id) || empty($srdata)) exit;
        $model = M('device_relation_group');
        $data['ANM'] = $srdata;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->where(array('di_id'=>$di_id,'del_flag'=>0))->save($data);
        if($is_into == '1'){
            $dataed['fencheng'] = $my_name1.'-'.$precent1.','.$my_name2.'-'.$precent2.','.$my_name3.'-'.$precent3;
        }
        $dataed['update_date'] = date('Y-m-d H:i:s',time());
        $cid = M('device_info')->where(array('del_flag'=>0,'id'=>$di_id))->save($dataed);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>'恭喜你，修改成功'
            ,'url'=>U('device_list',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'对不起，修改失败'));
        }
    }

    public function xiche(){
        $device_type = $_GET['device_type'];
        $charger = $_GET['charger'];
        $di_id = $_GET['di_id'];
        $openid = $_GET['openid'];
        $dgi = M('device_relation_group')->where(array('di_id'=>$di_id,'del_flag'=>0,'device_type'=>7))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('charger',$charger);
        $this->assign('openid',$openid);
        $this->assign('di_id',$di_id);
        $this->assign('out',$out);
        $this->assign('device_type',$device_type);
        $this->display();
    }
    public function xiche_update(){
        $di_id = $_POST['di_id'];
        $srdata = $_POST['srdata'];
        $openid = $_POST['openid'];
        if(empty($di_id) || empty($srdata)) exit;
        $model = M('device_relation_group');
        $data['charger'] = $srdata;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->where(array('di_id'=>$di_id,'del_flag'=>0,'create_by'=>$this->office_id))->save($data);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>'恭喜你，修改成功'
            ,'url'=>U('device_list',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'对不起，修改失败'));
        }
    }
    /*
     * ================================
     * 厕纸价格修改    START
     * ================================
     * */
    public function ceji(){
        $device_type = $_GET['device_type'];
        $charger = $_GET['charger'];
        $di_id = $_GET['di_id'];
        $openid = $_GET['openid'];
        $dgi = M('device_relation_group')->where(array('di_id'=>$di_id,'del_flag'=>0,'device_type'=>8))->find();
        $new = implode('=',explode('-',$dgi['charger']));
        $out = explode('=',$new);
        $this->assign('charger',$charger);
        $this->assign('openid',$openid);
        $this->assign('di_id',$di_id);
        $this->assign('out',$out);
        $this->assign('device_type',$device_type);
        $this->display();
    }
    public function update_ceji(){
        $di_id = $_POST['di_id'];
        $srdata = $_POST['srdata'];
        $openid = $_POST['openid'];
        if(empty($di_id) || empty($srdata)) exit;
        $model = M('device_relation_group');
        $data['charger'] = $srdata;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $uid = $model->where(array('di_id'=>$di_id,'del_flag'=>0,'create_by'=>$this->office_id))->save($data);
        if($uid){
            echo json_encode(array('code'=>200,'msg'=>'恭喜你，修改成功'
            ,'url'=>U('device_list',array('openid'=>$openid))));
        } else {
            echo json_encode(array('code'=>500,'msg'=>'对不起，修改失败'));
        }
    }
    /*
     * ================================
     * 厕纸价格修改    END
     * ================================
     * */
}