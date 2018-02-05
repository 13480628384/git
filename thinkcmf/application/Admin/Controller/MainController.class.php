<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class MainController extends AdminbaseController {
	
    public function index(){
    	$mysql= M()->query("select VERSION() as version");
    	$mysql=$mysql[0]['version'];
    	$mysql=empty($mysql)?L('UNKNOWN'):$mysql;
        $today = date('Y-m-d 00:00:00');
        $whereed['create_date'] = array('egt', $today);
        $whereed['del_flag'] = '0';
        $whereed['transfer_status'] = '0';
        $whereed['consume_status'] = '1';
        $whereed['command_status'] = '2';
        $weixin_count = M('device_consume_rec')->where($whereed)->sum('consume_account');

        $where['create_date'] = array('egt', $today);
        $where['pay_status'] = 1;
        $where['del_flag'] = 0;
        $weixin_pay = M('weixin_pay_rec')->where($where)->sum('pay_account');
        //微信路线图
        $start=strtotime(date('Y-m-01 00:00:00'));
        $end = strtotime(date('Y-m-d H:i:s'));
        $wherees['create_date'] = array('between',array($start,$end));
       if(get_current_admin_id()==1){
           $result = M('weixin_come')->query('
        SELECT weixin_decome,weixin_pay,DATE_FORMAT(create_date,\'%m-%d\') as days FROM 
        weixin_come where DATE_SUB(CURDATE(), INTERVAL 14 DAY)
         <= date(create_date) ORDER BY create_date desc');
       }
    	$info = array(
    			L('OPERATING_SYSTEM') => PHP_OS,
    			L('OPERATING_ENVIRONMENT') => $_SERVER["SERVER_SOFTWARE"],
    	        L('PHP_VERSION') => PHP_VERSION,
    			L('PHP_RUN_MODE') => php_sapi_name(),
				L('PHP_VERSION') => phpversion(),
    			L('MYSQL_VERSION') =>$mysql,
    			L('UPLOAD_MAX_FILESIZE') => ini_get('upload_max_filesize'),
    			L('MAX_EXECUTION_TIME') => ini_get('max_execution_time') . "s",
    			L('DISK_FREE_SPACE') => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
    	);
        /*$str = 15;
        $array = array();
        for($i=14;$i<$str;$i--) {
            $array[] = date("m-d",strtotime("-$i day"));
            if($i<=0) break;
        }*/
        $tt = array();
        foreach ($result as $v){
            $tt[] = $v['days'];
        }
        $res = array_reverse($result);
        $pay = array();
        $come = array();
        foreach ($res as $v) {
            $pay[] = $v['weixin_pay'];
            $come[] = $v['weixin_decome'];
        }
        $pay_str = implode(',',$pay);
        $come_str = implode(',',$come);
        $strs = implode(",",array_reverse($tt));
    	$this->assign('server_info', $info);
    	$this->assign('array',$strs);
    	$this->assign('pay_str',$pay_str);
    	$this->assign('come_str',$come_str);
    	$this->assign('weixin_pay', $weixin_pay);
    	$this->assign('weixin_count', $weixin_count);
    	$this->assign('result', $result);
        $this->assign('info',M('info')->find());
    	$this->display();
    }
	//留言
	public function message(){
		$this->display();
	}
	public function add_post(){
		$email = $_POST['email'];
		$content = $_POST['content'];
		if(empty($email)) $this->error('请输入邮箱');
		if(empty($content)) $this->error('请填写建议');
		$user = M('sys_user')->where(array('id'=>get_current_admin_id(),'del_flag'=>0))->find();
		$data['id'] = generateNum();
		$data['full_name'] = $user['name'];
		$data['email'] = $email;
		$data['title'] = $user['name'];
		$data['msg'] = $content;
		$data['createtime'] = date('Y-m-d H:i:s',time());
		if(M('guestbook')->add($data)){
			$this->success('非常感谢你提供的建议');
		} else {
			$this->error('提交失败');
		}
	}
}