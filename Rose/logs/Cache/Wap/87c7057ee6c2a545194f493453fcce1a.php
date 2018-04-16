<?php if (!defined('THINK_PATH')) exit(); if(is_array($alipay_list)): $i = 0; $__LIST__ = $alipay_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
        <td width="20%" align="center" valign="middle" class="style3"><?php if($v['type'] == 2): ?>支付宝消费
            <?php elseif($v['type'] == 4): ?>按摩椅消费
            <?php elseif($v['type'] == 6): ?>充电器消费
            <?php else: ?>其它<?php endif; ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo ($v["consume_account"]); ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo substr($v['create_date'],0,10) ?></td>
        <td width="20%" align="center" valign="middle" class="style2 style3"><?php if($v['consume_status'] == 1): ?>成功<?php else: ?>失败<?php endif; ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>