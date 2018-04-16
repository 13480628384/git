<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>售货机商城</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="tpl/Wap/default/css/me.css?123">
    <link rel="stylesheet" href="tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="tpl/Wap/default/css/mobi.css">
</head>
<style>

    .indent-tab-title {
        margin-top: inherit;
        background-color: #fff;
        margin-bottom: 10px;
        /* border-top: 1px solid #e6e6e6; */
        border-bottom: 1px solid #e6e6e6;
    }
    .size-right{
        display: table;
        clear: both;
        width: 100%;
    }
    .commodity1{
        border-bottom: 1px solid #e6e6e6;

    }
    .order-tab-main-content-right1{
        display: -webkit-box;
        padding: 10px;
    }
    .order-tab-main-content-left1{
        border-bottom: 1px solid #e6e6e6;
    }
    .order-tab-main-content-right-money1 {
        margin-left:10px;
        color: #f03e3e;
        font-size: 14px;
        text-align: left;
        -webkit-box-flex:1;
    }
    .leftd{
        float: left;
        margin-left: 10px;
        line-height: 54px;
    }
    .headers{
        line-height: 38px;
    }
    .headers li{
        float: left;
        width: 49%;
        text-align: center;
        border-right:1px solid #ccc
    }
    .right{
        float: right;
        font-size: 18px;
        clear: both;
    }
    .addvideo {
        position: fixed;
        left: 0;
        width: 100%;
        z-index: 100;
        bottom: 0;
    }
    .addvideo li{
        height: 50px;
        border-bottom: 1px solid #f0ad4e;
        color: #666;
        text-align: center;
        font-size: 18px;
        line-height: 50px;
        background: #fff;
    }
    .f-overlay {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0,0,0,0.5);
        width: 100%;
        height: 100%;
        z-index: 99;
        display: none;
    }
    .imgs img{
        width: 100%;
        max-height: 300px;
        min-height: 100px;
        margin-left: initial;
        margin-right: initial;
    }
    .refund{
        color: #d33838;
        width: 100%;
        height: 72px;
        text-align: center;
        position: fixed;
        bottom: 0;
        letter-spacing: 1px;
        font-size: 12px;
    }
</style>
<body>
<div class="headers">
    <ul>
        <li>玫瑰：<?php echo ($rose); ?>枚</li>
        <li style="border: none">余额：<?php echo ($count); ?>元</li>
    </ul>
</div>
<div class="f-overlay"></div>
<!--微信支付和消费记录-->
<div class="addvideo" style="display: none;">
    <ul>
        <li><a href="<?php echo U('weixin_pay',array('openid'=>$openid,'device_code'=>$res['device_code']));?>">我的支付记录</a></li>
        <li style="border: none">
            <a href="<?php echo U('weixin_consume',array('openid'=>$openid,'device_code'=>$res['device_code']));?>">我的消费记录</a></li>
    </ul>
</div>
<?php if($shop == null): ?><div style="text-align: center;    margin-top: 50%;">该售货机还没有添加商品,请联系工作人员</div><?php endif; ?>
<!--微信支付和消费记录-->
<div class="indent-tab">
    <ul class="indent-tab-ul">
        <li>
            <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['id'] != null): ?><div class="indent-tab-title">
                    <div class="commodity1">
                        <div class="order-tab-main-content-left1 imgs">
                            <?php if($v['image'] != null): ?><img src="<?php echo ($v["image"]); ?>" /><?php endif; ?>
                        </div>
                        <div class="order-tab-main-content-right1">
                            <div class="order-tab-main-content-right-size">
                                <?php echo ($v["name"]); ?>
                            </div>
                            <div class="order-tab-main-content-right-money1 clearfix">
                                ￥<?php echo ($v["shop_price"]); ?>
                            </div>
                            <div class="tesxt-right clearfix" style="color: #999;">
                                通道【<?php echo ($v["number_order"]); ?>】
                            </div>
                        </div>
                    </div>
                    <div class="size-right" shop_number="<?php echo ($v["shop_number"]); ?>">
                        <span class="leftd">
                            <?php if($v['status'] == 0): ?><tt style="color:#f00">已停用</tt>
                                <?php elseif($v['status'] == 1): ?><tt style="color:#59dc7d">有货</tt>
                            <?php elseif($v['status'] == 2): ?><tt style="color:#f00">缺货</tt>
                            <?php elseif($v['status'] == 3): ?><tt style="color:#f00">错误</tt>
                            <?php elseif($v['status'] == 4): ?><tt style="color:#f00">正在忙</tt>
                            </else>其他问题<?php endif; ?>
                            余货：<?php echo ($v["shop_number"]); ?>
                        </span>
                        <span class="indent-btn buy_goods" number_order="<?php echo ($v["number_order"]); ?>" shopid="<?php echo ($v["id"]); ?>" dataprice="<?php echo ($v["shop_price"]); ?>" dataid="<?php echo ($v["gid"]); ?>" datastatus="<?php echo ($v["status"]); ?>" style="float:right" >
                            <a>
                                <span class="colorf57789">余额购买</span>
                            </a>
                        </span>
                        <span class="indent-btn rose_buy" number_order="<?php echo ($v["number_order"]); ?>" shopid="<?php echo ($v["id"]); ?>" dataprice="<?php echo ($v["shop_price"]); ?>" dataid="<?php echo ($v["gid"]); ?>" datastatus="<?php echo ($v["status"]); ?>" style="float:right" >
                            <a><span class="colorf57789">玫瑰购买</span></a>
                        </span>
                    </div>
                </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </li>
    </ul>
</div>
<div style="height: 48px;"></div>
<div class="refund">若出货失败，将在10分钟内退回到余额中</div>
<div class="balance" style="background-color: #D33838; color: #FFF;width: 100%; height: 48px; text-align: center;line-height: 48px; position: fixed; bottom: 0px;  letter-spacing: 1px;font-size:14px">
    去充值
</div>
<input type="hidden" id="balance_recharge" value="<?php echo U('balance_recharge');?>">
<input type="hidden" id="buy_goods" value="<?php echo U('buy_goods');?>">
<input type="hidden" id="rose_goods" value="<?php echo U('rose_goods');?>">
<input type="hidden" id="openid" value="<?php echo ($openid); ?>">
<input type="hidden" id="device_code" value="<?php echo ($res["device_code"]); ?>">
<input type="hidden" id="owner_id" value="<?php echo ($res["owner_id"]); ?>">
<input type="hidden" id="count" value="<?php echo ($count); ?>">
<input type="hidden" id="rose" value="<?php echo ($rose); ?>">
<script src="tpl/Wap/default/js/zepto.js"></script>
<script src="tpl/Wap/default/js/frozen.js"></script>
<script src="tpl/Wap/default/js/jquery.min.js"></script>
</body>
<script type="text/javascript">
    $('.balance').click(function () {
        var balance_recharge = $('#balance_recharge').val();
        var openid = $('#openid').val();
        var device_code = $('#device_code').val();
        var owner_id = $('#owner_id').val();
        var count = $('#count').val();
        window.location.href=balance_recharge+'&openid='+openid+'&device_code='+device_code+'&owner_id='+owner_id+'&count='+count;
    });
    $(function () {
        //点击弹出层
        $('.right').click(function () {
            $('.addvideo').show();
            $('.f-overlay').show();
        });
        $('.f-overlay').click(function () {
            $('.f-overlay').hide();
            $('.addvideo').hide();
        });
    })
    Zepto(function($){
       //购买商品 start
        $('.buy_goods').tap(function(){
            if(confirm('确定购买吗')){
                var openid = $('#openid').val();
                var device_code = $('#device_code').val();
                var owner_id = $('#owner_id').val();
                var count = parseInt($('#count').val());
                var buy_goods = $('#buy_goods').val();
                var gid = $(this).attr('dataid');
                var price = $(this).attr('dataprice');
                var status = $(this).attr('datastatus');
                var shopid = $(this).attr('shopid');
                var number_order = $(this).attr('number_order');
                if(count <= 0){
                    $.dialog({
                        content: '余额不足，请先充值',
                        button: ['ok']
                    });
                    return false;
                }
                if(parseInt($(this).parent().attr('shop_number')) <= 0){
                    $.dialog({
                        content: '该通道没商品了',
                        button: ['ok']
                    });
                    return false;
                }
                if(status == '0'){
                    $.dialog({
                        content: '该通道已停用',
                        button: ['ok']
                    });
                    return false;
                }else if(status == '2'){
                    $.dialog({
                        content: '该通道缺货',
                        button: ['ok']
                    });
                    return false;
                } else if (status == '3'){
                    $.dialog({
                        content: '该通道出错了',
                        button: ['ok']
                    });
                    return false;
                } else if(status == '4'){
                    $.dialog({
                        content: '该通道正在忙',
                        button: ['ok']
                    });
                    return false;
                }
                var DATA = {
                    "openid":openid,
                    "device_code":device_code,
                    "owner_id":owner_id,
                    "gid":gid,
                    "price":price,
                    "shopid":shopid,
                    "number_order":number_order
                }
                var el=$.loading({
                    content:'正在购买'
                });
                $.post(buy_goods,DATA,function(data){
                    el.hide();
                    if(data.code == 200){
                        var DG = $.dialog({
                            content:data.msg,
                            button: ['ok']
                        });
                        DG.on('dialog:action',function(e){
                            document.location.href=data.url;
                        });
                        return false;
                    } else {
                        $.dialog({
                            content: data.msg,
                            button: ['ok']
                        });
                        return false;
                    }
                },'json');
            }
        });
        //购买商品 end

        //玫瑰购买 start
        $('.rose_buy').tap(function(){
            if(confirm('确定购买吗')){
                var openid = $('#openid').val();
                var device_code = $('#device_code').val();
                var owner_id = $('#owner_id').val();
                var rose = parseInt($('#rose').val());
                var rose_goods = $('#rose_goods').val();
                var gid = $(this).attr('dataid');
                var price = $(this).attr('dataprice');
                var status = $(this).attr('datastatus');
                var shopid = $(this).attr('shopid');
                var number_order = $(this).attr('number_order');
                if(rose <= 0){
                    $.dialog({
                        content: '玫瑰币不足，请先充值',
                        button: ['ok']
                    });
                    return false;
                }
                if(parseInt($(this).parent().attr('shop_number')) <= 0){
                    $.dialog({
                        content: '该通道没商品了',
                        button: ['ok']
                    });
                    return false;
                }
                if(status == '0'){
                    $.dialog({
                        content: '该通道已停用',
                        button: ['ok']
                    });
                    return false;
                }else if(status == '2'){
                    $.dialog({
                        content: '该通道缺货',
                        button: ['ok']
                    });
                    return false;
                } else if (status == '3'){
                    $.dialog({
                        content: '该通道出错了',
                        button: ['ok']
                    });
                    return false;
                } else if(status == '4'){
                    $.dialog({
                        content: '该通道正在忙',
                        button: ['ok']
                    });
                    return false;
                }
                var DATA = {
                    "openid":openid,
                    "device_code":device_code,
                    "owner_id":owner_id,
                    "gid":gid,
                    "price":price,
                    "shopid":shopid,
                    "number_order":number_order
                }
                var el=$.loading({
                    content:'正在购买'
                });
                $.post(rose_goods,DATA,function(data){
                    el.hide();
                    if(data.code == 200){
                        var DG = $.dialog({
                            content:data.msg,
                            button: ['ok']
                        });
                        DG.on('dialog:action',function(e){
                            document.location.href=data.url;
                        });
                        return false;
                    } else {
                        $.dialog({
                            content: data.msg,
                            button: ['ok']
                        });
                        return false;
                    }
                },'json');
            }
        });
        //玫瑰购买 end
    });
</script>
</html>