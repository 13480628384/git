<?php if (!defined('THINK_PATH')) exit();?><html><head>
    <title>更改编号信息</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>
.status{
    width:164px;
    height:40px;
}
.return-top {
    background-color: #ffffff;
    height: 42px;
    line-height: 42px;
    padding: 0 10px;
    position: fixed;
    width: calc(100% - 20px);
    width: -webkit-calc(100% - 20px);
    border-bottom: 1px solid #e6e6e6;
    z-index: 10;
}
.return-text {
    font-size: 15px;
    margin-left: 5px;
    color: #d1d1d1;
}
.icon-chevron-thin-left.thin-left {
    color: #818181;
    font-size: 16px;
    vertical-align: text-bottom;
}
    </style>
</head>
<body>
<div class="return-top" onclick="history.go(-1);">
            <span class="icon-chevron-thin-left thin-left"></span>
            <span class="return-text"> < 返回上一页</span>
        </div>
<div class="space-20"></div>
<header class="ucenter-t" style="margin-top: 30px;">
    更改群组内信息&nbsp;所属的组：（<?php echo $cname;?>）</header>
<section class="ucenter-main animated fadeInDown">
    <div class="space-10"></div>
    <ul class="um-list um-list-form">
		<li>
			<label for="cuser_name" class="label">更改群组</label>
				<select name="p_code" id="device_group_id" style="width:150px;">
			</select>
		</li>
        <li><label for="cuser_name" class="label">组内名称</label><input type="text" placeholder="请输入编号名称" id="group_code" value="<?php echo $group_code;?>"></li>
        <li>
            <label for="cuser_name" class="label">状态</label>
            <select name="status" class="status">
                <option value="1" <?php if($status==1){echo 'selected="selected"';}?>>有效</option>
                <option value="0" <?php if($status==0){echo 'selected="selected"';}?>>无效</option>
            </select>    
        </li>
        <li>
            <label for="cuser_name" class="label">排序</label><input value="<?php echo $ords;?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" id="ords">
        </li>
		<li>
            <label for="cuser_name" class="label">硬编码</label><input value="<?php echo $device_command;?>" readonly="readonly">
        </li>
		<li>
            <label for="cuser_name" class="label">软编码</label><input value="<?php echo $device_code;?>" readonly="readonly">
        </li>
    </ul>
   
</section>

<div class="space-20"></div>
<aside class="account-submit">
    <input type="hidden" id="group_id" value="<?php echo $group_id?>">
    <input type="hidden" id="cname" value="<?php echo $cname?>">
    <input type="hidden" id="device_command" value="<?php echo $device_command?>">
    <button class="ui-btn-lg ui-btn-danger" type="button" id="save_update">保存</button>
</aside>
<div class="space-20"></div>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
</body>
<script type="text/javascript">
Zepto(function($){
        var el=$.loading({
            content:'拼命加载中...'
        });
        var cname = $('#cname').val();
        $.post("<?php echo U('query_device_group');?>",function(reg){
          if(reg.msg==1){
            $.each(reg.datas,function(i,o){

            if(o.group_name==cname){
                var selected = "selected=selected";
            }
              $('#device_group_id').append(
                '<option value="'+o.id+'"'+selected+'>'+o.group_name+'</option>'
              );
            });
          }
          el.hide();
        },'json');
        /*
    *  更改群组内信息
    */
    var save_update = $('#save_update');
    save_update.tap(function(){
        var group_id    = $('#device_group_id').val();//原组名的id
        var group_code  = $('#group_code').val();//组内名称
        var status      = $('.status').val();//是否有效
        var ords      = $('#ords').val();//排序
        var device_command  = $('#device_command').val();//排序
        //alert(group_id+','+group_code+','+status+','+ords+','+device_command);
        var DATA = {
            group_id : group_id,
            group_code : group_code,
            status : status,
            device_command : device_command,
            ords:ords
        }
        if(group_code==''){
            $.dialog({
                    content:'请输入名称',
                    button:['我知道了']
            });
            return false;
        }
        if(group_code.length>10){
            $.dialog({
                    content:'名称不能超出10个字哦',
                    button:['我知道了']
            });
            return false;
        }
        if(!ords){
            $.dialog({
                    content:'请输入排序号',
                    button:['我知道了']
            });
            return false;
        }
        if(ords.length>5){
            $.dialog({
                    content:'排序号不能超出5位哦',
                    button:['我知道了']
            });
            return false;
        }
        var el=$.loading({
            content:'正在保存'
        });
        $.post("<?php echo U('update_device_group');?>",DATA,function(data){
            if(data.msg==1){
                var DG=$.dialog({
                    content:'恭喜您，保存成功！',
                    button:['我知道了']
                });
                DG.on('dialog:action',function(e){
                    document.location.href="<?php echo U('group');?>";
                });
            }else{
                $.dialog({
                    content:'网络错误，请重试',
                    button:['我知道了']
                });
            }
            el.hide();
        },'json');
    })
});  
</script>
</html>