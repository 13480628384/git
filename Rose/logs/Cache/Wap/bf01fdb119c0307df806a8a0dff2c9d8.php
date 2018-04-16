<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/report_forms.css">
    <title>用户消费记录</title>
    <style>
        .tab_num1,.tab_dev1,.tab_ear1{
            width: 25%;
        }
        .report_forms .tabs .thistab, .tabs .thistab a:hover {
            border-bottom: 1px solid #f57789;
            color: #f57789;
            height: 3rem;
            line-height: 3rem;
            font-size: 2rem;
            font-family: 微软雅黑;
        }
        .tabs {
            border-top: solid 2px #f57789;
            padding-top: 1rem;
        }
        .tabs li{
            font-size: 16px!important;
            width: 40% !important;
        }
        .tabs_return{
            font-size: 16px!important;
        }
    </style>
</head>
<body>
<!--微信用户消费记录-->
<div class="report_forms">
    <div id="tabbox">
        <ul class="tabs" id="tabs">
            <div class="tabs_return">
                <a href="<?php echo U('index',array('openid'=>$openid,'device_code'=>$device_code));?>">
                    <!--<img src="./tpl/Wap/default/img/left.png" alt="" width="30%">-->返回</a></div>
            <li class="thistab">消费记录</li>
            <li><a href="<?php echo U('rose_pay',array('openid'=>$openid,'device_code'=>$device_code));?>">我的玫瑰</a></li>
            <div class="clear"></div>
        </ul>
        <ul class="tab_conbox" id="tab_conbox">
            <li class="tab_con ">
                <div class="tab_date">
                    <div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_num1" style="background: #fff;color:#f57789;font-size: 1.5rem;">购买商品</div>
                    <div class="tab_num1" style="background: #fff;color:#f57789;font-size: 1.5rem;">消费金额</div>
                    <div class="tab_dev1" style="text-align: center;background: #fff;color: #f57789;">消费状态</div>
                    <div class="tab_ear1" style="background: #fff;color: #f57789;font-size: 1.5rem;">消费时间</div>
                </div>
                <div style="height: 0.5rem; background: #f2f2f2"></div>
                <?php if(is_array($consume)): $i = 0; $__LIST__ = $consume;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_date">
                        <div class="tab_num1"> - <?php echo ($v["name"]); ?></div>
                        <div class="tab_num1"> - <?php echo ($v["consume_account"]); ?></div>
                        <div class="tab_dev1" style="width: 20%;text-align: center;">
                            <?php if($v['command_status'] == 2): ?>购买成功
                                <?php elseif($v['command_status'] == 1): ?>购买成功
                                <?php elseif($v['command_status'] == 3): ?>已退币<?php endif; ?>
                        </div>
                        <div class="tab_ear1" style="width: 30%"><?php echo ($v["create_date"]); ?></div>
                    </div>
                    <div style="height: 0.5rem; background: #f2f2f2"></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </li>
            <div id="now_add">正在玩命加载中...</div>
        </ul>
    </div>
</div>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
</html>