<?php
    header("Content-type: text/html; charset=utf-8");
    $typesd = $_SERVER['HTTP_USER_AGENT'] ;
    if(strpos($typesd,'AlipayClient')>0){
        header("Location:http://wxpay.roseo2o.com/alipayscan/game_test/anm.php?scan_code=".$_GET['scan_code']);exit;
    }
    require_once 'mysql/mysqldbread.php';
    require_once "wxpay/lib/WxPay.Api.php";
    require_once "wxpay/WxPay.JsApiPay.php";
    $appId = WxPayConfig::APPID;
    //$tools = new JsApiPay();
    //$openId = $tools->GetOpenid();
    $openId = 'odOIPv5RJwDqO94UaCbpKQvdjhLE';
    $scan_code = isset($_GET['scan_code'])?$_GET['scan_code']:null;
    if(is_null($scan_code) ){
        echo "页面参数错误";
        die;
    }
    $device_info_sql = "select * from device_info where scan_code = '$scan_code' and  device_status = '1' and del_flag = '0'";
    $device_info = $db->get_row($device_info_sql);
    if(is_null($device_info) ){
        echo "页面参数错误";
        die;
    }
    $select_groupid_sql = "select * from device_relation_group where di_id='$device_info->id' and del_flag=0 and device_type=4";
$resu = $db->get_row($select_groupid_sql);
$adv_url="http://wxpay.roseo2o.com/adv_merchant/index.php?m=WechatAttention&a=Get_User_Info&group_id="
        .$resu->dgi_id."&openid=$openId&appid=$appId&di_id=".$device_info->id;
    $new = implode('=',explode('-',$resu->ANM));
    $out = explode('=',$new);
?>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="apple-touch-fullscreen" content="YES">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi">
    <link href="css/reset.css" rel="stylesheet" type="text/css">
    <link href="css/anm_style.css" rel="stylesheet" type="text/css?v1.0.0.1">
    <title>按摩椅启动</title>
    <style>
        .fozi{border-bottom:solid 1px #00A098; margin-top:20px; padding-bottom:30px;}
        .fozi a{
            clear: both;
            color: #5FD0CA;
            line-height: 50px;
            font-size:3rem;

            display:block;

            text-decoration: none;
        }

        .helpimg{ width:100px; position:fixed; bottom:300px; right:0px;}
        .helpimg img{ width:100px;}

        li.lion1 {
            background: #ea7215;
        }
        /*添加*/
        .inputdate{width: 450px; margin:auto; text-align: center;font-size: 2rem;    font-family: 微软雅黑;}
        .inputdate input{ border:none;width:50px;  overflow:scroll;margin-top: 20px;}
        /*添加*/
    </style>
</head>
<body>
    <div class="width750">
        <img src="images/a4.png?10" alt="玫瑰云网" width="100%" class="adv">

        <div class="botton1 anm_wei_pay" dataprice="<?php echo $out[0];?>" datanum="<?php echo $out[1];?>">支付<?php echo $out[0];?>元 按摩<?php echo $out[1];?>分钟</div>
        <div class="botton2 anm_wei_pay" dataprice="<?php echo $out[2];?>" datanum="<?php echo $out[3];?>">支付<?php echo $out[2];?>元 按摩<?php echo $out[3];?>分钟</div>
        <?php if($device_info->remarks == 'test'){?>
        <div class="inputdate">
            <div class="botton3 botton2"> 租【<input type = "number" id="inputA" value = "" onkeyup="fillB()" />】天,需【
                <span id="inputB"></span>】元</div>
        </div>
        <?php }?>
        <div class="fozi" style="padding-left:3%">&nbsp;</div>
        <div class="fozi" style="padding-left:3%"><a href="http://www.roseo2o.com"><em>玫瑰物联</em> <br>商业设备物联化,运营交易平台化</a></div>
        <div class="fozi" style="padding-left:3%"><a href="http://coworking.cn"><em>众志联盟</em> <br>创建全球领先的智能开关研发制造企业</a></div>

        <h6>玫瑰物联版权所有</h6>

    </div>
</body>
<input type="hidden" id="appId" value="<?php echo $appId;?>"/>
<input type="hidden" id="openId" value="<?php echo $openId;?>"/>
<input type="hidden" id="adv_url" value="<?php echo $adv_url;?>"/>
<input type="hidden" id="device_command" value="<?php echo $device_info->device_command;?>"/>
<input type="hidden" id="device_id" value="<?php echo $device_info->id;?>"/>
<input type="hidden" id="online_status" value="<?php echo $resu->online_status;?>"/>
<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="js/msg.js?2"></script>
<script>
    /*===============定时加载设备是否在线 [[============*/
    function timimg(){
        var device_command = $('#device_command').val();
            $.ajax({
                type: 'POST',
                url: 'timing.php',
                data: {"device_command":device_command},
                dataType: 'json',
                async:false,
                success: function(data){
                    if(data.code == 200){
                        $('#online_status').val(1);
                    } else {
                        $('#online_status').val(0);
                    }
                },
                error: function(xhr, type){
                }
            });
    }
    setInterval("timimg()",1000*30);
    /*===============定时加载设备是否在线 ]]============*/
    function fillB(){
        var a=document.getElementById("inputA").value;
        document.getElementById("inputB").innerHTML=parseInt(a)*24;
    }
    $("#inputA").click(function(e) {
        e.stopPropagation();
    });
    $('.adv').click(function(){
        var adv_url = $('#adv_url').val();
        window.location.href=adv_url;
    });
    $(".botton3").click(function() {
        var price=	$("#inputB").html();
        var online_status = $('#online_status').val();;
        if(online_status==0){
            msg.alert('设备不在线');
            return false;
        }
        callpay1(price);
    });
    //是否判断可加载
    var canUpdateRemainsum = true;

    //调用微信JS api 支付
    function jsApiCall( jsApiParameters,outTradeNo,device_command,price,datanum,device_id)
    {

        var jsPs = eval('(' + jsApiParameters + ')');
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            jsPs ,
            function(res){
                //WeixinJSBridge.log(res.err_msg);
                if(res.err_msg == "get_brand_wcpay_request:ok" ){
                    //更新支付明细
                    //返回支付的汇总记录
                    $.ajax({
                        type: 'POST',
                        url: 'anm_weixin_update.php',
                        data: {"app_id":jsPs.appId,
                            "out_trade_no":outTradeNo,
                            "device_command":device_command,
                            "datanum":datanum,
                            "price":price,
                            "device_id":device_id,
                            "open_id":$("#openId").val()},
                        dataType: 'json',
                        async:false,
                        success: function(data){
                            if(data.code == 200){
                                msg.alert('按摩椅已工作');
                            }else{
                                msg.alert('按摩椅不工作');
                            }
                            canUpdateRemainsum = true;

                        },
                        error: function(xhr, type){
                            msg.alert('充值错误，请重新充值');
                            canUpdateRemainsum = true;
                        }
                    });

                }
            }
        );
    }
    var clicktag=0;
    $('.anm_wei_pay').click(function(){
        var openId = $('#openId').val();
        var appId = $('#appId').val();
        var price = $(this).attr('dataprice');
        var datanum = $(this).attr('datanum');
        var online_status = $('#online_status').val();;
        if(online_status==0){
            msg.alert('设备不在线');
            return false;
        }
        if(price=='' || datanum==''||openId==''||appId==''){
            msg.alert('支付错误，请重新扫码');
            return false;
        }
        if(clicktag==1){
            return ;
        }
        if(clicktag==0){
            clicktag=1;
            setTimeout(function () { clicktag = 0 }, 1500);
        }
        callpay(price,datanum);
    })
    function callpay(price,datanum)
    {
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
            var openId = $('#openId').val();
            var appId = $('#appId').val();
            var device_command = $('#device_command').val();
            var device_id = $('#device_id').val();
            $.ajax({
                type: 'POST',
                url: 'wxpay/anm_weixin_pay.php',
                data: {"price":price,"openId":openId},
                dataType: "json",
                timeout: 3000,
                async:false,
                success: function(data){
                    if(data.code == 200){
                        isGoToPay = true;
                        jsApiParameters = data.jsApiParameters;
                        outTradeNo = data.outTradeNo;
                        //msg.alert('充值成功，按摩椅已启动');
                    }else{
                        msg.alert('充值失败');
                    }
                },
                error: function(xhr, type){
                  msg.alert('充值错误,请重新充值');
                }
            });
            //判断是否调用支付控件
            if(isGoToPay){
                jsApiCall(jsApiParameters,outTradeNo,device_command,price,datanum,device_id);
            }


        }
    }
    function callpay1(price)
    {
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
            var openId = $('#openId').val();
            var appId = $('#appId').val();
            var device_command = $('#device_command').val();
            var device_id = $('#device_id').val();
            $.ajax({
                type: 'POST',
                url: 'wxpay/anm_weixin_pay1.php',
                data: {"price":price,"openId":openId},
                dataType: "json",
                timeout: 3000,
                async:false,
                success: function(data){
                    if(data.code == 200){
                        isGoToPay = true;
                        jsApiParameters = data.jsApiParameters;
                        outTradeNo = data.outTradeNo;
                        //msg.alert('充值成功，按摩椅已启动');
                    }else{
                        msg.alert('充值失败');
                    }
                },
                error: function(xhr, type){
                    msg.alert('充值错误,请重新充值');
                }
            });
            //判断是否调用支付控件
            if(isGoToPay){
                jsApiCall1(jsApiParameters,outTradeNo,device_command,price,device_id);
            }
        }
    }
    var canUpdateRemainsum1 = true;

    //调用微信JS api 支付
    function jsApiCall1( jsApiParameters,outTradeNo,device_command,price,device_id)
    {

        var jsPs = eval('(' + jsApiParameters + ')');
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            jsPs ,
            function(res){
                //WeixinJSBridge.log(res.err_msg);
                if(res.err_msg == "get_brand_wcpay_request:ok" ){
                    //更新支付明细
                    //返回支付的汇总记录
                    $.ajax({
                        type: 'POST',
                        url: 'anm_weixin_update1.php',
                        data: {"app_id":jsPs.appId,
                            "out_trade_no":outTradeNo,
                            "device_command":device_command,
                            "price":price,
                            "device_id":device_id,
                            "open_id":$("#openId").val()},
                        dataType: 'json',
                        async:false,
                        success: function(data){
                            if(data.code == 200){
                                msg.alert('按摩椅已工作');
                            }else{
                                msg.alert('按摩椅不工作');
                            }
                            canUpdateRemainsum1 = true;

                        },
                        error: function(xhr, type){
                            msg.alert('充值错误，请重新充值');
                            canUpdateRemainsum1 = true;
                        }
                    });

                }
            }
        );
    }
</script>
</html>

