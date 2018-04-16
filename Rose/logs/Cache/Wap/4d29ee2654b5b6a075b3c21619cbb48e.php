<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/facility.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>电动车价格修改</title>
    <style>
        .tishi_yanz{ margin-bottom: 0!important; }
    </style>
</head>
<body style="background: #f2f2f2">
<div class="facility" data-ng-app="myApp" ng-controller="myCtrl">
    <div class="tishi"><span style="color:#f00">提示</span>:“若不填则表示不修改”。旧<?php echo ($charger); ?></div>
    <div class="tishi_yanz">价格1
        <input  ng-model="firstName1" type="text" placeholder="价格" pattern="^([1-9]\d*)$" required="required" style="width: 20%">元
        <input  ng-model="lastName1" type="text" placeholder="时间" pattern="^([1-9]\d*)$" required="required" style="width: 20%">分钟</div>
    <div class="tishi_yanz">价格时间<input id="srdata" type="text" value="{{firstName1 + '=' + lastName1}}"></div>
    <button class="anniu" type="button">确定修改</button>
</div>
<input type="hidden" class="di_id" value="<?php echo ($di_id); ?>">
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
    });
    Zepto(function($){
        $('.anniu').tap(function(){
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
            $.post("<?php echo U('xiche_update');?>",{openid:openid,srdata:srdata,di_id:di_id},function(data){
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