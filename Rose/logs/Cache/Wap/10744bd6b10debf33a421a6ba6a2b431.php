<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>个人信息</title>
    <style>
        .click_pic{ margin-top: 1.5rem !important; margin-bottom: 1.5rem; }
        .anniu{ font-size: 1.8rem; }
        .del_vip .del_li{height: 5rem;}
    </style>
</head>
<body style="background: #f2f2f2">
<div class="head">
    <span class="head-ct">个人信息</span>
</div>
<div class="del_vip">
    <ul>
        <li class="del_li"><span>用户名:</span><input type="text" placeholder="<?php echo ($user["user_login"]); ?>" readOnly="true"></li>
        <li class="del_li"><span>电话:</span><input type="text"placeholder="<?php echo ($user["phone"]); ?>" readOnly="true" ></li>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('JuicerPersonal/index',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('JuicerPersonal/weixin_income',array('openid'=>$openid));?>">微信收益</li>
    <li data-url="<?php echo U('JuicerPersonal/alipay_income',array('openid'=>$openid));?>">支付宝收益</li>
    <li data-url="<?php echo U('JuicerPersonal/personal',array('openid'=>$openid));?>">个人信息</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer_rose li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    var code = url.split("&code")[0];
    $("[data-url='"+code+"']").addClass('active');
    function onBridgeReady(){
        WeixinJSBridge.call('hideOptionMenu');
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
</body>
<script>
    Zepto(function($){
        var REG = {
            name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
            phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
            passwd:/^[0-9]{6,8}$/,
            id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/,
            email:/\w+[@]{1}\w+[.]\w+/
        }
        $('#edit_name').tap(function(){
            var mobile = $.trim($('.mobile').val());
            var phone = $.trim($('.phone').val());
            var openid = $.trim($('.openid').val());
            var email = $.trim($('.email').val());
            var percent = $.trim($('.percent').val());
            if (email == '' || phone == '' || mobile == '') {
                $.dialog({
                    content: '请输入手机号码或邮箱或提现比例',
                    button: ['好']
                });
                return false;
            }
            if(!REG.email.test(email)){
                $.dialog({
                    content:'请输入正确的邮箱',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(mobile)){
                $.dialog({
                    content:'请输入正确的电话',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(phone)){
                $.dialog({
                    content:'请输入正确的手机号码',
                    button:['好']
                });
                return false;
            }
            var el = $.loading({
                content: '正在提交'
            });
            $.post("",{openid:openid,email:email,phone:phone,mobile:mobile},function(data){
                if(data.code == 200){
                    $.dialog({
                        content:data.success,
                        button:['好']
                    });
                } else {
                    $.dialog({
                        content:data.error,
                        button:['好']
                    });
                }
                el.hide();
            },'json')
        })
    })
</script>
</html>