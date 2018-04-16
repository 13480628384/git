<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css" type="text/css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/report_forms.css" type="text/css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>流水报表</title>
    <style>
        .tab_ear1{width: 20%;}
        .tab-yf-box-main-name{width: 20%}
    </style>
</head>
<body>
<div class="report_forms">
    <div id="tabbox">
        <ul class="tabs" id="tabs">
            <div class="tabs_return" onclick='javascript:history.go(-1)'><img src="./tpl/Wap/default/img/left.png" alt="" width="30%"></div>
            <li class="thistab">24小时</li>
            <li><a href="<?php echo U('week_index',array('openid'=>$openid));?>">一周</a></li>
            <li><a href="<?php echo U('Rose2OrderflowMonth/index',array('openid'=>$openid));?>">月份</a></li>
            <div class="clear"></div>
        </ul>
        <ul class="tab_conbox" id="tab_conbox">
            <div class="tab-yf-box-main f14">
                <div class="tab-yf-box-main-name">
                    <div>编号</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>收益</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>类型</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>状态</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>时间</div>
                </div>
            </div>
            <!-- 每周内容 -->
            <li class="tab_con ">
                <?php if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_date">
                        <div class="tab_ear1"><?php echo ($v["deivce_command"]); ?></div>
                        <div class="tab_ear1"><span><?php echo ($v["consume_account"]); ?> 元</span><p></p></div>
                        <div class="tab_ear1">
                            <?php if($v['type'] == 1): ?>微信消费
                                <?php elseif($v['type'] == 3): ?>按摩椅消费
                                <?php elseif($v['type'] == 4): ?>按摩椅支付宝消费
                                <?php elseif($v['type'] == 2): ?>支付宝消费
                                <?php elseif($v['type'] == 5): ?>充电器消费
                                <?php elseif($v['type'] == 6): ?>充电器支付宝消费
                                <?php elseif($v['type'] == 9): ?>微信洗衣机消费
                                <?php elseif($v['type'] == 10): ?>支付宝洗衣机消费
                                <?php elseif($v['type'] == 15): ?>微信洗车
                                <?php elseif($v['type'] == 16): ?>支付宝洗车
                                <?php elseif($v['type'] == 13): ?>微信电动车消费
                                <?php elseif($v['type'] == 14): ?>支付宝电动车消费
                                <?php else: ?>其它<?php endif; ?>
                        </div>
                        <div class="tab_ear1">
                            <?php if($v['command_status'] == 2): ?>成功消费
                                <?php elseif($v['command_status'] == 1): ?>正在消费
                                <?php elseif($v['command_status'] == 3): ?>已退币<?php endif; ?>
                        </div>
                        <div class="tab_ear1"><?php echo ($v["create_date"]); ?></div>
                    </div>
                    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
            <div id="now_add">正在玩命加载中...</div>
        </ul>
    </div>
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
<script>
    var n=0;
    var stop=0;
    var timeOut = null;
    $(document).scroll(function(){
        if(stop==1){
            return false;
        }
        //滚动条顶部的偏移==总个文档的高度-窗口的高度
        var diff = Number($(window).height()) + Number(20);
        if($(document).height() - $(window).scrollTop() < diff){
            if(timeOut != null) {
                return false;
            }
            $('#now_add').css('display','block');
            n++;
            timeOut = setTimeout(function(){//1秒加载一次
                $.post('',{"n": n,},function(html){
                    if($.trim(html).length>0){
                        $(html).appendTo('.tab_con');
                        timeOut=null;
                    }else{
                        $('#now_add').html('没有啦');
                        stop=1;
                    }
                },'html');
            },1000);
        }
    });
</script>
</body>
</html>