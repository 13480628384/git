<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/tak.css">
    <title>设备管理</title>
    <style>
        .header{
            height: 50px;
            line-height: 50px;
            text-align: center;
            background: #fff;
        }
        .content{
            text-align: center;
            background: #fff;
            margin-top: 8px;
            margin-right: auto;
            margin-bottom: auto;
            margin-left: auto;
        }
        .device_command{
            font-size: 24px;
        }
        .update{
            height: 40px;
            line-height: 40px;
            background: #5fd497;
        }
        .update li{
            width: 33%;
            float: left;
        }
        .update .on{
            border-left:1px solid #b3aeae;
        }
        .update a{
            color: #fff;
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
        .con{
            margin:0;padding:0
        }
        .del{
            color: #fff;
        }
    </style>
</head>
<body>
<div class="header">
    设备管理
</div>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="content">
        <span class="device_command"><?php echo ($v["device_command"]); ?></span>
        <ul class="con">
            <li>设备编码：<?php echo ($v["device_code"]); ?></li>
            <li>设备状态：<?php if($v['online_status'] == 1): ?><span style="color: #30bf75;">在线</span><?php else: ?>
                <span style="color: #f00;">离线</span><?php endif; ?></li>
            <li>货道数：<?php echo ($v["number_routes"]); ?></li>
            <li>创建时间：<?php echo ($v["create_date"]); ?></li>
        </ul>
        <ul class="update">
            <li><a href="<?php echo U('update',array('id'=>$v['id']));?>">修改</a></li>
            <li class="on"><a href="<?php echo U('management',array('id'=>$v['id']));?>">上下货管理</a></li>
            <li class="on del" dataid="<?php echo ($v["id"]); ?>">删除设备</li>
        </ul>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
<button class="anniu" id="edit_name"><a href="<?php echo U('add_device');?>">新增设备</a></button>
<div style="height: 80px"></div>
<div class="footer_vend">
    <ul>
        <li><a href="<?php echo U('index');?>">首页</a></li>
        <li class="active">设备管理</li>
        <li><a href="<?php echo U('personal');?>">个人信息</a></li>
    </ul>
</div>
<input type="hidden" id="update" value="<?php echo U('update_price');?>"/>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
</body>
<script>
    Zepto(function($) {
        $('.del').tap(function () {
            var id = $(this).attr('dataid');
            var el=$.loading({
                content:'删除中...'
            });
            var DATA = {
                id:id
            };
            $.post("<?php echo U('del');?>",DATA,function(data){
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
    });
</script>
</html>