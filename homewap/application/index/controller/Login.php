<?php
namespace app\index\controller;
use think\Db;
use lib\JsApiPay;
use think\Request;
    class Login extends \think\Controller {
        public function index(){
            $request = Request::instance();
            $getopenid = $request->param('openid');
            if(isset($getopenid)){
                $openid = $getopenid;
            }else{
                $wx = new \lib\JsApiPay();
                $openid = $wx->GetOpenid();
            }
            //print_r($wx->getuserinfo());die;
            //$openid = '';
            $this->assign([
                'openid'=>$openid
            ]);
            return $this->fetch('index');
        }
        //一键登录
        public function keylogin(){
            $openid = trim($_POST['openid']);
            if(empty($openid)){
                return ['code'=>201,'msg'=>'参数缺少，请重新进入'];
            }
            $result = Db::table('weixin_userinfo')->where(['openid'=>$openid,'type'=>2])->find();
            if(!empty($result['phone']) && !empty($result['password'])) {
                session('openid',$openid);
                return ['code'=>200,'msg'=>'登录成功','url'=>url('Index/index')];

            } else {
                return ['code'=>201,'msg'=>'手机号码或密码错误'];
            }
        }
        //登录验证
        public function login_check(){
            $phone = trim($_POST['phone']);
            $password = trim($_POST['passwd']);
            $capth = trim($_POST['capth']);
            $openid = trim($_POST['openid']);
            if(empty($openid)){
                return ['code'=>201,'msg'=>'参数缺少，请重新进入'];
            }
            if(!captcha_check($capth)){
                return ['code'=>201,'msg'=>'参验证码错误'];
            }
            if(empty($openid)){
                return ['code'=>201,'msg'=>'参数缺少，请重新进入'];
            }
            //手机号码和密码必须正确才能登录保存信息
            $result = Db::table('weixin_userinfo')->where(['phone'=>$phone,'password'=>sp_password($password),'type'=>2])->find();
            if($result) {
                session('openid',$openid);
                return ['code'=>200,'msg'=>'登录成功','url'=>url('Index/index')];

            } else {
                return ['code'=>201,'msg'=>'手机号码或密码错误'];
            }
        }
        /*
         * =================
         * 红家君助注册
         * 2018-1-16
         * =================
         * */
        public function resigter(){
            $wx = new \lib\JsApiPay();
            $openid = $wx->GetOpenid();
            //echo $openid.'------';die;
            //print_r($wx->getuserinfo());die;
            $res = $wx->getuserinfo();
            //p($openid);die;
            //$openid = '';
            //添加用户信息
           $data =array(
                'id' => generateNum(),
                'openid' => $openid,
                'create_by' => $openid,
                'status' => '1',
                'create_date' => date('Y-m-d H:i:s',time()),
                'update_date' => date('Y-m-d H:i:s',time()),
                'sex' => $res['sex'],
                'nickname' => $res['nickname'],
                'headimgurl' => $res['headimgurl'],
                'country' => $res['country'],
                'city' => $res['city'],
                'province' => $res['province'],
                'type' => '1',
            );
            if(!Db::table('weixin_userinfo')->where(array('openid'=>$openid))->find()){
                Db::table('weixin_userinfo')->insert($data);
            }
            $this->assign([
                'openid'=>$openid
            ]);
            return $this->fetch('resigter');
        }
        /*
         * =====================
         * 注册提交
         * 2018-1-16
         * =====================
         * */
        public function login_resigter(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $phone = trim($_POST['phone']);
                $password = trim($_POST['passwd']);
                $openid = trim($_POST['openid']);
                if(empty($openid) || !isset($openid)){
                    return ['code'=>201,'msg'=>'参数缺少，请重新进入'];
                }
                //判断是否已经注册
                $result = Db::table('weixin_userinfo')->where(['phone'=>$phone,'password'=>sp_password($password)])->find();
                if(!$result) {
                    $data['phone'] = $phone;
                    $data['password'] = sp_password($password);
                    $data['update_date'] = date('Y-m-d H:i:s',time());
                    $data['type'] = '2';
                    $res = Db::table('weixin_userinfo')->where(['openid'=>$openid])->update($data);
                    if($res){
                        return ['code'=>200,'msg'=>'注册成功','url'=>url('Login/index')];
                    } else {
                        return ['code'=>201,'msg'=>'网络错误'];
                    }
                } else {
                    return ['code'=>201,'msg'=>'您已经注册了，请登录'];
                }
            }
        }
    }