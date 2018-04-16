<?php

class Vanke
{
    const CLIENT_ID         = '55152eaf75c84823d00be4cc';
    const CLIENT_SECRET     = '6f87efc9cc915df57b42624815ccce87';
    const TOKEN_ENDPOINT    = 'http://szapi.vanke.com/api/v1/oauth/token';

    function __construct()
    {
    }

    /*未更新，暂时不可用
    //根据openid获取业主信息
    //$getCustomerUrl = "http://szapi.vanke.com/api/v1/customers/check_customer?provider=wx&account=%s";
    $getCustomerUrl = "http://localhost:8098/api/v1/customers/check_customer?provider=wx&account=%s";
    $wxOpenid = "ouPwxuLDhOfQU9p2AV4feUpDxBZ8";
    $getCustomerUrl = sprintf($getCustomerUrl,$wxOpenid);
    $customerObj = curl_get_crm($getCustomerUrl,$tokenJson->access_token);
    var_dump($customerObj);*/

    //获取token
    function getToken($username, $password){

        $parameters = array(
            "grant_type"=>"password",
            "username" => $username,
            "password" => $password
        );

        return $this->OAuth2(self::TOKEN_ENDPOINT, self::CLIENT_ID, self::CLIENT_SECRET, $parameters);
    }
    //刷新token
    function refreshToken($refreshToken){
        $parameters = array(
            "refresh_token"=> $refreshToken,
            "grant_type"=>"refresh_token"
        );
        return OAuth2(self::TOKEN_ENDPOINT, self::CLIENT_ID, self::CLIENT_SECRET, $parameters);
    }

    function OAuth2($url, $app_id, $app_secret, $json_data){

        $ch = curl_init($url);

        $signature = base64_encode($app_id .  ':' . $app_secret);

        $parameters = http_build_query( $json_data, null, '&');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic '. $signature,
            'Content-Type:application/x-www-form-urlencoded'
        ));

        $buffer = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($buffer);

        return $result;
    }


    function curl_get_crm($url,$apitoken)
    {
        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_HTTPHEADER] = array('Authorization: Bearer '.$apitoken,'Content-Type: application/json','Accept:application/json','Corporation-Id: szdc');
        $curl_options[CURLOPT_RETURNTRANSFER] = true; //不打印返回结果

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $dataresult = curl_exec($ch);
        //var_dump($result);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($http_status=="500")      //服务器内部错误
        {
            $dataresult = '{"code" : '.$http_status.',"message" : "服务器内部错误。"}';
        }
        else if($http_status == "400" || $http_status == "401")
        {
            $dataresult = '{"code" : '.$http_status.',"message" : "无效的token。"}';
        }
        else if($http_status == "404")     //未找到
        {
            $datajson = json_decode($dataresult);     //重复冲突
            $dataresult = '{"code":404,"message":"'.$datajson->message.'"}';
        }
        else if($http_status == "409")
        {
            $datajson = json_decode($dataresult);
            $dataresult = '{"code":'.$http_status.',"message":"'.$datajson->message.'"}';
        }
        curl_close($ch);
        return $dataresult;
    }

    function getUserInfo($wxOpenid){
        //var_dump(getToken("kgshop","7B09_pp"));
        $tokenJson = $this->getToken("kgshop","7B09_pp");          //获取token
        //var_dump($tokenJson);
        //var_dump(refreshToken("b532d3686b1b7815492570cad233d8ed6bba292b"));


        //获取微信粉丝信息
        $getFollowerUrl = "http://szapi.vanke.com/api_open/v1/followers/szvkservice/%s";
        $getFollowerUrl = sprintf($getFollowerUrl,$wxOpenid);
        return json_decode($this->curl_get_crm($getFollowerUrl,$tokenJson->access_token),true);
    }

    /*
     *  获取万科业主信息
     */
    public function getVankeBindUser($wxOpenid)
    {
        /*未更新，暂时不可用*/
        //根据openid获取业主信息
        $getCustomerUrl = "http://szapi.vanke.com/api/v1/customers/checkall_customer?provider=wx&account=%s";
        //$getCustomerUrl = "http://localhost:8098/api/v1/customers/check_customer?provider=wx&account=%s";
        //$wxOpenid = "ouPwxuLDhOfQU9p2AV4feUpDxBZ8";
        $getCustomerUrl = sprintf($getCustomerUrl,$wxOpenid);
        $tokenJson = $this->getToken("kgshop","7B09_pp");          //获取token
        return $this->curl_get_crm($getCustomerUrl,$tokenJson->access_token);
    }

    public function getOrderStatus($orderid)
    {
        $getCustomerUrl = "http://szapi.vanke.com/api/v1/weixin/orders/outsource/kgshop/%s";
        $getCustomerUrl = sprintf($getCustomerUrl,$orderid);
        $tokenJson = $this->getToken("kgshop","7B09_pp");          //获取token
        return $this->curl_get_crm($getCustomerUrl,$tokenJson->access_token);
    }

    /*
     *  发短信
     */
    public static function sendText($phone, $msg)
    {
        $url = 'http://szapi.vanke.com//api/v1/crm/sendsms';
        $data = array(
            'smsmobile'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return;
    }
}
?>
