<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/report_forms.css">
    <title>用户消费记录</title>
</head>
<body>
<!--微信用户消费记录-->
<div class="report_forms">
    <div id="tabbox">
        <ul class="tabs" id="tabs">
            <div class="tabs_return" onclick='javascript:history.go(-1)'><img src="./tpl/Wap/default/img/left.png" alt="" width="30%"></div>
            <li style="width: 25%;    font-size: 1rem;"><a href="<?php echo U('weixin_pay',array('openid'=>$openid));?>">我的充值记录</a></li>
            <li class="thistab"  style="width: 25%;    font-size: 1rem;">我的消费记录</li>
            <li style="width: 25%;    font-size: 1rem;">
                <a href="<?php echo U('user_consume',array('openid'=>$openid));?>">消费报表</a></li>
            <div class="clear"></div>
        </ul>
        <ul class="tab_conbox" id="tab_conbox">
            <li class="tab_con ">
                <div class="tab_date">
                    <div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_num1" style="background: #fff;color:#5BCC91;font-size: 1.5rem;">消费金额</div>
                    <div class="tab_dev1" style="width: 40%;text-align: center;background: #fff;color: #5BCC91;font-size: 1.5rem;">消费状态</div>
                    <div class="tab_ear1" style="width: 30%;background: #fff;color: #5BCC91;font-size: 1.5rem;">消费时间</div>
                </div>
                <div style="height: 0.5rem; background: #f2f2f2"></div>
                <?php if(is_array($balance)): $i = 0; $__LIST__ = $balance;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div style="height: 0.5rem; background: #f2f2f2"></div>
                    <div class="tab_date">
                        <div class="tab_num1"> - <?php echo ($v["consume_account"]); ?></div>
                        <div class="tab_dev1" style="width: 40%;text-align: center;">
                            <?php if($v['command_status'] == 2): ?>成功
                                <?php elseif($v['command_status'] == 1): ?>正在消费
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
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>
    var n=0;
    var stop=0;
    var timeOut = null;
    $(document).scroll(function(){
        if(stop==1){
            return false;
        }
        //滚动条顶部的偏移==总个文档的高度-窗口的高度
        var diff = Number($(window).height()) + Number(20);
        if($(document).height() - $(window).scrollTop() < diff){
            if(timeOut != null) {
                return false;
            }
            $('#now_add').css('display','block');
            n++;
            timeOut = setTimeout(function(){//1秒加载一次
                var openid = $('.openid').val();
                $.post('',{"n": n,openid:openid},function(html){
                    if($.trim(html).length>0){
                        $(html).appendTo('.tab_con');
                        timeOut=null;
                    }else{
                        $('#now_add').html('没有啦');
                        stop=1;
                    }
                },'html');
            },1000);
        }
    });
</script>
</html>