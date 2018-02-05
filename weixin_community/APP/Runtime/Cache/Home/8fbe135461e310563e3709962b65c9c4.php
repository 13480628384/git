<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/update_reset.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/update_style1.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <title>修改个人信息</title>
</head>
<body>
<div class="top">
    <span>头像：</span>
    <div class="head-portrait"><img class="headimgurl" data="<?php echo ($personal["headimgurl"]); ?>" src="<?php echo ($personal["headimgurl"]); ?>" alt=""></div>
    <b class="add_img">上传头像</b>
</div>
<div class="top2">
    <span>昵称：</span>
    <div class="name"><input type="text" name="name" class="nickname" value="<?php echo ($personal["nickname"]); ?>"></div>
</div>
<div class="submit">
    <input type="button" class="bg-blue white update_personal" value="保存修改">
</div>
<footer>
    <ul>
        <li id="footer1" onclick="window.location.href='<?php echo U('Index/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'">
            <img src="__PUBLIC__/Home/img/icon_30.png" alt="">
            <br/>微信支付
        </li>
        <li id="footer4" onclick="window.location.href='<?php echo U('Personal/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'">
            <img src="__PUBLIC__/Home/img/icon-p.png" alt="">
            <br/>玩家中心
        </li>

        <li id="footer3" onclick="window.location.href='<?php echo U('Nomoney/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'">
            <img src="__PUBLIC__/Home/img/icon_16.png" alt="">
            <br/>免费逗币
        </li>
        <li id="footer2" onclick="window.location.href='<?php echo U('Community/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'">
            <img src="__PUBLIC__/Home/img/icon_17.png" alt="">
            <br/>微社区
        </li>
    </ul>
</footer>
<input class="personal" value="<?php echo U(personal);?>" type="hidden">
<input class="openid" value="<?php echo ($personal["openid"]); ?>" type="hidden">
<input type="hidden" class="add_img_upload" value="<?php echo U('Roadnext/update_per_img');?>">
</body>
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/update.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
    ]
});
</script>
</html>