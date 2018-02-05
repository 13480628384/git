<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>上传图片</title>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/index.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/style.css" />
    </head>
    <body>
    <div class="message-top">
        填写信息
    </div>

    <div class="message-content">
        <div class="message-content-top">
            <div class="rank">
                <span class="rank-title">姓名</span>
	  			<span class="rank-input">
	  				<input type="text" placeholder="请输入您的姓名" name="user" id="reg_name" value="">
	  			</span>
            </div>
            <div class="rank">
                <span class="rank-title">手机</span>
	  			<span class="rank-input">
	  				<input type="text" placeholder="请输入您的手机号码" name="tel" id="reg_phone" value="">
	  			</span>
            </div>
            <div class="rank">
                <span class="rank-title">地址</span>
	  			<span class="rank-input">
				<input type="hidden" value="" id="secret">
	  				<input type="text" placeholder="请输入您的快递地址" name="home" id="tag_code" value="">
	  			</span>
            </div>
        </div>
        <div class="upload ">
            <div class="upload-title">上传照片</div>
            <div class="upload-main">
                <ul class="upload-main-ul">
                    <li class="upload-main-ul-li c1">
                        <img src="" style="display:none">
                        <input id="img1" type="hidden" class="img2" name="img1" value="">
                        <div class="icon-add"></div>
                        <div class="upload-main-ul-text">上传前面板照片</div>
                        <div class="upload-main-ul-text">（机器型号要清晰）</div>
                    </li>
                    <li class="upload-main-ul-li c1">
                        <img src="" style="display:none">
                        <input id="img2" type="hidden" class="img2" name="img2" value="">
                        <div class="icon-add"></div>
                        <div class="upload-main-ul-text">上传后面板照片</div>
                        <div class="upload-main-ul-text">（序列号要清晰）</div>

                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="hint">
        <span class="hint-top">提示：</span>
	  	<span class="hint-size">
	  		请您务必准确填写以上信息，否则可能收不到我们邮寄给你的奖品
	  	</span>
    </div>
    <input type="hidden" id="ruid" value="/index.php?g=Wap&m=Wxdj&a=yj_submit&token=5d8a87bab30de695954b17fc835b9d12&openid=o6cYQt29FWcRHbCA5DlKRj5W_T7M">
    <div class="btn-submit" id="J_submitReg1">
        提交信息
    </div>
    </body>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
<script type="text/javascript">
wx.config({
  debug: false,
  appId: '<?php echo $signPackage["appId"];?>',
  timestamp: '<?php echo $signPackage["timestamp"];?>',
  nonceStr: '<?php echo $signPackage["nonceStr"];?>',
  signature: '<?php echo $signPackage["signature"];?>',
  jsApiList: [
      'chooseImage',
      'uploadImage',
      'downloadImage',
      'previewImage'
  ]
});
wx.ready(function () {
    var images = {
        localId: [],    //
        serverId: []
    };
    $('.close').click(function(){
        wx.closeWindow();
    });
    $(".c1").click(function(e) {
        var a1=$(this);
        wx.chooseImage({
            count: 1,
            success: function (res) {
                images.localId = res.localIds;
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
                            }else{
                                var  url="<?php echo U('upload_chw');?>";
                                $.post(url,{imgs:encodeURIComponent(images.serverId)},function(data){
                                    alert(data);
                                    var leng=data.imgs.length;
                                    $.each(data.imgs, function(e,t){
                                        a1.find(".img2").val(t);
                                        a1.find('img').attr('src',t);
                                        a1.find('img').show();
                                    })
                                },'json');
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
})
Zepto(function($){
    $('#J_submitReg1').tap(function(){
        if($('#J_submitReg1').hasClass('ing'))
        {
            return false;
        }
        var reg_name = $('#reg_name').val();
        var reg_phone = $('#reg_phone').val();
        var tag_code = $('#tag_code').val();
        var sn = $('#sn').val();
        var secret = $('#secret').val();
        var img1 = $('#img1').val();
        var img2 = $('#img2').val();
        if(!reg_name)
        {
            $.dialog({
                content:'您还没有填写姓名,请输入姓名',
                button:['我知道了']
            });
            return false;
        }
        if(reg_phone.length!=11)
        {
            $.dialog({
                content:'您输入的手机号码不正确',
                button:['我知道了']
            });
            return false;
        }
        $('#J_submitReg1').html('提交中...').addClass('ing');;
        $.post($('#ruid').val(),{reg_name:reg_name,reg_phone:reg_phone,tag_code:tag_code,sn:sn,secret:secret,img1:img1,img2:img2},function(data){
            $('#J_submitReg1').html('提交').removeClass('ing');
            if(data.status==1)
            {

            }
        },'json')

    });
});    
</script>
</html>