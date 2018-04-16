<?php if (!defined('THINK_PATH')) exit();?>
<html><head>
    <title>申请退款</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
</head>
<body>

<header class="header-top">
    申请退款
</header>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form" id="J_TJJJRPhone">
        <li><label for="customer_name" class="label">姓名</label><input type="text" placeholder="请输入姓名" id="customer_name"></li>
        <li><label for="customer_phone" class="label">手机</label><input type="text" placeholder="请输入手机号码" id="customer_phone" pattern="[0-9]*"></li>
        <li><label for="customer_phone" class="label">微信号</label><input type="text" placeholder="请输入微信号" id="wechatid"></li>
    </ul>
    <div class="space-10"></div>
    <ul class="um-list um-list-form" id="J_chooseProject">
        <li><label class="label">退款金额</label><input type="text"  id="customer_project" readonly="readonly" class="color-blue"  value="<?php echo ($count); ?>"></li>
    </ul>
    <div class="space-10"></div>
    <ul class="um-list um-list-form">
        <li><label  class="label">备注</label>
            <input style="width: 60%" class="remarks" type="text" placeholder="请留下您的宝贵意见！"></li>
    </ul>
    <p class="um-tips"><em>提示：</em>申请退款期间，余额仍然可以使用，余额会在7个工作日内退款到微信钱包，请注意查收。请务必输入真实的信息，否则会影响余额到账</p>
</section>
<div class="space-20"></div>
<aside class="account-submit">
    <button class="ui-btn-lg ui-btn-danger" type="button" id="J_submitCustomer">确认</button>
</aside>
<div class="space-20"></div>
<input type="hidden" class="check_total" value="<?php echo U('check_total');?>"/>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>"/>
<input type="hidden" class="count" value="<?php echo ($count); ?>"/>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script type="text/javascript">
    Zepto(function($) {
        var REG = {
            name: /^[a-zA-Z\u4e00-\u9fa5]{2,6}$/,
            phone: /^(((13[0-9]{1})|159|153)+\d{8})$/,
            passwd:/^[0-9]{6,8}$/,
            id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/
        };
        $('#J_submitCustomer').tap(function () {
            var openid = $('.openid').val();
            var remarks = $('.remarks').val();
            var check_total = $('.check_total').val();
            var count = $('.count').val();
            var customer_name = $('#customer_name').val();
            var customer_phone = $('#customer_phone').val();
            var wechatid = $('#wechatid').val();
            if(customer_name==''){
                $.dialog({
                    content:'姓名不能为空',
                    button:['好']
                });
                return false;
            }
            if(customer_phone==''){
                $.dialog({
                    content:'手机号不能为空',
                    button:['好']
                });
                return false;
            }if(wechatid==''){
                $.dialog({
                    content:'微信号不能为空',
                    button:['好']
                });
                return false;
            }
            if(wechatid.length>30){
                $.dialog({
                    content:'微信号长度错误',
                    button:['好']
                });
                return false;
            }
            if(!REG.name.test(customer_name)){
                $.dialog({
                    content:'请输入正确的姓名',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(customer_phone)){
                $.dialog({
                    content:'请输入正确的手机号码',
                    button:['好']
                });
                return false;
            }
            if(count<=0){
                $.dialog({
                    content:'你的余额不足，请勿申请',
                    button:['好']
                });
                return false;
            }
            var el = $.loading({
                content: ''
            });
            $.post(check_total,{openid:openid,
                name:customer_name
                ,phone:customer_phone,
                total:count,wechatid:wechatid,
                remarks:remarks},function(res){
                el.hide();
                if(res.code == 200){
                    $.dialog({
                        content: res.msg,
                        button: ['好']
                    });
                    WeixinJSBridge.call('closeWindow');
                }else{
                    $.dialog({
                        content: res.msg,
                        button: ['好']
                    });
                }
            },'json')
        });
    });
</script>
</body></html>