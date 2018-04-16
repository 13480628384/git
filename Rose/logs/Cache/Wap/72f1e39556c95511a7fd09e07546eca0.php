<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>消费情况</title>
    <style>
        .header{
            width: 100%;
            height: 40px;
            line-height: 40px;
            text-align: CENTER;
            background: #18b4ed;
            color: #fff;
            font-size: 16px;
        }
        .list li{
            width: 100%;
            background: #fff;
            padding: 4px;
            margin: auto;
            margin-top: 6px;
        }
    </style>
</head>
<body>
<div class="facility" style="margin-bottom: 80px;">
    <header class="header">每天消费数据</header>
    <ul class="list">
        <?php if(is_array($result)): $i = 0; $__LIST__ = $result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="subject_4 pbw">
                <div style="white-space:normal; ">
                    <p class="pl">消费时间：<?php echo ($v["create_date"]); ?> </p>
                    <b style="font-size: 16px;font-weight: 600;">消费金额：<?php echo $v['consume_account']?$v['consume_account']:'0'; ?></b><br/>
                    支付金额：<?php echo $v['pay_account']?$v['pay_account']:'0'; ?> <br/>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
</html>