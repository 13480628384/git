<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css" type="text/css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/report_forms.css" type="text/css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>微信收益</title>
    <style>
        .tab-yf-box-main-name,.tab_ear1{
            width:25%;
        }
    </style>
</head>
<body>
<div class="report_forms">
    <div id="tabbox">
        <ul class="tab_conbox" id="tab_conbox">
            <div class="tab-yf-box-main f14">
                <div class="tab-yf-box-main-name">
                    <div>金额</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>状态</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>指令码</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>时间</div>
                </div>
            </div>
            <li class="tab_con ">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_date">
                        <div class="tab_ear1"><?php echo ($v["consume_account"]); ?></div>
                        <div class="tab_ear1"><?php if($v['status'] == 1): ?>有效<?php else: ?>无效<?php endif; ?></div>
                        <div class="tab_ear1"><?php echo ($v["device_command"]); ?></div>
                        <div class="tab_ear1"><?php echo substr($v['create_date'],0,10) ?></div>
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
                var group_id = $('.group_id').val();
                $.post('',{"n": n},function(html){
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
</html>