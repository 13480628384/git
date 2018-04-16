<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/report_forms.css">
    <title>我的充值记录</title>
</head>
<body>
<div class="report_forms">
    <div id="tabbox">
        <ul class="tabs" id="tabs">
            <div class="tabs_return" onclick='javascript:history.go(-1)'><img src="./tpl/Wap/default/img/left.png" alt="" width="30%"></div>
            <li class="thistab" style="width: 35%">我的充值记录</li>
            <li  style="width: 35%"><a href="<?php echo U('index',array('openid'=>$openid,'scan_code'=>$scan_code));?>">我的消费记录</a></li>
            <div class="clear"></div>
        </ul>
        <ul class="tab_conbox" id="tab_conbox">
            <li class="tab_con ">
                <div class="tab_date">
                    <div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_num1" style="background: #fff;color:#5BCC91;font-size: 1.5rem;">充值金额</div>
                    <div class="tab_dev1" style="width: 40%;text-align: center;background: #fff;color: #5BCC91;font-size: 1.5rem;">充值状态</div>
                    <div class="tab_ear1" style="width: 30%;background: #fff;color: #5BCC91;font-size: 1.5rem;">充值时间</div>
                </div>
                <div style="height: 0.5rem; background: #f2f2f2"></div>
                <?php if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_date">
                        <div class="tab_num1"> + <?php echo ($v["pay_account"]); ?></div>
                        <div class="tab_dev1" style="width: 40%;text-align: center;"><?php if($v['pay_status'] == 1): ?>成功<?php else: ?>失败<?php endif; ?></div>
                        <div class="tab_ear1" style="width: 30%"><?php echo ($v["create_date"]); ?></div>
                    </div>
                    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
        </ul>
    </div>
</div>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>

</script>
</html>