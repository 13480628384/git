<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-CN" xml:lang="zh-CN">
<head>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta charset="UTF-8" />
<title>免费逗币中心</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/style_nomoney.css?v1.0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
</head>
<body>
<input type="button" class="top" value="推荐商家赚逗币" onclick="location.href='http://tt.7i1.cn/doubi/money_user_openid.php'">
<?php if(!empty($doubi)){ ?>
<div class="top-bottom">免费逗币</div>
<div class="user-list">
    <ul>
        <?php if(is_array($doubi)): $i = 0; $__LIST__ = $doubi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="lingquan" merchantid="<?php echo ($v["merchant_id"]); ?>" quantity="<?php echo ($v["quantity"]); ?>" ccid="<?php echo ($v["id"]); ?>">
            <div class="title">
                <div class="b-title"><?php echo ($v["brand_name"]); ?></div>
                <div class="s-title"><?php echo ($v["title"]); ?></div>
                <div class="star-level"><?php echo ($v["star_level"]); ?></div>
            </div>
            <div class="describe-click">
                <div class="describe"><span><?php echo ($v["description"]); ?></span></div>
                <!--<div class="click">点击量10</div>-->
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <div style="clear:both"></div>
</div>
<?php } ?>
<div style="clear:both"></div>
<?php if(!empty($ads)){ ?>
 <div class="mid-top">消费送逗币</div>
 <div style="clear:both"></div>
<div class="goods-list">
       <ul>
        <?php foreach($ads as $k=>$va){ $dou = trim($va[area_id],','); $area_ids = explode(',',$dou); foreach($area_ids as $v){ if(in_array($v,$area_ids)){ ?>
            <li onclick="location.href='http://tt.7i1.cn/doubi/money_dpay_index.php?id=<?php echo $va[id];?>&money=<?php echo intval($va[price]);?>&merchant_id=<?php echo $va[merchant_id];?>'">
              <img src="<?php echo $va['adv_img']; ?>">
              <div class="intro"><?php echo $va['title']; ?></div>
              <div class="cash"><img src="__PUBLIC__/Home/img/icon-cash.png" alt=""><span><?php echo intval($va[price]); ?></span></div>
              <div class="pay-infor">
                  <div class="give">(送(<?php echo intval($va[price]); ?>)逗币)</div>
                  <div class="pay-count">125<?php echo $k+1; ?>人付款</div>
              </div>
            </li>
        <?php }}} ?>
       </ul>
</div>
<?php } ?>
<div style="clear:both"></div>
<div class="show"></div>
<footer style="border-top: 1px solid #ccc;">
    <ul>
        <li id="footer1" onclick="window.location.href='<?php echo U('Index/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
          <img src="__PUBLIC__/Home/img/icon_30.png" alt="">
          <br/>微信支付
        </li>
        <li id="footer2" onclick="window.location.href='<?php echo U('Personal/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
          <img src="__PUBLIC__/Home/img/icon-p1.png" alt="">
          <br/>玩家中心
        </li>
        <li id="footer3">
          <img src="__PUBLIC__/Home/img/icon_26.png" alt="">
          <br/>免费逗币
        </li>
        <li id="footer4" onclick="window.location.href='<?php echo U('Community/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'">
          <img src="__PUBLIC__/Home/img/icon_17.png" alt="">
          <br/>根据地
        </li>
    </ul>
</footer>
<input class="insert_doubi" value="<?php echo U(insert_doubi);?>" type="hidden">
<input class="openId" value="<?php echo ($openid); ?>" type="hidden">
<input class="device_command" value="<?php echo ($device_command); ?>" type="hidden">
</body>
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/nomoney.js"></script>
<script>
    var useragent = navigator.userAgent;
     if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
     var opened = window.open('about:blank', '_self');
     opened.opener = null;
     opened.close();
     }
</script>
</html>