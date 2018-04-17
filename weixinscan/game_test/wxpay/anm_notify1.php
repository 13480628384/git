<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'log.php';
require_once('../mysql/mysqldbwrite.php');
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
    private $db;

    public function  setDb($db){
        $this->db = $db;
    }
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    // where组装
    private function get_where($parms)
    {
        $sql = '';
        foreach ( $parms as $field => $val )
        {
            if ( $val === 'true' ) $val = 1;
            if ( $val === 'false' ) $val = 0;

            if ( $val == 'NOW()' )
            {
                $sql .= "$field = ".$val." and ";
            }
            else
            {
                $sql .= "$field = '".$val."' and ";
            }
        }

        return substr($sql,0,-4);
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        //更新充值pay_info记录，商户订单号
        if(array_key_exists("out_trade_no", $data)){
            $out_trade_no = $data["out_trade_no"];

            $pay_info_status = array(
                'pay_status' => '1',
                'transaction_id' =>$data["transaction_id"]
            );
            $pay_where = array('out_trade_no'=>$out_trade_no,'from_username'=>$data["openid"]);

            $update_sql = "update weixin_pay_rec Set ".$this->db->get_set($pay_info_status)."  WHERE ". $this->get_where($pay_where) ;
            $update_result = $this->db->query($update_sql);
            Log::DEBUG("notify out_trade_no=$out_trade_no , result=$update_result");

            //更新微信用户信息表
            $now = date("Y-m-d H:i:s");
            $app_id = $data['appid'];
            $from_username = $data['openid'];
            //1.查询总充值
            $weixin_pay_total_sql  = "select sum(p.pay_account) pay_accounts from weixin_pay_rec p
        where pay_status = '1'  and is_close = '0'  and del_flag = '0' and p.app_id = '$app_id'
        and p.from_username = '$from_username' " ;
            $weixin_pay_total_result = $this->db->get_var($weixin_pay_total_sql);


            //2.更新总充值 update_by 3 标示异步更新
            /* $weixin_userinfo_account = array(
                 'pay_total_account' => $weixin_pay_total_result ,
                 'update_date' =>$now,
                 'update_by'=>'3'
             );
             $weixin_userinfo_where  = array('app_id'=>$app_id,'from_username'=>$from_username);
             $update_weixin_userinfo_sql = "update weixin_userinfo Set ".$this->db->get_set($weixin_userinfo_account)."  WHERE ". $this->get_where($weixin_userinfo_where) ;
             $update_weixin_userinfo_result = $this->db->query($update_weixin_userinfo_sql);
             Log::DEBUG("notify update_weixin_userinfo_sql=$update_weixin_userinfo_sql , result=$update_weixin_userinfo_result");*/
            return true;
        }
        return true;
    }
}
Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->setDb($db);
$notify->Handle(false);