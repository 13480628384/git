<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:46
 */
class Glass_notifyAction extends VendBackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    public function notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'weixin_pay2.log';
        Log::write($xml,'INFO','',$logpath,'');
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        if($notify->checkSign() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['pay_status'] = 1;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no']))->save($da);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no']))->save($da);
        }
    }
    //会员通知
    public function chongzhi_notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'weixin_pay2.log';
        Log::write($xml,'INFO','',$logpath,'');
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        if($notify->checkSign() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['pay_status'] = 1;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no'],'type'=>2))->save($da);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no'],'type'=>2))->save($da);
        }
    }
    public function test(){
        $sms = array("OP_HD"=>intval(4),'TG_TIME'=>'1800','TG_MES'=>only_order());
        $res = $this->glass->send_data_to_edp(22806437, 1, 0, $sms);
        p($res);die;
    }
    //设备点击发送请求接口
    public  function  Clicks_post(){
        require_once "util.php";
        $raw_input = file_get_contents('php://input');
        $resolved_body = Util::resolveBody($raw_input);
        //$array = str_replace('}','',$json);
        $count=strpos($resolved_body,"}");
        $str=substr_replace($resolved_body,"",$count,1);
        $array = json_decode($str);
        $logpath = LOG_PATH.'glass.log';
        //Log::write($raw_input,'INFO','',$logpath,'');
        $array_res = object_arrays($array);
        Log::write($resolved_body,'result','',$logpath,'');
        if(isset($array_res['TEL_CHK']) && isset($array_res['PWR_CHK'])){
            $nums = isset($array_res['OP_HD'])?$array_res['OP_HD']:0;//通道号
            $device_command = isset($array_res['dev_id'])?$array_res['dev_id']:0;//设备号
            $phone = isset($array_res['TEL_CHK'])?$array_res['TEL_CHK']:0;
            $passwd = isset($array_res['PWR_CHK'])?$array_res['PWR_CHK']:0;
            if($phone == '13823564759'){
                Log::write($phone,'result','',$logpath,'');
                exit;
            }
            //找出openid
            $result = M('glass_user')->where(array('phone'=>$phone,'password'=>$passwd))->find();
            $result_phone = M('glass_user')->where(array('phone'=>$phone))->find();
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
            if(!$result_phone){
                $sms = array('TEL_PROC'=>intval(3),'OP_HD'=>intval($nums),'TEL_CHK'=>"$phone");
                $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                Log::write(json_encode($sms),'no phone','',$logpath,'');
                exit;
            }
            if($result){
                //判断是否还能不能可以玩，即次数是否大于0
                if($result['time_nums'] <= '0'){
                    $sms = array('TEL_PROC'=>intval(1),'OP_HD'=>intval($nums),'TEL_CHK'=>"$phone");
                    $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                    Log::write($result['time_nums'],'count<','',$logpath,'');
                    exit;
                }
                //Log::write($result['time_nums'],'count<','',$logpath,'');
               //余额充足，启动消费
                $sms = array("OP_HD"=>intval($nums),'TG_TIME'=>'1800','TG_MES'=>only_order());
                $res = $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);//发送启动
                if($res){
                    //消费成功减去次数
                    $sql = M('glass_user')->where(array('phone'=>$phone,'password'=>$passwd))->setDec('time_nums');
                    sleep(2);
                    $sms1 = array('TEL_PROC'=>intval(0),'OP_HD'=>intval($nums),'TEL_CHK'=>"$phone");
                    $r = $this->glass->send_data_to_edp($device_command, $qos, $timeout,$sms1);
                    Log::write(json_encode($r),'cmd_uuid','',$logpath,'');
                }
            }else{
                $sms = array('TEL_PROC'=>intval(2),'OP_HD'=>intval($nums),'TEL_CHK'=>"$phone");
                $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                exit;
            }
        }

    }
    //vip 充值
    public function vip_chongzhi_notify(){
        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logpath = LOG_PATH.'wei222.log';
        Log::write($xml,'INFO','',$logpath,'');
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        if($notify->checkSign() == TRUE)
        {
            $paydata = $notify->getData();
            if ($notify->data["result_code"] == "SUCCESS") {
                $da['transaction_id'] = $paydata['transaction_id'];
                $da['pay_status'] = 1;
                $da['update_date'] = date('Y-m-d H:i:s',time());
                M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                    'out_trade_no'=>$paydata['out_trade_no'],'type'=>2))->save($da);
            }
        }else{
            $paydata = $notify->getData();
            $da['transaction_id'] = $paydata['transaction_id'];
            $da['pay_status'] = 1;
            $da['update_date'] = date('Y-m-d H:i:s',time());
            M('glass_pay_rec')->where(array('from_username'=>$paydata['openid'],
                'out_trade_no'=>$paydata['out_trade_no'],'type'=>2))->save($da);
        }
    }
}