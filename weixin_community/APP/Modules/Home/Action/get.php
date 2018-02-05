<?php
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
    $APP_ID='wx76a6e13f999425e4';
    $APP_SECRET='026a798ee66f19d6f89ae2bd3ab687dc';
    $codes = $_GET['code'];
    $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APP_ID&secret=$APP_SECRET&code=$codes&grant_type=authorization_code";
    $return_results = file_get_contents($access_token_url);
    $return_json = json_decode($return_results);
    $access_token = $return_json->access_token;
    $openid = $return_json->openid;
    $get_openid = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";

    $edopenid = file_get_contents($get_openid);
    $head = json_decode($edopenid);
    $array = object_array($head);
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/weixin_community/index.php?m=Index&a=strategy&province='.$array['province'].'&nickname='.$array['nickname'].'&openid='.$openid.'&city='.$array['city'].'&language='.$array['language'].'&sex='.$array['sex'].'&country='.$array['country'].'&headimgurl='.$array['headimgurl'];
header("Location:$url");
?>