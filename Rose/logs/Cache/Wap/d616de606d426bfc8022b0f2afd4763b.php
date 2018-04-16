<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html manifest="" lang="ch"><head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title><?php if($title == null): ?>热爱天然自助洗车联盟<?php else: echo ($title); endif; ?></title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=640, user-scalable=yes, target-densitydpi=device-dpi">

    <link rel="stylesheet" href="./tpl/Wap/default/css/wechat.css">

    <link rel="stylesheet" href="./tpl/Wap/default/css/mui.min.css">

    <link rel="stylesheet" href="./tpl/Wap/default/css/play_cd1.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <style>

        *{box-sizing: initial;}

        .sqcdq li{    margin-top: 40px;

            margin-bottom: 40px;

            height: 75px;

            line-height: 75px;}
        #launch{
            padding:0;
        }
        .sqcdq li{
            float:left;
            width:49.6%;
            border-radius:0;
            padding: 0;
            margin: 0;
            height: 200px;
            line-height: 200px;
            border: 1px solid #fff;
        }
        .dao{
            clear: both;
            width: 100%;
            height: 50px;
            text-align: center;
            font-size: 24px;
            line-height: 50px;
            color: #ec1515;
        }
        .feilei span{
            text-align: center;
            text-indent:0;
            border-radius:0;
            width:auto;
            padding:2px 26px 2px 26px;
        }
        .feilei b{
            border-radius: 0;
        }
    </style>

</head>

<body style="height:auto;">

<header style="background: #00a098;padding-top: 16px;" >

    <aside>
        洗额<b><span id="balances"><?php echo ($count); ?></span></b>
    </aside>

    <div class="feilei">
        <!--<span class="cash this" sv="1">1元</span>-->
        <!-- <span class="cash this" sv="3">3元</span>-->
        <!--<span class="cash" sv="6">6元</span>-->
        <span class="cash this" sv="10">10元</span>
        <span class="cash" sv="20">20元</span>
        <span class="cash" sv="199">年卡600洗币199元</span>
        <div id="wxpayid" style="display: inline-block;margin-top: 4px;margin-left:4px;"><b>购买</b>	</div>

    </div>

</header>

<div class="xlts">选择洗车,点击开始 <?php echo ($out["0"]); ?>元洗<?php echo ($out["1"]); ?>分钟</div>

<ul id="launch" class="sqcdq">

    <li  price="<?php echo ($out["0"]); ?>" cd="A" class="lion">
        <tt id="zimuA" class="z1" style="display: none">0</tt>
        洗车
    </li>
    <li  price="<?php echo ($out["0"]); ?>" cd="B" class="lion">
        <tt id="zimuB" class="z1" style="display: none">0</tt>
        暂停
    </li>
    <li  price="<?php echo ($out["0"]); ?>" cd="C" class="lion">
        <tt id="zimuC" class="z1" style="display: none">0</tt>
        泡沫
    </li>
    <li  price="<?php echo ($out["0"]); ?>" cd="D" class="lion">
        <tt id="zimuD" class="z1" style="display: none">0</tt>
        洗手
    </li>

</ul>
<div class="dao"></div>
<div class="xlts">服务电话:<?php echo ($phone); ?></div>
<div class="xlts"><a href="<?php echo U('Userefund/index',array('openid'=>$openid,'total'=>$count,'device_command'=>$device_command));?>">我要退款</a></div>
<div style="clear:both; height:100px;"></div>
<footer style="background: #00A098; line-height: 45px; font-size: 24px">

    <div class="menu" style="position: relative; background: #00A098">

        <?php if($weixin_alipay_type == wechat): ?><a href="<?php echo U('WeixinUserConsume/index',array('openid'=>$openid));?>" style="color:#fff;text-decoration:none;">我的消费记录</a>

            <?php else: ?>

            <a href="<?php echo U('WeixinUserConsume/alipay_index',array('buyer_id'=>$buyer_id));?>" style="color:#fff;text-decoration:none;">我的消费记录</a><?php endif; ?>

    </div>

</footer>
<input id="xl" value="1" type="hidden">
</body>

<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">

<input type="hidden" class="price" value="1">

<input type="hidden" class="all" value="<?php echo ($count); ?>">

<input type="hidden" class="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">

<input type="hidden" class="openid" value="<?php echo ($openid); ?>">

<input type="hidden" class="buyer_id" value="<?php echo ($buyer_id); ?>">

<input type="hidden" class="device_command" value="<?php echo ($device_command); ?>">

<input type="hidden" class="device_id" value="<?php echo ($device_id); ?>">

<input type="hidden" class="online_status" value="<?php echo ($online_status); ?>">

<input type="hidden" class="vehicle_pay" value="<?php echo U('CommonWeixin/car_pay_owner');?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/mui.min.js"></script>
<script src="./tpl/Wap/default/js/common.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</html>
<script>
    //充值选项
    $(".cash").click(function(){
        //$("#current_payprice").val(sv);
        var sv = $(this).attr('sv');
        $(".cash").removeClass('this');
        $(this).addClass('this');
        $('.price').val(sv);
    });
    //点击充值
    $('#wxpayid').on('click',function(){
        var online_status = $.trim($('.online_status').val());
        var openid = $.trim($('.openid').val());
        var weixin_alipay_type = $('.weixin_alipay_type').val();
        var price = $('.price').val();
        if(online_status == 0){
            alert('设备不在线，请勿充值');
            return false;
        }
        if(weixin_alipay_type == 'wechat'){
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                var jsApiParameters =  null;
                var outTradeNo =null;//商户订单号
                var isGoToPay = false;
                var vehicle_pay = $('.vehicle_pay').val();
                var device_command = $('.device_command').val();
                $.ajax({
                    type: 'POST',
                    url: vehicle_pay,
                    data: {"price":price,"openid":openid,"device_command":device_command},
                    dataType: "json",
                    timeout: 3000,
                    async:false,
                    success: function(data){
                        if(data.code == 200){
                            isGoToPay = true;
                            jsApiParameters = data.jsapi;
                            outTradeNo = data.out_trade_no;
                        }else{
                            alert(data.error);
                        }
                    },
                    error: function(xhr, type){
                        alert('支付错误,请重新支付');
                    }
                });
                //判断是否调用支付控件
                if(isGoToPay){
                    var jsPs = eval('(' + jsApiParameters + ')');
                    WeixinJSBridge.invoke(
                        'getBrandWCPayRequest',
                        jsPs ,
                        function(res){
                            if(res.err_msg == "get_brand_wcpay_request:ok" ){
                                //更新支付明细
                                var device_command = $('.device_command').val();
                                var device_id = $('.device_id').val();
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo U('CommonWeixin/car_update_owner');?>",
                                    data: {
                                        "out_trade_no":outTradeNo,
                                        "price":price,
                                        "device_command":device_command,
                                        "openid":$(".openid").val()},
                                    dataType: 'json',
                                    async:false,
                                    success: function(data){
                                        if(data.code == 200){
                                            $('#balances').html(data.msg);
                                            $('.all').val(data.msg);
                                        }else{
                                            alert('支付失败');
                                        }
                                        canUpdateRemainsum1 = true;
                                    },
                                    error: function(xhr, type){
                                        alert('支付错误，请重新支付');
                                        canUpdateRemainsum1 = true;
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
                scan_code:scan_code,
                price:price,
                device_command:device_command,
                device_id:device_id,
                buyer_id:buyer_id
            }
            post("<?php echo U('AlipayCommon/car_ali_pay_xiche');?>",datae)
        }
    })
    var clicktag = 0;
    Zepto(function($) {
        $("#launch > li").tap(function () {
            if (clicktag == 1) {
                return;
            }
            if (clicktag == 0) {
                clicktag = 1;
                setTimeout(function () {
                    clicktag = 0
                }, 1500);
            }
            var zimu = $(this).attr("cd");
            var c = $('#zimu' + zimu).html();
            var numbers = parseInt(c) + 1;
            $('#zimu' + zimu).html(numbers);
            var three = $('#zimu' + zimu).html();
            $(this).addClass('lion').siblings().removeClass('lion one');
            if (three >= 2 || $(this).is(".one") || $(this).is(".onete")) {
                var price = $(this).attr('price');
                var times = $(this).attr('times');
                var openid = $('.openid').val();
                var weixin_alipay_type = $('.weixin_alipay_type').val()
                var buyer_id = $('.buyer_id').val();
                var device_command = $('.device_command').val();
                var all = $('.all').val();
                if (all < parseInt(price)) {
                    $.dialog({
                        content:'洗额不足',
                        button:['好']
                    });
                    return false;
                }
                var countdown=60*10;
                var i = setInterval(function() {
                    if (countdown == 0) {
                        $('.dao').removeClass('co');
                        $('.dao').html("");
                        countdown = 60;
                        clearInterval(i);
                        return;
                    } else {
                        $('.dao').addClass('co');
                        $('.dao').html("还剩（"+countdown+"）秒");
                        countdown--;
                    }
                },1000);
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
                $.post("<?php echo U('Start/car_start_pay');?>", dat, function (data) {
                    if (data.code == 200) {
                        alert(data.msg);
                        $('#balances').html(data.count);
                        $('.all').val(data.count);
                    } else {
                        alert(data.msg);
                    }
                    $(".Rose_xyj_staty .botton2").removeClass('on');
                    el.hide();
                }, 'json');
            } else if (three == 1) {
                $(this).siblings().children('tt').html(0);
            }
        });
    });
</script>