<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>设备排行榜</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <style type="text/css">
        .bg-df3100 {
            background: #30bf75;
        }
        .border-rad{
            border-radius:5px !important;
        }
        .table-row-radius-ul {
            border-radius: 20px !important;
        }
    </style>
</head>
<body class="bg-df3100">

<div class="space-20"></div>
<div class="table-row table-row-radius">
    <ul class="border-rad"><li class="table-row-red">我的机器</li></ul></div>
<div class="space-20"></div>
<div class="table-row table-row-t">
    <ul style="color: #fff;margin-bottom:10px">
        <li></li>
        <li>消费状态</li>
        <li>硬编码</li>
        <li>金额（元）</li>
    </ul>
</div>
<?php if(is_array($panding)): $k = 0; $__LIST__ = $panding;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($k <= 3): ?><div class="table-row table-row-radius">
            <ul class="table-row-radius-ul ">
                <li><i class="icon-<?php echo ($k); ?>st"></i></li>
                <li style="width: 20%;">有效</li>
                <li style="width: 38%;"><?php echo ($v["device_code"]); ?></li>
                <li style="width: 20%;"><?php echo ($v["count"]); ?></li>
            </ul>
        </div>
        <div class="space-10"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
<div class="space-10"></div>
<div class="table-row kk1">
    <?php if(is_array($panding)): $k = 0; $__LIST__ = $panding;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($k > 3): ?><ul>
                <li><?php echo ($k); ?></li>
                <li style="width: 20%;">有效</li>
                <li style="width: 38%;"><?php echo ($v["device_code"]); ?></li>
                <li style="width: 20%;"><?php echo ($v["count"]); ?></li>
            </ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div class="space-20"></div>
<div class="space-20"></div>
<div class="space-20"></div>
<div class="space-20"></div>
<div class="space-20"></div>
<div style="width: 100%; height: 20px;display: none; text-align: center;color:#fff;" id="now_add">
    <span style="position: relative; display: inline-block;width: 12px;height: 10px; line-height: 10px;" class="a" ></span><span class="b">正在加载...</span>
</div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
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
</body></html>