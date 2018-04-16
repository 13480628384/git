<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html manifest="" lang="ch"><head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>热爱天然自助洗车联盟</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=640, user-scalable=yes, target-densitydpi=device-dpi">

    <link rel="stylesheet" href="./tpl/Wap/default/css/wechat.css">

    <link rel="stylesheet" href="./tpl/Wap/default/css/mui.min.css">

    <link rel="stylesheet" href="./tpl/Wap/default/css/play_cd1.css">

    <style>

        *{box-sizing: initial;}

        .sqcdq li{    margin-top: 40px;

            margin-bottom: 40px;

            height: 75px;

            line-height: 75px;}

    </style>

</head>

<body style="height:auto;">

<header style="background: #00a098">

    <aside>

        洗额<b><span id="balances"><?php echo ($count); ?></span></b>

    </aside>

    <div class="feilei">

        <span class="cash this" sv="1">1元</span>
        <span class="cash" sv="3">3元</span>
        <span class="cash" sv="6">6元</span>
        <span class="cash" sv="100">100元</span>
        <span class="cash" sv="200">200元</span>
        <div id="wxpayid" style="display: inline-block;margin-top: 10px;margin-left: 30px;"><b>充值</b>	</div>

    </div>

</header>

<div class="xlts" style="height:70px;      background: #fff;  line-height: 70px;text-align: center;padding-left: 0;color:#ea7215">
    <img src="./tpl/Wap/default/img/xiaoshou.png" width="80" height="35" style="vertical-align:middle;">
    按此处进入新版本</div>

<ul id="launch" class="sqcdq">

    <li dcid="3264608" price="<?php echo ($out["0"]); ?>" cd="A" class="lion"><tt id="zimuA" class="z1" style="display: none">0</tt><?php echo ($out["0"]); ?>元洗<?php echo ($out["1"]); ?>分钟</li>

</ul>

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

<input type="hidden" class="vehicle_pay" value="<?php echo U('CommonWeixin/car_pay');?>">

<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>

<script type="text/javascript" src="./tpl/Wap/default/js/mui.min.js"></script>

<script src="./tpl/Wap/default/js/common.js"></script>

</html>

<script>
    $('.xlts').click(function(){
        var weixin_alipay_type = $('.weixin_alipay_type').val();
        if(weixin_alipay_type == 'wechat'){
            window.location.href="<?php echo U('Car_Wash_Pay/index',array('openid'=>$openid,'scan_code'=>$scan_code));?>";
        }else{
            window.location.href="<?php echo U('Car_Wash_Pay/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code));?>";
        }
    })
    //充值选项

    $(".cash").click(function(){

        $("#current_payprice").val(sv);

        var sv = $(this).attr('sv');

        $(".cash").removeClass('this');

        $(this).addClass('this');

        $('.price').val(sv);

    });

    //点击充值

    $('#wxpayid').on('click',function(){
        alert('请到新版本消费');
        return false;
        var weixin_alipay_type = $('.weixin_alipay_type').val();
        if(weixin_alipay_type == 'wechat'){
            window.location.href="<?php echo U('Car_Wash_Pay/index',array('openid'=>$openid,'scan_code'=>$scan_code));?>";
        }else{
            window.location.href="<?php echo U('Car_Wash_Pay/index',array('buyer_id'=>$buyer_id,'scan_code'=>$scan_code));?>";
        }
        return false;
        var online_status = $.trim($('.online_status').val());

        var openid = $.trim($('.openid').val());


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

                                //WeixinJSBridge.log(res.err_msg);

                                if(res.err_msg == "get_brand_wcpay_request:ok" ){

                                    //更新支付明细

                                    var device_command = $('.device_command').val();

                                    var device_id = $('.device_id').val();

                                    $.ajax({

                                        type: 'POST',

                                        url: "<?php echo U('CommonWeixin/car_update');?>",

                                        data: {

                                            "out_trade_no":outTradeNo,

                                            "price":price,

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

            post("<?php echo U('AlipayCommon/car_ali_pay');?>",datae)

        }

    })

    //点击充电

    $("#launch > li").click(function(){

        var zimu=$(this).attr("cd");

        var c = $('#zimu'+zimu).html();

        var numbers = parseInt(c)+1;

        $('#zimu'+zimu).html(numbers);

        var three = $('#zimu'+zimu).html();

        $(this).addClass('lion').siblings().removeClass('lion one');

        if(three>=2 || $(this).is(".one") ||$(this).is(".onete")){

            var price = $(this).attr('price');

            var times = $(this).attr('times');

            var openid = $('.openid').val();

            var weixin_alipay_type = $('.weixin_alipay_type').val();

            var buyer_id = $('.buyer_id').val();

            var device_command = $('.device_command').val();

            var all = $('.all').val();

            if(all<parseInt(price)){

                alert('余额不足');

                return false;

            }

            var device_id = $('.device_id').val();

            var dat = {

                weixin_alipay_type:weixin_alipay_type,

                openid:openid,

                times:times,

                price:price,

                buyer_id:buyer_id,

                device_id:device_id,

                device_command:device_command

            };

            $.post("<?php echo U('Start/car_start');?>",dat,function(data){

                if(data.code == 200){

                    alert(data.msg);

                    $('#balances').html(data.count);

                    $('.all').val(data.count);

                } else {

                    alert(data.msg);

                }

                $(".Rose_xyj_staty .botton2").removeClass('on');

            },'json');

            //mui.toast("已经选中，点击即启动");

        }else if(three==1){

            //mui.toast("第一次选中");

            $(this).siblings().children('tt').html(0);

        }

    });

</script>