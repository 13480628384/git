<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <title>用户列表</title>
</head>
<body>
<div class="facility" style="margin-bottom: 6rem;">
    <table width="100%" align="center" >
        <tr >
            <td width="12%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">姓名</td>
            <td width="22%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">电话</td>
            <td width="12%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">分成</td>
            <!--<td width="12%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">操作</td>-->
        </tr>
        <?php if($list != null): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr >
                    <td width="25%" align="center" valign="middle" class="style3"><?php echo ($v["name"]); ?></td>
                    <td width="25%" align="center" valign="middle" class="style3"><?php echo ($v["phone"]); ?></td>
                    <td width="25%" align="center" valign="middle" class="style3">
                        <?php if($v['is_into'] == 1): ?>是<?php else: ?>否<?php endif; ?>
                    </td>
                    <!--<td width="12%" align="center" valign="middle" class="style3">修改</td>-->
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
<script>

</script>
</html>