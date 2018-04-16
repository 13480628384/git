<?php
class PayAction extends Action{
    protected function _initialize(){
        $typesd = $_SERVER['HTTP_USER_AGENT'];
        if( !strpos($typesd,'MicroMessenger')>0 && !strpos($typesd,'AlipayClient') > 0){
            //exit('请用微信或支付宝打开');
        }
    }
}
?>