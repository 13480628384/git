<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mui.min.css">
    <title>转移设备</title>
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">转移设备</h1>
</header>
<div class="mui-content">
    <h5 class="mui-content-padded">请选择转移设备</h5>
    <div class="mui-card">
        <div class="mui-input-row mui-search">
            <input type="search" class="mui-input-clear search" placeholder="请输入要转移设备的用户手机号码">
        </div>
        <form class="mui-input-group ">
            <?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="mui-input-row mui-checkbox mui-left">
                    <label>设备码 : &nbsp;&nbsp;<?php echo ($v["device_code"]); ?></label>
                    <input name="checkbox" value="<?php echo ($v["id"]); ?>" type="checkbox" class="checkbox">
                    <p class="nesx" style="display: none" dataid="<?php echo ($v["device_command"]); ?>"></p>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </form>
    </div>
    <div class="mui-button-row">
        <button type="button" class="mui-btn mui-btn-primary" style="margin-bottom:60px;">确认</button>
    </div>
</div>
<input type="hidden" id="groupid" value=""/>
<input type="hidden" id="di_id" value=""/>
<input type="hidden" id="update" value="<?php echo U('update_price');?>"/>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/mui.min.js"></script>
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
    mui.init({
        swipeBack:true //启用右滑关闭功能
    });
    mui('.mui-input-group').on('change', 'input', function() {
        var value = this.checked?"true":"false";
        this.nextElementSibling.innerText=value;
        if(this.nextElementSibling.innerText == 'true'){
            this.nextElementSibling.setAttribute('class','nadd');
        }else{
            this.nextElementSibling.setAttribute('class','nesx');
        }
    });
    $('.mui-btn-primary').click(function(){
        if($(this).hasClass('on')){
            return false;
        }
        var text1='';
        $(".checkbox").next(".nadd").each(function() {
            if ($(this).attr("dataid")) {
                 text1 += $(this).attr("dataid")+",";
            }
        });
        var text1=text1.substring(-1,text1.length-1);
        var user_name = $('.search').val();
        if(user_name == ''){
            //mui.toast('用户不能为空');
            alert('用户手机号码不能为空');
            return false;
        }
        if(text1 == ''){
            //mui.toast('请选择设备');
            alert('请选择设备');
            return false;
        }
        $('.mui-btn-primary').addClass('on').html('设备转移中...');
        $.post("<?php echo U('changeing');?>",{text1:text1,user_name:user_name},function(data){
            if(data.code == 201){
                alert(data.msg);
            }else{
                alert(data.msg);
                window.location.href=window.location.href;
            }
            $('.mui-btn-primary').removeClass('on').html('确认');
        },'json');
    })
</script>
</html>