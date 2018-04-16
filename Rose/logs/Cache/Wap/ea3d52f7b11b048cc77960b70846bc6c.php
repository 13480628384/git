<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
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
        .user-common a {
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
    </style>
</head>
<body style="background: #f2f2f2">
<div class="user-information">
    <div class="user-imgs">
        <?php if($rose['photo'] == null): ?><img class="imgs" src="http://r.ytrss.com/mobile/img/user-head.png" />
         <?php else: ?>
            <img class="imgs" src="<?php echo ($rose["photo"]); ?>" /><?php endif; ?>
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
        <?php if($one_device['count'] == 0): ?>0<?php else: echo ($one_device["count"]); endif; ?></strong><span><?php echo ($scan_code); ?></span></a>
</div>
<div class="user-other cf">
        <a href="javascript:void(0)" class="shoucang"><span><i style="color: #26ad12">●</i> 在线设备<p class="online">
            <?php if($online[0]['online_status'] == '1'){echo $online[0]['count'];} ?>
            <?php if($online[1]['online_status'] == '1'){echo $online[1]['count'];} ?>
            台</p></span></a>
        <a href="javascript:void(0)" class="look-jilu"><span><i style="color: #f00">●</i> 离线设备<p class="online">
            <?php if($online[0]['online_status'] == '0'){echo $online[0]['count'];} ?>台</p></span></a>
</div>
<div class="user-common">
    <a class="youhuiquan" href="<?php echo U('panding',array('openid'=>$openid));?>">设备收入排行榜<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <a class="youhuiquan" href="<?php echo U('order',array('openid'=>$openid));?>">本月微信支付单<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <?php if($user_id == '2017060114_F3D7369C9EC0A54EF4CC194F955C2048'){ ?>
    <a class="youhuiquan" href="<?php echo U('Finance/index',array('openid'=>$openid));?>">财务数据<div class="yingyongnum"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <?php } ?>
    <a class="youhuiquan" href="<?php echo U('refund',array('openid'=>$openid));?>">退款申请<div class="yingyongnum"><span class="pink"><?php echo ($userfund); ?></span></div><em></em></a>
    <div class="hr"></div>
    <a class="yintai-phone" href="<?php echo U('my_qroce',array('openid'=>$openid));?>">我的二维码<div class="infor-phone"><span class="pink"></span></div><em></em></a>
    <div class="hr"></div>
    <?php if($rose['company_id'] == '2017041315_E15FAA1AA148A91061FBA5A92FD89AB5'): ?><a class="yintai-paypw" href="<?php echo U('users_add',array('openid'=>$openid));?>">
            添加用户<div class="infor-phone"><span class="pink"></span></div><em></em></a>
        <div class="hr"></div>
        <a class="address" href="<?php echo U('users_list',array('openid'=>$openid));?>">
            用户管理<em></em></a><?php endif; ?>
    <ul class="um-list um-list-form">
        <li><label class="label" style="    width: 35%;">更改扫码头部标题</label>
            <input type="text" maxlength="10" value="<?php echo ($rose["title"]); ?>" style="    width: 35%;"placeholder="选填" id="title"></li>
    </ul>
    <aside class="account-submit">
        <button class="ui-btn-lg ui-btn-danger" type="button" id="J_submitCustomer" style="background-color: #30BF75;border-color: #30bf75;">更新标题</button>
    </aside>
    <div class="space-20"></div>
    <div class="space-20"></div>
    <div class="space-20"></div>
</div>
<div class="space-10"></div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('Rose2Personal/index',array('openid'=>$openid));?>">首页</li>
    <li data-url="<?php echo U('Device/device_list',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('Device/group_list',array('openid'=>$openid));?>">群组列表</li>
    <li data-url="<?php echo U('Rose2Personal/presonal_new',array('openid'=>$openid));?>">个人信息</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer_rose li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    var code = url.split("&code")[0];
    $("[data-url='"+code+"']").addClass('active');
    function onBridgeReady(){
        WeixinJSBridge.call('hideOptionMenu');
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
</body>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo ($signPackage["appId"]); ?>',
        timestamp: <?php echo ($signPackage["timestamp"]); ?>,
        nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
        signature: '<?php echo ($signPackage["signature"]); ?>',
        jsApiList: [
            'chooseImage',
            'uploadImage',
            'downloadImage',
            'previewImage'
        ]
    });
    Zepto(function($){
        /*===============区域跳转============*/
        $('*[data-href]').tap(function(){
            window.location.href=$(this).attr('data-href');
        });
        /*===============区域跳转============*/
        var REG = {
            name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
            phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
            passwd:/^[0-9]{6,8}$/,
            id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/,
            email:/\w+[@]{1}\w+[.]\w+/
        }
        //更改扫描码标题
        $('.ui-btn-danger').tap(function () {
            var title = $.trim($('#title').val());
            var el = $.loading({
                content: '正在提交'
            });
            $.post("",{title:title},function(data){
                if(data.code == 200){
                    $.dialog({
                        content:'更新成功',
                        button:['好']
                    });
                } else {
                    $.dialog({
                        content:'更新失败',
                        button:['好']
                    });
                }
                el.hide();
            },'json')
        })
        $('#edit_name').tap(function(){
            var mobile = $.trim($('.mobile').val());
            var phone = $.trim($('.phone').val());
            var openid = $.trim($('.openid').val());
            var email = $.trim($('.email').val());
            var percent = $.trim($('.percent').val());
            if (email == '' || phone == '' || mobile == '') {
                $.dialog({
                    content: '请输入手机号码或邮箱或提现比例',
                    button: ['好']
                });
                return false;
            }
            if(!REG.email.test(email)){
                $.dialog({
                    content:'请输入正确的邮箱',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(mobile)){
                $.dialog({
                    content:'请输入正确的电话',
                    button:['好']
                });
                return false;
            }
            if(!REG.phone.test(phone)){
                $.dialog({
                    content:'请输入正确的手机号码',
                    button:['好']
                });
                return false;
            }
            var el = $.loading({
                content: '正在提交'
            });
            $.post("",{openid:openid,email:email,phone:phone,mobile:mobile},function(data){
                if(data.code == 200){
                    $.dialog({
                        content:data.success,
                        button:['好']
                    });
                } else {
                    $.dialog({
                        content:data.error,
                        button:['好']
                    });
                }
                el.hide();
            },'json')
        })
    });
    var images = {
        localId: [],    //
        serverId: []
    };
    //上传用户头像
    $(".user-imgs").click(function (e) {
        var a1 = $(this);
        var id = $('.openid').val();
        wx.chooseImage({
            count: 1,
            success: function (res) {
                images.localId = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片45
                var i = 0, length = images.localId.length;
                images.serverId = [];
                function upload() {
                    wx.uploadImage({
                        localId: images.localId[i],
                        success: function (res) {
                            i++;
                            images.serverId.push(res.serverId);
                            if (i < length) {
                                upload();
                            } else {
                                var url = "<?php echo U('Rose2Personal/update_img');?>";
                                $.post(url, {
                                    imgs: encodeURIComponent(images.serverId),
                                    id: id
                                }, function (data) {
                                    a1.find('.imgs').attr('src', data);
                                });
                            }
                        },
                        fail: function (res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
                upload();//上传
            }
        });
    })
</script>
</html>