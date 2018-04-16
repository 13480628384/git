<?php
require_once("JsApiPaySub.php");
class NewPayAction extends BackSubAction
{
    //洗车微信支付归属到商户账户
    public function car_pay_owner(){
        if(IS_POST){
            $price = $_POST['price'];
            $openid = trim($_POST['openid']);
            $device_command = trim($_POST['device_command']);
            $user = M('device_info')->where(array('device_command'=>$device_command,
                'del_flag'=>'0'))->find();
            //调用支付接口  start
            $out_trade_no = generateId();
            $tools = new JsApiPaySub();
            $input = new WxPayUnifiedOrder();
            $input->SetBody("共享洗车-智能终端充值：".$price."元");
            $input->SetAttach("深圳玫瑰物联技术有限公司提供");
            $input->SetOut_trade_no($out_trade_no);
            $input->SetTotal_fee($price*100);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("1");
            $input->SetNotify_url(get_dirname_url().'Wap/Notify/fennotify');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openid);
            $input->SetSub_Mch_id('1500171152');
            $order = Api::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            //调用支付接口 end
            if($price == '199'){
                $car_member = array(
                    'openid'=>$openid,
                    'account'=>360,
                    'status'=>0,
                    'out_trade_no'=>$out_trade_no,
                    'create_by'=>$user['owner_id'],
                    'create_date'=>date('Y-m-d H:i:s',time()),
                    'update_date'=>date('Y-m-d H:i:s',time())
                );
                M('car_member_pay')->add($car_member);
            }else{
                $weixin_pay_rec = array(
                    'id' => generateNum(),
                    'app_id' =>'wx6a9d4afeace38e53',
                    'from_username' => $openid,
                    'pay_status'=>'0',
                    'out_trade_no'=>$out_trade_no,
                    'pay_account' => $price,
                    'is_close' => 1,
                    'prepay_id'=>1,
                    'contents' => $jsApiParameters,
                    'create_date'=>date('Y-m-d H:i:s',time()),
                    'create_by'=>$user['owner_id'],
                    'owner_id'=>$user['owner_id'],
                    'update_by'=>'1',
                    'update_date'=>date('Y-m-d H:i:s',time())
                );
                M('weixin_pay_rec')->add($weixin_pay_rec);
            }
            if(!empty($user['car_fen'])){
                $car_fen = explode(',',$user['car_fen']);
                $array = array();
                foreach ($car_fen as $k){
                    $array[][] = $k;
                }
                foreach ($array as $k){
                    $fen = explode('-',$k[0]);
                    /*用户充值的钱直接到商户账上*/
                    $car_pay = array(
                        'id'=>generateNum(),
                        'account'=>($fen[1]/100*$price),
                        'status'=>'0',
                        'di_id'=>$user['id'],
                        'device_command'=>$device_command,
                        'out_trade_no'=>$out_trade_no,
                        'openid'=>$openid,
                        'create_by'=>$fen[0],
                        'create_date' => date('Y-m-d H:i:s',time()),
                        'update_date' => date('Y-m-d H:i:s',time())
                    );
                    M('car_pay')->add($car_pay);
                }
            }
            if($jsApiParameters){
                echo json_encode(array('jsapi'=>$jsApiParameters,'out_trade_no'=>$out_trade_no,'code'=>200));
            } else {
                json_encode(array('code'=>500,'error'=>'支付异常，请重新支付'));
            }
        }
    }
    public function car_update_owner(){
        $openid = trim($_POST['openid']);
        $out_trade_no = trim($_POST['out_trade_no']);
        $device_command = trim($_POST['device_command']);
        $price = trim($_POST['price']);
        $user = M('device_info')->where(array('device_command'=>$device_command,
            'del_flag'=>'0'))->find();
        $data['pay_status'] = 1;
        $data['update_date'] = date('Y-m-d H:i:s',time());
        $data['update_by'] = 2;
        //更新运营商得到的钱
        $desc['status'] = '1';
        $desc['update_date'] = date('Y-m-d H:i:s',time());
        $ca = M('car_pay')->where(array(
            'out_trade_no'=>$out_trade_no,
            'openid'=>$openid
        ))->save($desc);
        //会员充值
        if($price == '199'){
            $car_memda['status'] = 1;
            $car_memda['update_date'] = date('Y-m-d H:i:s',time());
           $rec= M('car_member_pay')->where(array(
                'out_trade_no'=>$out_trade_no,
                'openid'=>$openid
            ))->save($car_memda);
        } else{
            //更新微信支付表
            $weixin_id = M('weixin_pay_rec')->where(array(
                'from_username'=>$openid,
                'out_trade_no'=>$out_trade_no,
                'app_id'=>'wx6a9d4afeace38e53'))
                ->save($data);
        }
        //echo json_encode(array('code'=>200,'msg'=>intval(2),'mr'=>M('car_member_pay')->getLastSql()));die;
        $total1 = M('weixin_pay_rec')->where(array('from_username'=>trim($openid),
            'pay_status'=>'1','is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id']))->sum('pay_account');
        $total2 = M('device_consume_rec')->where(array('from_username'=>trim($openid),
            'is_close'=>1,'del_flag'=>0,'owner_id'=>$user['owner_id'],'consume_status'=>'2','command_status'=>array('in','1,2')))->sum('consume_account');
        $count = $total1-$total2;
        $all = '';
        if($count <=0 ){
            $all = 0;
        } else {
            $all = $count;
        }
        $car_member = M('car_member_pay')->where(array('openid'=>$openid,
            'status'=>'1'))->sum('account');
        if($weixin_id||$rec){
            echo json_encode(array('codes'=>200,'msg'=>intval($all),'mr'=>intval($car_member)));
        } else {
            echo json_encode(array('codes'=>201,'msg'=>intval($all),'mr'=>intval($car_member)));
        }
    }
}