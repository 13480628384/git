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
        .tab-yf-box-main-name,.tab_ear1{
            width:25%;
        }
    </style>
</head>
<body>
<div class="report_forms">
    <div id="tabbox">
        <ul class="tabs" id="tabs">
            <div class="tabs_return" onclick='javascript:history.go(-1)'><img src="./tpl/Wap/default/img/left.png" alt="" width="30%"></div>
            <li style="width: 60%;color: #2EBE74;height: 3rem;font-size: 2rem;">24小时(<?php echo ($group_name); ?>)</li>
            <div class="clear"></div>
        </ul>
        <ul class="tab_conbox" id="tab_conbox">
            <div class="tab-yf-box-main f14">
                <div class="tab-yf-box-main-name">
                    <div>二维码编号</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>编号</div>
                </div>
                <div class="tab-yf-box-main-name">
                    <div>总收益</div>
                </div>
                <div class="tab-yf-box-main-time">
                    <div>操作</div>
                </div>
            </div>
            <!-- 每周内容 -->
            <li class="tab_con ">
                <?php if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                <div class="tab_date">
                    <div class="tab_ear1"><?php echo ($v["code"]); ?></div>
                    <div class="tab_ear1"><?php echo ($v["group_word"]); ?></div>
                    <div class="tab_ear1"><span><?php echo ($v["count"]); ?> 元</span><p></p></div>
                    <div class="tab_ear1"><a href="<?php echo U('today_group_device_deteil',array('openid'=>$openid,'di_id'=>$v['id']));?>">
                        <img src="./tpl/Wap/default/img/r1.png" alt="" width="30%"></a></div>
                </div>
                <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
            <div id="now_add">正在玩命加载中...</div>
        </ul>
    </div>
</div>
<input type="hidden" class="group_id" value="<?php echo ($group_id); ?>">
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
                $.post('',{"n": n,group_id:group_id},function(html){
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