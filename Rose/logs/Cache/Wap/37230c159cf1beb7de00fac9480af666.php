<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="tpl/Wap/default/css/new_file.css?12" />
    <script type="text/javascript" src="tpl/Wap/default/js/jquery-1.11.2.min.js" ></script>
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
            width: 49%;
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
        <li><a href="<?php echo U('alipay_pay',array('buyer_id'=>$buyer_id,'device_code'=>$device_code));?>">我的支付记录</a></li>
        <li style="border: none"><a href="<?php echo U('alipay_consume',array('buyer_id'=>$buyer_id,'device_code'=>$device_code));?>">我的消费记录</a></li>
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
        <li money="0.1" rose="0">
            <h2>￥0.1</h2>
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
        <div style="clear: both;"></div>
    </ul>
    <div class="pic"></div>
    <div class="botton">我要充值</div>
    <div class="agreement"><p>点击我要充值，即您已经表示同意<a>《充返活动协议》</a></p></div>
    <div class="nav">
        余额:<?php echo ($count); ?>元 &nbsp;&nbsp;&nbsp;&nbsp;玫瑰：<?php echo ($rose); ?>币
    </div>
    <p style="text-align: center;color: #f00">注：赠送的为玫瑰币</p>
    <!--遮罩层-->
    <div class="f-overlay"></div>
    <div class="addvideo" style="display: none;">
        <h3>本次充值<span class="money_xuan">1</span>元</h3>
        <ul>
            <li class="weixin_pay">支付宝支付</li>
            <li class="cal">取消</li>
        </ul>
    </div>
</div>
<div class="footer" style="background-color: #f57789; color: #FFF;width: 100%; height: 40px; line-height: 40px; position: fixed; bottom: 0px;  letter-spacing: 1px;font-size:14px">
    <ul>
        <li><a href="<?php echo U('alipay_pay',array('buyer_id'=>$buyer_id,'device_code'=>$device_code));?>">支付记录</a></li>
        <li style="border:none"><a href="<?php echo U('alipay_consume',array('buyer_id'=>$buyer_id,'device_code'=>$device_code));?>">历史消费</a></li>
    </ul>
</div>
<input type="hidden" id="alipay_pay" value="<?php echo U('VendingPay/alipay_pay');?>">
<input type="hidden" id="buyer_id" value="<?php echo ($buyer_id); ?>">
<input type="hidden" id="device_code" value="<?php echo ($device_code); ?>">
<input type="hidden" id="owner_id" value="<?php echo ($owner_id); ?>">
<input type="hidden" id="rose" value="0">
<script src="./tpl/Wap/default/js/common.js"></script>
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
    });
    $(function(){
        var a=100;
        $(".person_wallet_recharge .ul li").click(function(e){
            $(this).addClass("current").siblings("li").removeClass("current");
            $(this).children(".sel").show(0).parent().siblings().children(".sel").hide(0);
            var rose = $(this).attr('rose');
            $('#rose').val(rose);
        });
        //充值
        $(".botton").click(function(e){
            if(!$(".person_wallet_recharge .ul li").hasClass('current')){
                layer.open({
                    content: '请选择金额',
                    style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
                    time:3
                });
                return false;
            }
            var money = $(".person_wallet_recharge ul li.current").attr('money');
            $('.money_xuan').html(money);
            $(".f-overlay").show();
            $(".addvideo").show();
        });
        $(".cal").click(function(e){
            $(".f-overlay").hide();
            $(".addvideo").hide();
        });
        //支付宝 start
        $('.weixin_pay').click(function () {
            var alipay_pay = $('#alipay_pay').val();
            var buyer_id = $('#buyer_id').val();
            var device_code = $('#device_code').val();
            var owner_id = $('#owner_id').val();
            var rose = $('#rose').val();//赠送玫瑰币
            var money_xuan = $('.money_xuan').html();
            var data = {"price": money_xuan, "buyer_id": buyer_id, "device_code": device_code, "owner_id": owner_id,"rose":rose};
            post(alipay_pay,data);
        });
        //支付宝 end
    });

</script>
</html>