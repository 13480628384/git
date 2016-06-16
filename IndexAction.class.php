<?php
class IndexAction extends BaseAction {
	public function index(){
		$this->display();
    }

    //检查是否存在软编码
    public function soft_device_code_exists(){
    	if(empty($this->user_id)){
    		exit;
    	}
    	$model = M('ter_device');
    	$device_code = $_POST['device_code'];
    	$res = $model->where(array('device_code'=>$device_code,'del_flag'=>'0'))->find();
    	if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
    }
    //检查是否存在硬编码
    public function hard_device_code_exists(){
    	if(empty($this->user_id)){
    		exit;
    	}
    	$model = M('ter_device');
    	$device_command = $_POST['device_command'];
    	$res = $model->where(array('device_command'=>$device_command,'del_flag'=>'0'))->find();
    	if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
    }

    //提交查询验证
	/*public function device_check(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$start_date  = $_POST['start_time'];
		$device_code = $_POST['device_code'];
		$end_date    = $_POST['end_time'];
		$res = M('weixin_no')
				->join('weixin_qcode on weixin_no.weixin_ori_id=weixin_qcode.wno')
				->field('weixin_qcode.device_code,weixin_no.weixin_name,weixin_no.weixin_ori_id,weixin_no.status')
				->where(array(
					'weixin_qcode.device_code'=>$device_code,
					'weixin_no.update_by'=>$this->user_id,
					'weixin_no.status'=>1,
					'weixin_qcode.start_date'=>array('gt',$start_date),
					'weixin_qcode.end_date'=>array('lt',$end_date)
					))
				->select();
		//echo M('weixin_no')->getLastSql();die;
		if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
	}*/
	//指令列表
	/*public function instruct(){
		$this->display();
	}
	public function device_list(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$device_code = $_POST['device_code'];
    	$res = M('device_group')->where(array('device_code'=>$device_code,'status'=>1,'update_by'=>$this->user_id))->find();
    	if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
	}*/
	//发送指令
	public function remote_http(){
		$device_command = trim($_POST['device_code']);
		$device = M('device_group')->where(array('device_command'=>$device_command,'status'=>1,'del_flag'=>0))->find();
		if($device['pay_price'] == 10){
			$data['datas']='201:AAAAA4A1';
		}elseif($device['pay_price'] == 2){
			$data['datas']='201:AAAAA421';
		}elseif($device['pay_price'] == 3){
			$data['datas']='201:AAAAA431';
		}elseif($device['pay_price'] == 4){
			$data['datas']='201:AAAAA441';
		}elseif($device['pay_price'] == 5){
			$data['datas']='201:AAAAA451';
		}elseif($device['pay_price'] == 6){
			$data['datas']='201:AAAAA461';
		}elseif($device['pay_price'] == 7){
			$data['datas']='201:AAAAA471';
		}elseif($device['pay_price'] == 8){
			$data['datas']='201:AAAAA481';
		}elseif($device['pay_price'] == 9){
			$data['datas']='201:AAAAA491';
		}else{
			$data['datas']='201:AAAAA401';
		}
		$url = "http://120.24.81.106:3030/IntelligenceServer2/cgi/message_send.action";
		$data['deviceId']= $device_command;
		$data['transCode']='601';
		$code = posts($url,$data);
		$json = json_decode($code);
		if($json->code == 200){
			$uid = M('sys_user')->where(array('id'=>$this->user_id,'del_flag'=>0))->setInc('start_num');
		}else{
			echo '失败';
		}
	}
	//支付列表
	public function pay(){
		$this->display();
	}
	public  function pay_info_count(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$oldtoday = date('Y-m-d H:i:s', strtotime('-7 days'));
		$today    = date('Y-m-d H:i:s',time());
		$device_code = $_POST['device_code'];
		$res = M('pay_info')->where(array(
			'device_code'=>$device_code,
			'type'=>0,'status'=>1,
			'sys_time'=>array('gt',$oldtoday),
			'sys_time'=>array('lt',$today)))
		->count();
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	public function pay_info_list(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$oldtoday = date('Y-m-d H:i:s', strtotime('-7 days'));
		$today    = date('Y-m-d H:i:s',time());
		$device_code = $_POST['device_code'];
		$res = M('pay_info')->where(array(
			'device_code'=>$device_code,
			'type'=>0,'status'=>1,
			'sys_time'=>array('gt',$oldtoday),
			'sys_time'=>array('lt',$today)))
		->select();
		if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
	}
	//设备信息
	public function device_news(){

		$this->display();
	}
	public function device_news_list(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$res = M('ter_device')
				->join('sys_area on ter_device.area_id=sys_area.id')
				->field('sys_area.id,ter_device.id,ter_device.set_status,ter_device.device_status,ter_device.status,ter_device.create_by,ter_device.remarks,ter_device.another_name,ter_device.device_code,ter_device.device_command,sys_area.name,ter_device.address')
				->where(array('ter_device.set_status'=>1,'ter_device.device_status','ter_device.status'=>1,'ter_device.create_by'=>$this->user_id))
				->select();
		if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
	}
/*	public function remote_http_request_address(){
		$url = "http://120.24.81.106:3030/IntelligenceServer2/cgi/message_send.action";
		$data['datas']='201:CCCCC090';
		$data['deviceId']=$_POST['device_command'];
		$data['transCode']='601';
		echo posts($url,$data);
	}*/
	//修改地址
	public function update_device_news(){
		if(isset($_GET['status'])){
    		$status       = $_GET['status'];
    		$remarks      = $_GET['remarks'];
		}if(isset($_GET['id'])){
		    $id           = $_GET['id'];
		    $another_name = $_GET['another_name'];
		}else{
		    exit('没有参数');
		}
		$this->assign('status',$status);
		$this->assign('id',$id);
		$this->assign('remarks',$remarks);
		$this->assign('another_name',$another_name);
		$this->display();
	}
	public function update_address(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$id = $_POST['id'];
    	$data['remarks'] = $_POST['remarks'];
    	$data['another_name'] = $_POST['another_name'];
    	$data['status'] = $_POST['status'];
		$res = M('ter_device')->where(array('id'=>$id))->save($data);
		if($res){
    		echo json_encode(array('msg'=>1,'datas'=>$res));
    	}else{
    		echo json_encode(array('msg'=>2));
    	}
	}
	//查询群组列表
	public function device_position(){
		$this->display();
	}
	public function group_select_code(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$group_code_key = $_POST['group_code_key'];
    	$res = M('device_group')
    			->join('device_group_info di on di.id = device_group.group_id')
    			->field('device_group.*,di.group_name')
    			->where(array('device_group.device_code'=>$group_code_key,'device_group.status'=>1,'device_group.del_flag'=>'0'))
    			->select();
    	if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	public function query_device_group(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	/*$res = M('device_group_info')
    			->where(array('status'=>1,'create_by'=>$this->user_id))
    			->order('create_time desc')
    			->select();*/
    	$res = M('device_group_info')
    			->where(array('status'=>1,'create_by'=>$this->user_id))
    			->order('create_time desc')
    			->select();
    	if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//更新者
	public function update_by(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$res = M('sys_user')->where(array('del_flag'=>0))->select();
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//提交软编码
	public function submit_device_infos(){
		if(!isset($this->user_id)){
    		exit;
    	}
        $device_code 		= $_POST['device_code'];
        $hard_device_code 	= $_POST['hard_device_code'];
        $device_group_id 	= $_POST['device_group_id'];
        $device_group_code  = $_POST['device_group_code'];
        $another_name 		= $_POST['another_name'];
        $ords 				= $_POST['ords'];
        $pay_price 			= $_POST['pay_price'];
		$model = M('ter_device');
		$model->startTrans();
		$offid = $model->where(array('del_flag'=>0,'device_code'=>$device_code))->find();
		$office = M('weixin_pay_config')->where(array('status'=>1,'company_id'=>$offid['office_id']))->find();
    	$data['device_command'] = $hard_device_code;
    	$data['another_name'] = $another_name;
    	$data['set_status'] = 1;
    	$data['device_status'] = 1;
    	$data['status'] = 1;
    	$data['p_version'] = 1;
    	$data['pay_price'] = $pay_price;
    	//$data['payconfig_id'] = $office['id'];
    	$rese = $model->where(array('device_code'=>$device_code))->save($data);
		$device_command = $model->where(array('device_command'=>$hard_device_code,'status'=>1,'del_flag'=>0))->find();
		$device_group = M('device_group')->where(array('device_command'=>$hard_device_code,'del_flag'=>0))->find();
		if($device_command && empty($device_group['device_command'])){
			$res['id'] = md5(uniqid());
			$res['group_id'] = $device_group_id;
			$res['group_code'] = $device_group_code;
			$res['device_id'] = $device_command['id'];
			$res['device_code'] = $device_code;
			$res['device_command'] = $hard_device_code;
			$res['pay_price'] = $pay_price;
			$res['update_by'] = $this->user_id;
			//$res['company_id'] = $this->company_id;///----------------------------
			$res['ords'] = $ords;
			$res['status'] = 1;
			$res['create_time'] = date('Y-m-d H:i:s',time());
			$id = M('device_group')->add($res);
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
	public function device(){
		$this->display();
	}
	public function query_24h_devices(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$today=date('Y-m-d 00:00:00');
		$res = M('device_group')
    			->join('device_group_info di on di.id = device_group.group_id')
    			->field('device_group.*,di.group_name')
    			->where(array(
    				//'update_by'=>$this->user_id,
    				'device_group.update_by'=>$this->user_id,
    				'device_group.create_time'=>array('egt',$today),
    				'device_group.del_flag'=>0
    				))
    			->order('device_group.create_time desc')
    			->select();
    	if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//初次化
	public function init_device_ads(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$device_command = $_POST['device_command'];
		$res = M('weixin_qcode')->where(array('device_code'=>'DEFAULT','status'=>1))->select();
		$ter = M('ter_device')->where(array('device_command'=>$device_command,'del_flag'=>0))->find();
		if($ter && $res){
			foreach($res as $key => $v){
	    		$data['device_id'] = $ter['id'];
	    		$data['id'] = md5(uniqid());
	    		$data['device_code'] = $device_command;
	    		$data['wno'] = $v['wno'];
	    		$data['status'] = $v['status'];
	    		$data['type'] = $v['type'];
	    		$data['weixin_type'] = $v['weixin_type'];
	    		$data['create_time'] = date('Y-m-d H:i:s',time());
	    		$data['start_date'] = date('Y-m-d H:i:s',time());
	    		$data['end_date'] = date('Y-m-d', strtotime('30 days'));
	    		$id = M('weixin_qcode')->add($data);
    		}
	    	echo json_encode(array('msg'=>1));
		}else{
	    	echo json_encode(array('msg'=>2));
		}
	}
	//群组修改
	public function group_update(){
		$name = trim($_GET['name']);
		$area_id = M('sys_area')->where(array('del_flag'=>0,'type'=>4))->select();
		$id = M('device_group_info')->where(array('del_flag'=>0,'group_name'=>$name))->find();
		foreach($area_id as $key=>$v){
			if($id['area_id'] == $v['id']){
				$area_id[$key]['is'] = 1;
			}
		}
		$this->assign('area_id',$area_id);
		$this->assign('name',$name);
		$this->display();
	}
	public function update_device_group_info(){
		if(!isset($this->user_id)){
    		exit;
    	}
		$device_group_id   = $_POST['device_group_id'];
		$device_group_name = $_POST['device_group_name'];
		$area_id = $_POST['area_id'];
		$data['group_name'] = $device_group_name;
		$data['area_id'] = $area_id;
		$res = M('device_group_info')->where(array(array('create_by'=>$this->user_id,'id'=>$device_group_id)))->save($data);
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//群组信息
	public function group_detail(){
		$detailid = $_GET['detailid'];
		$name = $_GET['name'];
		$this->assign('detailid',$detailid);
		$this->assign('name',$name);
		$this->display();
	}
	public function query_device_groups(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$device_group_id = $_POST['device_group_id'];
    	$res = M('device_group')->where(array('status'=>1,'group_id'=>$device_group_id,'del_flag'=>0))->select();
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//修改群组信息
	public function update_device(){
		if(isset($_GET['group_id'])){
	        $group_id = trim($_GET['group_id']);
	        $group_code = trim($_GET['group_code']);
	        $device_code = trim($_GET['device_code']);
	        $status = trim($_GET['status']);
	        $cname = $_GET['cname'];
	        $ords = trim($_GET['ords']);
	        $device_command = trim($_GET['device_command']);
	        $this->assign('group_id',$group_id);
	        $this->assign('group_code',$group_code);
	        $this->assign('device_code',$device_code);
	        $this->assign('status',$status);
	        $this->assign('cname',$cname);
	        $this->assign('ords',$ords);
	        $this->assign('device_command',$device_command);
    	}else{
        	echo '参数错误';
    	}
    $this->display();
	}
	public function update_device_group(){
		if(!isset($this->user_id)){
    		exit;
    	}
		 $device_command = trim($_POST['device_command']);
		 $group_code = trim($_POST['group_code']);
		 $group_id = trim($_POST['group_id']);
		 $status = trim($_POST['status']);
		 $ords = trim($_POST['ords']);
		 $data['group_code'] = $group_code;
		 $data['group_id'] = $group_id;
		 $data['status'] = $status;
		 $data['ords'] = $ords;
		 $res = M('device_group')->where(array('device_command'=>$device_command,'del_flag'=>0))->save($data);
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	//添加群组
	public function group_add(){
		$area_id = M('sys_area')->where(array('del_flag'=>0,'type'=>4))->order('create_date desc')->select();
		$this->assign('area_id',$area_id);
		$this->display();
	}
	public function add_device_group_info(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$device_group_name = $_POST['device_group_name'];
    	$area_id = $_POST['area_id'];
    	$data['group_name'] = $device_group_name;
    	$data['area_id'] = $area_id;
    	$data['id'] = md5(uniqid());;
    	$data['create_by'] = $this->user_id;
    	//$data['company_id'] = $this->company_id;// -----------------
    	$res = M('device_group_info')->add($data);
		if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}

	}
	//个人中心
	public function personal(){
		$this->display();
	}
	public function device_code_exists_or(){
		if(!isset($this->user_id)){
    		exit;
    	}
    	$hard_device_code = $_POST['hard_device_code'];
    	$condition['device_group.device_command'] = $hard_device_code;
    	$condition['device_group.device_code'] 	 = $hard_device_code;
    	$condition['_logic'] 		 = 'OR';
    	$condition['del_flag'] 		 = '0';
    	$res = M('device_group')
    			->join('ter_device td on td.device_command=device_group.device_command')
    			->field('device_group.*,td.another_name')
    			->where($condition)
    			->find();
    	if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
	public function query_group(){
		//select * from device_group where update_by =? and device_code=?
		if(!isset($this->user_id)){
    		exit;
    	}
    	$device_code = $_POST['device_code'];
    	$res = M('device_group')->where(array(
    		'update_by'=>$this->user_id,///---------------------
    		'device_code'=>$device_code,
    		'del_flag'=>0
    		))->find();
    	if($res){
			echo json_encode(array('msg'=>1,'datas'=>$res));
		}else{
			echo json_encode(array('msg'=>2));
		}

	}
	public function logout(){
		session_unset();
		session_destroy();
		header('Location:'.U('Login/login'));
	}
	public function pc(){
		$this->display();
	}
	//电脑端批量安装
	public function batch(){
		if(!isset($this->user_id)){
			exit;
		}
		$device_code 		= $_POST['device_code'];
		$hard_device_code 	= $_POST['hard_device_code'];
		$device_group_id 	= $_POST['device_group_id'];
		$device_group_code  = $_POST['device_group_code'];
		$another_name 		= $_POST['another_name'];
		$ords 				= $_POST['ords'];
		$pay_price 			= $_POST['pay_price'];
		$model = M('ter_device');
		$model->startTrans();
		$offid = $model->where(array('del_flag'=>0,'device_code'=>$device_code))->find();
		$office = M('weixin_pay_config')->where(array('status'=>1,'company_id'=>$offid['office_id']))->find();
		$data['device_command'] = $hard_device_code;
		$data['another_name'] = $another_name;
		$data['set_status'] = 1;
		$data['device_status'] = 1;
		$data['status'] = 1;
		$data['pay_price'] = $pay_price;
		$data['payconfig_id'] = $office['id'];
		$rese = $model->where(array('device_code'=>$device_code))->save($data);
		$device_command = $model->where(array('device_command'=>$hard_device_code,'status'=>1,'del_flag'=>0))->find();
		$device_group = M('device_group')->where(array('device_command'=>$hard_device_code,'del_flag'=>0))->find();
		if($device_command && empty($device_group['device_command'])){
			$res['id'] = md5(uniqid());
			$res['group_id'] = $device_group_id;
			$res['group_code'] = $device_group_code;
			$res['device_id'] = $device_command['id'];
			$res['device_code'] = $device_code;
			$res['device_command'] = $hard_device_code;
			$res['pay_price'] = $pay_price;
			$res['update_by'] = $this->user_id;
			//$res['company_id'] = $this->company_id;///----------------------------
			$res['ords'] = $ords;
			$res['status'] = 1;
			$res['create_time'] = date('Y-m-d H:i:s',time());
			$id = M('device_group')->add($res);
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
	public function excel()
	{
		require_once "UploadFile.class.php";
		require_once "Excel.php";
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize = 8145728;// 设置附件上传大小
		$upload->allowExts = array('xlsx', 'xls');// 设置附件上传类型
		$upload->savePath = './upload/';// 设置附件上传目录
		if (!$upload->upload()) {// 上传错误提示错误信息
			$err = $upload->getErrorMsg();
			$this->ajaxReturn(array('code' => -1, 'msg' => $err));
		} else {// 上传成功
			$data = $upload->getUploadFileInfo();
			$filename = $data[0]['savepath'] . $data[0]['savename'];
			$exceldata = Excel::excel2Arr($filename);
			//$exceldata = $this->read($filename);
			array_shift($exceldata);
			foreach ($exceldata as $ke => $v) {
				if(stristr(trim($v[1]),"'")){
					$command = str_replace("'",'',trim($v[1]));
					echo 1;
				}else{
					$command = trim($v[1]);
				}
				$model = M('ter_device');
				$offid = $model->where(array('del_flag' => 0, 'device_code' => $v[0]))->find();
				if(!$offid){
					$this->ajaxReturn(array('code' => 1, 'msg' => '安装失败,软编码'.$v[0].'不存在,请在后台上传软编码'));
					exit;
				}
				$office = M('weixin_pay_config')->where(array('status' => 1, 'company_id' => $offid['office_id']))->find();
				$device_group = $model->where(array('device_command' => $command, 'del_flag' => 0))->find();
				//查找群组名称
				$group_id = M('device_group_info')
					->where("concat(',', group_name, ',') like '%,".$v[5].",%' and status=1 and create_by='$this->user_id'")
					->find();
				if(!$group_id){
					$this->ajaxReturn(array('code' => 3, 'msg' => '安装失败,群组名称'.$v[5].'不存在,请核对是否安装员有这个群组名称'));
					exit;
				}
				//判断硬编码是否存在device_group
				if(!empty($device_group)){
					$this->ajaxReturn(array('code' => 4, 'msg' => '安装失败,硬编码'.$command.'已存在,注意：硬编
					码'.$command.'前的已经安装成功，请在excel中删除'.$command.'之前所有的数据,否则会安装失败,软编码：'.$v[0]));
					exit;
				}
				//判断价格是否不对
				if(intval($v[4]) >= 3){
					$this->ajaxReturn(array('code' => 6, 'msg' => '价格有误错误的价格为 '.$v[4].'价格不能大于3元,软编码为：'.$v[0]));
					exit;
				}
				if(!is_int(intval($v[4]))){
					$this->ajaxReturn(array('code' => 7, 'msg' => '价格有误错误的价格为 '.$v[4].',价格必须为整数,软编码为：'.$v[0]));
					exit;
				}
				$data['device_command'] = $command;
				$data['another_name'] = $v[3];
				$data['set_status'] = 1;
				$data['device_status'] = 1;
				$data['status'] = 1;
				$data['p_version'] = 1;
				$data['pay_price'] = trim($v[4]);
				$data['payconfig_id'] = $office['id'];
				$rese = $model->where(array('device_code' => $v[0]))->save($data);
				if(!$rese){
					$this->ajaxReturn(array('code' => 2, 'msg' => '更新设备表失败,请核对excel表中是否有问题,或者表中没有更新任何东西'));
					exit;
				}
				$device_command = $model->where(array('device_command' => $command, 'status' => 1, 'del_flag' => 0))->find();
				$p_array = array(
					'A' => '1','B' => '2','C' => '3','D' => '4','E' => '5',
					'F' => '6','G' => '7','H' => '8','I' => '9','J' => '10',
					'K' => '11','L' => '12','M' => '13','N' => '14','O' => '15',
					'P' => '16','Q' => '17','R' => '18','S' => '19','T' => '20',
					'U' => '21','V' => '22','W' => '23','X' => '24','Y' => '25','Z' => '26'
				);
				$ords = '';
				if(array_key_exists($v[2],$p_array)){
					$ords = $p_array[$v[2]];
				}else {
					$ords = 1;
				}
				$res['id'] = md5(uniqid());
				$res['group_id'] = $group_id['id'];
				$res['group_code'] = $v[2];
				$res['device_id'] = $device_command['id'];
				$res['device_code'] = $v[0];
				$res['device_command'] = $command;
				$res['pay_price'] = trim($v[4]);
				$res['update_by'] = $this->user_id;
				//$res['company_id'] = $this->company_id;///----------------------------
				$res['ords'] = $ords;
				$res['status'] = 1;
				$res['create_time'] = date('Y-m-d H:i:s', time());
				$id = M('device_group')->add($res);
			}
			$this->ajaxReturn(array('code' => 5, 'msg' => '安装成功'));
		}
	}
	//下载excel模板
	public function download_excel(){
		require_once "UploadFile.class.php";
		require_once "Excel.php";
		$data = array(
			array(
				'device_code'=>'WWSZKS00049',
				'device_command'=>"352425020963247",
				'group_num'=>A,
				'anther_name'=>'东门太阳百货负一楼百佳入口',
				'price'=>1,
				'group_name'=>'广州'
			),
			array(
				'device_code'=>'WWSZKS00023',
				'device_command'=>"352425020963248",
				'group_num'=>Z,
				'anther_name'=>'广州市天河区时尚天河游乐园群组1',
				'price'=>2,
				'group_name'=>'中山'
			),
		);
		Excel::arr2ExcelDownload($data,array('软编码', '硬编码','群组编号','运营编码','支付价格','群组名称'),'设备安装模板');
	}
}