<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>
        <?php if($car_pay != null): ?>热爱天然共享洗车联盟
            <?php else: ?>
            中移物联终端<?php endif; ?>
    </title>
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/Rose2_index_style.css">
</head>
<body>
<div class="web_v2">
    <div class="heard_con">
        <div class="clear"></div>
        <ul>
            <li class="heard_con_li">
                <a href="<?php echo U('Rose2Alllist/weixin_list',array('openid'=>$openid));?>">
                    <p class="heard_con_li_span1 " ><?php echo $balance; ?></p>
                    <p class="heard_con_li_span2" style="margin-bottom: 1.5rem">微信收益</p>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Rose2Alllist/alipay_list',array('openid'=>$openid));?>">
                    <p class="heard_con_li_span1"><?php echo $alipay_count; ?></p>
                    <p class="heard_con_li_span2" style="margin-bottom: 1.5rem">支付宝收益</p>
                </a>
            </li>
            <?php if($car_pay != null): ?><li class="heard_con_li heard_con_li1">
                    <a href="<?php echo U('CarWallet/index',array('user_id'=>$user_id));?>">
                        <p class="heard_con_li_span1"><?php if($car_account == null): ?>0<?php else: echo ($car_account); endif; ?></p>
                        <p class="heard_con_li_span2" style="margin-bottom: 1.5rem">分成提现</p>
                    </a>
                </li><?php endif; ?>
            <!--<li class="heard_con_li heard_con_li1">
                <a href="<?php echo U('Rose2Alllist/adv_list',array('openid'=>$openid));?>">
                    <p class="heard_con_li_span1">0</p>
                    <p class="heard_con_li_span2">玫瑰收益</p>
                </a>
            </li>-->
            <li class="heard_con_li1">
                <a href="<?php echo U('Rose2Alllist/line_list',array('openid'=>$openid));?>">
                    <p class="heard_con_li_span1"><?php echo ($lop_count); ?></p>
                    <p class="heard_con_li_span2">线下收益</p>
                </a>
            </li>
            <div class="clear"></div>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="device">可提现的金额：<?php echo ($sums); ?>&nbsp;<br/><span style="color:#f00b0d">提示：</span>等于线上总收益减去已提现金额</div>
    <ul class="deviceli">
        <!--<?php if($office['id'] == 'kefuanmoyitest'): ?><li><a href="<?php echo U('Rose2Red/index',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a1.png" alt=""><span>红包</span></a></li>
        <?php else: ?>
            <li><a href="<?php echo U('Rose2Wallet/index',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a1.png" alt=""><span>钱包</span></a></li><?php endif; ?>-->
        <li><a href="<?php echo U('Rose2Wallet/fuerkan',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a1.png" alt=""><span>收益提现</span></a></li>
        <li><a href="<?php echo U('Device/device_list',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a2.png" alt=""><span>设备管理</span></a></li>
        <li><a href="<?php echo U('Device/group_list',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a3.png" alt=""><span>群组列表</span></a></li>
        <li><a href="<?php echo U('Rose2Orderflow/index',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a4.png" alt=""><span>流水列表</span></a></li>
        <li><a href="<?php echo U('package',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a5.png" alt=""><span>设备安装</span></a></li>
        <?php if($car_pay != null): ?><li><a href="<?php echo U('CarFen/index',array('user_id'=>$user_id));?>"><img src="./tpl/Wap/default/img/a4.png" alt=""><span>分成报表</span></a></li><?php endif; ?>
            <!--   <li><a href="<?php echo U('Rose2Wallet/anm_cash',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a1.png" alt=""><span>按摩椅收益</span></a></li>
        <li><a href="<?php echo U('Rose2Wallet/charger_cash',array('openid'=>$openid));?>"><img src="./tpl/Wap/default/img/a1.png" alt=""><span>充电器收益</span></a></li> -->
        <div class="clear"></div>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
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