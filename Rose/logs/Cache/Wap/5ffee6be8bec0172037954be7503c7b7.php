<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>退款申请</title>
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
        .weui-form-preview__btn {
            position: relative;
            display: block;
            border-top:1px solid #d5d5d6;
            border-right:1px solid #d5d5d6;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
            color: #3CC51F;
            text-align: center;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        button.weui-form-preview__btn {
            background-color: transparent;
            border: 0;
            outline: 0;
            border-top:1px solid #d5d5d6;
            line-height: inherit;
            border-right: 1px solid #d5d5d6;
            font-size: inherit;
        }
        .weui-form-preview__ft {
            position: relative;
            line-height: 50px;
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
        }
        .carefull{
            color: #f00;
            padding: 8px;
        }
    </style>
</head>
<body class="bg-df3100">
<header class="header-top">
    退款申请
</header>
<div class="carefull">
    注意：退款通过加微信好友转账，后期会有支付宝，银行卡转账的形式。退款后点击通过即可（点击通过和不通过都会发一条短信告知用户）请按照实际退款金额进行退款
</div>
<div class="page__bd">
    <?php if(is_array($userfund)): $i = 0; $__LIST__ = $userfund;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">申请金额</label>
                    <em class="weui-form-preview__value" style="color: #333">¥<?php echo ($v["total"]); ?></em>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">实际退款金额</label>
                    <span class="weui-form-preview__value arrival" style="color: #f00"></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">用户姓名</label>
                    <span class="weui-form-preview__value"><?php echo ($v["name"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">电话号码</label>
                    <span class="weui-form-preview__value"><?php echo ($v["phone"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">当前状态</label>
                    <span class="weui-form-preview__value">
                        <?php if($v['status'] == 0): ?>未审核
                        <?php elseif($v['status'] == 1): ?>
                            审核通过
                        <?php else: ?>审核不通过<?php endif; ?>
                    </span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">申请时间</label>
                    <span class="weui-form-preview__value"><?php echo ($v["apple_time"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">微信号</label>
                    <span class="weui-form-preview__value"><?php echo ($v["wechatid"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">交易单号</label>
                    <span class="weui-form-preview__value"><?php echo ($v["payment_no"]); ?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">商户单号</label>
                    <span class="weui-form-preview__value"><?php echo ($v["partner_trade_no"]); ?></span>
                </div>
            </div>
            <?php if($v['status'] == 1): ?><div class="weui-form-preview__ft">
                    <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">已审核</a>
                </div>
            <?php else: ?>
                <div class="weui-form-preview__ft">
                    <button data="<?php echo ($v["id"]); ?>" class="shiji weui-form-preview__btn weui-form-preview__btn_default">实际退款</button>
                    <button data="<?php echo ($v["id"]); ?>" class="examine weui-form-preview__btn weui-form-preview__btn_default" dataid="1">通过</button>
                    <button data="<?php echo ($v["id"]); ?>" dataid ="2" type="submit" class="examine weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">不通过</button>
                </div><?php endif; ?>
        </div>
        <br><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="pay" value="">
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
<script>
    Zepto(function($){
        $('.shiji').tap(function(){
            var all = $(this).parent().parent().find('.weui-form-preview__value');
            var id = $(this).attr('data');
            $.post("<?php echo U('real');?>",{id:id},function(data){
                $('.pay').val(data.msg);
                all.html('￥'+data.msg);
            },'json')
        })
        $('.examine').tap(function(){
            if(confirm('审核请请确认是否已经退款到用户')){
                var status = $(this).attr('dataid');
                var id = $(this).attr('data');
                var openid = $.trim($('.openid').val());
                var pay = $.trim($('.pay').val());
                if(pay == ''){
                    $.dialog({
                        content:'请先获取实际退款金额',
                        button:['好']
                    });
                    return false;
                }
                var el = $.loading({
                    content: '正在审核'
                });
                $.post("<?php echo U('check_status');?>",{openid:openid,status:status,id:id},function(data){
                    if(data.code == 200){
                        var dg = $.dialog({
                            content:data.msg,
                            button:['好']
                        });
                        dg.on('dialog:action', function (e) {
                            window.location.reload();
                        });
                    } else {
                        $.dialog({
                            content:data.msg,
                            button:['好']
                        });
                    }
                    el.hide();
                },'json')
            }
        });
    })
</script>
</body></html>