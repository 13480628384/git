<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>统计收益</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
</head>
<body>
<style type="text/css">
    .tab-yf-null{
        width: 100%;
        text-align: center;
        padding: initial;
        color: #414141;
    }
    .tab-customer .tab-yf-box{
        background: #fff;
        padding: 0;
    }
    .tab-yj-nav>li.active {
        border-color: #ff0000;
        color: #ff0000;
    }
    .tab-customer .tab-yj-nav>li {
        width: 50%;
        border-bottom: 1px solid #eee;
        font-size: 16px;
    }
    .tab-yf-box-main-time {
        text-align: center;
        padding: 10px 0;
        width: 23%;
    }
</style>
<div class="tab-yj tab-customer">
    <ul class="tab-yj-nav">
        <li class="active">近30天收益</li>
        <li>近半年收益</li>
    </ul>
    <div class="tab-yf-box">
        <div class="tab-yf-null">
            <div class="tab-yf-box1">
                <div class="tab-yf-box-main f14" style="padding: 10px;">
                        <div style="width: 100%">收益</div>
                </div>
                <?php if(is_array($day)): $i = 0; $__LIST__ = $day;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-yf-box-main f12">
                    <div class="tab-yf-box-main-name">
                        <div><?php echo ($v["m"]); ?>月<?php echo ($v["days"]); ?>日</div>
                    </div>
                    <div class="tab-yf-box-main-time">
                        <?php echo ($v["counts"]); ?> 元
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
         </div>
    <div class="tab-yf-box" style="display:none">
        <div class="tab-yf-null">
            <div class="tab-yf-box1">
                <div class="tab-yf-box-main f14" style="padding: 10px;">
                    <div style="width: 100%">收益</div>
                </div>
                <?php if(is_array($month)): $i = 0; $__LIST__ = $month;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-yf-box-main f12">
                        <div class="tab-yf-box-main-name">
                            <div><?php echo ($v["m"]); ?>月<?php echo ($v["days"]); ?>日</div>
                        </div>
                        <div class="tab-yf-box-main-time">
                            <?php echo ($v["counts"]); ?> 元
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        </div>
</div>
<div class="space-60"></div>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>
    var tabYjNav=$('.tab-yj-nav>li');
    var tabYjBox=$('.tab-yf-box');
    tabYjNav.tap(function(){
        $(this).addClass('active').siblings('li').removeClass('active');
        var index=tabYjNav.index(this);
        tabYjBox.eq(index).show().siblings('.tab-yf-box').hide();
    });
</script>
</html>