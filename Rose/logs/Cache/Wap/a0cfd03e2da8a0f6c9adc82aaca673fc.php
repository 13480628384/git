<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/del_vip.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <title>商品管理</title>
    <style>
        .ui-header, .ui-footer {
            position: fixed;
            width: 100%;
            z-index: 100;
            left: 0;
        }
        .ui-header h1 {
            text-align: center;
            font-size: 18px;
        }
        .ui-header {
            top: 0;
            height: 45px;
            line-height: 45px;
        }
        .ui-header-positive, .ui-footer-positive {
            background-color: #30BF75;
            color: #fff;
        }
        .ui-header~.ui-container {
            border-top: 70px solid transparent;
        }
        .ui-list {
            background-color: #fff;
            width: 100%;
        }
        .ui-list>li {
            border-top: 1px solid #e0e0e0;
            position: relative;
            margin-left: 15px;
            display: -webkit-box;
        }
        .ui-footer {
            bottom: 0;
            height: 56px;
        }
        .ui-footer-btn {
            background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,#f9f9f9),to(#e0e0e0));
            color: #00a5e0;
        }
        .ui-footer {
            position: fixed;
            width: 100%;
            z-index: 100;
            left: 0;
            text-align: center;
            line-height: 56px;
            color: #30BF75;
        }
    </style>
</head>
<body style="background: #f2f2f2">
<header class="ui-header ui-header-positive ui-border-b">
    <h1>商品管理</h1>
</header>
<section class="ui-container" style="padding-bottom:55px;">
    <ul class="ui-list ui-list-link ui-border-tb">
        <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="ui-border-t" onclick="javascript:window.location.href='<?php echo U('update_shop',array('id'=>$v['id']));?>'">
                <div class="ui-list-img">
                    <span class="ui-tag-new" style="background-image:url(<?php echo ($v["image"]); ?>)"></span>
                </div>
                <div class="ui-list-info">
                    <h4 class="ui-nowrap"><?php echo ($v["name"]); ?></h4>
                    <p class="ui-nowrap"><?php echo ($v["remarks"]); ?></p>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</section>
<footer class="ui-footer ui-footer-btn">
    新增商品
</footer>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>

</body>
<script>
    $('.ui-footer-btn').click(function(){
        window.location.href="<?php echo U('add_shop');?>";
    })
</script>
</html>