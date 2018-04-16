<?php if (!defined('THINK_PATH')) exit(); if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
    <div class="tab_date">
        <div class="tab_ear1"><?php echo substr($v['create_date'],10) ?></div>
        <div class="tab_ear1"><span><?php echo ($v["code"]); ?></span><p></p></div>
        <div class="tab_ear1"><span><?php echo ($v["group_word"]); ?></span><p></p></div>
        <div class="tab_ear1"><?php echo ($v["consume_account"]); ?>元</div>
        <div class="tab_ear1" ><span><?php if($v['consume_status'] == 1): ?>有效<?php else: ?>无效<?php endif; ?></span><p></p></div>
    </div>
    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>