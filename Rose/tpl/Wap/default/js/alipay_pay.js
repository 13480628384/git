Zepto(function($){
    /*========立即充值 [[=========*/
    $('.recharge').tap(function(){
        //if($('.recharge').hasClass('on')){
          //  return false;
        //}
        //$('.recharge').addClass('on');
        var price  =  parseInt($(".imgchang span").html());
        var buyer_id= $('.buyer_id').val();
        var alipay_pay= $('.alipay_pay').val();
        var scan_code= $('.scan_code').val();
        var inok= $('.inok').val();
        if(inok==1){
            alert('设备不在线，请勿充值');
            return false;
        }
        post(alipay_pay,{price:price,buyer_id:buyer_id,scan_code:scan_code});
    });
    /*========立即充值 ]]=========*/
    $(".imgchang img").attr("src","./tpl/Wap/default/img/an3.png");
    $(".imgchang span").css("color","#000");
    //充值按钮js
    $(".game_top_right li").tap(function() {
        $(this).addClass('imgchang').siblings().removeClass('imgchang');
        $(".imgchang img").attr("src","./tpl/Wap/default/img/an3.png");
        $(".imgchang").siblings("li").children('img').attr("src","./tpl/Wap/default/img/an4.png");
        $(".imgchang span").css("color","#000");
        $(".imgchang").siblings().children('span').css("color","#fff");
        var price  =  parseInt($(".imgchang span").html());
    });

    $(".game_start_show li").tap(function() {
        $(this).addClass('game_start_show_li').siblings().removeClass('game_start_show_li');
    });
    /*===========玫瑰启动 [[==============*/
    $(".game_start_hide li").tap(function() {
        $(this).addClass('game_start_show_li').siblings().removeClass('game_start_show_li one');
        var zimu=$(this).attr("cd");
        var c = $('.zimu'+zimu).html();
        var numbers = parseInt(c)+1;
        $('.zimu'+zimu).html(numbers);
        var three = $('.zimu'+zimu).html();
        if(three==2 || $(this).is(".one") ||$(this).is(".onete")){
            $('.zimu'+zimu).html(0);
        var device_command = $(this).attr('data_device_command');
        var di_id = $(this).attr('daatadi_id');
        var price = $(this).attr('data_payice');
        var group_word = $(this).attr('data_group_word');
        var rose_count = $('.rose_count').html();
        var buyer_id = $('.buyer_id').val();
        var online_status = $('.online_status').val();
        if(parseInt(rose_count)<price){
            $.dialog({
                content:'额度不足,请充值',
                button:['好']
            });
            return false;
        }
        var isOnline= false;
        $.ajax({
            type: 'POST',
            url: online_status,
            data: {"device_command":device_command},
            dataType: 'json',
            timeout: 3000,
            async:false,
            success: function(data){
                if(data.code == 200){
                    isOnline = false;
                } else {
                    isOnline = true;
                }
            },
            error: function(xhr, type){
                isOnline = false;
            }
        });
        //不在线，不启动
        if(isOnline==false){
            $.dialog({
                content:group_word+'临时维护中,请点击其他字母启动',
                button:['好']
            });
            return false;
        }
        var el=$.loading({
            content:'正在启动'
        });
        var send_url = $('.send_rose_device_command').val();
        var DATA = {device_command:device_command,di_id: di_id,"buyer_id":buyer_id,price:price,group_word:group_word};
        $.post(send_url,DATA,function(data){
            el.hide();
            if(data.code==204){
                $('.rose_count').html(data.count);
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
                $(this).addClass('onete');
            } else if(data.code == 202){
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            }  else if(data.code == 201){
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            } else {
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            }
        },'json');
        }else if(three==1){
//如果第一次点击的时候当前点击的类以及有lion
            $(this).siblings().children('tt').html(0);

        }
    });
    /*===========玫瑰启动 ]]==============*/
    /*===========娃娃机启动 [[==============*/
    $(".game_start_show_doll li").tap(function() {
        $(this).addClass('game_start_show_li').siblings().removeClass('game_start_show_li one');
        var zimu=$(this).attr("cd");
        var c = $('#zimu'+zimu).html();
        var numbers = parseInt(c)+1;
        $('#zimu'+zimu).html(numbers);
        var three = $('#zimu'+zimu).html();
        if(three==2 || $(this).is(".one") ||$(this).is(".onete")){
            $('#zimu'+zimu).html(0);
        var device_command = $(this).attr('data_device_command');
        var di_id = $(this).attr('daatadi_id');
        var price = $(this).attr('data_payice');
        var group_word = $(this).attr('data_group_word');
        var weixin_count = $('.weixin_count').html();
        var buyer_id = $('.buyer_id').val();
        var online_status = $('.online_status').val();
        if(parseInt(weixin_count)<price){
            $.dialog({
                content:'额度不足,请充值',
                button:['好']
            });
            return false;
        }
        var isOnline= false;
        $.ajax({
            type: 'POST',
            url: online_status,
            data: {"device_command":device_command},
            dataType: 'json',
            timeout: 3000,
            async:false,
            success: function(data){
                if(data.code == 200){
                    isOnline = false;
                } else {
                    isOnline = true;
                }
            },
            error: function(xhr, type){
                isOnline = false;
            }
        });
        //不在线，不启动
        if(isOnline==false){
            $.dialog({
                content:group_word+'临时维护中,请点击其他字母启动',
                button:['好']
            });
            return false;
        }
        var el=$.loading({
            content:'正在启动'
        });
        var send_url = $('.send_device_command').val();
        var DATA = {device_command:device_command,di_id: di_id,"buyer_id":buyer_id,price:price,group_word:group_word};
        $.post(send_url,DATA,function(data){
            el.hide();
            if(data.code==204){
                $('.weixin_count').html(data.count);
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
                $(this).addClass('onete');
            } else if(data.code == 202){
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            }  else if(data.code == 201){
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            } else {
                $.dialog({
                    content:data.msg,
                    button:['好']
                });
            }
        },'json');
        }else if(three==1){
//如果第一次点击的时候当前点击的类以及有lion
            $(this).siblings().children('tt').html(0);

        }
    });
    /*===========娃娃机启动 ]]==============*/


    /*===========玫瑰币启动按钮 [[==============*/
    $(".game_start_hide li").tap(function() {
        $(this).addClass('game_start_show_li1').siblings().removeClass('game_start_show_li1');
    });
    /*===========玫瑰币启动按钮 ]]==============*/

    $(".game_start_click1").tap(function() {
        $(".game_start_show1").show();
        $(".game_start_hide").hide();
        $(this).addClass('game_start2').removeClass('game_start3');
        $(this).siblings().removeClass('game_start2').addClass('game_start3');
    });
    /*===========tap按钮切换 [[==============*/
    $(".game_start_click2").tap(function() {
        $(".game_start_show1").hide();
        $(".game_start_hide").show();
        $(this).addClass('game_start2').removeClass('game_start3');
        $(this).siblings().removeClass('game_start2').addClass('game_start3');
    });
    /*===========tap按钮切换 ]]==============*/

});
// 广告滚动
function autoScroll(obj, ul_bz){
    $(obj).find(ul_bz).animate({
        marginTop : "-3rem"
    },500,function(){
        $(this).css({marginTop : "0px"}).find("li:first").appendTo(this);
    });
}
setInterval('autoScroll("#oDiv", "#oUl")',5000);