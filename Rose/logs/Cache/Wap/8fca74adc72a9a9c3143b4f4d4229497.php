<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>群组信息列表</title>
</head>
<body>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form">
        <li>
            <label  class="label">更改群组</label>
            <select name="p_code" id="device_group_id" style="width:150px;">
                <?php if($group_name != null): if(is_array($group_name)): $i = 0; $__LIST__ = $group_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if($res['dgi_id'] == $v['id']): ?>selected="selected"<?php endif; ?>><?php echo ($v["group_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </select>
        </li>
        <li><label  class="label">组内名称</label>
            <!--<select name="p_code" id="device_group_code" width="50%">
                <?php if(is_array($Capital)): $k = 0; $__LIST__ = $Capital;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><option value="<?php echo ($k); ?>" <?php if($k == 1): ?>selected="selected"<?php endif; ?>><?php echo ($v["0"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>-->
            <input class="group_word" style="width: 70%;" type="text" placeholder="请输入字母如 A" maxlength="2" value="<?php echo ($res["group_word"]); ?>">
        </li>
        <li>
            <label  class="label">硬编码</label><input value="<?php echo ($res["device_command"]); ?>" readonly="readonly">
        </li>
        <li>
            <label  class="label">软编码</label><input value="<?php echo ($res["device_code"]); ?>" readonly="readonly">
        </li>
        <?php if($device_type == 4 or $device_type == 3): ?><li><label  class="label">价格时间</label>
                <input type="text" id="srdata" style="width:50%!important;" placeholder="请输入" value="<?php echo ($res["ANM"]); ?>">
            </li>
            <div class="tishi">提示:请严格按照以下格式来填写价格1=时间1-价格2=时间2。如1=2-2=4</div>
        <?php elseif($device_type == 2 or $device_type == 5): ?>
            <li><label  class="label">价格时间</label>
                <input type="text" id="charger" style="width:50%!important;" placeholder="请输入" value="<?php echo ($res["charger"]); ?>">
            </li>
            <div class="tishi">提示:请严格按照以下格式来填写价格1=时间1-价格2=时间2-价格3=时间3。如1=2-2=4-3=6</div><?php endif; ?>
    </ul>
    <button class="anniu" id="edit_name">确定修改</button>
</section>
<input type="hidden" id="ords" value="1">
<input type="hidden" id="device_type" value="<?php echo ($device_type); ?>">
<input type="hidden" id="group_id" value="<?php echo ($id); ?>">
<input type="hidden" id="device_code" value="<?php echo ($res["device_code"]); ?>">
<input type="hidden" id="group_wordk" value="A">
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
    $('#device_group_code').change(function(){
        var options=$("#device_group_code option:selected");  //获取选中的项
        $('#ords').val(options.val());
        $('#group_wordk').val(options.text());

    });
    Zepto(function($){
        $('#edit_name').tap(function(){
           // var device_group_code = $.trim($('#group_wordk').val());
            var ords = $.trim($('#ords').val());
            var device_group_id = $.trim($('#device_group_id').val());
            var group_id = $.trim($('#group_id').val());
            var srdata = $.trim($('#srdata').val());
            var charger = $.trim($('#charger').val());
            var device_type = $.trim($('#device_type').val());
            var group_word = $.trim($('.group_word').val());
            if(device_type == 3){
                var REG = {times:/^([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)$/};
                if(!REG.times.test(srdata)){
                    $.dialog({
                        content:'请严格按照格式填写',
                        button:['好']
                    });
                    return false;
                }
            }
            if(device_type == 2){
                var REG = {times:/^([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)$/};
                if(!REG.times.test(charger)){
                    $.dialog({
                        content:'请严格按照格式填写',
                        button:['好']
                    });
                    return false;
                }
            } else if(device_type == 5){
                var REG = {times:/^([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)-([1-9]\d*)=([1-9]\d*)$/};
                if(!REG.times.test(charger)){
                    $.dialog({
                        content:'请严格按照格式填写',
                        button:['好']
                    });
                    return false;
                }
            }
            var el=$.loading({
                content:'正在保存'
            });
            var DATA = {
                device_group_code:group_word,
                ords:ords,
                device_group_id:device_group_id,
                group_id:group_id,
                srdata:srdata,
                charger:charger
            };
            $.post("<?php echo U('update_device_group_detail');?>",DATA,function(data){
                if(data.msg==1){
                    var DG=$.dialog({
                        content:'恭喜您，保存成功！',
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href="<?php echo U('group_list',array('openid'=>$openid));?>";
                    });
                } else {
                    $.dialog({
                        content:'网络错误，请重试',
                        button:['好']
                    });
                }
                el.hide();
            },'json');
        })
    })
</script>
</html>