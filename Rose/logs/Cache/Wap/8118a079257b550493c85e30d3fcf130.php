<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta id="viewport" name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>问题反馈</title>
    <link rel="stylesheet" href="./tpl/Wap/default/css/base.css?2017">
    <link rel="stylesheet" href="./tpl/Wap/default/css/touch.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
</head>
<body>
<div id="wrapper">
    <header class="title">
        请写下你的建议或意见
    </header>
    <div class="content">
        <div class="txtArea">
            <textarea name="" id="content" cols="20" rows="5" placeholder="请输入" onkeyup="content()"></textarea>
        </div>
        <ul class="imgList clearfix">
            <li class="add_img" data="tpl/Wap/defaul/img/btn_addimg.jpg">
                <img src="tpl/Wap/default/img/btn_addimg.jpg">
                <!--<input type="file" name="picture" id="picture">-->
            </li>
        </ul>
    </div>
    <footer>
        <button type="submit" class="new_submit">发送</button>
    </footer>
</div>
</body>
<input type="hidden" class="submit_content" value="<?php echo U('submit_content');?>">
<input type="hidden" class="add_img_upload" value="<?php echo U('add_img');?>">
<input type="hidden" class="indexs" value="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>{:U('index',array('openid'=>$openid,'str'=>1))}">
<input type="hidden" class="is_all_none" value="0">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="img" value="tpl/Wap/default/img/btn_addimg.jpg">
<script src="tpl/Wap/default/js/zepto.js"></script>
<script src="tpl/Wap/default/js/frozen.js"></script>
<script src="tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="tpl/Wap/default/js/jsweixin1.0.js"></script>
<script>
    Zepto(function($) {
        $('.new_submit').tap(function () {
            var content = $('#content').val();
            var clearfix = $('.clearfix');
            var select_id = "";
            $('.clearfix').find('.img_url').each(function () {
                if ($(this).attr("data")) {
                    select_id += $(this).attr("data") + ",";
                }
            });
            var select_id = select_id.substring(0, select_id.length - 1);
            if (content == '') {
                $.dialog({
                    content: '请输入内容',
                    button: ['ok']
                });
                return false;
            }
            var el=$.loading({
                content:'提交中'
            });
            var url = $('.submit_content').val();
            var openid = $('.openid').val();
            $.post(url, {content: content, select_id: select_id, openid: openid}, function (data) {
                el.hide();
                if (data.code == 200) {
                    $.dialog({
                        content: data.msg,
                        button: ['ok']
                    });
                    WeixinJSBridge.call('closeWindow');
                } else {
                    $.dialog({
                        content: data.msg,
                        button: ['ok']
                    });
                }
            }, 'json')
        })
    })
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage'
        ]
    });
    /*图片上传*/
    //function wxready(){
    var images = {
        localId: [],
        serverId: []
    };
    var counts=0;
    $(".add_img").click(function() {
        wx.chooseImage({
            count: 1,
            sizeType: ['compressed'],
            success: function (res) {
                var localIds = res.localIds;
                images.localId = res.localIds;
                var i = 0, length = images.localId.length;
                counts++;
                images.serverId = [];
                function upload() {
                    wx.uploadImage({
                        localId: images.localId[i],
                        success: function (res) {
                            i++;
                            images.serverId.push(res.serverId);
                            if (i < length) {
                                upload();
                            }else{
                                var  url=$('.add_img_upload').val();
                                $.post(url,{imgs:encodeURIComponent(images.serverId)},function(data){
                                    var leng=data.imgs.length;
                                    $.each(data.imgs, function(e,t){
                                        $(".clearfix").prepend("<li class='img_url' data='"+t+"'><img src='"+t+"' class='images' /></li>");
                                    });
                                    if (counts >= 2) {
                                        $(".add_img").hide();
                                    }
                                },'json');
                            }
                        },
                        fail: function (res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
                upload();
            }
        });
    });
    //}

</script>
</html>