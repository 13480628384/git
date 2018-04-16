<?php
class Rose2AlllistAction extends Rose2BaseAction {
    /*====微信收益列表 [[========*/
    public function weixin_list(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $model = M('device_consume_rec');
            $weixin_list = $model
                ->where(array(
                    'del_flag'=>0,
                    'command_status'=>2,
					'consume_status'=>1,
                    'type'=>array('in','1,3,5,9,11,13,15,17'),
                    'transfer_status'=>0,
                    'create_by'=>$this->user_id
                ))
                ->field('type,sum(consume_account) as consume_account,command_status,create_date')
                ->group('create_date')
                ->order('create_date desc')
                ->limit($n,20)
                ->select();
            $this->assign('openid',$this->openid);
            $this->assign('weixin_list',$weixin_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2Alllist_weixin_list_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $weixin_list = $model
                ->where(array(
                    'del_flag' => 0,
					'consume_status'=>1,
                    'command_status' => 2,
                    'type' =>array('in','1,3,5,9,11,13,15,17'),
                    'transfer_status' => 0,
                    'create_by'=>$this->user_id
                ))
                ->field('type,sum(consume_account) as consume_account,command_status,create_date')
                ->group('create_date')
                ->order('create_date desc')
                ->limit(20)
                ->select();
            $this->assign('openid', $this->openid);
            $this->assign('weixin_list', $weixin_list);
            $this->display();
        }
    }
    /*====微信收益列表 ]]========*/

    /*====支付宝收益列表 [[========*/
    public function alipay_list(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $model = M('device_consume_rec');
            $alipay_list = $model
                ->where(array(
                    'del_flag'=>0,
					'consume_status'=>1,
                    'command_status'=>2,
                    'type'=> array('in','2,4,6,10,12,14,16,18'),
                    'transfer_status'=>0,
                    'create_by'=>$this->user_id
                ))
                ->field('type,consume_account,command_status,create_date')
                ->order('create_date desc')
                ->limit($n,20)
                ->select();
            $this->assign('openid',$this->openid);
            $this->assign('alipay_list',$alipay_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2Alllist_alipay_list_page.html');
            exit($html);
        } else {
            $model = M('device_consume_rec');
            $alipay_list = $model
                ->where(array(
                    'del_flag' => 0,
					'consume_status'=>1,
                    'command_status' => 2,
                    'type' =>array('in','2,4,6,10,12,14,16,18'),
                    'transfer_status' => 0,
                    'create_by'=>$this->user_id
                ))
                ->field('type,consume_account,command_status,create_date')
                ->order('create_date desc')
                ->limit(20)
                ->select();
            $this->assign('openid', $this->openid);
            $this->assign('alipay_list', $alipay_list);
            $this->display();
        }
    }
    /*====支付宝收益列表 ]]========*/

    /*====广告收益列表 [[========*/
    public function adv_list(){
        if(IS_POST){
            $n=$_POST['n']*20;
            $model = M('device_info');
            $adv_list = $model->alias('di')
                ->join('left join adv_uservisit_rec pwi ON pwi.di_id = di.id')
                ->join('LEFT JOIN adv_weixin_info awi ON awi.id = pwi.adv_id')
                ->where(array(
                    'pwi.status' => 2,
                    'pwi.del_flag' => 0,
                    'pwi.transfer_status' =>0,
                    'awi.status' =>1,
                    'awi.del_flag' => 0,
                    'di.office_id' => $this->office_id,
                    'di.del_flag'=>0
                ))
                ->field('awi.per_price,pwi.status,pwi.create_date,awi.type')
                ->limit($n,20)
                ->select();
            $this->assign('openid',$this->openid);
            $this->assign('adv_list',$adv_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2Alllist_adv_list_page.html');
            exit($html);
        } else {
            $model = M('device_info');
            $adv_list = $model->alias('di')
                ->join('left join adv_uservisit_rec pwi ON pwi.di_id = di.id')
                ->join('LEFT JOIN adv_weixin_info awi ON awi.id = pwi.adv_id')
                ->where(array(
                    'pwi.status' => 2,
                    'pwi.del_flag' => 0,
                    'pwi.transfer_status' =>0,
                    'awi.status' =>1,
                    'awi.del_flag' => 0,
                    'di.office_id' => $this->office_id,
                    'di.del_flag'=>0
                ))
                ->field('awi.per_price,pwi.status,pwi.create_date,awi.type')
                ->limit(20)
                ->select();
            $this->assign('openid', $this->openid);
            $this->assign('adv_list', $adv_list);
            $this->display();
        }
    }
    /*====广告收益列表 ]]========*/

    /*====线下收益列表 [[========*/
    public function line_list(){
        if(IS_POST){
            $n = $_POST['n']*20;
            /*$line_list = M('device_record')->alias('dr')->join("device_info di on dr.dev_id=di.device_command")
                ->where(array('di.del_flag' => 0, 'di.office_id' => $this->office_id))
                ->field('dr.Number_coins,dr.dev_id,dr.create_date')
                ->order("dr.create_date desc")
                ->limit($n,20)
                ->select();*/
            $model = new model();
            $line_list = $model->query("SELECT * from (SELECT * from (SELECT dr.* FROM `device_info` LEFT JOIN device_record
 dr on dr.dev_id=device_info.device_command WHERE ( device_info.del_flag = 0 )
 AND ( device_info.office_id = '$this->office_id' ) ORDER BY dr.LOC_OP desc)
 da GROUP BY da.dev_id ORDER BY da.LOC_OP desc) dw where id!='' limit $n,20");
            if (empty($line_list)) {
                $line_list = 0;
            }
            $this->assign('openid', $this->openid);
            $this->assign('line_list', $line_list);
            $html=$this->fetch('./tpl/Wap/default/Rose2Alllist_line_list_page.html');
            exit($html);
        } else {

            /*$line_list = M('device_record')->alias('dr')->join("device_info di on dr.dev_id=di.device_command")
                ->where(array('di.del_flag' => 0, 'di.office_id' => $this->office_id))
                ->field('dr.Number_coins,dr.dev_id,dr.create_date')
                ->order("dr.create_date desc")
                ->limit(20)
                ->select();*/
            $model = new model();
            $line_list = $model->query("SELECT * from (SELECT * from (SELECT dr.* FROM `device_info` LEFT JOIN device_record
 dr on dr.dev_id=device_info.device_command WHERE ( device_info.del_flag = 0 )
 AND ( device_info.office_id = '$this->office_id' ) ORDER BY dr.LOC_OP desc)
 da GROUP BY da.dev_id ORDER BY da.LOC_OP desc) dw where id!=''");
            if (empty($line_list)) {
                $line_list = 0;
            }
            $this->assign('openid', $this->openid);
            $this->assign('line_list', $line_list);
            $this->display();
        }
    }
    /*====线下收益列表 ]]========*/
}