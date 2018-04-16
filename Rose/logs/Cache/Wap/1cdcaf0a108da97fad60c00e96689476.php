<?php if (!defined('THINK_PATH')) exit(); if(is_array($line_list)): $i = 0; $__LIST__ = $line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
        <td width="25%" align="center" valign="middle" class="style3">线下</td>
        <td width="25%" align="center" valign="middle" class="style3"><?php echo ($v["LOC_OP"]); ?></td>
        <td width="25%" align="center" valign="middle" class="style3"><?php echo substr($v['create_date'],0,10) ?></td>
        <td width="25%" align="center" valign="middle" class="style2 style3"><?php echo ($v["dev_id"]); ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>