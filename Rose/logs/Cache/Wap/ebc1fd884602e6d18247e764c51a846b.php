<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>数据分析</title>
    <style>
        .header{
            width: 100%;
            height: 40px;
            line-height: 40px;
            text-align: CENTER;
            background: #18b4ed;
            color: #fff;
            font-size: 16px;
        }
        .list li{
            width: 100%;
            background: #fff;
            padding: 4px;
            margin: auto;
            margin-top: 6px;
        }
    </style>
</head>
<body>
<div class="facility" style="margin-bottom: 80px;">
    <header class="header"><?php echo ($month); ?>月份数据</header>
    <ul class="list">
        <?php if(is_array($result)): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="subject_4 pbw">
                <div style="white-space:normal; ">
                    <p class="pl">提现时间：<?php echo ($v["create_date"]); ?> </p>
                    <b style="font-size: 16px;font-weight: 600;">提现金额：<?php echo ($v["amount"]); ?></b><br/>
                    提现单号：<?php echo ($v["partner_trade_no"]); ?> <br/>
                    提现类型：微信<br/>
                    提现结果：<?php if($v['status'] == 1): ?><span style="color: #00bf00">成功</span>
                    <?php else: ?>失败<?php endif; ?>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
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
</html>