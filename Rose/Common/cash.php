<?php
/**
 * Created by PhpStorm.
 * User: zhang
 * Date: 2015/6/1
 * Time: 10:37
 */
class cash{
    private $sToken = null;
    private $sOpenid = null;
    public $ret = false;
    public function __construct($sToken='', $iSenceID = '',$sOpenid='')
    {
        $sToken = trim($sToken);
        if (empty($sToken) && empty($sOpenid)) {
            return false;
        }
        $this->sOpenid  = $sOpenid;
        $this->sToken   = $sToken;
        $this->iSenceID = $iSenceID; //活动id
    }

    public function cash_info(){
    	$this->ret = false;
        Vendor('wxredPacket.oauth2');//引入第三方库
        $oWeipayModel = M('Weipay_config');
        $appConfigData = $oWeipayModel->where(array('token'=>$this->sToken))->find();
        $oWxredModel = M('Wxredpay');
        $aRedcode = $oWxredModel->where(array('id'=>$this->iSenceID))->find();
        $aCashmoney = json_decode($aRedcode['redchance'],true);
        $aRedpay_key = array_values($aCashmoney);
        $aCashmoney_key = array_keys($aCashmoney);
        $randkey = rand(1,100);
        if($randkey>0 && $randkey<=$aRedpay_key[0]){
            $aRedpay_key_info = explode('-',$aCashmoney_key[0]);
            $ceshiredpay = rand($aRedpay_key_info[0]*100,$aRedpay_key_info[1]*100);
        }elseif($randkey>$aRedpay_key[0] && $randkey<=($aRedpay_key[0]+$aRedpay_key[1])){
            $aRedpay_key_info = explode('-',$aCashmoney_key[1]);
            $ceshiredpay = rand($aRedpay_key_info[0]*100,$aRedpay_key_info[1]*100);
        }elseif($randkey>($aRedpay_key[0]+$aRedpay_key[1]) && $randkey<=($aRedpay_key[0]+$aRedpay_key[1]+$aRedpay_key[2])){
            $aRedpay_key_info = explode('-',$aCashmoney_key[2]);
            $ceshiredpay = rand($aRedpay_key_info[0]*100,$aRedpay_key_info[1]*100);
        }elseif($randkey>($aRedpay_key[0]+$aRedpay_key[1]+$aRedpay_key[2]) && $randkey<=($aRedpay_key[0]+$aRedpay_key[1]+$aRedpay_key[2]+$aRedpay_key[3])){
            $aRedpay_key_info = explode('-',$aCashmoney_key[3]);
            $ceshiredpay = rand($aRedpay_key_info[0]*100,$aRedpay_key_info[1]*100);
        }elseif($randkey>($aRedpay_key[0]+$aRedpay_key[1]+$aRedpay_key[2]+$aRedpay_key[3]) && $randkey<=($aRedpay_key[0]+$aRedpay_key[1]+$aRedpay_key[2]+$aRedpay_key[3]+$aRedpay_key[4])){
            $aRedpay_key_info = explode('-',$aCashmoney_key[4]);
            $ceshiredpay = rand($aRedpay_key_info[0]*100,$aRedpay_key_info[1]*100);
        }
        $ceshiredpay = round($ceshiredpay/100,2);
	WL($ceshiredpay);
	WL(print_r($aRedpay_key_info, true));
        /* $this->app_mchid.date('YmdHis').rand(1000, 9999)商户号*/
        $date = date('Y-m-d H:i:s');
        $redusercount = intval(M('Redpay_user')->where(array('rid'=>$this->iSenceID,'openid'=>$this->sOpenid))->count());
        $dayreduser = intval(M('Redpay_user')->where(array('rid'=>$this->iSenceID,'openid'=>$this->sOpenid,'date'=>date('Y-m-d')))->count());
        $iMannum = intval(M('Redpay_user')->where(array('rid'=>$this->iSenceID))->count());
        $redtale = intval(M('Redpay_user')->where(array('rid'=>$this->iSenceID))->sum('amount'));
        if($aRedcode['is_open'] ==1){
            $str =  "活动未开启！";
            return $str;
        }else{
            if($aRedcode['strattime']>$date || $aRedcode['endtime']<=$date ){
                $str =  "现在活动时间范围内！";
                return $str;
            }else{
                if($aRedcode['total_num'] <= $iMannum){
                    $str =  "该活动的红包已发完！";
                    return $str;
                    //echo "该活动的红包已发完!";
                }else{
                    if($redtale>=$aRedcode['total_amount']){
                        $str =  "该活动的红包已发完！";
                        return $str;
                    }else{
                        if($aRedcode['maxnum'] <= $redusercount){
                            $str =  "你获取红包的次数已用完！";
                            return $str;
                        }else{
                            if($aRedcode['daynum'] <= $dayreduser){
                                $str =  "你今天获取红包的次数已用完！";
                                return $str;
                            }else{
                                $aRedcode['money'] = $ceshiredpay;
                                $aRedcode['mch_billno'] = $appConfigData['partnerkey'].date('YmdHis').rand(1000, 9999);
                                $info = $aRedcode;

                                $wxRedPacket = new Wxapi($appConfigData['appid'],$appConfigData['appsecret'],$appConfigData['partnerkey'],$appConfigData['appkey'],$info,$this->sToken);
                                $return = $wxRedPacket->pay($this->sOpenid);
                                if($return->return_code == "SUCCESS"){
                                    $this->setuserinfo($aRedcode['mch_billno'],$aRedcode['money']);
                                    $str = "下载APP即可领取微信现金红包！";
				    $this->ret = true;
                                    return $str;
                                }else{
				     $str = $return->return_msg;
                                     return $str;
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    public function setuserinfo($mch_billno,$amount){
        $oRedpayModel = M('Redpay_user');
        $aUser = M('Wxuser')->where(array('token'=>$this->sToken))->find();
        $aUsers = M('Wxusers')->where(array('uid'=>$aUser['id'],'openid'=>$this->sOpenid))->find();
        $oRedpayModel->add(array(
            'token'=>$this->sToken,
            'rid'=>$this->iSenceID,
            'openid'=>$this->sOpenid,
            'mch_billno'=>$mch_billno,
            'amount'=>$amount,
            'add_time'=>date('Y-m-d H:i:s'),
            'date'=>date('Y-m-d'),
            'nickname'=>$aUsers['nickname'],
            'headerimg'=>$aUsers['headimgurl']
        ));
    }




}
