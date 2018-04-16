<?php if (!defined('THINK_PATH')) exit(); if(is_array($adv_list)): $i = 0; $__LIST__ = $adv_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
        <td width="20%" align="center" valign="middle" class="style3"><?php if($v['type'] == 1): ?>订阅号<?php else: ?>服务号<?php endif; ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo ($v["per_price"]); ?></td>
        <td width="30%" align="center" valign="middle" class="style3"><?php echo substr($v['create_date'],0,10) ?></td>
        <td width="20%" align="center" valign="middle" class="style2 style3"><?php if($v['consume_status'] == 1): ?>无效<?php else: ?>有效<?php endif; ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>