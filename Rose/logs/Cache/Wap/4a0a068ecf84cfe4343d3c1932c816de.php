<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>我的红包</title>
    <style>
        .facility{
            text-align: center;
            margin-top: 20px;
        }
        .red-money{
            font-size: 76px;
            color: #f05e5e;
        }
        .rose-img{
            margin-top: 20px;
        }
        .redbag{
            text-align: center;
            margin: 14px;
        }
        .redbag a {
            color: #f09090;
        }
    </style>
</head>
<body style="background: #fff">
<div class="facility">
        <span class="red-money">
            <?php echo ($sums); ?><tt style="font-size: 14px;">元</tt>
        </span>
        <p style="color:#bdb9b9;">金额超过10元可提现，每天可提现1次</p>
        <div class="rose-img">
            <img src="./tpl/Wap/default/img/rose_img.jpg">
        </div>
</div>
<?php if($sums < '10'): ?><button class="anniu" style="border-radius:0;margin-top:60px;background: #ccc;" type="button">提现</button>
<?php else: ?>
    <button class="anniu my_cash" style="border-radius:0;margin-top:60px;background: #e24b4b;" type="button">提现</button><?php endif; ?>
<div class="redbag">
    <a href="<?php echo U('Rose2Wallet/Present',array('openid'=>$openid));?>" class="presd">红包记录
    </a></div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="sum" value="<?php echo ($sums*100); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/angular.min.js"></script>
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
        $('.my_cash').tap(function(){
            var sum = parseInt($.trim($('.sum').val()));
            var openid = $.trim($('.openid').val());
            var el=$.loading({
                content:'正在提现'
            });
            var DATA = {
                openid:openid,
                sum:sum
            };
            $.post("<?php echo U('my_cash');?>",DATA,function(data){
                if(data.code == 200){
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href=data.url;
                    });
                } else {
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                }
                el.hide();
            },'json')
        })
    });
</script>
</body>
</html>