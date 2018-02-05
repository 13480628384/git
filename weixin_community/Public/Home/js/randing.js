Zepto(function($){
    $(".date li").css('background','#fff');
    $(".date li a").css('color','rgb(42,182,144)');
    $("#date ").css('background','rgb(42,182,144)');
    $("#date a").css('color','#fff'); //日、周、月按钮颜色变化

    $(".area li").css('border','1px solid #aaa');
    $(".area li a").css('color','#777');
    $("#single1").css('border','1px solid rgb(255,110,53)');
    $("#single1 a").css('color','rgb(255,110,53)');

    $(".rank").css('display','none');
    $("#date-single").css('display','block');
    $("#single1").tap(function(){
        $(".rank").css('display','none');
        $("#date-single").css('display','block');
    });
    $("#region1").tap(function(){
        $(".rank").css('display','none');
        $("#date-region").css('display','block');
    });

    $("#country1").tap(function(){
        $(".rank").css('display','none');
        $("#date-country").css('display','block');
    });
    $("#date").tap(function(){
        $(".area").css('display','none');
        $(".area-date").css('display','block'); //“日”下的第一个单台、全区、全国出现


        $(".date li").css('background','#fff');
        $(".date li a").css('color','rgb(42,182,144)');
        $("#date ").css('background','rgb(42,182,144)');
        $("#date a").css('color','#fff'); //日、周、月按钮颜色变化

        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single1").css('border','1px solid rgb(255,110,53)');
        $("#single1 a").css('color','rgb(255,110,53)');

        $(".rank").css('display','none');
        $("#date-single").css('display','block');
        $("#single1").tap(function(){
            $(".rank").css('display','none');
            $("#date-single").css('display','block');
        });
        $("#region1").tap(function(){
            $(".rank").css('display','none');
            $("#date-region").css('display','block');
        });

        $("#country1").tap(function(){
            $(".rank").css('display','none');
            $("#date-country").css('display','block');
        });
    });
    //点击周
    $("#week").tap(function(){
        $(".area").css('display','none');
        $(".area-week").css('display','block');

        $(".date li").css('background','#fff');
        $(".date li a").css('color','rgb(42,182,144)');
        $("#week").css('background','rgb(42,182,144)');
        $("#week a").css('color','#fff'); //按钮颜色变化

        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single2").css('border','1px solid rgb(255,110,53)');
        $("#single2 a").css('color','rgb(255,110,53)');

        $(".rank").css('display','none');
        $("#week-single").css('display','block');

        if($('#week').hasClass('on')){
            return false;
        }
        var Week_Stand_alone_Randing = $('.Week_Stand_alone_Randing').val();
        var openid = $('.openid').val();
        var group_id = $('.group_id').val();
        var device_command = $('.device_command').val();
        $('#week').addClass('on');
        $('.tail_spin').css('display','block');
        $.post(Week_Stand_alone_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
            $('#week').removeClass('on');
            $('.tail_spin').css('display','none');
            if($.trim(data).length>0){
                $('#week-single').html(data);
            }else{
                $(".nonumber").css('display','block');
                $(".nonumber").html("今周单台机器还没有人玩哦，抢先占第一名");
            }
        }, 'html');
        $("#single2").tap(function(){
            $(".rank").css('display','none');
            $("#week-single").css('display','block');
        });
        //周的全区
        $("#region2").tap(function(){
            $(".rank").css('display','none');
            $("#week-region").css('display','block');
            if($('#region2').hasClass('on')){
                return false;
            }
            var Week_Region_Randing = $('.Week_Region_Randing').val();
            var openid = $('.openid').val();
            var group_id = $('.group_id').val();
            var device_command = $('.device_command').val();
            $('#region2').addClass('on');
            $('.tail_spin').css('display','block');
            $.post(Week_Region_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
                $('#region2').removeClass('on');
                $('.tail_spin').css('display','none');
                if($.trim(data).length>0){
                    $('#week-region').html(data);
                }else{
                    $(".nonumber").css('display','block');
                    $(".nonumber").html("今周全区机器还没有人玩哦，抢先占第一名");
                }
            }, 'html');
        });
        //周的全国
        $("#country2").tap(function(){
            $(".rank").css('display','none');
            $("#week-country").css('display','block');

            if($('#country2').hasClass('on')){
                return false;
            }
            var Week_Counity_Randing = $('.Week_Counity_Randing').val();
            var openid = $('.openid').val();
            var group_id = $('.group_id').val();
            var device_command = $('.device_command').val();
            $('#country2').addClass('on');
            $('.tail_spin').css('display','block');
            $.post(Week_Counity_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
                $('#country2').removeClass('on');
                $('.tail_spin').css('display','none');
                if($.trim(data).length>0){
                    $('#week-country').html(data);
                }else{
                    $(".nonumber").css('display','block');
                    $(".nonumber").html("今周全国机器还没有人玩哦，抢先占第一名");
                }
            }, 'html');
        });
    });
    $("#month").tap(function(){
        $(".area").css('display','none');
        $(".area-month").css('display','block'); //“月”下的第一个单台、全区、全国出现

        $(".date li").css('background','#fff');
        $(".date li a").css('color','rgb(42,182,144)');
        $("#month").css('background','rgb(42,182,144)');
        $("#month a").css('color','#fff'); //颜色变化

        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single3").css('border','1px solid rgb(255,110,53)');
        $("#single3 a").css('color','rgb(255,110,53)');

        $(".rank").css('display','none');
        $("#month-single").css('display','block');

        if($('#month').hasClass('on')){
            return false;
        }
        var Month_Stand_alone_Randing = $('.Month_Stand_alone_Randing').val();
        var openid = $('.openid').val();
        var group_id = $('.group_id').val();
        var device_command = $('.device_command').val();
        $('#month').addClass('on');
        $('.tail_spin').css('display','block');
        $.post(Month_Stand_alone_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
            $('#month').removeClass('on');
            $('.tail_spin').css('display','none');
            if($.trim(data).length>0){
                $('#month-single').html(data);
            }else{
                $(".nonumber").css('display','block');
                $(".nonumber").html("今月单台机器还没有人玩哦，抢先占第一名");
            }
        }, 'html');

        $("#single3").tap(function(){
            $("#single3").css('border','1px solid rgb(255,110,53)');
            $("#single3 a").css('color','rgb(255,110,53)');
            $(".rank").css('display','none');
            $("#month-single").css('display','block');
        });
        $("#region3").tap(function(){
            $(".rank").css('display','none');
            $("#month-region").css('display','block');

            if($('#single3').hasClass('on')){
                return false;
            }
            var Month_Region_Randing = $('.Month_Region_Randing').val();
            var openid = $('.openid').val();
            var group_id = $('.group_id').val();
            var device_command = $('.device_command').val();
            $('#single3').addClass('on');
            $('.tail_spin').css('display','block');
            $.post(Month_Region_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
                $('#single3').removeClass('on');
                $('.tail_spin').css('display','none');
                if($.trim(data).length>0){
                    $('#month-region').html(data);
                }else{
                    $(".nonumber").css('display','block');
                    $(".nonumber").html("今月全区机器还没有人玩哦，抢先占第一名");
                }
            }, 'html');
        });

        $("#country3").tap(function(){
            $(".rank").css('display','none');
            $("#month-country").css('display','block');

            if($('#country3').hasClass('on')){
                return false;
            }
            var Month_Counity_Randing = $('.Month_Counity_Randing').val();
            var openid = $('.openid').val();
            var group_id = $('.group_id').val();
            var device_command = $('.device_command').val();
            $('#country3').addClass('on');
            $('.tail_spin').css('display','block');
            $.post(Month_Counity_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
                $('#country3').removeClass('on');
                $('.tail_spin').css('display','none');
                if($.trim(data).length>0){
                    $('#month-country').html(data);
                }else{
                    $(".nonumber").css('display','block');
                    $(".nonumber").html("今月全区机器还没有人玩哦，抢先占第一名");
                }
            }, 'html');
        });
    });
    $("#single1").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single1").css('border','1px solid rgb(255,110,53)');
        $("#single1 a").css('color','rgb(255,110,53)');
    });
    //单日全区
    $("#region1").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#region1").css('border','1px solid rgb(255,110,53)');
        $("#region1 a").css('color','rgb(255,110,53)');

        if($('#region1').hasClass('on')){
            return false;
        }
        var Now_Region_Randing = $('.Now_Region_Randing').val();
        var openid = $('.openid').val();
        var group_id = $('.group_id').val();
        var device_command = $('.device_command').val();
        $('#region1').addClass('on');
        $('.loading_animate').css('display','block');
        $('.tail_spin').css('display','block');
        $.post(Now_Region_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
            $('#region1').removeClass('on');
            $('.tail_spin').css('display','none');
            if($.trim(data).length>0){
                $('#date-region').html(data);
            }else{
                $(".nonumber").css('display','block');
                $(".nonumber").html("今日全区还没有人玩哦，抢先占第一名");
            }
        }, 'html');
    });
    //单日全国
    $("#country1").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#country1").css('border','1px solid rgb(255,110,53)');
        $("#country1 a").css('color','rgb(255,110,53)');

        if($('#country1').hasClass('on')){
            return false;
        }
        var Now_Counity_Randing = $('.Now_Counity_Randing').val();
        var openid = $('.openid').val();
        var group_id = $('.group_id').val();
        var device_command = $('.device_command').val();
        $('#country1').addClass('on');
        $('.loading_animate').css('display','block');
        $('.tail_spin').css('display','block');
        $.post(Now_Counity_Randing, {group_id:group_id,openid:openid,device_command:device_command}, function (data) {
            $('#country1').removeClass('on');
            $('.tail_spin').css('display','none');
            if($.trim(data).length>0){
                $('#date-country').html(data);
            }else{
                $(".nonumber").css('display','block');
                $(".nonumber").html("今日全国还没有人玩哦，抢先占第一名");
            }
        }, 'html');
    });


    $("#single2").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single2").css('border','1px solid rgb(255,110,53)');
        $("#single2 a").css('color','rgb(255,110,53)');
    });
    $("#region2").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#region2").css('border','1px solid rgb(255,110,53)');
        $("#region2 a").css('color','rgb(255,110,53)');
    });
    $("#country2").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#country2").css('border','1px solid rgb(255,110,53)');
        $("#country2 a").css('color','rgb(255,110,53)');
    });
    $("#single3").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#single2").css('border','1px solid rgb(255,110,53)');
        $("#single2 a").css('color','rgb(255,110,53)');
    });
    $("#region3").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#region3").css('border','1px solid rgb(255,110,53)');
        $("#region3 a").css('color','rgb(255,110,53)');
    });
    $("#country3").tap(function(){
        $(".area li").css('border','1px solid #aaa');
        $(".area li a").css('color','#777');
        $("#country3").css('border','1px solid rgb(255,110,53)');
        $("#country3 a").css('color','rgb(255,110,53)');
    });
});