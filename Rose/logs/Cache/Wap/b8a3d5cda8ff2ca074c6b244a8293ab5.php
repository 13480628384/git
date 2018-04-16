<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>本草丹充值</title>
    <style>
        .time_nums{
            width: 110px;
            height: 36px;
            margin-top: 5px;
            position: absolute;
        }
        .glass_pay{
            margin-top: 10px;
            width: 99%;
        }
        .glass_pay ul{
            width: 92%;
            height: 74px;
            line-height: 74px;
            border-radius: 6px;
            margin-bottom: 6px;
            text-align: center;
            background: url(./tpl/Wap/default/img/vip.png) no-repeat #fff;
            background-size: 60px 60px;
            background-position: 100% 0;
            margin: auto;
            border: 1px solid #2ca767;
        }
        .glass_pay .on{
            border: 1px solid #ed2929;
        }
        .glass_pay ul li{
            width:32%;
            height:40px;
            margin-left: 4px;
            float: left;
        }
        .types li{
            float: left;
            width: 33%;
            text-align: center;
        }
        .span-10{
            height: 10px;
        }
    </style>
</head>
<body style="background: #f2f2f2">
<header class="header-top">
    本草丹充值
</header>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form" id="J_TJJJRPhone">
       <li><label class="label">金额</label><input type="number" style="width:30%;" pattern="[0-9]*" class="totals" placeholder="0" maxlength="5" ></li>
    </ul>
    <aside class="account-submit">
        <button class="ui-btn-lg ui-btn-danger add_name" type="button" id="J_submitCustomers" style="background-color: #30BF75;border-color: #30bf75;">确定</button>
    </aside>
    <span>充值类型</span>
    <ul class="types">
        <li>VIP类型</li>
        <li>金额</li>
        <li>次数</li>
    </ul>
    <div style="margin-bottom: 26px;"></div>
    <div class="glass_pay">
        <ul class="on" price="<?php echo ($res["0"]); ?>" cishu="<?php echo intval($res[0]/$result['glass_total']); ?>">
            <li>白金卡VIP</li>
            <li>￥<?php echo ($res["0"]); ?></li>
            <li><?php echo intval($res[0]/$result['glass_total']); ?></li>
        </ul>
        <div class="span-10"></div>
        <ul price="<?php echo ($res["1"]); ?>" cishu="<?php echo intval($res[1]/$result['glass_total']); ?>">
            <li>金卡VIP</li>
            <li>￥<?php echo ($res["1"]); ?></li>
            <li><?php echo intval($res[1]/$result['glass_total']); ?></li>
        </ul>
        <div class="span-10"></div>
        <ul  price="<?php echo ($res["2"]); ?>" cishu="<?php echo intval($res[2]/$result['glass_total']); ?>">
            <li>银卡VIP</li>
            <li>￥<?php echo ($res["2"]); ?></li>
            <li><?php echo intval($res[2]/$result['glass_total']); ?></li>
        </ul>
        <div class="span-10"></div>
        <ul price="<?php echo ($res["3"]); ?>" cishu="<?php echo intval($res[3]/$result['glass_total']); ?>">
            <li>绿卡VIP</li>
            <li>￥<?php echo ($res["3"]); ?></li>
            <li><?php echo intval($res[3]/$result['glass_total']); ?></li>
        </ul>
    </div>
    <div style="clear: both"></div>
    <!--<p class="um-tips"><em>备注：</em>金卡8800元44次，银卡6600元33次，普通卡3000元15次，每次<?php echo ($result["glass_total"]); ?>元-->
    </p>
    <aside class="account-submit">
        <button class="ui-btn-lg ui-btn-danger vip_pay" type="button" id="J_submitCustomer" style="background-color: #30BF75;border-color: #30bf75;">确定</button>
    </aside>
</section>
<div class="space-20"></div>
<div style="text-align: center;"> 剩余<b style="color: #f00b0d;" class="cishu"><?php echo ($kewan); ?></b>次</div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="owner_id" value="<?php echo ($owner_id); ?>">
<input type="hidden" class="glass_total" value="<?php echo ($result["glass_total"]); ?>">
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
</body>
<script>
    $('.glass_pay ul').click(function(){
       //alert($(this).addClass('on'));
        $(this).addClass("on").siblings("ul").removeClass("on");
    });
    Zepto(function($) {
        //vip充值
        $('.vip_pay').tap(function(){
            var openid = $.trim($('.openid').val());
            var owner_id = $.trim($('.owner_id').val());
            var price = $('.glass_pay').find('.on').attr('price');
            var cishu = $('.glass_pay').find('.on').attr('cishu');
            var glass_total = $.trim($('.glass_total').val());
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
                $.ajax({
                    type: 'POST',
                    url: "<?php echo U('Glass_Pay/vip_pay_chongzhi');?>",
                    data: {"price": price, "openid": openid,cishu:cishu,"owner_id":owner_id},
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
                            //WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                //更新支付明细
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo U('Glass_Pay/vip_update');?>",
                                    data: {
                                        "out_trade_no": outTradeNo,
                                        "cishu": cishu,
                                        "price": price,
                                        "glass_total":glass_total,
                                        "openid": $(".openid").val()
                                    },
                                    dataType: 'json',
                                    async: false,
                                    success: function (data) {
                                        if (data.code == 200) {
                                            $('.cishu').html(data.msg);
                                            alert('支付成功');
                                        } else {
                                            alert('支付失败');
                                        }
                                        canUpdateRemainsum1 = true;

                                    },
                                    error: function (xhr, type) {
                                        alert('支付错误，请重新支付');
                                        canUpdateRemainsum1 = true;
                                    }
                                });

                            }
                        }
                    );
                }
            }
        });
        //普通充值
        $('.add_name').tap(function () {
            var openid = $.trim($('.openid').val());
            var totals = $.trim($('.totals').val());
            var owner_id = $.trim($('.owner_id').val());
            if(totals == ''){
                alert('请输入充值金额');
                return false;
            }
            var glass_total = $.trim($('.glass_total').val());
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
                $.ajax({
                    type: 'POST',
                    url: "<?php echo U('Glass_Pay/pay_chongzhi');?>",
                    data: {"price": totals, "openid": openid,"owner_id":owner_id},
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
                            //WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                //更新支付明细
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo U('Glass_Pay/pay_chongzhi_update');?>",
                                    data: {
                                        "out_trade_no": outTradeNo,
                                        "price": totals,
                                        "glass_total":glass_total,
                                        "openid": $(".openid").val()
                                    },
                                    dataType: 'json',
                                    async: false,
                                    success: function (data) {
                                        if (data.code == 200) {
                                            $('.cishu').html(data.msg);
                                            alert('支付成功');
                                        } else {
                                            alert('支付失败');
                                        }
                                        canUpdateRemainsum1 = true;

                                    },
                                    error: function (xhr, type) {
                                        alert('支付错误，请重新支付');
                                        canUpdateRemainsum1 = true;
                                    }
                                });

                            }
                        }
                    );
                }
            }
        });
    })
</script>
</html>