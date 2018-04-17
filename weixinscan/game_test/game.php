<?php
$typesd = $_SERVER['HTTP_USER_AGENT'] ;
if(strpos($typesd,'AlipayClient')>0){
	header("Location:http://wxpay.roseo2o.com/alipayscan/game_test/index.php?scan_code=".$_GET['scan_code']);exit;
}
ini_set('date.timezone','Asia/Shanghai');

//session_start();

require_once "wxpay/lib/WxPay.Api.php";
require_once "wxpay/WxPay.JsApiPay.php";
require_once 'wxpay/log.php';

//echo xdebug_time_index(), "==>1^\n";  

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$appId = WxPayConfig::APPID;

//①、获取用户openid

$tools = new JsApiPay();
$openId = $tools->GetOpenid();
//echo xdebug_time_index(), "==>3^\n";  
//die;

//test 
//$appId = 'test01';
//$openId = '123456';

//读数据库
require_once 'mysql/mysqldbread.php';
//echo xdebug_time_index(), "==>3.001^\n";  
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = //chr(123) "{"
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
                //.chr(125); "}"
        return $uuid;
    }
}
//获取设备信息
$scan_code = isset($_GET['scan_code'])?$_GET['scan_code']:null;
if(is_null($scan_code) ){
	echo "页面参数错误";
	die;
}
if(strstr($scan_code,'/qrcode/')){
	$last = explode('/',$scan_code);
	$scan_code = $last[2];
}
$ju_device_info_detail_sql = "select * from ju_device_info_detail where scan_code = '$scan_code' and  del_flag = '0'";
$ju_device_info_detail = $db->get_row($ju_device_info_detail_sql);
if($ju_device_info_detail){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=JuicerCode&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
$device_info_sql = "select * from device_info where scan_code = '$scan_code' and  device_status = '1' and del_flag = '0'";
$device_info = $db->get_row($device_info_sql);
if(is_null($device_info) ){
	echo "页面参数错误";
	die;
}
/*if($device_info->device_type == '1'){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=ScanCode&a=index&user_id=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}*/
//echo xdebug_time_index(), "==>3.1^\n";
//按摩椅
if($device_info->device_type == 4){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Anm&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//充电器
if($device_info->device_type == 2){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Charger&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//洗衣机
if($device_info->device_type == 5){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Washing&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//售货机
if($device_info->device_type == 3){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Machine&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//电动车充电
if($device_info->device_type == 6){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Vehicle&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//洗车
if($device_info->device_type == 7){
    if($device_info->car_type == '1'){
        $wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Car_Wash_Pay&a=news&openid=$openId&scan_code=".$scan_code;
        header("Location:".$wei_url);
        exit;
    }
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Car_wash&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
//厕纸机
if($device_info->device_type == 8){
	$wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Ce_ji&a=index&openid=$openId&scan_code=".$scan_code;
	header("Location:".$wei_url);
	exit;
}
// 眼镜怡
if($device_info->device_type == 9){
    $wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Glass&a=index&openid=$openId&scan_code=".$scan_code;
    header("Location:".$wei_url);
    exit;
}
// 纸巾
if($device_info->device_type == 10){
    $wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Zhijin&a=index&openid=$openId&scan_code=".$scan_code;
    header("Location:".$wei_url);
    exit;
}
// 擦鞋机
if($device_info->device_type == 11){
    $wei_url = "http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=Chaxie&a=index&openid=$openId&scan_code=".$scan_code;
    header("Location:".$wei_url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ch" manifest="">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Onenet物联终端</title>
	<meta charset="utf-8"><meta name="apple-touch-fullscreen" content="YES">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="pragram" content="no-cache">
	<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
	<link rel="stylesheet" type="text/css" href="css/play.css?20161104">
	<link rel="stylesheet" type="text/css" href="css/zepto.alert.css?122">
 <!--加的css样式-->
    <style>
	*{    
			-webkit-touch-callout:none;  /*系统默认菜单被禁用*/    
			-webkit-user-select:none; /*webkit浏览器*/    
			-khtml-user-select:none; /*早期浏览器*/    
			-moz-user-select:none;/*火狐*/    
			-ms-user-select:none; /*IE10*/    
			user-select:none;    
			}   

			input {       
			-webkit-user-select:auto; /*webkit浏览器*/      
			}   
    
	.fozi{border-bottom:solid 1px #00A098; margin-top:20px; padding-bottom:30px;}
   .fozi a{
	clear: both;
	color: #5FD0CA;
	line-height: 50px;
	font-size:2rem;

	display:block;
	
	text-decoration: none;
	}
li.lion1 {
    background:#ea7215;
}
    </style>
 <!--加的css样式-->
	<!--移动端版本兼容 -->	
</head>
<body style="height:auto;">


<?php
				// TODO  $scan_code = $_SESSION['scan_code'];
				

	
	
	//设备id
	$di_id = $device_info->id;
	
	
	//查询group_id
	
	$select_groupid_sql = "select dgi_id from device_relation_group where di_id='$di_id'";
	$device_group_id_row = $db->get_row($select_groupid_sql);

	$sqled = "select * from device_relation_group where del_flag=0 and online_status=1 and dgi_id='$device_group_id_row->dgi_id'";
	$row = $db->get_row($sqled);
	$isok = '';
	if(!$row){
		$isok = 1;
	}
	//广告链接地址
	$adv_url="http://wxpay.roseo2o.com/adv_merchant/index.php?m=WechatAttention&a=Get_User_Info&group_id=".$device_group_id_row->dgi_id."&openid=$openId&appid=$appId&di_id=".$di_id;
	

	
	//暂时支持1元支付
	//$sql_p = "select min(pay_price) min_p from device_group where status=1 and group_id='".$group_id."'";
	
	//$min_result = $db->get_var($sql_p);
	//if($min_result == null){
		$min_p = 1;
	//} else {
	//	$min_p = $min_result;
	//}



?>
			
			
		
	<!--<div class="bg-img" id="adurl_div"><img src="img/go.gif" class="go"></div>-->
	
	<!--<img src="img/adv4.gif" id="adurl_div" width="100%">-->
	
		<!--条幅-->
		
	<header>
		<aside>
			余额<b><span id="balances">0</span></b>
		</aside>
		<div class="feilei">
			<!--class="this" -->
			
			<?php if( !($min_p> 1)) {?><!-----如果数据库的金额小于1块钱---->
				<span class="cash" sv="1">1元</span>
			<?php }if(!($min_p> 2)){?>
				<span class="cash" sv="2">2元</span>
			<?php }if(!($min_p >5)){?>
				<span class="cash" sv="5">5元</span>
			<?php }if(!($min_p >10)){?>
				<span class="cash" sv="10">10元</span>
			<?php }if(!($min_p >20)){?>
				<span class="cash" sv="20">20元</span>
			<?php }?>
			<span class="cash" sv="50">50元</span>	
			<span class="cash" sv="100">100元</span>
			<!--<div class="kong20"></div>-->
			<div id="wxpayid" style="display: inline-block;margin-top: 10px;margin-left: 30px;"><b>点击充值</b>	</div>
		</div>		
	</header>
	<section>
		<h2>点击下面遥控</h2>
		<?php 
				//获取设备群组信息
				
				
				$device_relation_group_sql = "select drg.* from device_relation_group drg , device_relation_group  one_drg  where drg.status ='1' and one_drg.del_flag = '0' and drg.dgi_id = one_drg.dgi_id  and drg .del_flag = '0' and drg.device_type=1 and one_drg.di_id = '$di_id'  order by drg.ords";
				
		
		?>
		
		<ul id="launch" style=" padding-bottom:100px">
	

		<?php
			$results = $db->get_results($device_relation_group_sql);
			
			//echo xdebug_time_index(), "==>4.\n";  

			
			foreach($results as $vv ){
		?>
					<li   dcid="<?php echo $vv->device_command;?>"  did="<?php echo $vv->di_id;?>" dprice="<?php echo  number_format($vv->pay_price,0);?>"
					cd="<?php echo $vv->group_word;?>"
					><tt id="zimu<?php echo $vv->group_word;?>" style="display: none">0</tt><b><?php echo $vv->group_word;?></b><img src="img/icon_01.png" />&nbsp;<?php echo number_format($vv->pay_price,0);?>元</li>
					
		<?php }?>
                <div class="fozi" style="padding-left:3%;clear:both">&nbsp;</div>
                <div class="fozi" style="padding-left:3%;"><a href="http://www.roseo2o.com" style="font-size:1.5rem;"><em>玫瑰物联</em> <br>商业设备物联化,运营交易平台化。</a></div>
                <div class="fozi" style="padding-left:3%;"><a href="https://open.iot.10086.cn"style="font-size:1.5rem;"><em>onenet设备云平台</em> <br>物联网服务平台为您提供的产品功能</a></div>

		</ul>
	</section>
	<footer>
		<div class="menu" style="position: relative;">
			<!--
			
			<a href="http://help.roseo2o.cn/help/code.php?openid=<?php echo $openId ;?>" style="color:#fff;text-decoration:none;">
         <b style="font-size:30px;color:#fff;margin-bottom:-21px;display:block;">
            点击客服联系</b><br/>若扣余额未启动,5分钟内自动退币.</a>
			
			-->
			
			<a href="#" style="color:#fff;text-decoration:none;">
         <b style="font-size:30px;color:#fff;margin-bottom:-21px;display:block;">
            <a href="http://wxpay.roseo2o.com/Rose/index.php?g=Wap&m=WeixinUserConsume&a=index&openid=<?php echo $openId;?>">我的消费记录</a></b><br/>若扣余额未启动,5分钟内自动退币.</a>
   </div>
</footer>
<?php 
/*


<input type="hidden" id="current_new" value="<?php echo $_SESSION['current_new'];?>"/>
*/
?>
<input type="hidden" id="current_payprice" value="<?php echo $min_p;?>"/>	
<input type="hidden" id="isok" value="<?php echo $isok;?>"/>
<input type="hidden" id="defaultDevicecommand" value="<?php echo $device_info->device_command;?>"/>

<input type="hidden" id="appId" value="<?php echo $appId;?>"/>
<input type="hidden" id="openId" value="<?php echo $openId;?>"/>
<input type="hidden" id="adv_url" value="<?php echo $adv_url;?>"/>


<script type="text/javascript" src="js/zepto.min.js"></script>
<script type="text/javascript" src="js/zepto.alert.js"></script>
<script type="text/javascript" src="js/fastclick.min.js"></script>
<script type="text/javascript" src="js/main.js?v1.0.3" charset="UTF-8"></script>

</body>
</html>