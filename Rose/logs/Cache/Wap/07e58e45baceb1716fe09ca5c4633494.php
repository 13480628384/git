<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>修改信息列表</title>
    <style>
        .um-list li input{
            width:55%;
        }
    </style>
</head>
<body>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form">
        <li>
            <label  class="label">硬件设备码</label>
            <input value="<?php echo ($res["device_code"]); ?>" class="device_code"/>
        </li>
        <li>
            <label  class="label">硬件指令码</label>
            <input value="<?php echo ($res["device_command"]); ?>" class="device_command">
        </li>
        <li><label  class="label">价格</label>
            <input type="text" class="pay_price" style="width:50%!important;"value="<?php echo ($res["pay_price"]); ?>">
        </li>
    </ul>
    <button class="anniu" id="edit_name">确定修改</button>
</section>
<input type="hidden" id="id" value="<?php echo ($uid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
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
        $('#edit_name').tap(function(){
            var id = $.trim($('#id').val());
            var device_code = $.trim($('.device_code').val());
            var device_command = $.trim($('.device_command').val());
            var pay_price = $.trim($('.pay_price').val());
            if(device_code == ''){
                $.dialog({
                    content:'硬件设备码不能为空',
                    button:['好']
                });
                return false;
            }
            if(device_command == ''){
                $.dialog({
                    content:'硬件指令码不能为空',
                    button:['好']
                });
                return false;
            }
            if(pay_price == ''){
                $.dialog({
                    content:'价格',
                    button:['好']
                });
                return false;
            }
            var el=$.loading({
                content:'正在保存'
            });
            var DATA = {
                device_code:device_code,
                device_command:device_command,
                pay_price:pay_price,
                id:id
            };
            $.post("",DATA,function(data){
                if(data.code==200){
                    var DG=$.dialog({
                        content:'恭喜您，保存成功！',
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href="<?php echo U('JuicerPersonal/index',array('openid'=>$openid));?>";
                    });
                } else {
                    $.dialog({
                        content:'网络错误，请重试',
                        button:['好']
                    });
                }
                el.hide();
            },'json');
        })
    })
</script>
</html>