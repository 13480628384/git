<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta id="viewport" name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>回复</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/base.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
</head>
<body>
<div id="wrapper">
    <header class="title">
        回复内容
    </header>
    <div class="content">
        <div class="txtArea">
            <textarea name="" maxlength="50" id="content" cols="20" rows="5" placeholder="输入内容" ></textarea>
        </div>
    </div>
    <footer>
        <button type="submit" class="new_submit">发布</button>
    </footer>
</div>
</body>
<input type="hidden" class="strate_url_of" value="<?php echo U('Community/strate_url_of');?>">
<input type="hidden" class="submit_content" value="<?php echo U('Community/pl_url');?>">
<input type="hidden" class="indexs" value="<?php echo U('Community/index');?>">
<input type="hidden" class="is_all_none" value="0">
<input type="hidden" class="content" value="0">
<input type="hidden" class="userid" value="<?php echo ($userid); ?>">
<input type="hidden" class="replybuyer_id" value="<?php echo ($replybuyer_id); ?>">
<input type="hidden" class="friendinfo_id" value="<?php echo ($friendinfo_id); ?>">
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script>
    $('.new_submit').click(function(){
        if($('.new_submit').hasClass('on')){
            return false;
        }
        var content = $('#content').val();
        //var check = $('.content').val();
        var replybuyer_id = $('.replybuyer_id').val();
        var friendinfo_id = $('.friendinfo_id').val();
        var url = $('.submit_content').val();
        var userid = $('.userid').val();
        var indexs = $('.indexs').val();
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
            new TipBox({type:'tip',str:'请输入合法内容',clickDomCancel:true,setTime:1000});
            return false;
        }
        $('.new_submit').html('发布中...').addClass('on');
        $.post(url,{content:content,replybuyer_id:replybuyer_id,userid:userid,friendinfo_id:friendinfo_id},function(data){
            if(data.code==200){
                new TipBox({type:'success',str:'评论成功',setTime:1000});
                window.location.href=indexs;
            }
            $('.new_submit').html('发布').removeClass('on');
        },'json');
    })
</script>
</html>