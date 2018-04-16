<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<title>玫瑰-注册操作</title>
	<meta charset="utf-8">
	<meta content="" name="description">
	<meta content="" name="keywords">
	<meta content="eric.wu" name="author">
	<meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
	<meta content="telephone=no, address=no" name="format-detection">
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
	<link rel="stylesheet" href="./tpl/Wap/default/css/reg.css?12">
	<link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
	<link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
</head>
<body>
<div class="web_reg">
	<div class="logos"><img src="./tpl/Wap/default/img/logo.png" alt="玫瑰云网" ></div>
	<form action="">
		<div class="web_reg_input" ><span>昵称</span>
			<input  type="text" placeholder="输入2-8个字的昵称" id="reg_name"></div>
		<div class="web_reg_input" ><span>手机</span>
			<input pattern="[0-9]*" type="number" id="reg_phone" placeholder="输入有效的手机号码"></div>
		<div  class="web_reg_input web_reg_input_code"><span>验证</span>
			<input  type="number" placeholder="短信验证码" id="code" pattern="[0-9]*">
			<button class="web_reg_button code">短信验证</button></div>
		<button class="web_reg_button_submit" id="J_submitReg"><p>立即注册</p></button>
	</form>
</div>
<aside class="account-submit">
	<input type="hidden" id="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">
	<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" id="scan_code" value="<?php echo ($scan_code); ?>">
	<input type="hidden" id="submitUrl" value="<?php echo U('BindVerfityCode');?>">
	<input type="hidden" id="shortmessage" value="<?php echo U('shortmessage');?>">
</aside>
<div class="space-50"></div>
</body>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/V_2base.js?v08"></script>
</html>