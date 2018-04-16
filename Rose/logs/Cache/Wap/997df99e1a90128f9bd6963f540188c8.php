<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>微信收益列表</title>
</head>
<body>
<div class="facility">
    <table width="100%" align="center" class="loading">
        <tr >
            <td width="16%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">类型</td>
            <td width="16%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">金额</td>
            <td width="38%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">时间</td>
            <td width="20%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1 style2">状态</td>
        </tr>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                <td width="16%" align="center" valign="middle" class="style3">微信收益</td>
                <td width="16%" align="center" valign="middle" class="style3"><?php echo ($v["consume_account"]); ?></td>
                <td width="38%" align="center" valign="middle" class="style3"><?php echo ($v["create_date"]); ?></td>
                <td width="20%" align="center" valign="middle" class="style2 style3">
                    <?php if($v['command_status'] == 2): ?>成功消费
                        <?php elseif($v['command_status'] == 1): ?>正在消费
                        <?php elseif($v['command_status'] == 3): ?>已退币<?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <!--<div id="now_add">正在玩命加载中...</div>-->
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>
    var n=0;
    var stop=0;
    var timeOut = null;
    $(document).scroll(function(){
        if(stop==1){
            return false;
        }
        //滚动条顶部的偏移==总个文档的高度-窗口的高度
        var diff = Number($(window).height()) + Number(20);
        if($(document).height() - $(window).scrollTop() < diff){
            if(timeOut != null) {
                return false;
            }
            $('#now_add').css('display','block');
            n++;
            timeOut = setTimeout(function(){//1秒加载一次
                $.post('',{"n": n},function(html){
                    if($.trim(html).length>0){
                        $(html).appendTo('.loading');
                        timeOut=null;
                    }else{
                        $('#now_add').html('没有啦');
                        stop=1;
                    }
                },'html');
            },1000);
        }
    });
</script>
</html>