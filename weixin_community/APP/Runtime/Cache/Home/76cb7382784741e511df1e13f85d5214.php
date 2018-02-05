<?php if (!defined('THINK_PATH')) exit(); if($Month_Counity_Randing != null): ?><ul>
        <?php if(is_array($Month_Counity_Randing)): $k = 0; $__LIST__ = $Month_Counity_Randing;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($k <= 10): ?><li <?php if(isset($v['my_pai'])){echo "class='user'";} ?>>
                <span>NO.<?php echo ($v["user_count"]); ?></span> <img src="<?php if($v[headimgurl] == null): ?>__PUBLIC__/Home/img/bg.png<?php else: echo ($v["headimgurl"]); endif; ?>" alt="">
                <span class="userName"><?php echo ($v["nickname"]); ?></span>
                <div class="right">
                    总分 <span class="goal"><?php echo ($v["count"]); ?></span>
                </div>
                </li><?php endif; ?>
            <?php if(isset($v['my_pai']) && $k>10){ ?>
            <li <?php if(isset($v['my_pai'])){echo "class='user'";} ?>>
            <span>NO.<?php echo ($v["user_count"]); ?></span> <img src="<?php if($v[headimgurl] == null): ?>__PUBLIC__/Home/img/bg.png<?php else: echo ($v["headimgurl"]); endif; ?>" alt="">
            <span class="userName"><?php echo ($v["nickname"]); ?></span>
            <div class="right">
                总分 <span class="goal"><?php echo ($v["count"]); ?></span>
            </div>
            </li>
            <?php } endforeach; endif; else: echo "" ;endif; ?>
        <div style="clear:both"></div>
    </ul><?php endif; ?>