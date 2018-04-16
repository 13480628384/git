<?php if (!defined('THINK_PATH')) exit(); if(is_array($index)): $i = 0; $__LIST__ = $index;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="tab_con month-year">
        <div class="years_tall">
            <span><?php echo ($v["year"]); ?>年-<?php echo ($v["month"]); ?>月</span>
            <br/><span>总收益&nbsp;&nbsp;<b class="count"><?php echo ($v["count"]); ?></b>（元）</span>
        </div>
        <div class="look-detail">
            <a href="<?php echo U('group_details_list',array('openid'=>$openid,'month'=>$v['month']));?>">查看明细</a>
        </div>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>