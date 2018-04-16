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
            <?php echo ($count); ?><tt style="font-size: 14px;">元</tt>
        </span>
    <p style="color:#bdb9b9;">金额超过10元可退款，每天只能申请1次</p>
    <div class="rose-img">
        <img src="./tpl/Wap/default/img/rose_img.jpg">
    </div>
</div>
<?php if($count < '10'): ?><button class="anniu" style="border-radius:0;margin-top:60px;background: #ccc;" type="button">提交申请</button>
    <?php else: ?>
    <button class="anniu" style="border-radius:0;margin-top:60px;background: #e24b4b;" type="button">自动退款暂未开通，可手动申请退款</button><?php endif; ?>
<div class="redbag">
    <a href="<?php echo U('Present',array('openid'=>$openid,'device_code'=>$device_code));?>" class="presd">退款记录
    </a>
    <a href="<?php echo U('Manual_application',array('openid'=>$openid,'device_code'=>$device_code));?>" class="presd">手动申请
</a>
</div>
<!--<p>注：退款先关注玫瑰物联公众号，关注后即可申请退款，将以红包发送到公众号领取</p>-->
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="sum" value="<?php echo ($sums*100); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/angular.min.js"></script>
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