<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html manifest="" lang="ch"><head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title><?php if($title == null): ?>热爱天然共享洗车联盟<?php else: echo ($title); endif; ?></title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=640, user-scalable=yes, target-densitydpi=device-dpi">

    <link rel="stylesheet" href="./tpl/Wap/default/css/wechat.css">

    <link rel="stylesheet" href="./tpl/Wap/default/css/mui.min.css">

    <!--<link rel="stylesheet" href="./tpl/Wap/default/css/play_cd1.css">-->
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <style>
        .top{
            background: #6daeee;
            height: 150px;
        }
        .left{
            margin-left: 12%;
            float: left;
            line-height: 150px;
        }
        .right{
            float: left;
            margin-left: 10%;
            margin-top: 40px;
            color: #fff;
            font-size: 22px;
        }
        .pay-miao{
            font-size: 21px;
        }
        .pay-li li{
            width: 21%;
            margin-left: 3.2%;
            float: left;
            color: #fff;
            height: 104px;
            font-size: 14px;
            line-height: 36px;
            margin-top: 10px;
            background: #6daeee;
            border-radius: 8px;
            text-align: center;
            box-shadow: 4px 10px 9px 2px #88888A;
        }
        .pay-li{
            margin: auto;
        }
        .pay-miao li{
            width: 21%;
            float: left;
            margin-left: 3.2%;
            margin-top: 17px;
            text-align: center;
        }
        .pay-zan img{
            width: 80px;
        }
        .launch{
            width: 50%;
            height: 70px;
            text-align: center;
            margin-top: 140px;
            line-height: 70px;
            /* margin: auto; */
            margin-left: 25%;
            /* margin-bottom: 60px; */
            border-radius: 5%;
            color: #fff;
            font-size: 30px;
            box-shadow: 4px 10px 9px 2px #88888A;
            background: #6daeee;
        }
        .pay-li{
            margin-top: 6%;
        }
        .pay-zan li{
            width: 21%;
            margin-left: 3.2%;
            float: left;
            height: 104px;
            font-size: 12px;
            line-height: 94px;
            margin-top: 10px;
            background: #6daeee;
            border-radius: 8px;
            text-align: center;
            box-shadow: 4px 10px 9px 2px #88888A;
        }
        .divs{
            height: 120px;
            width: 100%;
        }
        .footer{
            text-align: center;
            font-size: 21px;
            height: 50px;
            margin-top: 25px;
        }
        .footers li{
            float: left;
            width: 25%;
            text-align: center;
            line-height: 100px;
            color: #fff;
        }
        .f{
            background: #6daeee; font-size: 24px;width:100%;
            height:100px;
            position: fixed;
            bottom: 0;
        }
        .pay-li img{
            width:100px;
        }
    </style>

</head>

<body style="height:auto;">
<header class="top">
    <div class="left"><img src="./tpl/Wap/default/img/relogo.png" width="109" height="105"></div>
    <div class="right">RE AI TIAN RAN <br/>深圳热爱天然环境科技有限公司</div>
</header>
<div style="clear: both"></div>
    <ul class="pay-li">
        <li price="6"><img src="./tpl/Wap/default/img/pay1.png"></li>
        <li><img src="./tpl/Wap/default/img/pay2.png"></li>
        <li price="199"><img src="./tpl/Wap/default/img/pay3.png"></li>
        <li><img src="./tpl/Wap/default/img/pay4.png"></li>
    </ul>
<div style="clear: both"></div>
<ul class="pay-miao">
    <li>充值6元</li>
    <li>洗币<span class="accounts"><?php echo ($count); ?></span>元</li>
    <li>开通199元</li>
    <li><span class="member">尊享<?php if($car_member == null): ?>0<?php else: echo ($car_member); endif; ?></span>元</li>
</ul>
<div style="clear: both"></div>
<div class="launch" price="<?php echo ($out["0"]); ?>" times="<?php echo ($out["1"]); ?>">
    点我洗车
</div>
<div class="divs"></div>
<ul class="pay-zan">
    <li><img src="./tpl/Wap/default/img/pay5.png"></li>
    <li><img src="./tpl/Wap/default/img/pay6.png"></li>
    <li><img src="./tpl/Wap/default/img/pay7.png"></li>
    <li><img src="./tpl/Wap/default/img/pay8.png"></li>
</ul>
<div style="clear: both"></div>
<ul class="pay-miao bo">
    <li>清水-暂停</li>
    <li>泡沫-暂停</li>
    <li>洗手-暂停</li>
    <li>吸尘-暂停</li>
</ul>
<div style="clear: both"></div>
<div class="footer">全国统一服务热线：<a href="tel:400-040-8809">400-040-8809</a></div>

<footer class="f">
    <ul class="footers">
        <li>我的</li>
        <li>附近</li>
        <li>车保</li>
        <li>商城</li>
    </ul>
</footer>
</body>

<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">

<input type="hidden" class="price" value="6">

<input type="hidden" class="all" value="<?php echo ($count); ?>">
<input type="hidden" class="members" value="<?php echo ($car_member); ?>">

<input type="hidden" class="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">

<input type="hidden" class="openid" value="<?php echo ($openid); ?>">

<input type="hidden" class="buyer_id" value="<?php echo ($buyer_id); ?>">

<input type="hidden" class="device_command" value="<?php echo ($device_command); ?>">

<input type="hidden" class="device_id" value="<?php echo ($device_id); ?>">

<input type="hidden" class="online_status" value="<?php echo ($online_status); ?>">

<input type="hidden" class="vehicle_pay" value="<?php echo U('NewPay/car_pay_owner');?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/mui.min.js"></script>
<script src="./tpl/Wap/default/js/common.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</html>
<script>
    //点击充值
    $('.pay-li li').on('click',function(){
        if($(this).attr('price')) {
            var openid = $.trim($('.openid').val());
            var weixin_alipay_type = $('.weixin_alipay_type').val();
            var price = $(this).attr('price');
            if (weixin_alipay_type == 'wechat') {
                if (typeof WeixinJSBridge == "undefined") {
                    if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                } else {
                    var jsApiParameters = null;
                    var outTradeNo = null;//商户订单号
                    var isGoToPay = false;
                    var vehicle_pay = $('.vehicle_pay').val();
                    var device_command = $('.device_command').val();
                    $.ajax({
                        type: 'POST',
                        url: vehicle_pay,
                        data: {"price": price, "openid": openid, "device_command": device_command},
                        dataType: "json",
                        timeout: 3000,
                        async: false,
                        success: function (data) {
                            if (data.code == 200) {
                                isGoToPay = true;
                                jsApiParameters = data.jsapi;
                                outTradeNo = data.out_trade_no;
                            } else {
                                alert(data.error);
                            }
                        },
                        error: function (xhr, type) {
                            alert('支付错误,请重新支付');
                        }
                    });
                    //判断是否调用支付控件
                    if (isGoToPay) {
                        var jsPs = eval('(' + jsApiParameters + ')');
                        WeixinJSBridge.invoke(
                            'getBrandWCPayRequest',
                            jsPs,
                            function (res) {
                                if (res.err_msg == "get_brand_wcpay_request:ok") {
                                    //更新支付明细
                                    var device_command = $('.device_command').val();
                                    var device_id = $('.device_id').val();
                                    $.ajax({
                                        type: 'POST',
                                        url: "<?php echo U('NewPay/car_update_owner');?>",
                                        data: {
                                            "out_trade_no": outTradeNo,
                                            "price": price,
                                            "device_command": device_command,
                                            "openid": $(".openid").val()
                                        },
                                        dataType: 'json',
                                        async: false,
                                        success: function (data) {
                                            if (data.codes == '200') {
                                                $('.accounts').html(data.msg);
                                                $('.all').val(data.msg);
                                                $('.member').html('尊享'+data.mr);
                                                $('.members').val(data.mr);
                                            } else {
                                                alert('支付失败');
                                            }
                                        },
                                        error: function (xhr, type) {
                                            alert('支付错误，请重新支付');
                                        }
                                    });
                                }
                            }
                        );
                    }
                }
            } else {
                var buyer_id = $.trim($('.buyer_id').val());
                var device_id = $('.device_id').val();
                var scan_code = $('.scan_code').val();
                var device_command = $('.device_command').val();
                var datae = {
                    scan_code: scan_code,
                    price: price,
                    device_command: device_command,
                    device_id: device_id,
                    buyer_id: buyer_id
                }
                post("<?php echo U('AlipayCommon/car_ali_pay_xiche');?>", datae)
            }
        }
    })
    //var clicktag = 0;
    Zepto(function($) {
        $(".launch").tap(function () {
            if($(this).hasClass('co')){
                $.dialog({
                    content:'正在洗车中',
                    button:['好']
                });
                return false;
            }
            var price = $(this).attr('price');
            var times = $(this).attr('times');
            var openid = $('.openid').val();
            var weixin_alipay_type = $('.weixin_alipay_type').val()
            var buyer_id = $('.buyer_id').val();
            var device_command = $('.device_command').val();
            var all = $('.all').val();//余额
            var member = $('.members').val();
            if (all < parseInt(price) && member < parseInt((price))) {
                $.dialog({
                    content:'洗额不足',
                    button:['好']
                });
                return false;
            }
            var device_id = $('.device_id').val();
            var dat = {
                weixin_alipay_type: weixin_alipay_type,
                openid: openid,
                times: times,
                price: price,
                buyer_id: buyer_id,
                device_id: device_id,
                device_command: device_command
            };
            var el=$.loading({
                content:'加载中...'
            });
            $.post("<?php echo U('Car_New/car_start_pay');?>", dat, function (data) {
                if (data.code == 200) {
                    $('.accounts').html(data.count);
                    $('.all').val(data.count);
                    $('.members').val(data.member);
                    $('.member').html(data.member);
                    $.dialog({
                        content:'启动成功',
                        button:['好']
                    });
                    var countdown=60;
                    var i = setInterval(function() {
                        if (countdown == 0) {
                            $('.launch').removeClass('co');
                            countdown = 60;
                            clearInterval(i);
                            return;
                        } else {
                            $('.launch').addClass('co');
                           // $('.dao').html("还剩（"+countdown+"）秒");
                            countdown--;
                        }
                    },1000);
                } else {
                    alert(data.msg);
                }
                $(".Rose_xyj_staty .botton2").removeClass('on');
                el.hide();
            }, 'json');
        });
    });
</script>