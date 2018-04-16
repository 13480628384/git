<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html manifest="" lang="ch">
<head>
    <meta charset="utf-8"/>
    <meta name="apple-touch-fullscreen" content="YES">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="pragram" content="no-cache">
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <title>深圳市富捷电子科技有限公司</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=640, user-scalable=yes, target-densitydpi=device-dpi">
    <link rel="stylesheet" href="./tpl/Wap/default/css/play.css">
    <style>.call_wx{clear:both; font-size: 24px; line-height: 40px; width: 600px; color: #00A098; margin:auto;text-align: center;}</style>
</head>
<!--加的-->
<body style="height:auto;">
<!--加的-->
<a href="#"><img src="./tpl/Wap/default/img/banner2.png" width="100%"></a>
<div style="height: 20px"></div>
<header style="background: #298FE8">
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
</header>
<div style="height: 5px;"></div>
<section style="margin-top: 10px;">
    <!-- <h2 style="   
    font-size: 24px;
    color: #00A098;
    text-align: center;
    margin-bottom: 0;
    margin-top: 10px;">排队连续使用时,请间隔1分钟后启动。</h2> -->
    <!--加的-->
    <ul id="launch" style=" padding-bottom:100px" class="Rose_xyj_staty">
        <!--加的-->
        <li class="lion" times="<?php echo ($out["1"]); ?>" price="<?php echo ($out["0"]); ?>" cd="A">
            <tt id="zimuA" class="z1" style="display: none">0</tt>
            <div>单脱水<br><?php echo ($out["0"]); ?>元<br><?php echo ($out["1"]); ?>分钟</div></li>
        <li times="<?php echo ($out["3"]); ?>" price="<?php echo ($out["2"]); ?>" cd="B">
            <tt id="zimuB" class="z1" style="display: none">0</tt><div>快洗<br><?php echo ($out["2"]); ?>元<br><?php echo ($out["3"]); ?>分钟</div></li>
        <li times="<?php echo ($out["5"]); ?>" price="<?php echo ($out["4"]); ?>" cd="C">
            <tt id="zimuC" class="z1" style="display: none">0</tt><div>标准洗<br><?php echo ($out["4"]); ?>元<br><?php echo ($out["5"]); ?>分钟</div></li>
        <li times="<?php echo ($out["7"]); ?>" price="<?php echo ($out["6"]); ?>" cd="D">
            <tt id="zimuD" class="z1" style="display: none">0</tt><div>大物洗<br><?php echo ($out["6"]); ?>元<br><?php echo ($out["7"]); ?>分钟</div></li>
    </ul>
</section>
 <p class="call_wx">
<a href="tel:<?php echo ($phone); ?>">本机服务电话:<?php echo ($phone); ?></a>
     <a href="<?php echo U('Userefund/index',array('openid'=>$openid,'total'=>$count,'device_command'=>$device_command));?>">我要退款</a>
</p>

<div style="clear:both; height:100px;"></div>
<footer>
    <div class="menu" style="position: relative;">
        <b style="font-size:30px;color:#fff;margin-bottom:-21px;display:block;">
           <?php if($weixin_alipay_type == wechat): ?><a href="<?php echo U('WeixinUserConsume/index',array('openid'=>$openid));?>" style="color:#F00">我的消费记录</a>
           <?php else: ?>
               <a href="<?php echo U('WeixinUserConsume/alipay_index',array('buyer_id'=>$buyer_id));?>" style="color:#F00">我的消费记录</a><?php endif; ?>
            <a href="./tpl/Wap/default/Washing_shuoming.html" style="color:#F00">使用说明</a>
        </b>
    </div>
</footer>
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
<input type="hidden" class="washing_pay" value="<?php echo U('CommonWeixin/washing_pay');?>">
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/common.js"></script>
<script>
    $(document).ready(function(){
        /*$(".Rose_xyj_money li").click(function() {
            $(this).addClass('hover').siblings().removeClass('hover');
            $('.price').val(($(this).attr('price')));
        });*/
        //启动
        $(".Rose_xyj_staty li").click(function() {
            var zimu=$(this).attr("cd");
            var c = $('#zimu'+zimu).html();
            var numbers = parseInt(c)+1;
            $('#zimu'+zimu).html(numbers);
            var three = $('#zimu'+zimu).html();
            $(this).addClass('lion').siblings().removeClass('lion one');
            if(three>=2 || $(this).is(".one") ||$(this).is(".onete")){
            $(this).addClass('lion').siblings().removeClass('lion');
            if($(this).hasClass('on')){
                return false;
            }
            //lio$(this).addClass('on');
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
            $.post("<?php echo U('AlipayCommon/start');?>",dat,function(data){
                if(data.code == 200){
                    alert(data.msg);
                    $('.count').html(data.count);
                } else {
                    alert(data.msg);
                }
                $(".Rose_xyj_staty li").removeClass('on');
            },'json');
            }else if(three==1){
                //如果第一次点击的时候当前点击的类以及有lion
                //alert("第一次选中");
                $(this).siblings().children('tt').html(0);
            }
        });
    });
    var clicktag=0;
    $('.weixinpay').click(function(){
        var online_status = $.trim($('.online_status').val());
        var openid = $.trim($('.openid').val());
        var weixin_alipay_type = $('.weixin_alipay_type').val();
        var price = $('.price').val();
        /*if(online_status == 0){
         alert('设备不在线');
         return false;
         }*/
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
                $.ajax({
                    type: 'POST',
                    url: washing_pay,
                    data: {"price":price,"openid":openid},
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
                                        url: "<?php echo U('CommonWeixin/washing_update');?>",
                                        data: {
                                            "out_trade_no":outTradeNo,
                                            "price":price,
                                            "openid":$(".openid").val()},
                                        dataType: 'json',
                                        async:false,
                                        success: function(data){
                                            if(data.code == 200){
                                                $('.count').html(data.msg);
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
            post("<?php echo U('AlipayCommon/washing_pay');?>",datae)
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