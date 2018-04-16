<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css?v1">
    <link rel="stylesheet" href="./tpl/Wap/default/css/zoology.css?v6">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>生态商</title>
</head>
<body style="background: #f2f2f2">
<!--我是生态商-->
<div class="zoology">
    <!-- 内容头部 -->
    <div class="zoology-top">
        <div class="zoology-top-top">
            <?php if($rose['headimgurl'] == null): ?><img src="./tpl/Wap/default/img/reg_1.png" alt=""><?php else: ?>
                <img src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
            <p>玫瑰ID：<?php echo ($rose["rose_id"]); ?></p>
        </div>
        <div class="zoology-top-bottom">
            <img src="./tpl/Wap/default/img/reg_3.png" alt="">
            <p class="red_count"><?php echo intval($rose['ecological_red_rose']); ?></p>
        </div>
    </div>
    <div class="zoology-give">
        <!-- 内容赠送部分 -->
            <h2>我要赠送生态红玫瑰</h2>
            <p style="border:none;margin-bottom:0;">赠送用户
                <input type="text" class="nickname" placeholder="输入对方昵称">
                <img class="user_click" src="./tpl/Wap/default/img/st.png" alt=""></p>
            <p>赠送数量<input class="giverose_input total" type="text"  value="0" pattern="^([1-9]\d*)$"></p>
            <ul class="giverose">
                <li class="giveroseli1"><img src="./tpl/Wap/default/img/st1.png" alt=""></li>
                <li class="giveroseli2"><img src="./tpl/Wap/default/img/st7.png" alt=""></li>
                <li class="giveroseli3"><img src="./tpl/Wap/default/img/st3.png" alt=""></li>
                <li class="giveroseli4"><img src="./tpl/Wap/default/img/st4.png" alt=""></li>
            </ul>
            <p>玫瑰赠言<input type="text" class="content" placeholder="30字以内" ></p>
            <input class="zoology-give-button2 send_rose" type="submit" value="" >

        <div style="height:1rem; background: #f2f2f2"></div>
        <!-- 内容购买部分 -->

            <h2>我要购买生态红玫瑰</h2>
            <p>购买数量<input type="text" class="buyrose_input"  value="0"  pattern="^([1-9]\d*)$"></p>

            <ul class="buyrose">
                <li class="buyroseli1"><img src="./tpl/Wap/default/img/st1.png" alt=""></li>
                <li class="buyroseli2"><img src="./tpl/Wap/default/img/st7.png" alt=""></li>
                <li class="buyroseli3"><img src="./tpl/Wap/default/img/st3.png" alt=""></li>
                <li class="buyroseli4"><img src="./tpl/Wap/default/img/st4.png" alt=""></li>
            </ul>
            <input class="zoology-give-button1 buy_red_rose" type="button" value="">
    </div>
</div>
<input type="hidden" id="J_submitReg" value="<?php echo U('V_2RoseAjax/send_rose');?>">
<input type="hidden" id="Alipay" value="<?php echo U('Alipay/buy_red_rose');?>">
<input type="hidden" id="Wechat" value="<?php echo U('V_2WechatPay/buy_red_rose');?>">
<input type="hidden" id="update" value="<?php echo U('V_2WechatPay/rose_update');?>">
<input type="hidden" id="quotient_id" value="<?php echo ($rose["id"]); ?>">
<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
<input type="hidden" id="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" id="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/zoology.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/common.js?v1"></script>
<script src="./tpl/Wap/default/js/send_rose.js?v1.6"></script>
<script src="./tpl/Wap/default/js/antbridge.min.js"></script>
<ul class="footer">
    <?php if($weixin_alipay_type == 'wechat'): ?><li data-url="<?php echo U('V_2WechantDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li>
        <?php else: ?>
        <li data-url="<?php echo U('V_2AlipayDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li><?php endif; ?><!--<li data-url="<?php echo U('space',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type));?>">玫瑰空间</li>-->
    <li data-url="<?php echo U('V_2Rose/quotient',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">生态商</li>
    <li data-url="<?php echo U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">我的</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    $("[data-url='"+url+"']").addClass('active');
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
            'scanQRCode'
        ]
    });
    var weixin_alipay_type = $('#weixin_alipay_type').val();
    if(weixin_alipay_type == 'wechat'){
        $('.user_click').click(function(){
            wx.scanQRCode({
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var urlt = res.resultStr;
                    $.post("<?php echo U('V_2RoseAjax/get_name');?>",{url:urlt},function(data){
                        if(data == 2){
                            alert('用户昵称不存在');
                            $('.nickname').val('');
                        } else {
                            $('.nickname').val(data);
                        }
                    });
                }
            });
        })
    } else {
        $('.user_click').click(function(){
            if(navigator.userAgent.indexOf("AlipayClient")===-1){
                alert('请在支付宝钱包内运行');
            }else{
                if((Ali.alipayVersion).slice(0,3)>=8.1){
                    Ali.scan({
                        type: 'qr' //qr(二维码) / bar(条形码) / card(银行卡号)
                    }, function(result) {
                        if(result.errorCode){
                            //没有扫码的情况
                            //errorCode=10，用户取消
                            //errorCode=11，操作失败
                        }else{
                            //alert(result.qrCode);
                            //成功扫码的情况
                            //result.barCode	string	扫描所得条码数据
                            //result.qrCode	string	扫描所得二维码数据
                            //result.cardNumber	string	扫描所得银行卡号
                            $.post("<?php echo U('V_2RoseAjax/get_name');?>",{url:result.qrCode},function(data){
                                if(data == 2){
                                    alert('用户昵称不存在');
                                    $('.nickname').val('');
                                } else {
                                    $('.nickname').val(data);
                                }
                            });
                        }
                    });
                }else{
                    Ali.alert({
                        title: '亲',
                        message: '请升级您的钱包到最新版',
                        button: '确定'
                    });
                }
            }
        })
    }
</script>
</html>