<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta id="viewport" name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>写攻略</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/base.css?0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
</head>
<body>
<div id="wrapper">
    <header class="title">
        编辑内容
    </header>
    <div class="content">
        <div class="txtArea">
            <textarea name="" id="content" cols="20" rows="5" placeholder="输入内容" ></textarea>
        </div>
        <ul class="imgList clearfix">
            <!--<li class="img_url" data="__PUBLIC__/Home/img/img1.jpg">
                <img src="__PUBLIC__/Home/img/img1.jpg">
            </li>
            <li class="img_url" data="__PUBLIC__/Home/img/img2.jpg">
                <img src="__PUBLIC__/Home/img/img2.jpg">
            </li>
            <li class="img_url" data="__PUBLIC__/Home/img/img3.jpg">
                <img src="__PUBLIC__/Home/img/img3.jpg">onchange="upload(event)"
            </li>-->
            <li class="add_img" data="__PUBLIC__/Home/img/btn_addimg.jpg">
                <img src="__PUBLIC__/Home/img/btn_addimg.jpg">
            </li>
        </ul>
    </div>
    <footer>
        <button type="submit" class="new_submit">发布</button>
    </footer>
</div>
</body>
<input type="hidden" class="strate_url_of" value="<?php echo U('strate_url_of');?>">
<input type="hidden" class="submit_content" value="<?php echo U('submit_content');?>">
<input type="hidden" class="add_img_upload" value="<?php echo U('Roadnext/add_img');?>">
<input type="hidden" class="indexs" value="<?php echo 'http://'.$_SERVER['HTTP_HOST']; echo U('index',array('openid'=>$openid,'str'=>1));?>">
<input type="hidden" class="is_all_none" value="0">
<input type="hidden" class="content" value="0">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="token" value="<?php echo ($token); ?>">
<input type="hidden" class="img" value="__PUBLIC__/Home/img/btn_addimg.jpg">
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
<script>
    /*function search(){
        var add_title = $('#add_title').val();
        var strate_url_of = $('.strate_url_of').val();
        $.post(strate_url_of,{add_title:add_title},function(data){
            if(data.code==200){
                $('.is_all_none').val(1);
            }else{
                $('.is_all_none').val(0);
            }
        },'json');
    }*/
    /*function content(){
        var content = $('#content').val();
        var strate_url_of = $('.strate_url_of').val();
        $.post(strate_url_of,{add_title:content},function(data){
            if(data.code==200){
                $('.content').val(1);
            }else{
                $('.content').val(0);
            }
        },'json');
    }*/
    $('.new_submit').click(function(){
        if($('.new_submit').hasClass('on')){
            return false;
        }
        var content = $('#content').val();
        //var check = $('.content').val();
        var clearfix = $('.clearfix');
        var select_id="";
        $('.clearfix').find('.img_url').each(function() {
            if ($(this).attr("data")) {
                select_id += $(this).attr("data")+",";
            }
        });
        var select_id=select_id.substring(0,select_id.length-1);
        if(content==''){
            new TipBox({type:'tip',str:'请输入内容',clickDomCancel:true,setTime:1500});
            return false;
        }
		
		var strate_url_of = $('.strate_url_of').val();
		var check = false;
        $.ajax({
            type: 'POST',
            url: strate_url_of,
            data: {add_title:content},
            dataType: 'json',
            async:false,
            success: function(data){
                if(data.code == 200){
                    check = true;
                }
            },
            error: function(xhr, type){
                check = false;
            }
        });
		
		
        if(check==true){
            new TipBox({type:'tip',str:'请输入合法内容',clickDomCancel:true,setTime:1500});
            return false;
        }
        /*if(select_id==''){
         new TipBox({type:'tip',str:'请上传图片',clickDomCancel:true,setTime:1500});
         return false;
         }*/
        $('.new_submit').html('发布中...').addClass('on');
        var url = $('.submit_content').val();
        var openid = $('.openid').val();
        var indexs = $('.indexs').val();
        $.post(url,{content:content,select_id:select_id,openid:openid},function(data){
            if(data.code==200){
                new TipBox({type:'success',str:'评论成功',setTime:1500});
                window.location.href=indexs;
            }
            $('.new_submit').html('发布').removeClass('on');
        },'json');
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
            'downloadImage',
        ]
    });

    /*图片上传*/
    var images = {
        localId: [],
        serverId: []
    };
    var num = 0;
    $(".add_img").click(function() {
        wx.chooseImage({
            count: 1,
            success: function (res) {
                var localIds = res.localIds;
                images.localId = res.localIds;
                var i = 0, length = images.localId.length;
                images.serverId = [];
                function upload() {
                    wx.uploadImage({
                        localId: images.localId[i],
                        success: function (res) {
                            i++;
                            if (num >=4) {
                                $(".add_img").hide();
                            }
                            images.serverId.push(res.serverId);
                            if (i < length) {
                                upload();
                            }else{
                                var  url=$('.add_img_upload').val();
                                $.post(url,{imgs:encodeURIComponent(images.serverId)},function(data){
                                    num++;
                                    var leng=data.imgs.length;
                                    $.each(data.imgs, function(e,t){
                                        $(".clearfix").prepend("<li class='img_url' data='"+t+"'><img src='"+t+"' class='images' /></li>");
                                    });
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

</script>
</html>