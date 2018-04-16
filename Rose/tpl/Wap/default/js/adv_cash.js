Zepto(function($){
    $('.addBtn').tap(function(){
        if($(this).hasClass('on')){
            return false;
        }
        var tixian_money = $('.tixian_money').val();
        var amounts = $('#amount').val();
        var openid = $('.openid').val();
        var arral = $('.arral').val();
        var outtip = $('#outtip').text();
        var reg = /^[\u4e00-\u9fa5]+$/;
        var myreg = /.*\..*/;
        if (amounts==""){
            $.dialog({
                content:'请输入提现金额',
                button:['好']
            });
            return;
        }
        if(reg.test(amounts)){
            $.dialog({
                content:'请输入数字',
                button:['好']
            });
            return;
        }
        var amount = parseInt(amounts);
        if(myreg.test(amounts)){
            $.dialog({
                content:'请输入整数',
                button:['好']
            });
            return;
        }
        if(amount>=10) {
            var fact = amount - parseFloat(amount*0.006).toFixed(2);//实际到账
            if (outtip < fact) {
                $.dialog({
                    content: '余额不足',
                    button: ['好']
                });
                return;
            }
        }else{
            if (outtip < amount) {
                $.dialog({
                    content: '余额不足',
                    button: ['好']
                });
                return;
            }
        }
        if (amount > 20000){
            $.dialog({
                content:'最高提现金额2万元',
                button:['好']
            });
            return;
        }
        $(this).addClass('on');
        var Present = $('.Present_location').val();
        var el=$.loading({
            content:'正在提现'
        });
        $.post(tixian_money,{amount:amount,openid:openid},function(res){
            $('.addBtn').removeClass('on');
            if (res.result_code=='SUCCESS') {
                var DG = $.dialog({
                    content:res.return_msg,
                    button:['好']
                });
                $("#outtip").text(res.return_ext-amount);
                el.hide();
                DG.on('dialog:action',function(e){
                    document.location.href=Present+"&openid="+openid;
                });
                return;
            } else if(res.result_code==3){
                $.dialog({
                    content:res.return_msg,
                    button:['好']
                });
                el.hide();
                return;
            }else if(res.result_code==4){
                $.dialog({
                    content:res.return_msg,
                    button:['好']
                });
                el.hide();
                return;
            }else {
                $.dialog({
                    content:res.return_msg,
                    button:['好']
                });
                el.hide();
                return;
            }
        },'json')

    })
})