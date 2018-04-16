<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta property="qc:admins" content="20424726756105230763757164506000" />
    <meta charset="utf-8">
    <title>深圳物联技术有限公司</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="./tpl/Wap/default/css/style.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/supersized.css">
</head>
<body oncontextmenu="return false">
<div class="page-container">
    <h1>Login</h1>
    <div>
        <input type="text" name="username" class="username" placeholder="Username" autocomplete="off"/>
    </div>
    <button id="submit" type="button">登录</button>
    <div class="connect">
        <p>If we can only encounter each other rather than stay with each other,then I wish we had never encountered.</p>
        <p style="margin-top:20px;">如果只是遇见，不能停留，不如不遇见。</p>
    </div>
</div>
<div class="alert" style="display:none">
    <h2>消息</h2>
    <div class="alert_con">
        <p id="ts"></p>
        <p style="line-height:70px"><a class="btn">确定</a></p>
    </div>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/supersized.3.2.7.min.js"></script>
<script src="./tpl/Wap/default/js/supersized-init.js"></script>
<script>
    $(".btn").click(function(){
        is_hide();
    })
    var p = $("input[name=password]");
    $("#submit").click(function(){
        var u = $('.username').val();
        if(u==''){
            $("#ts").html("用户名不能为空~");
            is_show();
            return false;
        }
        $('#submit').html('请稍后...');
        $.post("<?php echo U('check');?>",{username:u},function(data){
            $('#submit').html('登录');
            if(data.code == 200){
                window.location.href=data.url;
            }else{
                $("#ts").html(data.msg);
                is_show();
                return false;
            }
        },'json');
    });
    $('.username').keyup(function(e){
        var key = e.which || e.keyCode;
        if(13 == key){
            $('#submit').trigger('click');
        }
    });
    window.onload = function()
    {
        $(".connect p").eq(0).animate({"left":"0%"}, 600);
        $(".connect p").eq(1).animate({"left":"0%"}, 400);
    }
    function is_hide(){ $(".alert").animate({"top":"-40%"}, 300) }
    function is_show(){ $(".alert").show().animate({"top":"45%"}, 300) }
</script>
</body>
</html>