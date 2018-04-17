<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
include "lib/WxMch.Pay.php";
class UserRefundController extends AdminbaseController{

    protected $user_unfund;

    public function _initialize() {
        parent::_initialize();
        include "Off_Tree.class.php";
        $this->user_unfund = D("Common/user_unfund");
    }

    // 微信管理列表
    public function index(){
        $request=I('request.');
        $start_time=I('request.start_time');
        if(!empty($start_time)){
            $where['create_date']=array(
                array('EGT',$start_time)
            );
        }
        $end_time=I('request.end_time');
        if(!empty($end_time)){
            if(empty($where['create_date'])){
                $where['create_date']=array();
            }
            array_push($where['create_date'], array('ELT',$end_time));
        }
        if(!empty($request['area_id'])){
            $where['area_id']=$request['area_id'];
        }if(!empty($request['status'])){
            $where['status']=$request['status'];
        }if($request['status'] == '0'){
            $where['status'] = '0';
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['openid']  = array('like', "%$keyword%");
            $keyword_complex['out_trade_no']  = array('like', "%$keyword%");
            $keyword_complex['transaction_id']  = array('like', "%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }

        $where['del_flag'] = 0;
        $count=$this->user_unfund
            ->where($where)->count();
        $page = $this->page($count, 20);
        $list = $this->user_unfund
            ->where($where)
            ->order("create_date DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    /*
     * 确定审核退款处理
     * */
    public function confirm_refund(){
        $this->error('该功能正等待上线！');die;
        $id = trim($_GET['id']);
        if(empty($id)) exit;
        $res = $this->user_unfund->where(array('del_flag'=>0,'id'=>$id))->find();
        if($res['status'] != 0){
            $this->error('该申请已经审核');
        }
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        $mchPay = new \WxMchPay();
        $mchPay->setParameter('openid',$res['openid']);
        $mchPay->setParameter('partner_trade_no', $orderSn);
        $mchPay->setParameter('check_name', 'NO_CHECK');
        $mchPay->setParameter('amount', $res['count']*100);
        $mchPay->setParameter('desc', '玫瑰物联');
        $mchPay->setParameter('spbill_create_ip',$this->GetIP());
        $response = $mchPay->postXmlSSLCurl_init();
        if(!empty($response)){
            $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
            $array_no = $this->json2arr($data);
            if(empty($array_no['payment_time']) || !isset($array_no['payment_time'])){
                $this->error('微信后台账号余额不足，请充值');
            }
            if($array_no['result_code'] == 'SUCCESS' || $array_no['return_code'] == 'SUCCESS'){
                $resd['status'] = '1';
                $resd['update_date'] = date('Y-m-d H:i:s',time());
                $resd['transaction_id'] = $array_no['payment_no'];
                $resd['arrival'] = $res['count'];
                $this->user_unfund->where(array('id'=>$id,'openid'=>$res['openid']))->save($resd);
                $this->success('退款成功',U('index'));
            }else{
                $this->error($array_no['err_code_des']);
            }
        }else{
            $this->error('退款失败');
        }
    }
    /*
     * 不退款处理
     * */
    public function noconfirm_refund(){
        $id = trim($_GET['id']);
        if(empty($id)) exit;
        $res = $this->user_unfund->where(array('del_flag'=>0,'id'=>$id))->find();
        if($res['status'] != 0){
            $this->error('该申请已经审核');
        }
        $resd['status'] = '2';
        $resd['update_date'] = date('Y-m-d H:i:s',time());
        $resd['remarks'] = '不退款';
        $result = $this->user_unfund->where(array('id'=>$id,'openid'=>$res['openid']))->save($resd);
        if($result){
            $this->success('审核成功',U('index'));
        } else {
            $this->error('退款失败',U('index'));
        }
    }
    /*
     * 获取ip
     * */
     /*获取IP*/
    public function GetIP(){
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif(!empty($_SERVER["REMOTE_ADDR"])){
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else{
            $cip = "无法获取！";
        }
        return $cip;
    }
    //二维json数据转换成二维数组
   public function json2arr($json){
        $arr = array();
        foreach((array)$json as $key=>$val){
            if(is_object($val))$arr[$key] = $this->json2arr($val);
            else $arr[$key] = $val;
        }
        return $arr;
    }
}