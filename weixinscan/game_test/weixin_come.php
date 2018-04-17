<?php
include "mysql/mysqldbwrite.php";
$weixin_count = $db->get_var("select sum(consume_account) consume_account from device_consume_rec where create_date>CURDATE() and del_flag=0 and transfer_status=0 and consume_status in(1,2) and command_status=2");
$weixin_pay = $db->get_var("select sum(pay_account) pay_account from weixin_pay_rec where create_date>CURDATE() and del_flag=0 and pay_status=1");

if($db->get_row('SELECT * from weixin_come where create_date>CURDATE()')){
    //更新今天的数量
    $date = date('Y-m-d H:i:s',time());
    $where_come = (empty($weixin_count))? 0:$weixin_count;
    $where_pay = (empty($weixin_pay))? 0:$weixin_pay;
    $res = $db->query("update weixin_come set weixin_pay='$where_pay',weixin_decome='$where_come',update_date='$date'  where create_date>CURDATE()");

} else {
    //今天还没有插入
    $data = array(
        'id'=>generateNum(),
        'weixin_decome'=>$weixin_count,
        'weixin_pay'=>$weixin_pay,
        'create_date'=>date('Y-m-d H:i:s',time()),
        'update_date'=>date('Y-m-d H:i:s',time())
    );
    $result = $db->query("INSERT INTO weixin_come SET " .$db->get_set($data));
}
function generateNum() {
    //strtoupper转换成全大写的
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
    return date('YmdH_',time()).$uuid;
}