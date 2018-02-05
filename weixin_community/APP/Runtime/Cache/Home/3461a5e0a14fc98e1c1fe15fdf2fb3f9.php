<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-CN" xml:lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>玩家中心</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/person_style.css?v1.1" />
</head>
<body>
<div class="top">
    <div class="head-portrait"><img src="<?php echo ($personal["headimgurl"]); ?>" alt=""></div>
    <div class="base-infor">
        <p>昵称：<?php echo str_substr($personal['nickname'],3); ?></p>
        <p>积分：<?php echo ($personal["user_integral"]); ?></p>
    </div>
    <div class="set-up" onclick="window.location.href='<?php echo U('update',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'"><img src="__PUBLIC__/Home/img/set1.png" alt=""></div>
    <div class="top-bottom">
        <input type="button" value="领取记录" onclick="window.location.href='http://tt.7i1.cn/doubi/money_voucher.php?openid=<?php echo ($openid); ?>'">
        <input type="button" value="消费记录" onclick="window.location.href='http://tt.7i1.cn/doubi/money_evidence_record.php?openid=<?php echo ($openid); ?>'">
    </div>
</div>
<div style="clear:both"></div>
<div class="title">
    <hr class="hr1">
    <span class="mid">我的排名</span>
    <hr class="hr2">
</div>
<div style="clear:both"></div>
<div class="date">
    <ul>
        <li id="date" class="date"><a href="#">日</a></li>
        <li id="week"><a href="#">周</a></li>
        <li id="month"><a href="#">月</a></li>
    </ul>
</div>
<div style="clear:both"></div>
<div class="area area-date"   style="display:block">
    <ul>
        <li id="single1"><a href="#">单台</a></li>
        <li id="region1"><a href="#">全区</a></li>
        <li id="country1"><a href="#">全国</a></li>
    </ul>
</div>
<div class="area area-week"   style="display:none">
    <ul>
        <li id="single2"><a href="#">单台</a></li>
        <li id="region2"><a href="#">全区</a></li>
        <li id="country2"><a href="#">全国</a></li>
    </ul>
</div>
<div class="area area-month"   style="display:none">
    <ul>
        <li id="single3"><a href="#">单台</a></li>
        <li id="region3"><a href="#">全区</a></li>
        <li id="country3"><a href="#">全国</a></li>
    </ul>
</div>
<div class="rank" id="date-single">
    <?php if($my_ranking != null): ?><ul>
        <?php if(is_array($my_ranking)): $k = 0; $__LIST__ = $my_ranking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($k <= 10): ?><li <?php if(isset($v['my_pai'])){echo "class='user'";} ?>>
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
</div>
<!--单日全区 [[-->
<div class="rank" id="date-region" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--单日全区 ]]-->

<!--单日全国 [[-->
<div class="rank" id="date-country" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--单日全国 ]]-->

<!--一周的今天 [[-->
<div class="rank" id="week-single" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--一周的今天 ]]-->

<!--一周的全区 [[-->
<div class="rank" id="week-region" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--一周的全区 ]]-->

<!--一周的全国 [[-->
<div class="rank" id="week-country" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--一周的全国 ]]-->

<!--月的单机 [[-->
<div class="rank" id="month-single" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--月的单机 ]]-->

<!--月的全区 [[-->
<div class="rank" id="month-region" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--月的全区 ]]-->

<!--月的全国 ]]-->
<div class="rank" id="month-country" style="display:none">
    <img src="__PUBLIC__/Home/img/tail-spin.svg" class="tail_spin" style="display: none">
    <span class="nonumber" style="display: none"></span>
</div>
<!--月的全国 ]]-->

<div style="clear:both"></div>
<div class="title">
    <hr class="hr1">
    <span class="mid">我的徽章</span>
    <hr class="hr2">
</div>
<div style="clear:both"></div>
<div class="badge">
    <ul>
        <li>
            <img src="__PUBLIC__/Home/img/badge1.png" alt="">
        </li>
        <li>
            <img src="__PUBLIC__/Home/img/badge2.png" alt="">
        </li>
        <li>
            <img src="__PUBLIC__/Home/img/badge3.png" alt="">
        </li>
        <li>
            <img src="__PUBLIC__/Home/img/badge4.png" alt="">
        </li>
        <li>
            <img src="__PUBLIC__/Home/img/badge5.png" alt="">
        </li>
    </ul>
</div>
<div style="clear:both"></div>
<div class="title">
    <hr class="hr1">
    <span class="mid">我的发布</span>
    <hr class="hr2">
</div>
<div style="clear:both"></div>
<div class="publish">
    <ul>
        <?php if(is_array($community)): $i = 0; $__LIST__ = $community;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
                <img src="<?php echo ($v["imgs"]); ?>" alt="">
                <b><?php echo ($v["text"]); ?></b>
                <!--<span>评论<?php echo ($v["community_total"]); ?></span>-->
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div style="clear:both"></div>
<div class="shadow"></div>
<footer>
    <ul>
        <li id="footer1" onclick="window.location.href='<?php echo U('Index/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
            <img src="__PUBLIC__/Home/img/icon_30.png" alt="">
            <br/>微信支付
        </li>
        <li id="footer2" >
            <img src="__PUBLIC__/Home/img/icon-p.png" alt="">
            <br/>玩家中心
        </li>
        <li id="footer3" onclick="window.location.href='<?php echo U('Nomoney/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
            <img src="__PUBLIC__/Home/img/icon_16.png" alt="">
            <br/>免费逗币
        </li>
        <li id="footer4" onclick="window.location.href='<?php echo U('Community/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
            <img src="__PUBLIC__/Home/img/icon_17.png" alt="">
            <br/>根据地
        </li>
    </ul>
</footer>
</body>
<input class="Now_Region_Randing" value="<?php echo U('Randing/Now_Region_Randing');?>" type="hidden">
<input class="Now_Counity_Randing" value="<?php echo U('Randing/Now_Counity_Randing');?>" type="hidden">
<input class="Week_Stand_alone_Randing" value="<?php echo U('Randing/Week_Stand_alone_Randing');?>" type="hidden">
<input class="Week_Region_Randing" value="<?php echo U('Randing/Week_Region_Randing');?>" type="hidden">
<input class="Week_Counity_Randing" value="<?php echo U('Randing/Week_Counity_Randing');?>" type="hidden">
<input class="Month_Stand_alone_Randing" value="<?php echo U('Randing/Month_Stand_alone_Randing');?>" type="hidden">
<input class="Month_Region_Randing" value="<?php echo U('Randing/Month_Region_Randing');?>" type="hidden">
<input class="Month_Counity_Randing" value="<?php echo U('Randing/Month_Counity_Randing');?>" type="hidden">
<input class="group_id" value="<?php echo ($group_id); ?>" type="hidden">
<input class="device_command" value="<?php echo ($device_command); ?>" type="hidden">
<input class="group_id" value="<?php echo ($group_id); ?>" type="hidden">
<input class="openid" value="<?php echo ($openid); ?>" type="hidden">
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/randing.js?v1.0"></script>
<script>
    /*var useragent = navigator.userAgent;
     if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
     var opened = window.open('about:blank', '_self');
     opened.opener = null;
     opened.close();
     }*/
</script>
</html>