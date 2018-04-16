<?php if (!defined('THINK_PATH')) exit(); if(is_array($weixin_list)): $i = 0; $__LIST__ = $weixin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
        <td width="20%" align="center" valign="middle" class="style3"><?php if($v['type'] == 1): ?>微信消费
            <?php elseif($v['type'] == 3): ?>按摩椅消费
            <?php elseif($v['type'] == 5): ?>充电器消费
            <?php else: ?>其它<?php endif; ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo ($v["consume_account"]); ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo substr($v['create_date'],0,10) ?></td>
        <td width="20%" align="center" valign="middle" class="style2 style3">
            <?php if($v['command_status'] == 2): ?>成功消费
                <?php elseif($v['command_status'] == 1): ?>正在消费
                <?php elseif($v['command_status'] == 3): ?>已退币<?php endif; ?>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>