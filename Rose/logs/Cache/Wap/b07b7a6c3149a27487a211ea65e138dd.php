<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html manifest="" lang="ch">
<head>
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>玫瑰智能设备遥控器</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
    <link rel="stylesheet" href="./tpl/Wap/default/css/play.css">
    <style>.call_wx{clear:both; font-size: 24px; line-height: 40px; width: 600px; color: #00A098; margin:auto;text-align: center;}
    .width750 .botton2{ width: 450px; height: 100px; margin:auto;
        background-image: url("./img/anm2.png");
        background-repeat: no-repeat;
        background-position: center center;
        margin-bottom:50px;
        margin-top:50px;
        text-align: center;
        font-size: 3rem;
        font-family: 微软雅黑;
        line-height: 65px;
    }
    #launch .botton2 {
        width: 450px;
        height: 100px;
        margin: auto;
        background: url(./tpl/Wap/default/img/anm2.png);
        background-repeat: no-repeat;
        background-position: center center;
        margin-bottom: 50px;
        margin-top: 50px;
        text-align: center;
        font-size: 32px;
        font-family: 微软雅黑;
        line-height: 65px;
    }
    </style>
</head>
<!--加的-->
<body style="height:auto;">
<!--加的-->
<a href="#"><img src="./tpl/Wap/default/img/juicer.jpg" width="100%" height="290px"></a>
<div style="height: 20px"></div>
<!--<header style="background: #4D9ADA">
    <aside>
        余额<b><span class="count"><?php echo ($count); ?></span></b>
    </aside>
    <div class="feilei">
        <span class="cash this" price="1">1元</span>
        <span class="cash" price="2">2元</span>
        <span class="cash" price="5">5元</span>
        <span class="cash" price="10">10元</span>
        <span class="cash" price="20">20元</span>
        <span class="cash" price="50">50元</span>
        <span class="cash" price="100">100元</span>
        <div class="weixinpay" style="display: inline-block;margin-top: 10px;margin-left: 30px;"><b>点击充值</b>	</div>
    </div>
    <p style="
    clear: both;
    font-size: 22px;
    position: absolute;
    margin-top: 155px;
    left: 10px;
    color: #fff;
">选择模式 点击启动</p>
</header>-->
<div style="height: 5px;text-align:center" class="paying"></div>
<section style="margin-top: 10px;">
    <ul id="launch" style=" padding-bottom:100px; text-align: center;" class="Rose_xyj_staty">
        <div class="botton2" times="<?php echo ($out["1"]); ?>" price="<?php echo ($out["0"]); ?>" cd="A">
            支付<?php echo ($out["0"]); ?>元 <?php echo ($out["1"]); ?>杯
            <tt id="zimuA" class="z1" style="display: none">0</tt>
        </div>
        <div class="botton2" times="<?php echo ($out["3"]); ?>" price="<?php echo ($out["2"]); ?>" cd="B">
            支付<?php echo ($out["2"]); ?>元 <?php echo ($out["3"]); ?>杯
            <tt id="zimuB" class="z1" style="display: none">0</tt>
        </div>
        <?php if($out['4'] != null): ?><div class="botton2" times="<?php echo ($out["5"]); ?>" price="<?php echo ($out["4"]); ?>" cd="B">
            支付<?php echo ($out["4"]); ?>元 <?php echo ($out["5"]); ?>杯
            <tt id="zimuC" class="z1" style="display: none">0</tt>
        </div><?php endif; ?>
    </ul>
</section>
<div style="clear:both; height:100px;"></div>
<footer>
    <div class="menu" style="position: relative;">
        <b style="font-size:30px;color:#f00;margin-bottom:-21px;display:block;">
            <a href="<?php echo U('JuicerSubPay/user_unfund',array('openid'=>$openid,'scan_code'=>$scan_code,'area_id'=>$ju_device_info_detail['area_id']));?>" style="color:#F00">申请退款</a>
            <?php if($weixin_alipay_type == wechat): ?><a href="<?php echo U('WeixinUserConsume/juicer_index',array('openid'=>$openid));?>" style="color:#F00">我的消费记录</a>
                <?php else: ?>
                <a href="<?php echo U('WeixinUserConsume/juicer_alipay_index',array('buyer_id'=>$buyer_id));?>" style="color:#F00">我的消费记录</a><?php endif; ?>
        </b>
    </div>
</footer>
</body>
<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" class="price" value="1">
<input type="hidden" class="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="buyer_id" value="<?php echo ($buyer_id); ?>">
<input type="hidden" class="di_id" value="<?php echo ($ju_device_info_detail["id"]); ?>">
<input type="hidden" class="washing_pay" value="<?php echo U('JuicerSubPay/weixin_pay');?>">
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/common.js"></script>
<script>
    var clicktag=0;
    /*
     * ====================================
     * @param 微信支付宝支付
     * @param online_status 在线离线
     * */
    $('.Rose_xyj_staty .botton2').click(function(){
        var openid = $.trim($('.openid').val());
        var di_id = $('.di_id').val();
        var price = $(this).attr('price');
        var times = $(this).attr('times');
        var buyer_id = $('.buyer_id').val();
        var weixin_alipay_type = $('.weixin_alipay_type').val();
        if (clicktag == 1) {
            return;
        }
        if (clicktag == 0) {
            clicktag = 1;
            setTimeout(function () {
                clicktag = 0
            }, 1500);
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
                var washing_pay = $('.washing_pay').val();
                $('.paying').html('正在支付...');
                $.ajax({
                    type: 'POST',
                    url: washing_pay,
                    data: {"price":price,"openid":openid,"di_id":di_id},
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
                                    $('.paying').html('');
                                    $.post("<?php echo U('JuicerSubPay/update');?>",{
                                        "out_trade_no":outTradeNo,
                                        "price":price,
                                        "di_id":di_id,
                                        "openid":openid,
                                        "times":times
                                    },function(data){
                                        $('.paying').html('');
                                        if(data.code == 200){
                                            alert(data.msg);
                                        }else{
                                            alert(data.msg);
                                        }
                                    },'json')
                                }
                            }
                    );
                }
            }
        } else {
            var datae = {
                price:price,
                di_id:di_id,
                times:times,
                buyer_id:buyer_id
            }
            post("<?php echo U('JuicerPay/alipay');?>",datae)
        }
    });
    $('.cash').click(function(){
        $(".cash").removeClass('this');
        $(this).addClass('this');
        $('.price').val(($(this).attr('price')));
    });
    $(".feilei .cash").first().addClass('this');
</script>
</html>