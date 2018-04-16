<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>上下货管理</title>
    <style>
        .um-list li {
            height: auto;
        }
        .um-list li .label{
            width: 50%;
        }
        .um-list li input{
            width: 20%;
        }
        .footer_vend{
            height: 4rem;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #dedada;
            font-size: 16px
        }
        .footer_vend li{
            width: 33%;
            text-align: center;
            float: left;
            line-height: 4rem;
        }
        .active{
            background-color: #30BF75;
            color:#fff;
        }
    </style>
</head>
<body>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><ul class="um-list um-list-form">
        <li><label  class="label">通道号</label>
            <input class="number"  type="number" readonly="readonly" value="<?php echo ($v["number"]); ?>" >
        </li>
        <li><label  class="label">商品数量</label>
            <input class="shop_number" type="number" value="<?php echo ($v["shop_number"]); ?>">
        </li>
        <li>
            <label  class="label">商品单价</label><input  type="number" class="shop_price" value="<?php echo ($v["shop_price"]); ?>">
        </li>
        <li>
            <label  class="label">投币价格</label>
            <input  type="number" class="toubi_price" value="<?php echo ($v["toubi_price"]); ?>">
        </li>
        <li>
            <label  class="label">启动货道命令</label>
            <input  type="number" class="toubi_price" value="<?php echo ($v["number_order"]); ?>">
        </li>
        <li>
            <label  class="label">货道总出货数</label>
            <input  type="number" class="toubi_price" readonly="readonly" value="<?php echo ($v["alls"]); ?>">
        </li>
        <li>
            <label  class="label">状态</label>
            <select name="status" style="width: 80px" class="status">
                <option value="0" <?php if($v['status'] == 0): ?>selected="selected"<?php endif; ?>>停用</option>
                <option value="1" <?php if($v['status'] == 1): ?>selected="selected"<?php endif; ?>>有货</option>
                <option value="2" <?php if($v['status'] == 2): ?>selected="selected"<?php endif; ?>>缺货</option>
                <option value="3" <?php if($v['status'] == 3): ?>selected="selected"<?php endif; ?>>货道错误</option>
                <option value="4" <?php if($v['status'] == 4): ?>selected="selected"<?php endif; ?>>货道忙</option>
            </select>
        </li>
        <li>
            <label  class="label">商品</label>
            <select name="shop_id" style="width: 80px" class="shop_id">
                <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($v['shop_id'] == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </li>
        <button class="anniu" id="update" dataid="<?php echo ($v["id"]); ?>">更新</button>
    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
</section>
<div class="footer_vend">
    <ul>
        <li><a href="<?php echo U('index');?>">首页</a></li>
        <li class="active">设备管理</li>
        <li><a href="<?php echo U('personal');?>">个人信息</a></li>
    </ul>
</div>
<input type="hidden" id="owner_id" value="<?php echo ($owner_id["id"]); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>
    Zepto(function($){
        $('#update').tap(function(){
            var shop_number = $(this).parents('ul').children('li').eq(1).children('input').val();
            var shop_price = $(this).parents('ul').children('li').eq(2).children('input').val();
            var toubi_price = $(this).parents('ul').children('li').eq(3).children('input').val();
            var number_order = $(this).parents('ul').children('li').eq(4).children('input').val();
            var status = $(this).parents('ul').children('li').eq(6).children('select').val();
            var shop_id = $(this).parents('ul').children('li').eq(7).children('select').val();
            var id = $(this).attr('dataid');
            var el=$.loading({
                content:'更新中...'
            });
            var DATA = {
                shop_number:shop_number,
                shop_price:shop_price,
                toubi_price:toubi_price,
                number_order:number_order,
                status:status,
                shop_id:shop_id,
                id:id
            };
            $.post("",DATA,function(data){
                if(data.code==200){
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href=document.location.href;
                    });
                } else {
                    $.dialog({
                        content:data.msg,
                        button:['好']
                    });
                }
                el.hide();
            },'json');
        })
    })
</script>
</html>