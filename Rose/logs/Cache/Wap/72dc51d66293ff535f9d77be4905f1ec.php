<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="tpl/Wap/default/css/new_file.css?12" />
    <script type="text/javascript" src="tpl/Wap/default/js/jquery-1.11.2.min.js" ></script>
    <script type="text/javascript" src="tpl/Wap/default/js/new_file.js?v1.0" ></script>
    <script type="text/javascript" src="tpl/Wap/default/css/layer.css?3" ></script>
    <script type="text/javascript" src="tpl/Wap/default/js/layer.js?2" ></script>
    <title>售货机充值</title>
    <style>
        .right{
            float: right;
            color: #f2f2f4;
            font-size: 18px;
            clear: both;
        }
        .addvideoes {
            position: fixed;
            left: 0;
            width: 100%;
            z-index: 100;
            bottom: 0;
        }
        .addvideoes li{
            height: 50px;
            border-bottom: 1px solid #f0ad4e;
            color: #666;
            text-align: center;
            font-size: 18px;
            line-height: 50px;
            background: #fff;
        }
        .footer li{
            float:left;
            width: 24.6%;
            text-align: center;
            border-right:1px solid #ccc
        }
        .footer li a{
            color: #fff;
        }
    </style>
</head>
<body>
<!--头部  star-->
<header>
    <a href="javascript:history.go(-1);">
        <div class="_left"><img src="tpl/Wap/default/img/Arrow_left_icon.png"></div>
        充值
    </a>
    <!--<span class="right">•••</span>-->
</header>
<!--微信支付和消费记录-->
<div class="addvideoes" style="display: none;">
    <ul>
        <li><a href="<?php echo U('weixin_pay',array('openid'=>$openid,'device_code'=>$device_code));?>">我的支付记录</a></li>
        <li style="border: none"><a href="<?php echo U('weixin_consume',array('openid'=>$openid,'device_code'=>$device_code));?>">历史消费</a></li>
    </ul>
</div>
<!--微信支付和消费记录-->
<!--头部 end-->
<div class="banner">
    <img src="tpl/Wap/default/img/balance.png" width="100%" height="100%"/>
</div>
<!--充值列表-->
<div class="person_wallet_recharge">
    <ul class="ul">
        <li money="1" rose="0">
            <h2>￥1</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="2" rose="0">
            <h2>￥2</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="5" rose="0">
            <h2>￥5</h2>
            <div class="sel" style=""></div>
        </li>
        <?php if($array2 != null): if(is_array($array2)): $i = 0; $__LIST__ = $array2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li money="<?php echo ($v["0"]); ?>" <?php if($v[1] != '0'): ?>rose="<?php echo ($v["1"]); ?>"<?php endif; ?>
            <?php if($v[1] == null or $v[1] == 0): ?>rose="0"<?php endif; ?>>
                <h2>￥<?php echo ($v["0"]); if($v[1] != '0'){echo '送'.$v[1];} ?></h2>
                <div class="sel" style=""></div>
            </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        <!--<li money="10">
            <h2>￥10</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="20">
            <h2>￥20</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="50">
            <h2>￥50</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="100">
            <h2>￥100</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="200">
            <h2>￥200</h2>
            <div class="sel" style=""></div>
        </li>
        <li money="500">
            <h2>￥500</h2>
            <div class="sel" style=""></div>
        </li>-->
        <div style="clear: both;"></div>
    </ul>
    <div class="pic"></div>
    <div class="botton">我要充值</div>
    <div class="agreement"><p>点击我要充值，即您已经表示同意<a>《充返活动协议》</a></p></div>
    <div class="nav">
        余额:<?php echo ($count); ?>元&nbsp;&nbsp;&nbsp;&nbsp;玫瑰：<?php echo ($rose); ?>币
    </div>
    <p style="text-align: center;color: #f00">注：赠送的为玫瑰币</p>
    <div style="height: 60px"></div>
    <!--遮罩层-->
    <div class="f-overlay"></div>
    <div class="addvideo" style="display: none;">
        <h3>本次充值<span class="money_xuan">1</span>元</h3>
        <ul>
            <li class="weixin_pay">微信支付</li>
            <li class="cal">取消</li>
        </ul>
    </div>
</div>
<div class="footer" style="background-color: #f57789; color: #FFF;width: 100%; height: 40px; line-height: 40px; position: fixed; bottom: 0px;  letter-spacing: 1px;font-size:14px">
    <ul>
        <li><a href="<?php echo U('weixin_pay',array('openid'=>$openid,'device_code'=>$device_code));?>">支付记录</a></li>
        <li><a href="<?php echo U('weixin_refund',array('openid'=>$openid,'device_code'=>$device_code));?>">申请退款</a></li>
        <li><a href="<?php echo U('weixin_consume',array('openid'=>$openid,'device_code'=>$device_code));?>">历史消费</a></li>
        <li style="border:none"><a href="<?php echo U('feedback',array('openid'=>$openid,'device_code'=>$device_code));?>">问题反馈</a></li>
    </ul>
</div>
<input type="hidden" id="weixin" value="<?php echo U('VendingPay/weixin_pay');?>">
<input type="hidden" id="update" value="<?php echo U('VendingPay/weixin_update');?>">
<input type="hidden" id="openid" value="<?php echo ($openid); ?>">
<input type="hidden" id="device_code" value="<?php echo ($device_code); ?>">
<input type="hidden" id="owner_id" value="<?php echo ($owner_id); ?>">
<input type="hidden" id="rose" value="0">
</body>
<script>
    $(function () {
        //点击弹出层
        $('.right').click(function () {
            $('.addvideoes').show();
            $('.f-overlay').show();
        });
        $('.f-overlay').click(function () {
            $('.f-overlay').hide();
            $('.addvideoes').hide();
        })
    })
</script>
</html>