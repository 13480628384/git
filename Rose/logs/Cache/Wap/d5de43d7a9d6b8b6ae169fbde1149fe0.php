<?php if (!defined('THINK_PATH')) exit(); if(is_array($index)): $i = 0; $__LIST__ = $index;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
    <div class="tab_date">
        <div class="tab_ear1"><?php echo ($v["deivce_command"]); ?></div>
        <div class="tab_ear1"><span><?php echo ($v["consume_account"]); ?> 元</span><p></p></div>
        <div class="tab_ear1">
            <?php if($v['type'] == 1): ?>微信消费
                <?php elseif($v['type'] == 3): ?>按摩椅消费
                <?php elseif($v['type'] == 4): ?>按摩椅支付宝消费
                <?php elseif($v['type'] == 2): ?>支付宝消费
                <?php elseif($v['type'] == 5): ?>充电器消费
                <?php elseif($v['type'] == 6): ?>充电器支付宝消费
                <?php elseif($v['type'] == 9): ?>微信洗衣机消费
                <?php elseif($v['type'] == 10): ?>支付宝洗衣机消费
                <?php elseif($v['type'] == 15): ?>微信洗车
                <?php elseif($v['type'] == 16): ?>支付宝洗车
                <?php elseif($v['type'] == 13): ?>微信电动车消费
                <?php elseif($v['type'] == 14): ?>支付宝电动车消费
                <?php else: ?>其它<?php endif; ?>
        </div>
        <div class="tab_ear1">
            <?php if($v['command_status'] == 2): ?>成功消费
                <?php elseif($v['command_status'] == 1): ?>正在消费
                <?php elseif($v['command_status'] == 3): ?>已退币<?php endif; ?>
        </div>
        <div class="tab_ear1"><?php echo ($v["create_date"]); ?></div>
    </div>
    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>