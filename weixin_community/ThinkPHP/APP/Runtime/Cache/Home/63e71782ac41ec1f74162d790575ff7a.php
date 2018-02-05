<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>群组信息列表</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/common.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
</head>
<body>
<div class="tab-yj tab-customer">
    <ul class="tab-yj-nav">
        <li class="active">群组信息列表</li>
    </ul>
    <div class="tab-yf-box">
        <div class="tab-yf-null">
            <div class="tab-yf-box1">
            <div class="tab-yf-box-main f14">
                    <div class="tab-yf-box-main-time">
                            <div>群组名称</div>
                    </div>
                    <div class="tab-yf-box-main-time">
                            <div>状态</div>
                    </div>
                    <div class="tab-yf-box-main-time">
                            <div>创建时间</div>
                    </div>
                <div class="tab-yf-box-main-time">
                    <div>操作</div>
                </div>
            </div>
        </div>
    </div>
        <button class="ui-btn-lg ui-btn-danger" type="button" id="submitCustomer" style="width: 95%;margin: 0 auto;margin-top: 20px;margin-bottom: 56px;" onclick="javascript:window.location.href='<?php echo U('group_add');?>'">添加群组</button>
        </div>
        </div>
</div>
      <menu class="ucenter-menu">
    <ul>
        <li id="index" onclick="window.location.href='<?php echo U('index');?>'" class="menu_li ">
          扫码
        </li>
        <li id="deivice" onclick="window.location.href='<?php echo U('device');?>'" class="menu_li">
          设备列表
        </li>
        <li id="group" onclick="window.location.href='<?php echo U('group');?>'" class="current">
          群组信息列表
        </li>
        <li id="personal" onclick="window.location.href='<?php echo U('personal');?>'" class="menu_li">
          个人信息
        </li>
    </ul>
  </menu>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
</body>
<script>
Zepto(function($){
        var el=$.loading({
            content:'加载中...'
        });
        $.post("<?php echo U('query_device_group');?>",function(datased){
            if(datased.msg == 1){
                $.each(datased.datas,function(i,o){
                    var status = '';
                    if(o.status==1){
                        status='有效';
                    }else{
                        status='无效';
                    }
                    $('.tab-yf-box1').append(
                        '<div class="tab-yf-box-main f14">'
                        +'<div class="tab-yf-box-main-time">'
                        +'<div>'+o.group_name+'</div>'
                        +'</div>'
                        +'<div class="tab-yf-box-main-time">'
                        +'<div>'+status+'</div>'
                        +'</div>'
                        +'<div class="tab-yf-box-main-time">'
                        +'<div>'+o.create_time.substr(0,10)+'</div>'
                        +'</div>'
                        +'<div class="tab-yf-box-main-time">'
                        +'<div>'
                        +'<a href="<?php echo U('group_update');?>&updateid='+o.id+'&name='+o.group_name+'">修改</a>'+'&nbsp;&nbsp;'
                        +'<a href="<?php echo U('group_detail');?>&detailid='+o.id+'&name='+o.group_name+'">详细</a>&nbsp;&nbsp;'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                    );
                });
            }
        el.hide();
        },'json');
});    
</script>
</html>