<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>添加群组</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
</head>
<body>

<header class="header-top">
    添加群组
</header>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form" id="J_TJJJRPhone">
        <li><label for="customer_name" class="label">群组名称</label><input type="text" placeholder="请输入群组名称" 
        class="group_name" value=""></li>
    </ul>
    <p class="um-tips"><em>提示：</em>请输入正确的群组名称</p>
</section>
<div class="space-20"></div>
<aside class="account-submit">
    <button class="ui-btn-lg ui-btn-danger" type="button" id="add_name">马上添加</button>
</aside>

<div class="space-20"></div>
<aside class="account-submit account-submit-fixed" style="display:none;">
    <div class="ui-btn-group-tiled ui-btn-wrap">
        <button class="ui-btn-lg ui-btn-danger" id="J_submitProjectChoose">确认</button> <button class="ui-btn-lg" id="J_cancelProjectChoose">取消</button>
    </div>
</aside>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
</body>
<script type="text/javascript">
Zepto(function($){
    /*
        *添加群组信息
        *
    */
    var add_name = $('#add_name');
    add_name.tap(function(){
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
        $.post("<?php echo U('add_device_group_info');?>",{device_group_name:group_name},function(reg){
            if(reg.msg==1){
                var DG=$.dialog({
                    content:'恭喜您，提交成功！',
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
})
</script>>
</html>