<?php
namespace User\Controller;

use Common\Controller\AdminbaseController;

class IndexadminController extends AdminbaseController {
	public function _initialize() {
		parent::_initialize();
		$this->assign("admin_id", get_current_admin_id());
	}
    // 后台本站用户列表
    public function index(){
        $where=array();
        $request=I('request.');
        if(!empty($request['uid'])){
            $where['sys_user.id']=intval($request['uid']);
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['sys_user.login_name']  = array('like', "%$keyword%");
            $keyword_complex['sys_user.name']  = array('like',"%$keyword%");
            $keyword_complex['sys_user.email']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
    	$users_model=M("sys_user");
		$where['sys_user.del_flag']=0;
		$where['so.del_flag']=0;
		$where['so1.del_flag']=0;
		if(get_current_admin_id() == 1 || $this->role_type == 2){
			$count=$users_model
				->join('sys_office so on so.id=sys_user.office_id')
				->join('sys_office so1 on so1.id = sys_user.company_id')
				->field('sys_user.login_name,sys_user.name,sys_user.email,sys_user.create_date,sys_user.login_flag,sys_user.id,
			sys_user.phone,sys_user.mobile,sys_user.user_type,sys_user.login_ip,sys_user.login_date,so.name as name1,so1.name as name2,
			sys_user.totals,sys_user.consume')
				->where($where)->count();
			$page = $this->page($count, 20);
			$list = $users_model
				->join('sys_office so on so.id=sys_user.office_id')
				->join('sys_office so1 on so1.id = sys_user.company_id')
				->field('sys_user.login_name,sys_user.percent,sys_user.name,sys_user.email,sys_user.create_date,sys_user.login_flag,sys_user.id,
			sys_user.phone,sys_user.mobile,sys_user.openid,sys_user.user_type,sys_user.login_ip,sys_user.login_date,so.name as name1,so1.name as name2,
			sys_user.totals,sys_user.consume')
				->where($where)
				->order("sys_user.create_date DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();
		} else {
			$company_id = M('sys_office')->where(array('id'=>get_current_office_id()))->getField('parent_id');
			$where['so1.id'] = $company_id;
			$count=$users_model
				->join('sys_office so on so.id=sys_user.office_id')
				->join('sys_office so1 on so1.parent_id = sys_user.company_id')
				->field('sys_user.login_name,sys_user.name,sys_user.email,sys_user.create_date,sys_user.login_flag,sys_user.id,
			sys_user.phone,sys_user.mobile,sys_user.user_type,sys_user.login_ip,sys_user.login_date,so.name as name1,so1.name as name2')
				->where($where)->count();
			$page = $this->page($count, 20);
			$list = $users_model
				->join('sys_office so on so.id=sys_user.office_id')
				->join('sys_office so1 on so1.id = sys_user.company_id')
				->field('sys_user.login_name,sys_user.percent,sys_user.name,sys_user.email,sys_user.create_date,sys_user.login_flag,sys_user.id,
			sys_user.phone,sys_user.mobile,sys_user.openid,sys_user.user_type,sys_user.login_ip,sys_user.login_date,so.name as name1,so1.name as name2')
				->where($where)
				->order("sys_user.create_date DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();
			//echo $users_model->getLastSql();die;
		}

    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display(":index");
    }
    //后台本站用户删除
	public function sys_user_del(){
		//thinkcmf/index.php?g=User&m=indexadmin&a=sys_user_del&id=0aff0d6c1d9f4a8694b041b338431927
		$id= I('get.id');
		if ($id) {
			$data['del_flag'] = '1';
			$result = M("sys_user")->where(array("id"=>$id,"del_flag"=>0))->save($data);
			$resulta = M("sys_user_role")->where(array("user_id"=>$id))->delete();
			if ($result && $resulta) {
				$this->success("会员删除成功！", U("indexadmin/index"));
			} else {
				$this->error('会员删除失败,会员不存在,或者是管理员！');
			}
		} else {
			$this->error('数据传入失败！');
		}
	}
	// 用户提现百分比
	public function percent() {
		$model = M('sys_user');
		$status = parent::_percents($model);
		if ($status) {
			$this->success("更新成功！");
		} else {
			$this->error("更新失败！");
		}
	}
	//后台本站用户修改
	public function sys_user_edit(){
		$id= I('get.id');
		if ($id) {
			$sys_user_role = M('sys_user_role')->where(array('user_id'=>$id))->select();
			//用户信息
			$sys_user = M('sys_user')->where(array('id'=>$id))->find();
			/*//归属公司
			$office = M('sys_office')->where(array('del_flag'=>0,'type'=>1))->select();
			foreach($office as $k => $va){
				if($va['id'] == $sys_user['company_id']){
					$office[$k]['com'] = 1;
				}
			}*/
			//归属部门
			/*$office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1)))->select();
			foreach($office_id as $k => $va){
				if($va['id'] == $sys_user['office_id']){
					$office_id[$k]['off'] = 1;
				}
			}*/
			//echo '<pre>';print_r($sys_user_role);die;
			/*foreach($result as $k=>$item){
				foreach($sys_user_role as $v){
					if($item['id'] == $v['role_id']){
						$result[$k]['on'] = 1;
					}
				}
			}*/
			if(get_current_admin_id() == 1 || $this->role_type == 2){
				//归属公司
				$office = M('sys_office')->where(array('del_flag'=>0,'type'=>1))->select();
				foreach($office as $k => $va){
					if($va['id'] == $sys_user['company_id']){
						$office[$k]['com'] = 1;
					}
				}
				//归属部门
				$office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1)))->select();
				foreach($office_id as $k => $va){
					if($va['id'] == $sys_user['office_id']){
						$office_id[$k]['off'] = 1;
					}
				}
				//角色
				$result = M("sys_role")->alias('sur')
					->where(array('del_flag'=>0))->select();
				foreach($result as $k=>$item){
					foreach($sys_user_role as $v){
						if($item['id'] == $v['role_id']){
							$result[$k]['on'] = 1;
						}
					}
				}
			} else {
				//归属公司
				$shangs = M('sys_office')->where(array('id'=>get_current_office_id()))->find();
				$office = M('sys_office')->where(array('del_flag'=>0,'type'=>1,'id'=>$shangs['parent_id']))->select();
				foreach($office as $k => $va){
					if($va['id'] == $sys_user['company_id']){
						$office[$k]['com'] = 1;
					}
				}
				//归属部门
				$office_id = M('sys_office')->where(array('parent_id'=>$shangs['parent_id']))->select();
				foreach($office_id as $k => $va){
					if($va['id'] == $sys_user['office_id']){
						$office_id[$k]['off'] = 1;
					}
				}
				//角色
				$user_role = M('sys_user_role')->where(array('user_id'=>$id))->find();
				$result = M("sys_role")
					->where(array('del_flag'=>0))->select();
				foreach($result as $k=>$item){
					if($item['id'] == $user_role['role_id']){
						$result[$k]['on'] = 1;
					}
				}
			}
			$this->assign('office',$office);
			$this->assign('office_id',$office_id);
			$this->assign('result',$result);
			$this->assign('sys_user',$sys_user);
		} else {
			$this->error('数据传入失败！');
		}
		$this->display(':sys_user_edit');
	}
	//后台本站用户修改 sp_password
	public function edit_post(){
		if(IS_POST){
			if(empty($_POST['role_id'])) $this->error("必须有一个角色！");
			$data['company_id'] = $_POST['company_id'];
			$data['office_id'] = $_POST['office_id'];
			$data['login_name'] = $_POST['user_login'];
			$data['name'] = $_POST['name'];
			$data['password'] = sp_password($_POST['password']);
			$data['user_pass'] = sp_password($_POST['password']);
			$data['email'] = $_POST['email'];
			$data['phone'] = $_POST['mobile'];
			$data['mobile'] = $_POST['mobile'];
			$data['remarks'] = $_POST['remarks'];
			$data['login_flag'] = $_POST['login_flag'];
			$data['user_type'] = $_POST['user_type'];
			$data['update_date'] = date('Y-m-d H:i:s',time());
            if($_POST['remarks'] == '售货机'){
                $data['no'] = '售货机';
            }else{
                $data['no'] = rand(100,10000);
            }
			$user_id = $_POST['user_id'];
			$sys_user_role = M('sys_user_role')->where(array('user_id'=>$user_id))->select();
			$role_id = array();
			foreach($sys_user_role as $k => $v){
				$role_id[] = $v['role_id'];
			}
			//数据表没有就添加角色
			foreach($_POST['role_id'] as $v){
				if(!in_array($v,$role_id)){
					$rose['user_id'] = $user_id;
					$rose['role_id'] = $v;
					M('sys_user_role')->add($rose);
				}
			}
			//复选框取消，但是数据表里面有
			foreach($role_id as $va){
				if(!in_array($va,$_POST['role_id'])){
					M('sys_user_role')->where(array('role_id'=>$va,'user_id'=>$user_id))->delete();
				}
			}
			$result = M('sys_user')->where(array('id'=>$user_id,'del_flag'=>0))->save($data);
			if($result){
				$this->success("保存成功！",U('Indexadmin/index'));
			} else {
				$this->error("保存失败！");
			}
		}
	}
	//本站用户管理添加
	public function sys_user_add(){
		if(get_current_admin_id() == 1 || $this->role_type == 2){
			//归属公司
			$office = M('sys_office')->where(array('del_flag'=>0,'type'=>1))->order('create_date desc')->select();
			//归属部门
			$office_id = M('sys_office')->where(array('del_flag'=>0,'type'=>array('neq',1)))->order('create_date desc')->select();
			//角色
			$result = M("sys_role")->alias('sur')
				->where(array('del_flag'=>0))->select();
		} else {
			//归属公司
			$shangs = M('sys_office')->where(array('id'=>get_current_office_id()))->find();
			$office = M('sys_office')->where(array('del_flag'=>0,'type'=>1,'id'=>$shangs['parent_id']))->select();
			//归属部门
			$office_id = M('sys_office')->where(array('parent_id'=>$shangs['parent_id']))->select();
			//角色
			$result = M("sys_role")
				->where(array('del_flag'=>0,'name'=>'终端商'))->select();
		}
		$this->assign('office',$office);
		$this->assign('result',$result);
		$this->assign('office_id',$office_id);
		$this->display(':sys_user_add');
	}
	//本站用户管理添加ajax
	public function add_post(){
		if(IS_POST){
			if(empty($_POST['role_id'])) $this->error("必须有一个角色！");
			$user_id_resu = M('sys_user')
				->where(array('del_flag'=>0,'phone'=>$_POST['mobile']))->find();
			$user_id_resu1 = M('sys_user')
				->where(array('del_flag'=>0,'login_name'=>$_POST['login_name']))->find();
			if($user_id_resu) $this->error("电话已被占用，请重新输入新的电话号码！");
			if($user_id_resu1) $this->error("登录名已被占用，请重新输入新的登录名！");
			$data['company_id'] = $_POST['company_id'];
			$data['office_id'] = $_POST['office_id'];
			$data['login_name'] = $_POST['login_name'];
			$data['name'] = $_POST['name'];
			$data['password'] = sp_password($_POST['password']);
			$data['user_pass'] = sp_password($_POST['password']);
			$data['email'] = $_POST['email'];
			$data['phone'] = $_POST['mobile'];
			$data['mobile'] = $_POST['mobile'];
			$data['remarks'] = $_POST['remarks'];
			$data['login_flag'] = $_POST['login_flag'];
			$data['user_type'] = $_POST['user_type'];
			$data['id'] = generateNum();
			if($_POST['remarks'] == '售货机'){
                $data['no'] = '售货机';
            }else{
                $data['no'] = rand(100,10000);
            }
			$data['create_date'] = date('Y-m-d H:i:s',time());
			$data['update_date'] = date('Y-m-d H:i:s',time());
			$resu = M('sys_user')->add($data);
			$user_id = M('sys_user')
				->where(array('del_flag'=>0,'phone'=>$_POST['mobile'],'login_name'=>$_POST['login_name']))->find();
			//数据表没有就添加角色
			foreach($_POST['role_id'] as $v){
				$rose['user_id'] = $user_id['id'];
				$rose['role_id'] = $v;
				M('sys_user_role')->add($rose);
			}
			if($resu){
				$this->success("添加成功！",U('Indexadmin/index'));
			} else {
				$this->error("添加失败！");
			}
		}
	}
    // 后台本站用户禁用
    public function ban(){
    	$id= I('get.id',0,'intval');
    	if ($id) {
    		$result = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status',0);
    		if ($result) {
    			$this->success("会员拉黑成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员拉黑失败,会员不存在,或者是管理员！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    
    // 后台本站用户启用
    public function cancelban(){
    	$id= I('get.id',0,'intval');
    	if ($id) {
    		$result = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status',1);
    		if ($result) {
    			$this->success("会员启用成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}
