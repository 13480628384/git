<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
    <div class="tab_date">
        <div class="tab_ear1"><?php echo ($v["consume_account"]); ?></div>
        <div class="tab_ear1"><?php if($v['status'] == 1): ?>有效<?php else: ?>无效<?php endif; ?></div>
        <div class="tab_ear1"><?php echo ($v["device_command"]); ?></div>
        <div class="tab_ear1"><?php echo substr($v['create_date'],0,10) ?></div>
    </div>
    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>