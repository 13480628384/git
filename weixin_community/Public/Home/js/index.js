Zepto(function($) {
    //隐藏
    //setTimeout("$('#dis').hide(1000);",3000);
    var tabYjNav=$('.tab-yj-nav>li');
    var tabYjBox=$('.tab-yf-box');
    tabYjNav.tap(function(){
        $(this).addClass('active').siblings('li').removeClass('active');
        var index=tabYjNav.index(this);
        tabYjBox.eq(index).show().siblings('.tab-yf-box').hide();
    });
    //写攻略
    $('.player-right').tap(function(){
        var nurl = $('.nurl').val();
        window.location.href=nurl;
    });
    //最火
    /*$('.player-center').tap(function(){
        var nurl = $('.hot_index').val();
        window.location.href=nurl;
    });*/
    //点击返回支付
    $('.back').tap(function(){
        var device_command = $('.device_command').val();
        var type = $('.type').val();
        var group_id = $('.group_id').val();
        var sc_small = $('.sc_small').val();
        if(sc_small==1){
            var url = 'http://tt.7i1.cn/small/redirect.php?type='+type+'&groupId='+group_id+'&defaultCode='+device_command;
        }else{
            var url = 'http://tt.7i1.cn/serverpay/wxpay/redirect.php?type='+type+'&group_id='+group_id+'&default_code='+device_command;
        }
       window.location.href=url;
    });
    //点击字母启动
    $('.content-left').tap(function(){
        var mchid = $('.mchid').val();
        var openid = $('.openid').val();
        var community_add = $('.community_add').val();
        var device_command = $('.device_command').val();
        var device_id = $('.device_id').val();
        var total_all = parseInt($('.total_all').html());//总余额
        var price = parseInt($('.tt').html());//设备的价格
        var group_code = $('.group_code').val();//是否在线
        var sc_small = $('.sc_small').val();
        if(total_all<price){
            /*$.dialog({
                content: '你的余额已不足',
                button: ['ok']
            });*/
            new TipBox({type:'tip',str:'你的余额已不足',clickDomCancel:true,setTime:1500});
            if(sc_small==1){
                var url = 'http://tt.7i1.cn/small/redirect.php?type='+type+'&groupId='+group_id+'&defaultCode='+device_command;
            }else{
                var url = 'http://tt.7i1.cn/serverpay/wxpay/redirect.php?type='+type+'&group_id='+group_id+'&default_code='+device_command;
            }
            window.location.href=url;
            return false;
        }
        var message="201:AAAAA4";
        //2016-03-17 chw 单一的发送指令修改成根据价格动态的发送指令
        if(price==1){
            message+="01";
        }else if(price==10){
            message+="A1";
        }else{
            message+=(price+"1");
        }
        if(price>10){
            /*$.dialog({
                content: '设备价格不支持，请启动其他设备',
                button: ['ok']
            });*/
            new TipBox({type:'tip',str:'价格不支持',clickDomCancel:true,setTime:1500});
            return false;
        }
        if(group_code==0){
            /*$.dialog({
                content:'设备临时维护中，请启动其他设备',
                button:['ok']
            });*/
            new TipBox({type:'tip',str:'设备维护中',clickDomCancel:true,setTime:1500});
            return false;
        }
        var sendSuc = false;
        var send_url = "http://120.24.81.106:3030/IntelligenceServer2/cgi/message_send.action";
        $.ajax({
            type: 'POST',
            url: send_url,
            data: {datas:message,deviceId:device_command,transCode:"601",commandId:Math.random()},
            dataType: 'json',
            async : false,
            success: function(data){
                if(data.code == 200){
                    new TipBox({type:'success',str:'设备已启动',setTime:1500});
                    sendSuc = true;
                    $('.total_all').html(total_all-price);
                } else {
                    new TipBox({type:'error',str:'启动错误',setTime:1500});
                }
            },
            error: function(xhr, type,error){
                new TipBox({type:'error',str:'启动错误',setTime:1500});
            }
        });
        //发送成功，添加消费记录
        if(sendSuc==true){
            $.post(community_add,{"mchid":mchid,"device_command":device_command,"device_id":device_id,
                "openid":openid,price:price},function(data){

                },'json'
            );
        }
    });
    //点击启动送次数
    $('.button-top').tap(function(){

        var wei=0;

        var mchid = $('.mchid').val();
        var openid = $('.openid').val();
        var community_more = $('.community_more').val();
        var device_command = $('.device_command').val();
        var device_id = $('.device_id').val();
        var total_all = parseInt($('.total_all').html());//总余额
        var price = parseInt($(this).find('.tt1').html());//设备的价格
        //var number_send = parseInt($(this).find('.number_send').html());//送次数
        var group_code = $('.group_code').val();//是否在线
        var all_message = (price);
        var sc_small = $('.sc_small').val();
        if(total_all<price){
            new TipBox({type:'tip',str:'你的余额已不足',clickDomCancel:true,setTime:1500});
            if(sc_small==1){
                var url = 'http://tt.7i1.cn/small/redirect.php?type='+type+'&groupId='+group_id+'&defaultCode='+device_command;
            }else{
                var url = 'http://tt.7i1.cn/serverpay/wxpay/redirect.php?type='+type+'&group_id='+group_id+'&default_code='+device_command;
            }
            window.location.href=url;
            return false;
        }
        if(group_code==0){
            new TipBox({type:'tip',str:'设备维护中',clickDomCancel:true,setTime:1500});
            return false;
        }
        if(all_message>10){
            return false;
        }

            var sendSuc = false;
            var ten_count = 0;
            var residue = 0;
            if (all_message > 10) {
                ten_count = parseInt(all_message / 10);
                for (var i = 0; i < ten_count; i++) {
                    if (i == 0) {
                        sendMessage(10);
                    } else {
                        sleep(5000);
                        sendMessage(10);
                    }
                }
                if (all_message % 10 > 0) {
                    residue = all_message % 10;
                    sleep(5000);
                    sendMessage(residue);
                }
            } else {
                sendMessage(all_message);
            }
            function sleep(d) {
                for (var t = Date.now(); Date.now() - t <= d;);
            }

            function sendMessage(all_message) {
                var message = "201:AAAAA4";
                if (all_message == 10) {
                    message += "A1";
                } else {
                    message += (all_message + "1");
                }
                if(wei==0){
                    wei++;
                }else{
                    sleep(5000);
                }
                var send_url = "http://120.24.81.106:3030/IntelligenceServer2/cgi/message_send.action";
                $.ajax({
                    type: 'POST',
                    url: send_url,
                    data: {datas: message, deviceId: device_command, transCode: "601", commandId: Math.random()},
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.code == 200) {
                            if (all_message <= 10) {
                                new TipBox({type: 'success', str: '设备已启动', setTime: 1500});
                            }
                            sendSuc = true;
                            $('.total_all').html(total_all - price);
                        } else {
                            if (all_message < 10) {
                                new TipBox({type: 'error', str: '设备启动错误', setTime: 1500});
                            }
                            return false;
                        }
                    },
                    error: function (xhr, type, error) {
                        new TipBox({type: 'error', str: '设备启动错误', setTime: 1500});
                        return false;
                    }
                });
            }

            //发送成功，添加消费记录
            if (sendSuc == true) {
                $.post(community_more, {
                        "mchid": mchid, "device_command": device_command, "device_id": device_id,
                        "openid": openid, price: price
                    }, function (data) {
                    }, 'json'
                );
            }

    });
    /*点赞 [[*/
    $('.bottom-two').tap(function(){
        /*if($(this).hasClass('on')){
            return false;
        }*/
        var This = $(this);
        var openid = $('.openid').val();
        var xin = $('.xin').val();
        var po = $(this).find('.po');
        var click_friend_url = $('.click_friend').val();
        var click_zan_more = $('.click_zan_more').val();
        var icon_img = $('.icon_img').val();
        var frid = $(this).attr('data');
        var zan = false;
        $.ajax({
            type:'post',
            url:click_zan_more,
            data:{openid:openid,id:frid},
            dataType:'json',
            async:false,
            success:function(data){
                if(data.code==200){
                    zan = true;
                    //new TipBox({type: 'tip', str: '你赞过了哦', setTime: 1500});
                }
            },
            error:function(type,error){
                alert(error);
            }
        });
        if(zan==false) {
            //点赞
            $.post(click_friend_url,{openid:openid,id:frid},function(data){
                if(data.code==200){
                    //This.addClass('on');
                    This.find('img').attr('src',xin);
                    po.html(data.all);
                }
            },'json');
        }else{
            //取消赞
            var cancel = $('.cancel').val();
            $.post(cancel,{openid:openid,id:frid},function(data){
                if(data.code==200){
                    //This.addClass('on');
                    This.find('img').attr('src',icon_img);
                    po.html(data.all);
                }
            },'json');
        }
    });
    /*加载用户评论*/
    var num = 2;
    var count = 11;
    var user_ajax_community_url = $('.user_ajax_community').val();
    var icon_img = $('.icon_img').val();
    var comm_img = $('.comm_img').val();
    var is_openid = $('.is_openid').val();
    var strate_url_of = $('.strate_url_of').val();
    var openid = $('.openid').val();
    $('.loading').tap(function () {
        if($('.loading').hasClass('on')){
            return false;
        }
        $('.loading').addClass('on');
        $('.more').html('');
        $('.loading_animate').css('display','block');
        $.post(user_ajax_community_url, {page: num,userid:openid,is_openid:is_openid}, function (data) {
            $('.loading').removeClass('on');
            $('.more').html('点击加载更多');
            $('.loading_animate').css('display','none');
            if($.trim(data).length>0){
                $('.community').append(data);
            }else{
                $(".loading").html("已加载全部");
            }
        }, 'html');
        num++;
    });
    /*点赞 ]]*/
});
