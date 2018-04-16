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
    <title>设备列表</title>
</head>
<body>
<div class="facility" style="margin-bottom: 6rem;">
    <table width="100%" align="center" >
        <tr >
            <td width="12%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">区域</td>
            <td width="24%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">设备码</td>
            <td width="9%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">指令码</td>
            <td width="11%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">状态</td>
            <td width="10%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">价格</td>
            <td width="12%" height="73" align="center" valign="middle" bgcolor="#2ebf74" class="style1">操作</td>
        </tr>
        <?php if($res != null): if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr >
                    <td width="10%" align="center" valign="middle" class="style3"><?php echo ($v["ja_name"]); ?></td>
                    <td width="24%" align="center" valign="middle" class="style3"><?php echo ($v["device_code"]); ?></td>
                    <td width="9%" align="center" valign="middle" class="style3"><?php echo ($v["device_command"]); ?></td>
                    <td width="11%" align="center" valign="middle" class="style3">
                        <?php if($v['online_status'] == 1): ?>在线<?php else: ?>离线<?php endif; ?></td>
                    <td width="12%" align="center" valign="middle" class="style3"><?php echo ($v["pay_price"]); ?></td>
                    <td width="12%" align="center" valign="middle" class="style3">
                        <a href="<?php echo U('update_detail_device',array('id'=>$v['id'],'openid'=>$openid));?>">修改</a>
                        <br/>
                        <a href="<?php echo U('test_device',array('id'=>$v['id'],'openid'=>$openid));?>">调试</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </table>
</div>
<div class="Mask" id="reform">
    <div class="Mask-content Mask-gai">
        <div class="Mask-title">价格修改</div>
        <div class="Mask-from">
            <div class="Mask-input date  update">
                <input type="text" maxlength="4" value="20" class="update_username"  pattern="[0-9]*" placeholder="请输入你改的价格">
            </div>
            <span style="margin-left:85px;color:#f00;" class="now-name" ></span>
        </div>
        <div class="Mask-btn">
            <div class="Mask-tab-btn removeclass">取消</div>
            <div class="Mask-tab-btn addclass check">确定</div>
        </div>
    </div>
</div>
<input type="hidden" id="groupid" value=""/>
<input type="hidden" id="di_id" value=""/>
<input type="hidden" id="update" value="<?php echo U('update_price');?>"/>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('JuicerPersonal/index',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('JuicerPersonal/weixin_income',array('openid'=>$openid));?>">微信收益</li>
    <li data-url="<?php echo U('JuicerPersonal/alipay_income',array('openid'=>$openid));?>">支付宝收益</li>
    <li data-url="<?php echo U('JuicerPersonal/personal',array('openid'=>$openid));?>">个人信息</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer_rose li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    var code = url.split("&code")[0];
    $("[data-url='"+code+"']").addClass('active');
    function onBridgeReady(){
        WeixinJSBridge.call('hideOptionMenu');
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
</body>
<script>
    $('.update-price').click(function(){
        $("#reform").addClass('is-visible');
    });
    $('.removeclass').click(function(){
        if($(this).hasClass('re')){
            return false;
        }
        $('.now-name').html('');
        $("#reform").removeClass('is-visible');
    });
    function update_price(price,id,di_id) {
        $('.update_username').val(price);
        $('#di_id').val(di_id);
        $('#groupid').val(id);
        $('.check').click(function(){
            $('.now-name').parent('.Mask-from').next('.Mask-btn').find('.removeclass').addClass('re');
            if ($('.now-name').hasClass('add')) {
                return false;
            }

            var di_id = $('#di_id').val();
            var id = $('#groupid').val();
            var update_username = parseInt($('.update_username').val());
            if (!update_username) {
                $('.now-name').html('请输入价格');
                return false;
            }
            $('.now-name').html('请稍候...').addClass('add');
            var update_username_url = $('#update').val();
            $.post(update_username_url, {price: update_username,id:id,di_id:di_id}, function (data) {
                $('.now-name').parent('.Mask-from').next('.Mask-btn').find('.removeclass').removeClass('re');
                if (data.result_code == 1) {
                    $('.now-name').css('color', '#7CCC6F');
                    $("#reform").removeClass('is-visible');
                    $('.now-name').html('');
                    window.location.href="<?php echo U('device_list',array('openid'=>$openid));?>";
                } else {
                    $('.now-name').css('color', '#7CCC6F');
                    $("#reform").removeClass('is-visible');
                    $('.now-name').html('');
                }
            }, 'json');
        })
    }
</script>
</html>