<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>退款申请</title>
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
    <p style="color:#bdb9b9;">每天可申请1次</p>
    <div class="rose-img">
        <img src="./tpl/Wap/default/img/rose_img.jpg">
    </div>
</div>
<?php if($sums <= '0'): ?><button class="anniu" style="border-radius:0;margin-top:60px;background: #ccc;" type="button">一键申请</button>
    <?php else: ?>
    <button class="anniu my_cash" style="border-radius:0;margin-top:60px;background: #e24b4b;" type="button">一键申请</button><?php endif; ?>
<!--<div class="redbag">
    <a href="<?php echo U('Rose2Wallet/Present',array('openid'=>$openid));?>" class="presd">红包记录
    </a></div>-->
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="sum" value="<?php echo ($sums); ?>">
<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" class="area_id" value="<?php echo ($area_id); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/angular.min.js"></script>
<script>
    Zepto(function($){
        $('.my_cash').tap(function(){
            var sum = $.trim($('.sum').val());
            var openid = $.trim($('.openid').val());
            var scan_code = $.trim($('.scan_code').val());
            var area_id = $.trim($('.area_id').val());
            if(sum < 1){
                $.dialog({
                    content:'退款不能低于1元',
                    button:['好']
                });
                return false;
            }
            var el=$.loading({
                content:'正在申请'
            });
            var DATA = {
                openid:openid,
                sum:sum,
                scan_code:scan_code,
                area_id:area_id
            };
            $.post("<?php echo U('user_cash');?>",DATA,function(data){
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