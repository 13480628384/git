<?php
class LoginAction extends Action {
	public function login(){
		$this->display();
	}
	public function check_login(){
		$login_name = $_POST['login_name'];
		$user_type  = $_POST['user_type'];
		$password  = $_POST['password'];
		$weixin_no  = $_POST['weixin_no'];
		$model = M('sys_user');
		$id = $model->where(array('login_name'=>$login_name,'del_flag'=>0,'user_type'=>$user_type))->find();
		if($id){
			session('username',$id['login_name']);
			session('user_id',$id['id']);
			echo json_encode(array('msg'=>1));
		}else{
			echo json_encode(array('msg'=>2));
		}
	}
}