<html><head>
    <title>首页</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="Description" content="红家君助">
    <meta name="Keywords" content="红家君助">
    <link type="text/css" rel="stylesheet" href="{$Think.URL}__CSS__/frozen.css">
    <link type="text/css" rel="stylesheet" href="{$Think.URL}__CSS__/mobi.css?v2">

</head>
<body>
<header class="home-header">
    红家君助
</header>
<div class="main-img" onclick="">
    <img src="../../static/img/banner1.jpg" id="topimg"/>
</div>
<section class="home-plist mb10" id="selling">
    <header class="hp-header">
        <h5>红家推荐</h5>
    </header>
    <ul class="hp-item hp-item1">
        {volist name="shop" id="v"}
        <li class="pd0">
            <figure>
                <img src="{$v.img}" alt="" data-href="123">
            </figure>
            <div class="fn-home">
                <h5 class="hpi-h5 f17 color191919">{$v.title}</h5>

                <div class="hpi-block" style="">
                    <div class="block-title" style="  width: 100%;display: table;clear: both;">
                        <div style="text-align: center;width: 33.33%;box-sizing: border-box;float: left;"><span class="color2e2e2e">到访</span><span class="txt-mark1">￥123</span> </div>
                        <div style="text-align: center;width: 33.33%;box-sizing: border-box;float: left;"><span class="color2e2e2e">佣金</span><span class="txt-mark1">1</span></div>
                        <div class="fr" onclick="" >
                            <span class="jia"></span><span class="jia-size">推荐</span>
                        </div>
                    </div>
                    <div class="client-block">
                    </div>
                    <span  class="f12">其他</span>
                </div></div>
            <div class="fn-clear"></div>
        </li>
        {/volist}
    </ul>

</section>

<div class="space-100"></div>
<if condition="$user_id eq null">
    <a class="account-fixed account-fixed-bottom" href="1">立即注册&nbsp;»</a>
</if>
<menu class="ucenter-menu">
    <ul>
        <li class="active" data-href="#">
            <i class="icon-home"></i><br>首页
        </li>
        <if condition="$user_id neq null">
            <li data-href="{weikucms::U('customer',array('token'=>$token,'openid'=>$openid))}">
                <i class="icon-custom"></i><br>我的订单
            </li>
            <else/>
            <li data-href="{weikucms::U('zhuce',array('token'=>$token,'openid'=>$openid))}">
                <i class="icon-custom"></i><br>我的订单
            </li>
        </if>
        <if condition="$user_id neq null">
            <li data-href="{weikucms::U('center',array('token'=>$token,'openid'=>$openid))}">
                <i class="icon-profile"></i><br>个人中心
            </li>
            <else/>
            <li data-href="{weikucms::U('zhuce',array('token'=>$token,'openid'=>$openid))}">
                <i class="icon-profile"></i><br>个人中心
            </li>
        </if>
    </ul>
</menu>
<script src="{$Think.URL}__JS__/zepto.js"></script>
<script src="{$Think.URL}__JS__/frozen.js"></script>
<script src="{$Think.URL}__JS__/base.js"></script>
<script src="{$Think.URL}__JS__/jweixin.js"></script>
</body>
</html>