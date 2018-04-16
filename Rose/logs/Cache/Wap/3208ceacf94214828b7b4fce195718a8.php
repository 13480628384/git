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
<div class="facility">
    <table width="100%" align="center" >
        <tr >
            <td width="14.6%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">编号</td>
            <td width="24.6%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">设备编码</td>
            <td width="14.6%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">状态</td>
            <td width="14.6%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">类型</td>
            <td width="14.6%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1 style2">操作</td>
        </tr>
        <?php if($res != null): if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr >
                    <td width="14.6%" align="center" valign="middle" class="style3"><?php echo ($v["group_word"]); ?></td>
                    <td width="24.6%" align="center" valign="middle" class="style3"><?php echo ($v["device_code"]); ?></td>
                    <td width="14.6%" align="center" valign="middle" class="style3">
                        <?php if($v['online_status'] == 1): ?>在线<?php else: ?>离线<?php endif; ?></td>
                    <td width="14.6%" align="center" valign="middle" class="style3">
                        <?php if($v['device_type'] == 4): ?>按摩椅
                            <?php elseif($v['device_type'] == 1): ?>娃娃机
                            <?php elseif($v['device_type'] == 2): ?>充电器
                            <?php elseif($v['device_type'] == 3): ?>售货机
                            <?php elseif($v['device_type'] == 4): ?>按摩椅
                            <?php elseif($v['device_type'] == 5): ?>洗衣机
                            <?php elseif($v['device_type'] == 6): ?>电动车
                            <?php elseif($v['device_type'] == 7): ?>洗车
                            <?php elseif($v['device_type'] == 8): ?>厕纸<?php endif; ?>
                    </td>
                    <td width="14.6%" align="center" valign="middle" class="style2 style3">
                        <a href="<?php echo U('update_group_detail_list',array('openid'=>$openid,'id'=>$v['id'],'device_type'=>$v['device_type']));?>">修改</a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </table>
</div>
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
</html>