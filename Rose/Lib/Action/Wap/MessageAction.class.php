<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:46
 */
class MessageAction extends VendBackAction{
    protected function _initialize(){
        parent::_initialize();
        define('CURRENT_FILE_PATH',dirname(__FILE__) );
        define('CUL','http:/wxpay.roseo2o.com/Rose/Lib/Action/Wap');
    }
    public  function  post_message(){
        require_once "util.php";
        $raw_input = file_get_contents('php://input');
        $resolved_body = Util::resolveBody($raw_input);
        $data = $_GET;
        //$array = str_replace('}','',$json);
        $count=strpos($resolved_body,"}");
        $str=substr_replace($resolved_body,"",$count,1);
        $array = json_decode($str);
        $logpath = LOG_PATH.'message.log';

        Log::write(json_encode($data),'INFO','',$logpath,'');
        $array_res = object_arrays($array);
        Log::write($resolved_body,'result','',$logpath,'');
        /*$Code = make_rand();
        $result = message('13824774573',$Code);*/
    }
}