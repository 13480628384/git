<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/anz.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/scrollDate.min.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
    <title>设备安装</title>
</head>
<body style="background: #f2f2f2">
<div class="anz">
    <div class="anzdiv1">
        <span class="anzspan1">扫码</span><span>
        <input id="hard_code" onkeyup="scan_code()" type="text" placeholder="请输入">
        <img src="./tpl/Wap/default/img/sm.png" alt="" class="hard"></span>
    </div>
    <div class="anzdiv1">
        <span class="anzspan1">群组名称</span>
        <select name="p_code" id="device_group_id">
            <?php if($query_group != null): if(is_array($query_group)): $i = 0; $__LIST__ = $query_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["group_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                <option value="1">群组1</option>
                <option value="2">群组2</option><?php endif; ?>
        </select>
    </div>
    <div class="anzdiv1">
        <span class="anzspan1">群组编号</span>
        <!--<select style="width: 60%" name="p_code" id="device_group_code">
            <?php if(is_array($Capital)): $k = 0; $__LIST__ = $Capital;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><option   value="<?php echo ($k); ?>" <?php if($k == 1): ?>selected="selected"<?php endif; ?>><?php echo ($v["0"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select>-->
        <input class="group_word" type="text" placeholder="请输入字母如 A" maxlength="2">
    </div>
    <div class="anzdiv1">
        <span class="anzspan1">创建时间</span>
        <input class="create_date date" type="text" readonly="readonly">
    </div>
    <div class="anniu">确定安装</div>
</div>
</body>
<input type="hidden" id="hcode" value="1">
<input type="hidden" id="ords" value="1">
<input type="hidden" id="group_wordk" value="A">
<input type="hidden" class="hard_device_code" value="<?php echo U('Device/hard_device_code_exists');?>">
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<script src="./tpl/Wap/default/js/mobiscrollDate.min.js"></script>
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
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'scanQRCode',
        ]
    });
    //点击扫码
    $(".hard").click(function(){
        wx.scanQRCode({
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var urlt = res.resultStr;
                var h = $('#hard_code').val();
                $('#hard_code').val(urlt);
                $.post("<?php echo U('hard_device_code_exists');?>",{device_command:$('#hard_code').val()},function(data){
                    if(data.msg == 1){
                        $('#hcode').val(1);
                    }else{
                        $('#hcode').val(2);
                    }
                },'json');
            }
        });
    });
    //手动输入设备
    function scan_code(){
        var hard_device_code = $('.hard_device_code').val();
        $.post(hard_device_code,{device_command:$('#hard_code').val()},function(data){
            if(data.msg == 1){
                $('#hcode').val(1);
            }else{
                $('#hcode').val(2);
            }
        },'json');
    }
    $('#device_group_code').change(function(){
        var options=$("#device_group_code option:selected");  //获取选中的项
       $('#ords').val(options.val());
       $('#group_wordk').val(options.text());

    });
    Zepto(function($){
    /*设备安装 [[*/
    var loading=$('.anniu');
    loading.tap(function() {
        var device_group_id = $.trim($('#device_group_id').val());
        var hard_code = $.trim($('#hard_code').val());
        //var device_group_code = $.trim($('#group_wordk').val());
        var another_name = $.trim($('#another_name').val());
        var ords = $.trim($('#ords').val());
        var group_word = $.trim($('.group_word').val());
        var create_date = $.trim($('.create_date').val());
        var reg = /.*\..*/;
        if (hard_code == '') {
            $.dialog({
                content: '请扫二维码',
                button: ['好']
            });
            return false;
        }
        if ($('#hcode').val() == 2) {
            $.dialog({
                content: '设备不存在',
                button: ['好']
            });
            return false;
        }
        if (create_date == '') {
            $.dialog({
                content: '请选择安装时间',
                button: ['好']
            });
            return false;
        }
        var el = $.loading({
            content: '正在提交'
        });
        var DATA = {
            hard_device_code: hard_code,
            device_group_id: device_group_id,
            device_group_code: group_word,
            ords: ords,
            create_date:create_date
        };
        $.post("<?php echo U('Device/submit_device_infos');?>", DATA, function (reg) {
            if (reg.msg == 1) {
                var DG = $.dialog({
                    content: '恭喜您，提交成功！',
                    button: ['好']
                });
                DG.on('dialog:action', function (e) {
                    document.location.href = "<?php echo U('package',array('openid'=>$openid));?>";
                    //document.location.href = document.location.href;
                });
            } else if (reg.msg == 4) {
                $.dialog({
                    content: '请勿重复安装',
                    button: ['好']
                });
            } else {
                $.dialog({
                    content: '网络错误，请重试',
                    button: ['好']
                });
            }
            el.hide();
        }, 'json');
    })
    });
    /*设备安装 ]]*/
    //执行日期方法
    //选择日期
    function scrollDate(opt,callback){
        $(opt.obj).mobiscroll().date({
            theme: 'ios',     // Specify theme like: theme: 'ios' or omit setting to use default
            mode: 'Scroller',       // Specify scroller mode like: mode: 'mixed' or omit setting to use default
            display: 'bottom', // Specify display mode like: display: 'bottom' or omit setting to use default
            lang: "zh",       // Specify language like: lang: 'pl' or omit settring to use default
            onSelect: function (valueText, inst) {
                function _setVal(obj,date){
                    $(obj).mobiscroll("setVal",date)
                }
                if(typeof callback === "function"){
                    callback.call(this,{
                        valueText:valueText,
                        inst:inst,
                        _setVal :_setVal
                    });
                }
            },
            minDate: opt.minDate,  // More info about minDate: http://docs.mobiscroll.com/2-14-0/datetime#!opt-minDate
            maxDate: opt.maxDate,   // More info about maxDate: http://docs.mobiscroll.com/2-14-0/datetime#!opt-maxDate
            stepMinute: 1  // More info about stepMinute: http://docs.mobiscroll.com/2-14-0/datetime#!opt-stepMinute
        });
    }
    function scrollDateAction (obj,callback) {
        scrollDate({
            "obj":obj,
            "minDate": new Date(2010,0,1,1),
            "maxDate" : new Date(2020,0,1,1)
        },function(data){
            var date = data.valueText;
            $(this).text(date);
            $(this).next().val(date);
            typeof callback === "function"?
                    callback():
                    null;
        })
    }
    $(function(){
        scrollDateAction(".date",function(){
        })
    })
</script>
</html>