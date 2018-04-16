<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>按摩椅价格修改</title>
    <style>
        .tishi_yanz{ margin-bottom: 0!important; }
        .add img,.add2 img{
            width: 26px;
            height: 26px;
            line-height: 46px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body style="background: #f2f2f2">
<div class="facility" data-ng-app="myApp" ng-controller="myCtrl">
    <div class="tishi"><span style="color:#f00">提示</span>:“若不填则表示不修改”。旧<?php echo ($anm); ?></div>
    <div class="tishi_yanz">按摩
        <input  ng-model="firstName1" type="text" placeholder="价格" pattern="^([1-9]\d*)$" required="required" style="width: 20%">元
        <input  ng-model="lastName1" type="text" placeholder="时间" pattern="^([1-9]\d*)$" required="required" style="width: 20%">分钟</div>

    <div class="tishi_yanz">按摩
    <input  ng-model="firstName2" type="text" placeholder="价格" pattern="^([1-9]\d*)$" required="required" style="width: 20%">元
    <input  ng-model="lastName2" type="text" placeholder="时间" pattern="^([1-9]\d*)$" required="required" style="width: 20%">分钟</div>

    <div class="tishi_yanz">按摩
        <input  ng-model="firstName3" type="text" placeholder="价格" pattern="^([1-9]\d*)$" required="required" style="width: 20%">元
        <input  ng-model="lastName3" type="text" placeholder="时间" pattern="^([1-9]\d*)$" required="required" style="width: 20%">分钟</div>
    <div class="tishi_yanz">价格时间<input id="srdata" type="text" value="{{firstName1 + '=' + lastName1 + '-' + firstName2 + '=' +lastName2 +'-' + firstName3 + '=' +lastName3}}"></div>
    <?php if($users['is_into'] == '1'): ?><div class="tishi_yanz">我的分成&nbsp;&nbsp;
        <input  type="text" style="width:30%;"readonly="readonly" value="<?php echo ($users["name"]); ?>">
        <input  type="hidden" class="my_name1" value="<?php echo ($users["id"]); ?>">
        <input type="number" class="precent1" value="<?php if(empty($two_array)){echo '100';}else{echo $two_array[1];} ?>" placeholder="百分比" style="width: 16%">
        %&nbsp;&nbsp;<b class="add"><img src="./tpl/Wap/default/img/jiahao2fill.png"></b></div>
    <div class="tishi_yanz add2" style="display: none">分成客户1
        <select name="user_id" class="my_name2" style="width:30%;float:inherit">
            <option value="0">请选择</option>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['id'] == $two_array[2]): ?><option value="<?php echo ($two_array["2"]); ?>" selected="selected"><?php echo ($v["name"]); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <?php if($li != null): ?><option value="<?php echo ($li["id"]); ?>"><?php echo ($li["name"]); ?></option><?php endif; ?>
        </select>
        <input type="number" class="precent2" maxlength="2" value="<?php if(empty($two_array)){echo '0';}else{echo $two_array[3];} ?>" placeholder="百分比" style="width: 16%">
        %&nbsp;&nbsp;<b class="add3"><img src="./tpl/Wap/default/img/jiahao2fill.png"></b></div>
    <div class="tishi_yanz add4" style="display: none">分成客户2
        <select name="user_id" class="my_name3" style="width:30%;float:inherit">
            <option value="0">请选择</option>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['id'] == $two_array[4]): ?><option value="<?php echo ($two_array["4"]); ?>" selected="selected"><?php echo ($v["name"]); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <?php if($li != null): ?><option value="<?php echo ($li["id"]); ?>"><?php echo ($li["name"]); ?></option><?php endif; ?>
        </select>
        <input type="number" class="precent3" maxlength="2" value="<?php if(empty($two_array)){echo '0';}else{echo $two_array[5];} ?>" placeholder="百分比" style="width: 16%">
        %</div>
    <p>提示：分成用户最多只能设置3个百分比</p><?php endif; ?>
    <button class="anniu" type="button">确定修改</button>
</div>
<input type="hidden" class="di_id" value="<?php echo ($di_id); ?>">
<input type="hidden" class="is_into" value="<?php echo ($users["is_into"]); ?>">
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/angular.min.js"></script>
<ul class="footer_rose">
    <li data-url="<?php echo U('Rose2Personal/index',array('openid'=>$openid));?>">首页</li>
    <li data-url="<?php echo U('Device/device_list',array('openid'=>$openid));?>">设备列表</li>
    <li data-url="<?php echo U('Device/group_list',array('openid'=>$openid));?>">群组列表</li>
    <li data-url="<?php echo U('Rose2Personal/presonal_new',array('openid'=>$openid));?>">个人信息</li>
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
<script>
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope) {
        $scope.firstName1= "<?php echo ($out[0]); ?>";
        $scope.lastName1= "<?php echo ($out[1]); ?>";

        $scope.firstName2= "<?php echo ($out[2]); ?>";
        $scope.lastName2= "<?php echo ($out[3]); ?>";

        $scope.firstName3= "<?php echo ($out[4]); ?>";
        $scope.lastName3= "<?php echo ($out[5]); ?>";

    });
    Zepto(function($){
        $('.add').tap(function(){
            $('.add2').css('display','block');
        });
        $('.add3').tap(function(){
            $('.add4').css('display','block');
        });
        $('.anniu').tap(function(){
            var my_name1 = $.trim($('.my_name1').val());
            var precent1 = parseInt($.trim($('.precent1').val()));
            var my_name2 = $.trim($('.my_name2').val());
            var precent2 = parseInt($.trim($('.precent2').val()));
            var my_name3 = $.trim($('.my_name3').val());
            var is_into = $.trim($('.is_into').val());
            var precent3 = parseInt($.trim($('.precent3').val()));
            if(is_into == '1'){
                if(my_name1==my_name2 || my_name1== my_name3 || my_name2==my_name3){
                    $.dialog({
                        content:'分成用户设置有误',
                        button:['好']
                    });
                    return false;
                }
                if((precent1+precent2+precent3)>100){
                    $.dialog({
                        content:'百分比错误',
                        button:['好']
                    });
                    return false;
                }
            }
            var srdata = $('#srdata').val();
            var di_id = $('.di_id').val();
            var openid = $('.openid').val();
            if(srdata == ''){
                $.dialog({
                    content:'请输入时间价格',
                    button:['好']
                });
                return false;
            }
            var el=$.loading({
                content:'正在修改'
            });
            var DATA = {
                openid:openid,srdata:srdata,
                di_id:di_id,my_name1:my_name1,
                my_name2:my_name2,my_name3:my_name3,
                precent1:precent1,precent2:precent2,
                precent3:precent3,
                is_into:is_into
            };
            $.post("<?php echo U('update_anm');?>",DATA,function(data){
                if(data.code == 200){
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                    DG.on('dialog:action',function(e){
                        document.location.href=data.url;
                    });
                } else {
                    var DG=$.dialog({
                        content:data.msg,
                        button:['好']
                    });
                }
                el.hide();
            },'json')
        })
    });
</script>
</body>
</html>