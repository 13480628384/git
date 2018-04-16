<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>修改</title>
    <style>
        .um-list li {
            height: auto;
        }
    </style>
</head>
<body>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form">

        <li><label  class="label">设备编号</label>
            <input class="device_code" style="width: 70%;" type="text" placeholder="" value="<?php echo ($res["device_code"]); ?>">
        </li>
        <li>
            <label  class="label">设备编码</label><input  type="text" class="device_command" value="<?php echo ($res["device_command"]); ?>">
        </li>
        <li>
            <label  class="label">充值价格</label>
            <textarea class="pay_price" rows="3" cols="24"><?php echo ($res["pay_price"]); ?></textarea>
        </li>
        <li>
            <label  class="label">货道数目</label>
            <select name="number_routes" style="width: 80px" class="number_routes">
                <option value="1" <?php if($res['number_routes'] == 1): ?>selected="selected"<?php endif; ?>>1</option>
                <option value="2" <?php if($res['number_routes'] == 2): ?>selected="selected"<?php endif; ?>>2</option>
                <option value="3" <?php if($res['number_routes'] == 3): ?>selected="selected"<?php endif; ?>>3</option>
                <option value="4" <?php if($res['number_routes'] == 4): ?>selected="selected"<?php endif; ?>>4</option>
                <option value="5" <?php if($res['number_routes'] == 5): ?>selected="selected"<?php endif; ?>>5</option>
                <option value="6" <?php if($res['number_routes'] == 6): ?>selected="selected"<?php endif; ?>>6</option>
                <option value="7" <?php if($res['number_routes'] == 7): ?>selected="selected"<?php endif; ?>>7</option>
                <option value="8" <?php if($res['number_routes'] == 8): ?>selected="selected"<?php endif; ?>>8</option>
                <option value="9" <?php if($res['number_routes'] == 9): ?>selected="selected"<?php endif; ?>>9</option>
                <option value="10" <?php if($res['number_routes'] == 10): ?>selected="selected"<?php endif; ?>>10</option>
            </select>
        </li>
        <li>
            <label  class="label">安放位置</label><input class="address" type="text" value="<?php echo ($res["address"]); ?>" >
        </li>
        <li>
            <label  class="label">备注</label><input  class="remarks" type="text" value="<?php echo ($res["remarks"]); ?>" >
        </li>
    </ul>
    <button class="anniu" id="edit_name">确定修改</button>
    <p style="color:#f00;margin-top: 10px">注：价格1-赠送玫瑰币1,价格2-赠送玫瑰币2,价格3-赠送玫瑰币3</p>
</section>
<input type="hidden" id="id" value="<?php echo ($res["id"]); ?>">
<input type="hidden" class="number" value="<?php echo ($res["number_routes"]); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
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
    Zepto(function($){
        $('#edit_name').tap(function(){
            var device_code = $.trim($('.device_code').val());
            var device_command = $.trim($('.device_command').val());
            var pay_price = $.trim($('.pay_price').val());
            var address = $.trim($('.address').val());
            var remarks = $.trim($('.remarks').val());
            var number_routes = $.trim($('.number_routes').val());
            var number = $.trim($('.number').val());
            var id = $.trim($('#id').val());
            if(device_code == ''){
                $.dialog({
                    content:'请输入设备编号',
                    button:['好']
                });
                return false;
            }
            if(device_command == ''){
                $.dialog({
                    content:'请输入设备编码',
                    button:['好']
                });
                return false;
            }
            if(pay_price == ''){
                $.dialog({
                    content:'请输入设备价格',
                    button:['好']
                });
                return false;
            }
            var el=$.loading({
                content:'正在保存'
            });
            var DATA = {
                device_code:device_code,
                device_command:device_command,
                pay_price:pay_price,
                address:address,
                remarks:remarks,
                id:id,
                number_routes:number_routes,
                number:number
            };
            $.post("<?php echo U('update_device');?>",DATA,function(data){
                if(data.code==200){
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href=data.url;
                    });
                } else {
                    $.dialog({
                        content:data.msg,
                        button:['好']
                    });
                }
                el.hide();
            },'json');
        })
    })
</script>
</html>