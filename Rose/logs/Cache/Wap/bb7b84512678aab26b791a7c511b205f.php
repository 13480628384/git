<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>修改会员信息</title>
</head>
<body>
<div class="del_vip">
    <div class="head">
        <span class="head-lf" onclick="javascript:history.go(-1);"></span>
        <span class="head-ct">修改会员信息</span>
    </div>
        <ul>
            <li class="click_pic">
                <?php if($rose['headimgurl'] == ''): ?><img class="imgs" src="./tpl/Wap/default/img/button1.png" alt="">
                <?php else: ?>
                    <img class="imgs" src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
                <div class="click_pic_div">点击修改</div>
            </li>
            <div class="del_vip_tis"><span>友情提示:</span>只需修改对应选项，若不修改不填即可</div>
            <li class="del_li"><span>昵称</span><input class="nickname" type="text" value="<?php echo ($rose["nickname"]); ?>"></li>
            <li class="del_li"><span>微信号</span><input class="wechat_number" type="text" value="<?php echo ($rose["wechat_number"]); ?>"></li>
            <li class="del_li"><span>支付宝账号</span><input class="alipay_number" type="text" value="<?php echo ($rose["alipay_number"]); ?>"></li>
            <li class="del_li"><span>手机号码</span><input class="phone" type="number" value="<?php echo ($rose["phone"]); ?>"></li>
            <li class="del_li"><span>邮箱</span><input  class="email" type="email" value="<?php echo ($rose["email"]); ?>"></li>
        </ul>
        <button class="anniu" type="button">确定修改</button>
</div>
<input type="hidden" id="J_submitReg" value="<?php echo U('V_2RoseAjax/update_vip');?>">
<input type="hidden" id="uid" value="<?php echo ($rose["id"]); ?>">
<input type="hidden" id="user_id" value="<?php echo ($user_id); ?>">
<input type="hidden" id="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" id="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/zoology.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/antbridge.min.js"></script>
<script src="./tpl/Wap/default/js/update_vip.js?01"></script>
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
            'chooseImage',
            'uploadImage',
            'downloadImage',
            'previewImage'
        ]
    });
    var weixin_alipay_type = $('#weixin_alipay_type').val();
    if(weixin_alipay_type == 'wechat') {
        wx.ready(function () {
            var images = {
                localId: [],    //
                serverId: []
            };
            $(".click_pic_div").click(function (e) {
                var a1 = $(this);
                var id = $('#uid').val();
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
                                        var url = "<?php echo U('V_2RoseAjax/update_img');?>";
                                        $.post(url, {
                                            imgs: encodeURIComponent(images.serverId),
                                            id: id
                                        }, function (data) {
                                            a1.parent().find('.imgs').attr('src', data);
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
        });
    } else {
        if(navigator.userAgent.indexOf("AlipayClient")===-1){
            alert('请在支付宝钱包内运行');
        }else{
            $('.click_pic_div').click(function(){
                if((Ali.alipayVersion).slice(0,3)>=8.1){
                    AlipayJSBridge.call('photo', {
                        dataType: 'dataURL',
                        imageFormat: 'jpg',
                        quality: 75,
                        maxWidth: 500,
                        maxHeight: 500,
                        allowEdit: true
                    }, function (result) {
                        image = document.getElementById('myImage');
                        var id = $('#uid').val();
                        $.post("<?php echo U('V_2RoseAjax/alipay_update_img');?>",{id:id,img:"data:image/jpeg;base64,"+result.dataURL},function(data){
                            $('.imgs').attr('src', data);
                        })
                    });
                }else{
                    Ali.alert({
                        title: '亲',
                        message: '请升级您的钱包到最新版',
                        button: '确定'
                    });
                }
            })
        }
    }
</script>
</html>