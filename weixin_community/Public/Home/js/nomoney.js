
Zepto(function($) {
    $(".lingquan").tap(function(){
        if($(".lingquan").hasClass('on')){
            return false;
        }
        var ccid = $(this).attr('ccid');
        var quantity = $(this).attr('quantity');
        var merchantid = $(this).attr('merchantid');
        var openId = $(".openId").val();
        var deviceCode = $(".device_command").val();
        var insert_doubi_url = $(".insert_doubi").val();
        $(".lingquan").addClass('on');
        $.ajax({
            type: 'POST',
            url: insert_doubi_url,
            data: {"user_id":openId,"merchant_id":merchantid,"card_config_id":ccid,"quantity":quantity,"device_code":deviceCode},
            dataType: 'json',
            timeout: 3000,
            async:false,
            success: function(data){
                $(".lingquan").removeClass('on');
                if(data.code==40001){
                    new TipBox({type: 'tip', str: data.msg, setTime: 1500});
                }
                if(data.code==200){
                    new TipBox({type: 'success', str: data.msg, setTime: 1500});
                }else {
                    new TipBox({type: 'error', str: data.msg, setTime: 1500});
                }

            },
            error: function(xhr, type){
                $(".lingquan").removeClass('on');
                new TipBox({type: 'error', str: '领取错误', setTime: 1500});
            }
        });
    });

});