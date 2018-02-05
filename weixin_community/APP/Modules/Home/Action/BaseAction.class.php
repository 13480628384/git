<?php
class BaseAction extends Action
{
    public $openid = null;
    public $wxusers = null;
    public $token = null;
    public $user_id = null;
    public $tpl =null;
    public $autoShare = false;
    public $APP_ID = null;
    public $APP_SECRET = null;
    public $WNO = null;
    public $community = null;
    public $ak = null;
    public $sk = null;
    public $namespace = null;
    public $group_id = null;
    public $device_command = null;
    //public $uploadPolicy = null;
    //public $aliImage = null;

    protected function _initialize()
    {
        /*
         * 引入微信js接口
        */
        //图片上传
        Vendor('alimedia.alimage');
        //require_once('alimedia/alimage.class.php');
        $ak = '23391646';
        $sk = '4f2d3de59a76cc0a3dd1885a3fb5a876';
        $namespace = 'chw';
        $aliImage  = new AlibabaImage($ak, $sk);
        $uploadPolicy = new UploadPolicy( $namespace );
        $token = $aliImage->token($uploadPolicy);
        $this->token=$token;
        $this->sk=$sk;
        $this->ak=$ak;
        $this->group_id=$_REQUEST['group_id'];
        $this->device_command=$_REQUEST['device_command'];
        $this->namespace=$namespace;
       // $this->uploadPolicy=$uploadPolicy;
        //$this->aliImage=$aliImage;
        $this->WNO='gh_02a8fc124767';
        $this->APP_ID='wx76a6e13f999425e4';
        $this->APP_SECRET='026a798ee66f19d6f89ae2bd3ab687dc';
        $this->assign('token',$token);
        Vendor('weixin.jssdk');
        Vendor('weixin.ez_sql_core');
        Vendor('weixin.ez_sql_mysql');
        //$this->community = new Mysql("guangjia3test.mysql.rds.aliyuncs.com","guangjia","guangjiatest","ad_iwork");
        //$this->community = new Mysql("573bd98146cf7.gz.cdb.myqcloud.com:11781","cdb_outerroot","chw2016=","ad_iwork");
        $this->community = new ezSQL_mysql("cdb_outerroot","chw2016=","ad_iwork","573bd98146cf7.gz.cdb.myqcloud.com:11781");
        $jssdk = new JSSDK($this->APP_ID,$this->APP_SECRET);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
    }
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

