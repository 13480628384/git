<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:46
 */
class Glass_tokenAction extends VendBackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    //设备点击发送请求接口
    public  function  post(){
        require_once "util.php";
        $raw_input = file_get_contents('php://input');
        $resolved_body = Util::resolveBody($raw_input);
        $body = json_decode($raw_input, TRUE);
        //$array = str_replace('}','',$json);
        $count=strpos($resolved_body,"}");
        $str=substr_replace($resolved_body,"",$count,1);
        $array = json_decode($str);
        $logpath = LOG_PATH.'glass.log';
        Log::write($resolved_body,'INFO','',$logpath,'');
        $array_res = object_arrays($array);
        if(!is_array($array_res)){
            $msg = array('code'=>'500','msg'=>'空数据');
            Log::write(json_encode($msg),'result','',$logpath,'');
            exit;
        }
            $nums = isset($array_res['OP_HD'])?$array_res['OP_HD']:0;//通道号
            $device_command = isset($array_res['dev_id'])?$array_res['dev_id']:0;//设备号
            $phone = isset($array_res['TEL_CHK'])?$array_res['TEL_CHK']:0;
            $passwd = isset($array_res['PWR_CHK'])?$array_res['PWR_CHK']:0;
            //找出openid
            $result = M('glass_user')->where(array('phone'=>$phone,'password'=>$passwd))->find();
            $result_phone = M('glass_user')->where(array('phone'=>$phone))->find();
            $qos = '1'; //1需要响应  0 不需要响应
            $timeout = '0';//为“秒”，默认“0”
			Log::write(json_encode($resolved_body),'22','',$logpath,'');
            if(!$result_phone){
                $sms = array('TEL_PROC'=>'3','OP_HD'=>$nums,'TEL_CHK'=>$phone,);
                Log::write(json_encode($sms),'no phone','',$logpath,'');
                $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                exit;
            }
            if($result){
                //判断是否还能不能可以玩，即次数是否大于0
                if($result['time_nums'] <= 0){
                    $sms = array('TEL_PROC'=>'1','OP_HD'=>$nums,'TEL_CHK'=>$phone);
                    $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                    exit;
                }
                Log::write(json_encode($result),'result4','',$logpath,'');
                //找出设备id
                $device_id = M('device_info')->where(array('device_command'=>$device_command,'del_flag'=>'0'))->getField('id');
                //余额充足，启动消费
                $sms = array("OP_HD"=>intval($nums),'TG_TIME'=>'1800','TG_MES'=>only_order());
                $res = $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                if(!empty($res)){
                    //消费成功减去次数
                    M('glass_user')->where(array('phone'=>$phone,'password'=>$passwd))->setDec('time_nums');
                    $this->glass->send_data_to_edp($device_command, $qos, $timeout,
                        array('TEL_PROC'=>'0','OP_HD'=>$nums,'TEL_CHK'=>$phone));
                }
            } else {
                $sms = array('TEL_PROC'=>'2','OP_HD'=>$nums,'TEL_CHK'=>$phone);
                $this->glass->send_data_to_edp($device_command, $qos, $timeout, $sms);
                exit;
            }
    }

}