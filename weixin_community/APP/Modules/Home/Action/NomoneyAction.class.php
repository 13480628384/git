<?php
require_once('WxPay.JsApiPay.php');
class NomoneyAction extends BaseAction{
    public function index(){
        //$openid = 'og5WUjmApU2pOqbZlxrppXCNhsio';
        //$tools = new JsApiPay();
        //$openid = $tools->GetOpenid();
        $group_id = trim($_GET['group_id']);
        $openid = trim($_GET['openid']);
        $mchid = trim($_GET['mchid']);
        $device_command = trim($_GET['device_command']);
        $doubi = M('card_config')->query("SELECT  cc.*  FROM	card_config cc ,card_merchant cm WHERE	cc.card_status = '1'
				and cc.type=1 AND  cm.id = cc.merchant_id AND  TO_DAYS(NOW()) BETWEEN TO_DAYS(cc.begin_timestamp) AND
				 TO_DAYS(cc.end_timestamp) AND cc.del_flag = '0' AND cc.surplus_quantity > 0 and
				 INSTR(cm.default_group_id ,'$group_id')> 0 ORDER BY	rand()");
        //定金
        $area = $this->community->get_row("select area_id from device_group_info where id='$group_id' and del_flag=0");
        $area = object_array($area);
        $area_id = $this->community->get_row("select id,parent_ids from sys_area where del_flag=0 and id='$area[area_id]'");
        $area_id = object_array($area_id);
        $ads = $this->community->get_results("select * from card_deposit where del_flag=0 order by create_date desc");
        $ads = object_array($ads);
        //删除逗号
        $parent_ids = trim($area_id['parent_ids'],',');
        //组成数组
        $parent_ids = explode(',',$parent_ids);
        $this->assign('doubi',$doubi);
        $this->assign('ads',$ads);
        $this->assign('mchid',$mchid);
        $this->assign('openid',$openid);
        $this->assign('group_id',$group_id);
        $this->assign('device_command',$device_command);
        $this->assign('parent_ids',$parent_ids);
        $this->display();
    }
    public function insert_doubi(){
        $user_id = $_POST['user_id'];
        $merchant_id = $_POST['merchant_id'];
        $card_config_id = $_POST['card_config_id'];
        $quantity = $_POST['quantity'];
        $device_code = $_POST['device_code'];
        $ISSET = M('card_doubi')->where(array('type'=>1,'card_config_id'=>$card_config_id,'user_id'=>$user_id))->find();
        //echo M('card_doubi')->getLastSql();die;
        if($ISSET){
            echo json_encode(array('code'=>40001,'msg'=>'你已经领取了'));
            exit;
        }
        $doubi_info = array(
            'id' => guid(),
            'user_id' => $user_id,
            'merchant_id' => $merchant_id,
            'card_config_id' => $card_config_id,
            'quantity' => $quantity,
            'coin_type' => '2',
            'coin_status' => '0',
            'user_type' => '1',
            'device_code'=>$device_code,
            'create_by'=>$user_id,
            'update_by'=>$user_id,
            'create_date' => date('Y-m-d H:i:s', time()),
            'update_date' => date('Y-m-d H:i:s', time())
        );
        $receive = M('card_doubi')->add($doubi_info);
        if($receive){
            echo json_encode(array('code'=>200,'msg'=>'领取成功'));
        }else{
            echo json_encode(array('code'=>40002,'msg'=>'领取失败'));
        }
    }
    /*
     * author chw
     * time 2016-07-07
     * param 对象转为数组
     *
     * */
    public function object_array($array){
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }
}
?>