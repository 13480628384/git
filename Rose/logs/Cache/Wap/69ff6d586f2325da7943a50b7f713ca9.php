<?php if (!defined('THINK_PATH')) exit(); if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
    <div class="tab_date">
        <div class="tab_ear1"><?php echo ($v["code"]); ?></div>
        <div class="tab_ear1"><?php echo ($v["group_word"]); ?></div>
        <div class="tab_ear1"><span><?php echo ($v["count"]); ?> 元</span><p></p></div>
        <div class="tab_ear1"><a href="<?php echo U('today_group_device_deteil',array('openid'=>$openid,'di_id'=>$v['id']));?>">
            <img src="./tpl/Wap/default/img/r1.png" alt="" width="30%"></a></div>
    </div>
    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>