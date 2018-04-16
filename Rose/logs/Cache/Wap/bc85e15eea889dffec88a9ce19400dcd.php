<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>添加群组</title>
</head>
<body style="background: #f2f2f2">
<div class="facility">
    <div class="tishi">提示:请输入正确的群组名称。</div>
    <div class="tishi_yanz">群组名称<input type="text" class="group_name" placeholder="请输入群组"></div>
    <button class="anniu" id="add_name">确定添加</button>

</div>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('Rose2Personal/index',array('openid'=>$openid));?>">首页</li>
    <li data-url="<?php echo U('Device/device_list',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('Device/group_list',array('openid'=>$openid));?>">群组列表</li>
    <li data-url="<?php echo U('Rose2Personal/presonal_new',array('openid'=>$openid));?>">个人信息</li>
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
        $('#add_name').tap(function(){
            var group_name = $.trim($('.group_name').val());
            if(!group_name){
                $.dialog({
                    content:'请填写群组名称',
                    button:['好']
                });
                return false;
            }
            var el=$.loading({
                content:'正在提交'
            });
            $.post("<?php echo U('add_device_group_info');?>",{device_group_name:group_name},function(reg){
                if(reg.msg==1){
                    var DG=$.dialog({
                        content:'恭喜您，提交成功！',
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href="<?php echo U('group_list',array('openid'=>$openid));?>";
                    });
                }else if(reg.msg==500){
                    var DG=$.dialog({
                        content:'群组已存在，请重新命名！',
                        button:['好']
                    });
                }else{
                    $.dialog({
                        content:'网络错误，请重试',
                        button:['好']
                    });
                }
                el.hide();
            },'json');
        });
    })
</script>
</html>