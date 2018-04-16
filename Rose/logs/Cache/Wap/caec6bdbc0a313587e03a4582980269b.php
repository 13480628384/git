<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <title>云端调试</title>
    <style>
        .test li,.tests li{
            width:48%;
            float: left;
            padding: 1%;
        }
        .ui-btn-dangers{
            font-size: 12px;
            height: 44px;
            line-height: 42px;
            display: block;
            color: #333;
            width: 100%;
            border-radius:1px;
            background: #fff;
            border-bottom: 1px solid #39f;
        }
        .OFF .ui-btn-dangers{
            background: #e5e0e0;
        }
        .half .ui-btn-dangers{
            background: #f06d5e;
        }
        .ON .ui-btn-dangers{
            background:#4cd664;
        }
        .ui-btn-lg{
            width: 90%;
            margin: auto;
            border-radius: 2px;
        }
    </style>
</head>
<body>
<header class="header-top">
    云端调试  （<?php if($detail['online_status'] == '0'): ?>离线<?php else: ?>在线<?php endif; ?>）
</header>

<section class="ucenter-main animated fadeInDown">
    <p>请选择对应按钮调试（绿色为开，灰色为关）</p>
    <ul class="test">
        <li data="ON" bit="0" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">急停（开）</button>
        </li>
        <li data="OFF" bit="0" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">急停（关）</button>
        </li>
        <li data="ON" bit="1" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">故障复位（开）</button>
        </li>
        <li data="OFF" bit="1" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">故障复位（关）</button>
        </li>
        <li data="ON" bit="3" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手自动切换（开）</button>
        </li>
        <li data="OFF" bit="3" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手自动切换（关）</button>
        </li>
        <li data="ON" bit="4" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动一层电机（开）</button>
        </li>
        <li data="OFF" bit="4" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动一层电机（关）</button>
        </li>
        <li data="ON" bit="5" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动二层电机（开）</button>
        </li>
        <li data="OFF" bit="5" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动二层电机（关）</button>
        </li>
        <li data="ON" bit="6" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动三层电机（开）</button>
        </li>
        <li data="OFF" bit="6" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动三层电机（关）</button>
        </li>
        <li data="ON" bit="7" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动榨汁电机（开）</button>
        </li>
        <li data="OFF" bit="7" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动榨汁电机（关）</button>
        </li>
        <li data="ON" bit="8" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动落杯电机（开）</button>
        </li>
        <li data="OFF" bit="8" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动落杯电机（关）</button>
        </li>
        <li data="ON" bit="9" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动落盖电机（开）</button>
        </li>
        <li data="OFF" bit="9" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动落盖电机（关）</button>
        </li>
        <li data="ON" bit="10" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动托盘旋转电机（开）</button>
        </li>
        <li data="OFF" bit="10" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动托盘旋转电机（关）</button>
        </li>
        <li data="ON" bit="11" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动压盖电机下（开）</button>
        </li>
        <li data="OFF" bit="11" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动压盖电机下（关）</button>
        </li>
        <li data="ON" bit="12" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动压盖电机上（开）</button>
        </li>
        <li data="OFF" bit="12" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动压盖电机上（关）</button>
        </li>
        <li data="ON" bit="13" one="100" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动储汁电机动作（开）</button>
        </li>
        <li data="OFF" bit="13" one="100" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动储汁电机动作（关）</button>
        </li>
        <li data="ON" bit="16" one="101" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动一层反转（开）</button>
        </li>
        <li data="OFF" bit="16" one="101" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动一层反转（关）</button>
        </li>
        <li data="ON" bit="17" one="101" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动门开（开）</button>
        </li>
        <li data="OFF" bit="17" one="101" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动门开（关）</button>
        </li>
        <li data="ON" bit="18" one="101" class="ON" dataid="">
            <button class="ui-btn-dangers" type="button">手动门关（开）</button>
        </li>
        <li data="OFF" bit="18" one="101" class="OFF" dataid="">
            <button class="ui-btn-dangers" type="button">手动门关（关）</button>
        </li>
        <li data="TRIG" bit="2" dataid="">
            <button class="ui-btn-dangers" type="button">付款完成</button>
        </li>
        <li data="TRIG" bit="14" dataid="">
            <button class="ui-btn-dangers" type="button">使用橙子个数清零</button>
        </li>
        <li data="TRIG" bit="15" dataid="">
            <button class="ui-btn-dangers" type="button">榨汁杯数清零</button>
        </li>
    </ul>
    <div class="space-10"></div>
    <div class="clear"></div>
    <p>16字节写命令</p>
    <ul class="um-list um-list-form" id="J_TJJJRPhone">
        <li><label for="customer_name" class="label">WRT_REG</label><input type="number" class="WRT_REG" pattern="[0-9]*" placeholder="请输入命令" id="customer_name"></li>
        <li><label for="customer_phone" class="label">VAL16</label><input type="number" class="VAL16" pattern="[0-9]*" placeholder="请输入命令" id="customer_phone"></li>
    </ul>
    <div class="space-10"></div>
    <button class="ui-btn-lg ui-btn-danger test_16" type="button" id="J_submitCustomer">发送</button>
    <br/>
    <p>云端读取</p>
    <ul class="tests">
        <li data="34" bit="0" class="ON">
            <button class="ui-btn-dangers" type="button">橙子使用个数统计</button>
        </li>
        <li data="36" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">榨汁杯数统计</button>
        </li>
        <li data="110" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">发送110命令</button>
        </li>
        <li data="59" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">门上限位</button>
        </li>
        <li data="60" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">门下限位</button>
        </li>
        <li data="61" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">设备处于初始状态</button>
        </li>
        <li data="62" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">设备正常运行</button>
        </li>
        <li data="63" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">榨汁完成</button>
        </li>
        <li data="64" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">自动模式状态</button>
        </li>
        <li data="111" bit="1" class="ON">
            <button class="ui-btn-dangers" type="button">发送111命令</button>
        </li>
        <li data="112" bit="1" class="ON" style="    margin-bottom: 71px;">
            <button class="ui-btn-dangers" type="button">发送112命令</button>
        </li>
    </ul>
    <br/><br/><br/>
    <div style="margin-bottom:100px;"></div>
</section>
<input type="hidden" class="device_command" value="<?php echo ($detail["device_command"]); ?>"/>
<input type="hidden" class="send_device_command" value="<?php echo U('send_test_device');?>"/>
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
    Zepto(function($) {
        $('.test li').tap(function () {
            var el = $.loading({
                content: '正在调试'
            });
            var status = $(this).attr('data');
            var dataid = $(this).attr('dataid');
            var one = $(this).attr('one');
            var BIT = $(this).attr('bit');
            var send_url = $('.send_device_command').val();
            var device_command = $('.device_command').val();
            var THIS = $(this);
            if(status == 'TRIG'){
                $.post("<?php echo U('TRIG');?>",{device_command: device_command,
                    status:status,BIT:BIT,one:one},function(res){
                    el.hide();
                    if(res.code == 200){
                        $.dialog({
                            content: res.msg,
                            button: ['好']
                        });
                    }else{
                        $.dialog({
                            content: res.msg,
                            button: ['好']
                        });
                    }
                },'json')
            }else{
                var DATA = {device_command: device_command,status:status,BIT:BIT,one:one};
                $.post(send_url, DATA, function (data) {
                    el.hide();
                    //THIS.attr('class','half');
                    if (data.code == 200) {
                        //THIS.attr('dataid',data.result);
                        $.dialog({
                            content: data.msg,
                            button: ['好']
                        });
                    }else{
                        $.dialog({
                            content: data.msg,
                            button: ['好']
                        });
                    }
                }, 'json');
            }
        });
        //16位调试
        $('.test_16').tap(function () {
            var WRT_REG = $('.WRT_REG').val();
            var VAL16 = $('.VAL16').val();
            if(WRT_REG == '' || VAL16 == ''){
                $.dialog({
                    content: '请输入命令',
                    button: ['好']
                });
                return false;
            }
            var el = $.loading({
                content: '正在调试'
            });
            var device_command = $('.device_command').val();
            var DATA = {WRT_REG: WRT_REG,VAL16:VAL16,device_command:device_command};
            $.post("<?php echo U('send_test_16');?>", DATA, function (data) {
                el.hide();
                if (data.code == 200) {
                    $.dialog({
                        content: data.msg,
                        button: ['好']
                    });
                }else{
                    $.dialog({
                        content: data.msg,
                        button: ['好']
                    });
                }
            }, 'json');
        });
        //发送110命令
        $('.tests li').tap(function () {
            var el = $.loading({
                content: '正在调试'
            });
            var status = $(this).attr('data');
            var device_command = $('.device_command').val();
            var THIS = $(this);
            var DATA = {device_command: device_command,status:status};
            $.post("<?php echo U('send_110');?>", DATA, function (data) {
                el.hide();
                if (data.code == 200) {
                    $.dialog({
                        content: data.msg,
                        button: ['好']
                    });
                }else{
                    $.dialog({
                        content: data.msg,
                        button: ['好']
                    });
                }
            }, 'json');
        });
    })
</script>
</html>