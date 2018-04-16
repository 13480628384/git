<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/pim.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>个人信息</title>
</head>
<body style="background: #f2f2f2">
<ul class="pimdiv ">
    <li><span class="pimdivtd1">扫码</span><span class="pimdivtd2">
        <input type="text"  onkeyup="scan_code()" class="device_cod" placeholder="请输入或扫码">
        <img src="./tpl/Wap/default/img/sm2.png" class="hard"></span></li>
    <li><span class="pimdivtd1">更改群组</span><span class="pimdivtd2">
        <select name="p_code" id="device_group_id" style="width:150px;">
            <?php if($group_name != null): if(is_array($group_name)): $i = 0; $__LIST__ = $group_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["group_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </select>
    </span></li>
    <li><span class="pimdivtd1">状态</span><span class="pimdivtd2 status"></span></li>
    <li><span class="pimdivtd1">硬编码</span><span class="pimdivtd2 device_command"></span></li>
    <li><span class="pimdivtd1">软编码</span><span class="pimdivtd2 device_code"></span></li>
    <li><span class="pimdivtd1">旧群组</span><span class="pimdivtd2 old_group"></span></li>
</ul>
<button class="anniu" id="edit_name">保存</button>
<input type="hidden" class="hard_device_code" value="<?php echo U('device_code_exists_or');?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('Rose2Personal/index',array('openid'=>$openid));?>">首页</li>
    <li data-url="<?php echo U('Device/device_list',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('Device/group_list',array('openid'=>$openid));?>">群组列表</li>
    <li data-url="<?php echo U('Rose2Personal/personel_new',array('openid'=>$openid));?>">个人信息</li>
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
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'scanQRCode',
        ]
    });
    //点击扫码
    $(".hard").click(function(){
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var urlt = res.resultStr;
                $('.device_cod').val(urlt);
                var hard_device_code = $('.hard_device_code').val();
                $.post(hard_device_code,{hard_device_code:$('.device_cod').val()},function(data){
                    if(data.msg == 1){
                        var online = '';
                        if(data.datas.online_status == 1){
                            online = '在线';
                        } else {
                            online = '离线';
                        }
                        $('.status').html(online);
                        $('.device_command').html(data.datas.device_command);
                        $('.device_code').html(data.datas.device_code);
                        $('.old_group').html(data.group_names);
                    }else{
                        $('#group_code').html('');
                        $('#ords').html('');
                        $('.pcode_select').html('');
                        $('.another_name').html('');
                        $('.hcode_select').html('');
                    }
                },'json');
            }
        });
    });
    function scan_code(){
        var hard_device_code = $('.hard_device_code').val();
        $.post(hard_device_code,{hard_device_code:$('.device_cod').val()},function(data){
            if(data.msg == 1){
                var online = '';
                if(data.datas.online_status == 1){
                    online = '在线';
                } else {
                    online = '离线';
                }
                $('.status').html(online);
                $('.device_command').html(data.datas.device_command);
                $('.device_code').html(data.datas.device_code);
                $('.old_group').html(data.group_names);
            }else{
                $('#group_code').html('');
                $('#ords').html('');
                $('.pcode_select').html('');
                $('.another_name').html('');
                $('.hcode_select').html('');
            }
        },'json');
    }
    Zepto(function($){
        $('#edit_name').tap(function(){
            var device_cod = $.trim($('.device_cod').val());
            var device_group_id = $.trim($('#device_group_id').val());
            if (device_cod == '') {
                $.dialog({
                    content: '请输入编码',
                    button: ['好']
                });
                return false;
            }
            var el = $.loading({
                content: '正在提交'
            });
            $.post("<?php echo U('update_device_grouped');?>",
                    {device_group_id:device_group_id,device_cod:device_cod},
            function(data){
                if(data.msg == 200){
                    var DG = $.dialog({
                        content: '保存成功',
                        button: ['好']
                    });
                    DG.on('dialog:action', function (e) {
                        document.location.href = "<?php echo U('Device/group_list',array('openid'=>$openid));?>";
                    });
                } else {
                    $.dialog({
                        content: '保存失败',
                        button: ['好']
                    });
                }
                el.hide();
            },'json')
        })
    })
</script>
</html>