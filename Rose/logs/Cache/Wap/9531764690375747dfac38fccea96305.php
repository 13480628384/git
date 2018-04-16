<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/vase.css">
    <title>我的玫瑰花瓶</title>
</head>
<body style="background: #f2f2f2">
<!-- 头部 -->
<div class="head">
    <span class="head-lf" onclick="javascript:history.go(-1)"></span>
    <span class="head-ct">我的玫瑰花瓶</span>
    <span class="head-ri head-ri-add"></span>
</div>
<!--我的玫瑰花瓶-->
<div class="vase">
    <div class="vase_top">
        <?php if($rose['headimgurl'] == null): ?><img src="./tpl/Wap/default/img/reg_1.png" alt="">
            <?php else: ?><img src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
        <div class="vase_top_zi">
            <p>这是玫瑰ID：<?php echo ($rose["rose_id"]); ?></p>
            <p>昵称：<?php echo ($rose["nickname"]); ?></p>
        </div>
    </div><!--<img src="./tpl/Wap/default/img/wchat.gif" alt="" width="100%">-->
    <div class="vase_bottom code-main">

    </div>
    <p class="scan">扫一扫上面的二维码，送玫瑰给我</p>
</div>
<input type="hidden" value="<?php echo ($url); echo U('my',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$id,'scan_code'=>$scan_code));?>" class="qrcode">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/jquery.qrcode.min.js"></script>
</body>
<script>
    $(function(){
        $(".code-main").qrcode({
            width: 230, //宽度
            height: 230, //高度
            foreground:'rgb(51, 51, 51)',
            text: $('.qrcode').val()
        });
    });
</script>
</html>