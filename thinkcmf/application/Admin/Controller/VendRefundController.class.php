<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
include "lib/WxMch.Pay.php";
class VendRefundController extends AdminbaseController{
    protected $Dserefund;
    public function _initialize() {
        parent::_initialize();
        $this->Dserefund = D("Common/vending_userefund");
    }
    // 用户退款处理列表
    public function index(){
        $request=I('request.');
        if(!empty($request['status'])){
            $where['status']=$request['status'];
        }if($request['status'] == '0'){
            $where['status'] = 0;
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['partner_trade_no']  = array('like', "%$keyword%");
            $keyword_complex['payment_no']  = array('like',"%$keyword%");
            $keyword_complex['name']  = array('like',"%$keyword%");
            $keyword_complex['phone']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $device=$this->Dserefund
            ->where($where)
            ->count();
        $page = $this->page($device,30);
        $list = $this->Dserefund
            ->where($where)
            ->order("apple_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign("page", $page->show('Admin'));
        $this->assign("list",$list);
        $this->display();
    }
    //审核
    public function check_status(){
        $id = I('get.id');
        if(empty($id)) exit;
        $res = $this->Dserefund->where(array('id'=>$id))->find();
        //判断余额是否足够
        $pay_count = M('goods_weixin_alipay_pay_rec')
            ->where(array('wechat_alipay_id'=>$res['openid'],
                'pay_status'=>'1','is_close'=>'0','del_flag'=>'0','type'=>'1'))->sum('pay_account');
        $consume_count = M('goods_consume_rec')->where(array('type'=>'1','wechat_alipay_id'=>$res['openid']
        ,'command_status'=>array('in','1,2'),'is_close'=>'0','del_flag'=>'0'))->sum('consume_account');
        $counts = $pay_count-$consume_count;
        if($counts<=0){
            $count = 0;
        } else {
            $count = $counts;
        }
        if($res['status'] != '0'){
            $this->error('该用户已经审核了');
        }
        $this->assign('res',$res);
        $this->assign('pay',$count);
        $this->display();
    }
    public function check_ajax(){
        if(IS_POST){
            $this->error('该功能正在更新');
            $id = $_POST['id'];
            $openid = $_POST['openid'];
            if(empty($openid)) $this->error('openid为空');
            $status = $_POST['status'];
            if(empty($status)){
                $this->error('请选择审核状态');
            }
            if($status == '2' || $status == '0'){
                $resd['status'] = '2';
                $resd['update_date'] = date('Y-m-d H:i:s',time());
                $resd['payment_no'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);;
                $resu = M('vending_userefund')->where(array('id'=>$id,'openid'=>$openid))->save($resd);
                if($resu){
                    $this->success('确认成功',U('index'));
                }else{
                    $this->error('确认失败');
                }
            }else{
                $pay = intval($_POST['pay']);//实际退款金额
                //$pay = 4;//实际退款金额
                if($pay<=0){
                    $this->error('用户余额不足，不必审核');
                }
                if($pay>=100){
                    $this->error('金额大于20元，请开发人员审核');
                }
                $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
                $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m')))
                    . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
                $mchPay = new \WxMchPay();
                $mchPay->setParameter('openid',$openid);
                $mchPay->setParameter('partner_trade_no', $orderSn);
                $mchPay->setParameter('check_name', 'NO_CHECK');
                $mchPay->setParameter('amount', $pay*100);
                $mchPay->setParameter('desc', '玫瑰物联');
                $mchPay->setParameter('spbill_create_ip',GetIP());
                $response = $mchPay->postXmlSSLCurl_init();
                if(!empty($response)){
                    $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
                    $array_no = json2arr($data);
                    if(empty($array_no['payment_time']) || !isset($array_no['payment_time'])){
                        $this->error('微信后台账号余额不足，请充值');
                    }
                    if($array_no['result_code'] == 'SUCCESS' || $array_no['return_code'] == 'SUCCESS'){
                        $resd['status'] = '1';
                        $resd['update_date'] = date('Y-m-d H:i:s',time());
                        $resd['payment_no'] = $array_no['payment_no'];
                        $resd['arrival'] = $pay;
                        M('vending_userefund')->where(array('id'=>$id,'openid'=>$openid))->save($resd);
                        $dataed['id'] = generateNum();
                        $dataed['type'] = '1';
                        $dataed['wechat_alipay_id'] = $openid;
                        $dataed['command_status'] = 2;
                        $dataed['consume_account'] = $pay;
                        $dataed['create_by'] = $openid;
                        $dataed['create_date'] = date('Y-m-d H:i:s',time());
                        $dataed['create_by'] = $openid;
                        $dataed['remarks'] = '用户退款';
                        M('goods_consume_rec')->add($dataed);
                        $this->success('退款成功',U('index'));
                    }else{
                        $this->error($array_no['err_code_des']);
                    }
                }else{
                    $this->error('退款失败');
                }
            }
        }
    }
}