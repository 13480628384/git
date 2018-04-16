<?php if (!defined('THINK_PATH')) exit(); if(is_array($present_list)): $i = 0; $__LIST__ = $present_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
        <td width="20%" align="center" valign="middle" class="style3">
            <?php if($v['type'] == 1): ?>广告提现<?php elseif($v['type'] == 0): ?>
                余额提现<?php elseif($v['type'] == 2): ?>按摩提现<?php endif; ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo ($v["arrival"]); ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo substr($v['create_date'],0,10) ?></td>
        <td width="20%" align="center" valign="middle" class="style2 style3">
            <?php if($v['status'] == 1): ?>成功<?php else: ?>失败<?php endif; ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>