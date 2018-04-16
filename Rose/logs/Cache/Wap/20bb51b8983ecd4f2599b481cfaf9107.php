<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>微信支付</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <style type="text/css">
        .bg-df3100 {
            background: #f7f7f7;
        }
        .header-top{
            text-align: center;
            padding-bottom: 10px;
            padding-top: 10px;
        }
        .weui-form-preview {
            position: relative;
            background-color: #FFFFFF;
        }
        .weui-form-preview__hd {
            position: relative;
            padding: 10px 15px;
            text-align: right;
            line-height: 2.5em;
        }
        .weui-form-preview__item {
            overflow: hidden;
        }
        .weui-form-preview__label {
            float: left;
            margin-right: 1em;
            min-width: 4em;
            color: #999999;
            text-align: justify;
            text-align-last: justify;
        }
        .weui-form-preview__hd .weui-form-preview__value {
            font-style: normal;
            font-size: 1.6em;
        }
        .weui-form-preview__value {
            display: block;
            overflow: hidden;
            word-break: normal;
            word-wrap: break-word;
        }
        .weui-form-preview__bd {
            padding: 10px 15px;
            font-size: .9em;
            text-align: right;
            color: #999999;
            line-height: 2;
        }
        .weui-form-preview__item {
            overflow: hidden;
        }
        .weui-form-preview__label {
            float: left;
            margin-right: 1em;
            min-width: 4em;
            color: #999999;
            text-align: justify;
            text-align-last: justify;
        }
        .weui-form-preview__value {
            display: block;
            overflow: hidden;
            word-break: normal;
            word-wrap: break-word;
        }
        .page__bd{
            margin-bottom: 60px;
        }
    </style>
</head>
<body class="bg-df3100">
<header class="header-top">
    微信支付
</header>
<div class="page__bd">
    <?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">付款金额</label>
                    <em class="weui-form-preview__value" style="color: #333">¥<?php echo ($v["pay_account"]); ?></em>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">商品</label>
                    <span class="weui-form-preview__value">深圳玫瑰物联-充值</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">商户名称</label>
                    <span class="weui-form-preview__value">玫瑰物联</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">当前状态</label>
                    <span class="weui-form-preview__value"><?php if($v['pay_status'] == 1): ?>支付成功<?php else: ?>支付失败<?php endif; ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">支付时间</label>
                    <span class="weui-form-preview__value"><?php echo ($v["create_date"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">支付方式</label>
                    <span class="weui-form-preview__value">其它</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">交易单号</label>
                    <span class="weui-form-preview__value"><?php echo ($v["transaction_id"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">商户单号</label>
                    <span class="weui-form-preview__value"><?php echo ($v["out_trade_no"]); ?></span>
                </div>
            </div>
        </div>
        <br><?php endforeach; endif; else: echo "" ;endif; ?>
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