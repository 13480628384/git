<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>导流广告</title>
</head>
<body>
<div class="facility">
    <table width="100%" align="center" class="loading">
        <tr >
            <td width="35%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">标题</td>
            <td width="35%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">状态</td>
            <td width="15%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">点击数</td>
            <td width="15%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1 style2">展示数</td>
        </tr>
        <?php if(is_array($give_list)): $i = 0; $__LIST__ = $give_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                <td width="35%" align="center" valign="middle" class="style3"><?php echo str_substr($v['title'],6) ?></td>
                <td width="35%" align="center" valign="middle" class="style3">
                    <?php if($v['audit_status'] == 0): ?>未审核
                    <?php elseif($v['audit_status'] == 1): ?>审核通过
                    <?php else: ?>审核不通过<?php endif; ?>
                </td>
                <td width="15%" align="center" valign="middle" class="style3"><?php echo ($v["click_number"]); ?></td>
                <td width="15%" align="center" valign="middle" class="style2 style3"><?php echo ($v["show_number"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <div id="now_add">正在玩命加载中...</div>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<ul class="footer">
    <?php if($weixin_alipay_type == 'wechat'): ?><li data-url="<?php echo U('V_2WechantDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li>
        <?php else: ?>
        <li data-url="<?php echo U('V_2AlipayDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li><?php endif; ?><!--<li data-url="<?php echo U('space',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type));?>">玫瑰空间</li>-->
    <li data-url="<?php echo U('V_2Rose/quotient',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">生态商</li>
    <li data-url="<?php echo U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">我的</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    $("[data-url='"+url+"']").addClass('active');
</script>
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