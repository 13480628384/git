<?php
include "aop/AopClient.php";
include "Alipay.Config.php";
include "aop/request/AlipayUserUserinfoShareRequest.php";
function get_user_info(){
    if(!isset($_GET['auth_code'])){
        //获取用户信息
        $ENCODED_URL = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".AlipayConfig::APPID."&scope=auth_base&state=wl&redirect_uri=$ENCODED_URL";
        header("Location:".$url);
        exit();
    }else{
        $time = date('Y-m-d H:i:s',time());
        $auth_code = $_GET['auth_code'];
        $Arrays['app_id'] = AlipayConfig::APPID;
        $Arrays['method'] = "alipay.system.oauth.token";
        $Arrays['charset'] = 'utf-8';
        $Arrays['sign_type'] = 'RSA';
        $Arrays['timestamp'] = date('Y-m-d H:i:s',time());
        $Arrays['version'] = 1.0;
        $Arrays['grant_type'] = 'authorization_code';
        $Arrays['code'] = $auth_code;
        $getsign = sign(signcontents($Arrays));
        $array = array(
            'app_id'=>AlipayConfig::APPID,
            'method'=>'alipay.system.oauth.token',
            'charset'=>'utf-8',
            'sign_type'=>'RSA',
            'timestamp'=>$time,
            'sign'=>$getsign,
            'version'=>1.0,
            'grant_type'=>'authorization_code',
            'code'=>$auth_code);
        $result = request_post('https://openapi.alipay.com/gateway.do',$array);
        $json_array = json_decode($result);
        return object_array($json_array)['alipay_system_oauth_token_response']['user_id'];
    }
}
/*
 * http post 发送数据
 * */
function request_post($url = '', $post_data = array()) {
    if (empty($url) || empty($post_data)) {
        return false;
    }

    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}
/**
 * 校验$value是否非空
 *  if not set ,return true;
 *    if is null , return true;
 **/
 function checkEmpty($value) {
    if (!isset($value))
        return true;
    if ($value === null)
        return true;
    if (trim($value) === "")
        return true;

    return false;
}
function signcontents($params){
    ksort($params);

    $stringToBeSigned = "";
    $i = 0;
    foreach ($params as $k => $v) {
        if (false === checkEmptys($v) && "@" != substr($v, 0, 1)) {

            // 转换成目标字符集
            $v = characets($v, 'UTF-8');

            if ($i == 0) {
                $stringToBeSigned .= "$k" . "=" . "$v";
            } else {
                $stringToBeSigned .= "&" . "$k" . "=" . "$v";
            }
            $i++;
        }
    }

    unset ($k, $v);
    return $stringToBeSigned;
}
/**
 * 转换字符集编码
 * @param $data
 * @param $targetCharset
 * @return string
 */
function characets($data, $targetCharset) {
    if (!empty($data)) {
        $fileType = 'utf-8';
        if (strcasecmp($fileType, $targetCharset) != 0) {

            $data = mb_convert_encoding($data, $targetCharset);
            //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
        }
    }


    return $data;
}
function checkEmptys($value) {
    if (!isset($value))
        return true;
    if ($value === null)
        return true;
    if (trim($value) === "")
        return true;

    return false;
}

/* 签名 */
function sign($data, $rsaPrivateKey = '/key/rsa_private_key.pem') {
    $key = dirname(__FILE__).$rsaPrivateKey;
    /* 获取私钥PEM文件内容，$rsaPrivateKey是指向私钥PEM文件的路径 */
    $priKey = file_get_contents($key);
    //print_r($priKey);echo 3;die;
    /* 从PEM文件中提取私钥 */
    $res = openssl_get_privatekey($priKey);
    ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
    /* 对数据进行签名 */
    openssl_sign($data, $sign, $res);

    /* 释放资源 */
    openssl_free_key($res);

    /* 对签名进行Base64编码，变为可读的字符串 */
    $sign = base64_encode($sign);
    return $sign;
}
function object_array($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = object_array($value);
        }
    }
    return $array;
}
?>