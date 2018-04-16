<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>玫瑰物联</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="./tpl/Wap/default/css/personal/sm.min.css" />
    <link rel="stylesheet" type="text/css" href="./tpl/Wap/default/css/personal/sm-extend.min.css" />
    <link rel="stylesheet" type="text/css" href="./tpl/Wap/default/css/personal/style.css" />
    <link rel="stylesheet" type="text/css" href="./tpl/Wap/default/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="./tpl/Wap/default/css/mobi.css" />
    <style>
        .presd{
            color: #000 !important;
            float: right;
            height: 3rem;
            line-height: 3rem;
            margin-right: 1rem;
        }
        .head-lf {
            display: block;
            height: 1rem;
            width: 1rem;
            border-top: 2px solid #000;
            border-left: 2px solid #000;
            position: absolute;
            top: 1rem;
            left: 1rem;
            transform: rotate(-47deg);
            -ms-transform: rotate(-47deg);
            -moz-transform: rotate(-47deg);
            -webkit-transform: rotate(-47deg);
            -o-transform: rotate(-47deg);
        }
    </style>
</head>
<body>
<header class="bar bar-nav sy1s" style="height: 3rem; line-height: 3rem">
    <h1 class="title" style="color:#fff;">
        <a href="javascript:history.back(-1)" class="head-lf"></a>
        <a href="<?php echo U('Present',array('openid'=>$openid));?>" class="presd">提现记录</a>
    </h1>
</header>
<div class="content">
    <div class="list-block">
        <ul>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">提现方式：</div>
                        <select class="outway" id="payType" disabled="true">
                            <option value="W">微信</option>
                        </select>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">提现：</div>
                        <div class="item-input">
                            <input type="text" value="" id="amount"  placeholder="￥提现金额"  onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" >
                            余额<span id="outtip">0.00</span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">手续费：</div>
                        <div class="item-input">
                            <input type="text" value="￥0.00" readonly="readonly" class="Counter">
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">实际到账:</div>
                        <div class="item-input">
                            <input type="text" value="￥0.00" readonly="readonly" class="Actual">
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">手机号码：</div>
                        <div class="item-input">
                            <input type="text" value="<?php echo ($res["phone"]); ?>" readonly="readonly" pattern="[0-9]*" id="reg_phone"  placeholder="请输入手机号码" >
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-media"><i class="icon icon-form-name"></i></div>
                    <div class="item-inner">
                        <div class="item-title label">验证码：</div>
                        <div class="item-input">
                            <input type="text" value="" pattern="[0-9]*" id="code"  placeholder="请输入验证码" >
                            <button class="web_reg_button code">获取验证码</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <p class="text-center"><span class="color-warning">提示：</span>微信收取千分之六手续费<br/>10元以上收取手续费（包括10元）</p>
    <div class="content-block">
        <div class="row">
            <div class="col-100 addBtn button button-big button-fill button-success">提现</div>
        </div>
        <br/>
    </div>
    <div style="height:80px;"></div>
</div>
</body>
<input type="hidden" id="shortmessage" value="<?php echo U('shortmessage');?>">
<input type="hidden" class="cash_money" value="<?php echo U('fu_cash');?>">
<input type="hidden" class="tixian_money" value="<?php echo U('tixian_money');?>">
<input type="hidden" class="Present_location" value="<?php echo U('Present');?>">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="arral" value="0">
<script type="text/javascript" src="./tpl/Wap/default/js/zepto.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/frozen.js"></script>
<script>
    $("#amount").keyup(function(){
        if ($("#payType").val() == "W"){
            var amount = $("#amount").val();
            if(amount>=10){
                $(".Counter").val("￥"+ parseFloat(amount*0.006).toFixed(2));
                $(".Actual").val("￥"+ (amount - parseFloat(amount*0.006).toFixed(2)));
                $('.arral').val(amount - parseFloat(amount*0.006).toFixed(2));
            }else{
                $(".Counter").val("￥0.00");
                $(".Actual").val("￥0.00");
            }
        }
    });
    Zepto(function($){
        var REG = {
            name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
            phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
            passwd:/^[0-9]{6,8}$/,
            id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/
        }
        //验证码
        $('.code').tap(function(){
            if($('.code').hasClass('co')){
                return false;
            }
            var reg_phone=$('#reg_phone').val();
            var shortmessage=$('#shortmessage').val();
            if(reg_phone==''){
                $.dialog({
                    content:'手机号不能为空',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(reg_phone)){
                $.dialog({
                    content:'请输入正确的手机号码',
                    button:['好']
                });
                return false;
            }
            var countdown=60;
            var i = setInterval(function() {
                if (countdown == 0) {
                    $('.code').removeClass('co');
                    $('.code').html("获取验证码");
                    countdown = 60;
                    clearInterval(i);
                    return;
                } else {
                    $('.code').addClass('co');
                    $('.code').html("重新发送（"+countdown+"）");
                    countdown--;
                }
            },1000);
            $.post(shortmessage,{phone:reg_phone},function(data){
                if(data.code==200){
                    $.dialog({
                        content:'验证码已发出，请注意查收',
                        button:['好']
                    });
                    return false;
                }else if(data.code == 500){
                    $.dialog({
                        content:data.error,
                        button:['好']
                    });
                    return false;
                } else {
                    $.dialog({
                        content:'发送失败',
                        button:['好']
                    });
                    return false;
                }
            },'json')
        })
        //验证码
        var cash_money = $('.cash_money').val();
        var openid = $('.openid').val();
        $.ajax({
            type:"POST",
            dataType:'json',
            data:{openid:openid},
            url:cash_money,
            success:function(res){
                if (res.result==200){
                    if(res.reg == null){
                        $("#outtip").text(0);
                    }else{
                        $("#outtip").text(parseInt(res.reg));
                    }
                }else{
                    $("#outtip").text(0);
                }
            },
            error:function(){
                var DG=$.dialog({
                    content:'加载余额错误',
                    button:['好']
                });
            }
        });
        $('.addBtn').tap(function(){
            //if(confirm("确定要提现吗")) {
            if ($(this).hasClass('on')) {
                return false;
            }
            var tixian_money = $('.tixian_money').val();
            var amounts = $('#amount').val();
            var openid = $('.openid').val();
            var arral = $('.arral').val();
            var outtip = $('#outtip').text();
            var reg = /^[\u4e00-\u9fa5]+$/;
            var myreg = /.*\..*/;
            var code = $("#code").val();
            var reg_phone = $('#reg_phone').val();
            if (amounts == "") {
                $.dialog({
                    content: '请输入提现金额',
                    button: ['好']
                });
                return;
            }
            if (reg.test(amounts)) {
                $.dialog({
                    content: '请输入数字',
                    button: ['好']
                });
                return;
            }
            var amount = parseInt(amounts);
            if (myreg.test(amounts)) {
                $.dialog({
                    content: '请输入整数',
                    button: ['好']
                });
                return;
            }
            if (!REG.phone.test(reg_phone)) {
                $.dialog({
                    content: '请输入正确的手机号码',
                    button: ['好']
                });
                return false;
            }
            if (code == '') {
                $.dialog({
                    content: '手机验证码不能为空',
                    button: ['好']
                });
                return false;
            }

            if (amount >= 10) {
                var fact = amount - parseFloat(amount * 0.006).toFixed(2);//实际到账
                if (outtip < fact) {
                    $.dialog({
                        content: '余额不足',
                        button: ['好']
                    });
                    return;
                }
            } else {
                if (outtip < amount) {
                    $.dialog({
                        content: '余额不足',
                        button: ['好']
                    });
                    return;
                }
            }
            if (amount > 20000) {
                $.dialog({
                    content: '最高提现金额2万元',
                    button: ['好']
                });
                return;
            }
            $(this).addClass('on');
            var Present = $('.Present_location').val();
            var el = $.loading({
                content: '正在提现'
            });
            $.post(tixian_money, {amount: amount, openid: openid, arral: arral, code: code}, function (res) {
                $('.addBtn').removeClass('on');
                if (res.result_code == 'SUCCESS') {
                    var DG = $.dialog({
                        content: res.return_msg,
                        button: ['好']
                    });
                    $("#outtip").text(res.return_ext);
                    el.hide();
                    DG.on('dialog:action', function (e) {
                        document.location.href = Present + "&openid=" + openid;
                    });
                    return;
                } else if (res.result_code == 3) {
                    $.dialog({
                        content: res.return_msg,
                        button: ['好']
                    });
                    el.hide();
                    return;
                } else if (res.result_code == 4) {
                    $.dialog({
                        content: res.return_msg,
                        button: ['好']
                    });
                    el.hide();
                    return;
                } else {
                    $.dialog({
                        content: res.return_msg,
                        button: ['好']
                    });
                    el.hide();
                    return;
                }
            }, 'json')
            //}
        })
    })
</script>
</html>