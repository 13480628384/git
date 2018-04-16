<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>群组列表</title>
</head>
<body>
<div class="facility">
    <table width="100%" align="center" >
        <tr >
            <td width="24%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">群组名称</td>
            <td width="40%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">创建时间</td>
            <td width="20%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1 style2">操作</td>
        </tr>
        <?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr >
                <td width="24%" align="center" valign="middle" class="style3"><?php echo ($v["group_name"]); ?></td>
                <td width="40%" align="center" valign="middle" class="style3"><?php echo ($v["create_date"]); ?></td>
                <td width="20%" align="center" valign="middle" class="style2 style3">
                    <a href="<?php echo U('edit_group',array('openid'=>$openid,'id'=>$v['id'],'group_name'=>$v['group_name']));?>">修改</a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="<?php echo U('group_detail_list',array('openid'=>$openid,'id'=>$v['id']));?>">列表</a></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <div class="anniu"><a href="<?php echo U('add_group',array('openid'=>$openid));?>" style="color:#fff;">添加群组</a></div>
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