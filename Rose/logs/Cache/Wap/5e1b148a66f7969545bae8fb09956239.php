<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
</head>
<style type="text/css">
    body{
        background-color:#f5f5f5;
    }
    .top{  width: 100%;
        height: 40px;
        line-height: 40px;
        border: 1px solid #ccc;
        text-align: center;}
    .topt{  width: 100%;
        height: 40px;
        line-height: 40px;
        border: 1px solid #ccc;
        text-align: center;}
    .tab-sublevel-ul-li{
        width: 33.33%;
    }
</style>
<body>
<div class="commission-tab">
    <ul class="commission-tab-ul">
        <li class="commission-tab-ul-li active">日报表</li>
        <li class="commission-tab-ul-li ">月报表
        </li>
    </ul>
</div>
<div>
    <ul class="commission-main">
        <li>
            <ul class="tab-sublevel-ul">
                <li class="tab-sublevel-ul-li f14">设备号</li>
                <li class="tab-sublevel-ul-li f14">金额</li>
                <li class="tab-sublevel-ul-li f14">日期</li>
            </ul>
            <?php if(is_array($one)): $i = 0; $__LIST__ = $one;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><ul class="tab-sublevel-ul">
                    <li class="tab-sublevel-ul-li"><?php echo ($v["device_command"]); ?></li>
                    <li class="tab-sublevel-ul-li"><?php echo ($v["account"]); ?></li>
                    <li class="tab-sublevel-ul-li"><?php echo ($v["creaet_date"]); ?></li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>
        </li>
        <li class="hide">
            <ul class="tab-sublevel-ul">
                <li class="tab-sublevel-ul-li f14">年</li>
                <li class="tab-sublevel-ul-li f14">月</li>
                <li class="tab-sublevel-ul-li f14">金额</li>
            </ul>
            <?php if(is_array($week)): $i = 0; $__LIST__ = $week;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><ul class="tab-sublevel-ul">
                    <li class="tab-sublevel-ul-li"><?php echo ($v["year"]); ?></li>
                    <li class="tab-sublevel-ul-li"><?php echo ($v["month"]); ?></li>
                    <li class="tab-sublevel-ul-li"><?php echo ($v["count"]); ?></li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>

        </li>
    </ul>
</div>
</body>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<link rel="stylesheet" href="./tpl/Wap/default/js/jquery-1.11.2.min.js">
<script type="text/javascript">
    $(function(){
        $(".commission-tab-ul-li ").click(function(){
            $(this).addClass('active').siblings().removeClass('active');
            var index = $(this).index();
            $(".commission-main>li").eq(index).show().siblings().hide();
        })
    })
</script>
</html>