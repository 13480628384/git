<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>修改群组</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>
    #group_update{
            font-size: 18px;
    height: 44px;
    line-height: 42px;
    display: block;
    width: 100%;
    border-radius: 5px;
    background: #f00;
    color: #fff;
}
    }
    </style>
</head>
<body>

<header class="header-top">
    修改群组
</header>
<section class="ucenter-main animated fadeInDown">
    <ul class="um-list um-list-form">
        <li><label for="customer_name" class="label">原群组名称</label><input type="text" id="old" value="<?php echo $_GET['name']?>" readonly="readonly"></li>
    </ul>
    <div class="space-10"></div>
    <ul class="um-list um-list-form" id="J_TJJJRPhone">
        <li><label for="customer_name" class="label">新名称</label><input type="text" class="group_name" value=""></li>
    </ul>
    <div class="space-10"></div>
    <input type="hidden" class="group_id" value="<?php echo $_GET['updateid']?>">
    <p class="um-tips"><em>提示：</em>请输入正确的名称</p>
</section>
<div class="space-20"></div>
<aside class="account-submit">
    <button class="ui-btn-lg ui-btn-danger" type="button" id="group_update">修改</button>
</aside>

<div class="space-20"></div>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
</body>
<script>
Zepto(function($){
/*
    *群组名称修改
    *
    */
    var group_update = $('#group_update');
    group_update.tap(function(){
        var group_id = $('.group_id').val();
        var group_name = $.trim($('.group_name').val());
        if(!group_name){
            $.dialog({
                    content:'请填写群组名称',
                    button:['我知道了']
            });
            return false;
        }
        var el=$.loading({
            content:'正在提交'
        });
        $.post("<?php echo U('update_device_group_info');?>",{device_group_id:group_id,device_group_name:group_name},function(reg){
            if(reg.msg==1){
                var DG=$.dialog({
                    content:'恭喜您，修改成功！',
                    button:['我知道了']
                });
                DG.on('dialog:action',function(e){
                    document.location.href="<?php echo U('group');?>";
                });
            }else{
                $.dialog({
                    content:'网络错误，请重试',
                    button:['我知道了']
                });
            }
        el.hide();
        },'json');
    });
});
</script>
</html>