<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16
 * Time: 10:46
 */
class WapApiAction extends VendBackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
    }
    //设备点击发送请求接口
    public  function  onenet_post(){
        require_once "util.php";
        $msg = array('code'=>'200','msg'=>'onenet1234');
        echo json_encode($msg);
        exit;
        $raw_input = file_get_contents('php://input');
        $resolved_body = Util::resolveBody($raw_input);
        //$array = str_replace('}','',$json);
        $count=strpos($resolved_body,"}");
        $str=substr_replace($resolved_body,"",$count,1);
        $array = json_decode($str);
        $logpath = LOG_PATH.'onenet_post.log';
        $array_res = object_arrays($array);
        if(!is_array($array_res)){
            $msg = array('code'=>'500','msg'=>'空数据');
            Log::write(json_encode($msg),'result','',$logpath,'');
            exit;
        }
        $Code = make_rand();
        //$result = cashing('13824774573',$Code);
        Log::write($resolved_body,'INFO','',$logpath,'');
        $nums = isset($array_res['OP_HD'])?$array_res['OP_HD']:0;//通道号
        $device_command = isset($array_res['dev_id'])?$array_res['dev_id']:0;//设备号
        $phone = isset($array_res['TEL_CHK'])?$array_res['TEL_CHK']:0;
        $passwd = isset($array_res['PWR_CHK'])?$array_res['PWR_CHK']:0;

    }
}