<?php if (!defined('THINK_PATH')) exit(); if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
    <div class="tab_date">
        <div class="tab_num1"> + <?php echo ($v["total_amount"]); ?></div>
        <div class="tab_dev1" style="width: 40%;text-align: center;"><?php if($v['trade_status'] == 'TRADE_SUCCESS'): ?>成功<?php else: ?>失败<?php endif; ?></div>
        <div class="tab_ear1" style="width: 30%"><?php echo ($v["create_date"]); ?></div>
    </div>
    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>