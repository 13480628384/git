<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <title>个人信息</title>
    <style>
        .click_pic{ margin-top: 1.5rem !important; margin-bottom: 1.5rem; }
        .anniu{ font-size: 1.8rem; }
        .del_vip .del_li{height: 5rem;}
        .user-information {
            background-color: #30bf75;
            width: 100%;
            min-height: 120px;
            position: relative;
        }
        .user-information .user-imgs {
            text-align: center;
            padding: 10px 0 8px;
        }
        .user-information .user-imgs img {
            width: 70px;
            height: 70px;
            border-radius: 70px;
            border: 1px solid #fff;
        }
        .user-information .user-name {
            text-align: center;
            color: #fff;
        }
        .user-information .user-level {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #710028;
            font-size: 12px;
        }
        .user-money {
            background-color: #fff;
            height: 70px;
            border-bottom: 1px solid #d5d5d5;
        }
        .user-money a {
            width: 25%;
            float: left;
            text-align: center;
            text-decoration: none;
            margin: 10px 0px;
            padding: 2px 0;
            border-left: 1px solid #d5d5d5;
            margin-left: -1px;
        }
        .user-money a strong {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .user-other {
            margin-bottom: 80px;
            border-bottom: 1px solid #d5d5d5;
        }
        .user-other a {
            width: 50%;
            float: left;
            background-color: #fff;
            text-align: center;
            text-decoration: none;
            padding: 10px 0px;
            border-left: 1px solid #d5d5d5;
            margin-left: -1px;
            color: #333;
        }
        .online{
            font-size: 16px;
            font-weight: 500;
        }
        .user-common {
            background-color: #fff;
            margin: 0 0 10px;
            border-bottom: 1px solid #d5d5d5;
            border-top: 1px solid #d5d5d5;
        }
        .user-common a,.password {
            display: block;
            line-height: 26px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
            padding: 10px 15px;
            position: relative;
        }
        .user-common .hr {
            margin: 0 10px;
        }
        .user-common .yingyongnum, .user-common .infor-centernum, .user-common .help-tel, .user-common .infor-phone {
            position: absolute;
            right: 30px;
            top: 12px;
        }
        .user-common a em {
            display: block;
            position: absolute;
            background: url(http://r.ytrss.com/mobile/img/bai-12.png) 0 0/100% no-repeat;
            width: 8px;
            height: 14px;
            top: 17px;
            right: 10px;
        }
        .hr {
            display: block;
            height: 1px;
            background-color: #d5d5d5;
        }
        .footer_vend{
            height: 4rem;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #dedada;
            font-size: 16px
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
<body style="background: #f2f2f2">
<div class="user-information">
    <div class="user-imgs">
        <img class="imgs" src="http://r.ytrss.com/mobile/img/user-head.png" />
    </div>
    <p class="user-name"><?php echo ($rose["name"]); ?></p>
    <p class="user-level"></p>
</div>
<div class="user-money cf">
    <a href="javascript:void(0)" class="jifen">
        <strong><?php if($today_total == 0): ?>0<?php else: echo ($today_total); endif; ?></strong><span>今日收入</span></a>
    <a href="javascript:void(0)" class="yinyuan"><strong><?php if($month_total == 0): ?>0<?php else: echo ($month_total); endif; ?></strong><span>当月收入</span></a>
    <a href="javascript:void(0)" class="yue"><strong>
        <?php if($my_total == 0): ?>0<?php else: echo ($my_total); endif; ?>
    </strong><span>余额</span></a>
    <a href="javascript:void(0)" class="ytcard"><strong>
        <?php if($one_device['count'] == 0): ?>0<?php else: echo ($one_device["count"]); endif; ?></strong><span><?php echo ($scan_code); ?>单个设备</span></a>
</div>
<div class="user-other cf">
    <a href="javascript:void(0)" class="shoucang"><span><i style="color: #26ad12">●</i> 在线设备<p class="online">
            <?php if(empty($online[1]['count'])){echo '0';}else{ echo ($online["1"]["count"]); } ?>台</p></span></a>
    <a href="javascript:void(0)" class="look-jilu"><span><i style="color: #f00">●</i> 离线设备<p class="online">
            <?php if(empty($online[0]['count'])){echo '0';}else{ echo ($online["0"]["count"]); } ?>台</p></span></a>
</div>
<div class="user-common">
    <a class="youhuiquan" href="<?php echo U('panding');?>">设备收入排行榜<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <a class="youhuiquan" href="<?php echo U('order');?>">本月微信支付单<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <div class="youhuiquan password" href="<?php echo U('password');?>">修改登录密码<div class="yingyongnum"><span class="pink"></span></div><em></em></div>
    <div class="hr"></div>
    <a class="youhuiquan" href="<?php echo U('logouts');?>">退出登录<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>

</div>

<div class="Mask" id="reform">
    <div class="Mask-content Mask-gai">
        <div class="Mask-title">请输入新密码</div>
        <div class="Mask-from">
            <div class="Mask-input date  update">
                <input type="text" maxlength="10" value="" class="update_username"  pattern="[0-9a-z]*">
            </div>
            <span style="margin-left:85px;color:#f00;" class="now-name" ></span>
        </div>
        <div class="Mask-btn">
            <div class="Mask-tab-btn removeclass">取消</div>
            <div class="Mask-tab-btn addclass check">确定</div>
        </div>
    </div>
</div>
<div class="space-10"></div>
<div class="footer_vend">
    <ul>
        <li><a href="<?php echo U('index');?>">首页</a></li>
        <li><a href="<?php echo U('device_list');?>">设备管理</a></li>
        <li class="active">个人信息</li>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>

</body>
<script>
    $('.password').click(function(){
        $("#reform").addClass('is-visible');
    });
    $('.removeclass').click(function(){
        if($(this).hasClass('re')){
            return false;
        }
        $('.now-name').html('');
        $("#reform").removeClass('is-visible');
    });
    //密码修改
    $('.check').click(function(){
        $('.now-name').parent('.Mask-from').next('.Mask-btn').find('.removeclass').addClass('re');
        if ($('.now-name').hasClass('add')) {
            return false;
        }
        var update_username = parseInt($('.update_username').val());
        if (!update_username) {
            $('.now-name').html('请输入新密码');
            return false;
        }
        $('.now-name').html('请稍候...').addClass('add');
        $.post("<?php echo U('update_password');?>",{password:update_username}, function (data) {
            $('.now-name').parent('.Mask-from').next('.Mask-btn').find('.removeclass').removeClass('re');
            if (data.code == 200) {
                $('.now-name').css('color', '#7CCC6F');
                $("#reform").removeClass('is-visible');
                $('.now-name').html('');
                window.location.href=data.url;
            } else {
                $('.now-name').css('color', '#7CCC6F');
                $("#reform").removeClass('is-visible');
                $('.now-name').html('');
            }
        }, 'json');
    })
</script>
</html>