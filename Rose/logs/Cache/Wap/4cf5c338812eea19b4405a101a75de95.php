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
                            <option value="W">微信和支付宝</option>
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
                            <input type="text" value="" pattern="[0-9]*" id="reg_phone"  placeholder="请输入手机号码" >
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
<input type="hidden" class="cash_money" value="<?php echo U('cash_money');?>">
<input type="hidden" class="tixian_money" value="<?php echo U('tixian_money');?>">
<input type="hidden" class="Present_location" value="<?php echo U('Present');?>">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="arral" value="0">
<script type="text/javascript" src="./tpl/Wap/default/js/zepto.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/frozen.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/cash.js?6"></script>
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
</script>
</html>