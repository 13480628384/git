<?php
/*
 * 生成二维码
 */
class Code
{
    private $sToken = null;
    public function __construct($sToken='', $iSenceID = '')
    {
        $sToken = trim($sToken);
        if (empty($sToken)) {
            return false;
        }
        $this->sToken   = $sToken;
        $this->iSenceID = $iSenceID;
    }

    /*
     * 获取永久二维码
     */
    public function getYJCode() {
        $sParam   = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$this->iSenceID.'"}}}';
        /*获取access_token*/
        $api = M('Diymen_set')->where(array('token'=>$this->sToken))->find();
        if($api){
            $access_token = $this->getAccessToken($api['appid'], $api['appsecret']);
            $imgSource = $this->creatTicket($access_token, $sParam);
            return $imgSource['header']['url'];
        }
        return false;
    }

    /*
     * 获取临时二维码
     * */
    public function getLSCode(){
        $sParam   = '{"expire_seconds": 604800,"action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": "'.$this->iSenceID.'"}}}';
        /*获取access_token*/
        $api = M('Diymen_set')->where(array('token'=>$this->sToken))->find();
	if($api){
            $access_token = $this->getAccessToken($api['appid'], $api['appsecret']);
            $imgSource = $this->creatTicket($access_token, $sParam);
            return $imgSource['header']['url'];
        }
        return false;
    }

    public function getAccessToken($appid, $appsecret)
    {
        $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
        $json = json_decode(file_get_contents($url_get));
        return $json->access_token;
    }

    public function creatTicket($token, $sParam) {
        /*发送数据到微信服务器端并获取数据*/
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$token";
        $result = api_notice_increment($url, $sParam);
        $jsonInfo = json_decode($result, true);
        $ticket = $jsonInfo['ticket'];
	if(!$ticket){
		return false;
	}

        /*根据ticket获取图片资源*/
        $url2 = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url2);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        return array_merge(array('body'=>$package), array('header'=>$httpInfo));
    }
}
