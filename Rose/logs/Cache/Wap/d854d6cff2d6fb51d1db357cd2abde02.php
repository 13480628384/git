<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>中移物联终端</title>
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/Rose2_index_style.css">
    <style>
        .footer_vend{
            height: 4rem;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #dedada;
            font-size: 16px;
        }
        .footer_vend li{
            width: 33%;
            text-align: center;
            float: left;
            line-height: 4rem;
        }
        .active{
            background-color: #30BF75;
            color:#fff;
        }
    </style>
</head>
<body>
<div class="web_v2">
    <div class="heard_con">
        <div class="clear"></div>
        <ul>
            <li class="heard_con_li">
                <a href="<?php echo U('weixin_all');?>">
                    <p class="heard_con_li_span1 " ><?php echo ($weixin_count); ?></p>
                    <p class="heard_con_li_span2" style="margin-bottom: 1.5rem">微信收益</p>
                </a>
            </li>
            <li>
                <a href="">
                    <p class="heard_con_li_span1"><?php echo ($alipay_count); ?></p>
                    <p class="heard_con_li_span2" style="margin-bottom: 1.5rem">支付宝收益</p>
                </a>
            </li>
            <li class="heard_con_li heard_con_li1">
                <a href="">
                    <p class="heard_con_li_span1"><?php echo ($weixin_rose_count); ?></p>
                    <p class="heard_con_li_span2">微信玫瑰收益</p>
                </a>
            </li>
            <li class="heard_con_li1">
                <a href="">
                    <p class="heard_con_li_span1"><?php echo ($alipay_rose_count); ?></p>
                    <p class="heard_con_li_span2">支付宝玫瑰收益</p>
                </a>
            </li>
            <div class="clear"></div>
        </ul>
        <div class="clear"></div>
    </div>
    <ul class="deviceli">
        <!--<li><a href="<?php echo U('Wallet');?>">
            <img src="./tpl/Wap/default/img/a1.png" alt=""><span>钱包</span>
        </a></li>-->
        <li><a href="<?php echo U('device_list');?>">
            <img src="./tpl/Wap/default/img/a2.png" alt=""><span>设备管理</span>
        </a></li>
        <li><a href="<?php echo U('personal');?>">
            <img src="./tpl/Wap/default/img/a2.png" alt=""><span>个人信息</span>
        </a></li>
        <li><a href="<?php echo U('statistical');?>">
            <img src="./tpl/Wap/default/img/a2.png" alt=""><span>收益统计</span>
        </a></li>
        <li><a href="<?php echo U('shop_manage');?>">
            <img src="./tpl/Wap/default/img/a1.png" alt=""><span>商品管理</span>
        </a></li>
       <div class="clear"></div>
    </ul>
</div>
<div class="footer_vend">
    <ul>
        <li class="active">首页</li>
        <li><a href="<?php echo U('device_list');?>">设备管理</a></li>
        <li><a href="<?php echo U('personal');?>">个人信息</a></li>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
</body>
</html>