<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css?v1">
    <link rel="stylesheet" href="./tpl/Wap/default/css/game.css?v22">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>玫瑰云网物联终端</title>
</head>
<body>
<!-- 游戏游艺 -->
<div class="game">
    <div class="game_top">
        <div class="game_top_left">
            <img class="game_top_left_img" src="./tpl/Wap/default/img/reg_1.png" alt="">
            <p><img src="./tpl/Wap/default/img/reg_2.png" alt=""><span class="weixin_count"><?php echo ($total_count); ?></span></p>
            <p style="border-bottom: none;"><img src="./tpl/Wap/default/img/reg_3.png" alt=""><span class="rose_count"><?php echo intval($co); ?></span></p>
        </div>
        <div class="game_top_right">
            <ul>
                <li class="imgchang"><span>1元</span></li>
                <li><span>2元</span></li>
                <li><span>5元</span></li>
                <li><span>10元</span></li>
                <li><span>20元</span></li>
                <li><span>50元</span></li>
                <li><span>100元</span></li>
                <p class="recharge"><img src="./tpl/Wap/default/img/an8.gif" alt="立即充值"></p>
                <p class="consume"><img src="./tpl/Wap/default/img/an9.gif" alt="消费记录"></p>
            </ul>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="height20"></div>
    <!--广告滚动 -->
    <div class="game_gg">
        <div id="oDiv">
            <ul id="oUl">
                <li>深圳玫瑰物联</li>
            </ul>
        </div>
    </div>
    <div class="height20"></div>
    <div class="game_start">
        <div class="game_start1 game_start2 game_start_click1">余额 · 启动</div>
        <div class="game_start1 game_start3 game_start_click2">玫瑰 · 启动</div>
        <div class="game_start1 game_start3">选择字母,点击启动设备          </div>
        <div class="game_start1 game_start3">1元=10玫瑰</div>

        <div class="game_start_show_doll game_start_show1 game_start_show">
            <ul>
                <?php if(is_array($device_relation_group)): $i = 0; $__LIST__ = $device_relation_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li data_device_command="<?php echo ($v["device_command"]); ?>" daatadi_id="<?php echo ($v["di_id"]); ?>"
                        data_payice="<?php echo ($v["pay_price"]); ?>" data_group_word="<?php echo ($v["group_word"]); ?>" cd="<?php echo ($v["group_word"]); ?>"
                    <?php if($v['on'] == 1): ?>class="game_start_show_li"<?php endif; ?>>
                    <tt id="zimu<?php echo ($v["group_word"]); ?>" style="display: none">0</tt>
                    <p><?php echo ($v["group_word"]); ?></p><span><?php echo ($v["pay_price"]); ?>元</span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear"></div>
            </ul>
        </div>


        <div class="game_start_show game_start_hide">
            <ul>
                <?php if(is_array($device_relation_group)): $i = 0; $__LIST__ = $device_relation_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li data_device_command="<?php echo ($v["device_command"]); ?>" daatadi_id="<?php echo ($v["di_id"]); ?>" cd="<?php echo ($v["group_word"]); ?>"
                        data_payice="<?php echo $v['pay_price']*10; ?>" data_group_word="<?php echo ($v["group_word"]); ?>" <?php if($v['on'] == 1): ?>class="game_start_show_li"<?php endif; ?>>
                    <tt class="zimu<?php echo ($v["group_word"]); ?>" style="display: none">0</tt>
                    <p><?php echo ($v["group_word"]); ?></p><span><?php echo $v['pay_price']*10; ?>玫瑰</span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear"></div>
            </ul>
        </div>

    </div>
    <div class="game_end">
        <?php if(is_array($rose_adv)): $k = 0; $__LIST__ = $rose_adv;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($v['count'] == 2): ?><p class="startp1 startp2" dataid="<?php echo ($v["id"]); ?>" dataurl="<?php echo ($v["url"]); ?>"><?php echo ($v["title"]); ?></p><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <img  class="help" src="./tpl/Wap/default/img/help.png" alt="">
    <div class="game_end2">
        <p>温馨提示:若扣余额未启动,3分钟内自动退币。</p>
    </div>
</div>
</body>
<input type="hidden" class="buyer_id" value="<?php echo ($buyer_id); ?>">
<input type="hidden" class="inok" value="<?php echo ($inok); ?>">
<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" class="alipay_pay" value="<?php echo U('Alipay/alipay_pay');?>">
<input type="hidden" class="send_device_command" value="<?php echo U('send_device_command');?>">
<input type="hidden" class="send_rose_device_command" value="<?php echo U('send_rose_device_command');?>">
<input type="hidden" class="online_status" value="<?php echo U('V_2WechantDollMachine/online_status');?>">
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/common.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/alipay_pay.js?42151"></script>
<script>
    $('.consume').click(function(){
        window.location.href="<?php echo U('WeixinUserConsume/alipay_index',array('buyer_id'=>$buyer_id));?>";
    });
    $('.startp1').click(function(){
        if($(this).hasClass('on')){
            return false;
        }
        var id = $(this).attr('dataid');
        var dataurl = $(this).attr('dataurl');
        var user_id = "<?php echo ($user_id); ?>";
        $(this).addClass('on');
        $.post("<?php echo U('V_2RoseAjax/add_adv');?>",{id:id,user_id:user_id},function(data){
            if(data.code==200){
                window.location.href=dataurl;
            }else{
                alert('此广告无效');
            }
            $('.startp1').removeClass('on');
        },'json')
    });
</script>
</html>