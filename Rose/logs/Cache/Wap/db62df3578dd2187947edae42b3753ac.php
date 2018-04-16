<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>退款记录列表</title>
    <style>
    </style>
</head>
<body>
<div class="facility">
    <table width="100%" align="center" >
        <tr >
            <td width="30%" height="73" align="center" valign="middle" bgcolor="#f57789" class="style1">金额</td>
            <td width="30%" height="73" align="center" valign="middle" bgcolor="#f57789" class="style1">申请时间</td>
            <td width="20%" height="73" align="center" valign="middle" bgcolor="#f57789" class="style1 style2">状态</td>
        </tr>
        <?php if(is_array($present_list)): $i = 0; $__LIST__ = $present_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                <td width="20%" align="center" valign="middle" class="style3">
                    <?php echo ($v["total"]); ?></td>
                <td width="30%" align="center" valign="middle" class="style3"><?php echo ($v["apple_time"]); ?></td>
               <td width="20%" align="center" valign="middle" class="style2 style3">
                   <?php if($v['status'] == 0): ?>未审核<?php elseif($v['status'] == 1): ?>
                       审核通过<?php elseif($v['status'] == 2): ?>审核失败<?php endif; ?>
               </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
</html>