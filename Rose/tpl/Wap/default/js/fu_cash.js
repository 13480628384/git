Zepto(function($){
    var REG = {
        name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
        phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
        passwd:/^[0-9]{6,8}$/,
        id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/
    }
    //验证码
    $('.code').tap(function(){
        if($('.code').hasClass('co')){
            return false;
        }
        var reg_phone=$('#reg_phone').val();
        var shortmessage=$('#shortmessage').val();
        if(reg_phone==''){
            $.dialog({
                content:'手机号不能为空',
                button:['好']
            });
            return false;
        }
        if(!REG.phone.test(reg_phone)){
            $.dialog({
                content:'请输入正确的手机号码',
                button:['好']
            });
            return false;
        }
        var countdown=60;
        var i = setInterval(function() {
            if (countdown == 0) {
                $('.code').removeClass('co');
                $('.code').html("获取验证码");
                countdown = 60;
                clearInterval(i);
                return;
            } else {
                $('.code').addClass('co');
                $('.code').html("重新发送（"+countdown+"）");
                countdown--;
            }
        },1000);
        $.post(shortmessage,{phone:reg_phone},function(data){
            if(data.code==200){
                $.dialog({
                    content:'验证码已发出，请注意查收',
                    button:['好']
                });
                return false;
            }else if(data.code == 500){
                $.dialog({
                    content:data.error,
                    button:['好']
                });
                return false;
            } else {
                $.dialog({
                    content:'发送失败',
                    button:['好']
                });
                return false;
            }
        },'json')
    })
    //验证码
    var cash_money = $('.cash_money').val();
    var openid = $('.openid').val();
    $.ajax({
        type:"POST",
        dataType:'json',
        data:{openid:openid},
        url:cash_money,
        success:function(res){
            if (res.result==200){
                if(res.reg == null){
                    $("#outtip").text(0);
                }else{
                    $("#outtip").text(Math.round(res.reg*100)/100);
                }
            }else{
                $("#outtip").text(0);
            }
        },
        error:function(){
            var DG=$.dialog({
                content:'加载余额错误',
                button:['好']
            });
        }
    });
    $('.addBtn').tap(function(){
        //if(confirm("确定要提现吗")) {
        if ($(this).hasClass('on')) {
            return false;
        }
        var tixian_money = $('.tixian_money').val();
        var amounts = $('#amount').val();
        var openid = $('.openid').val();
        var arral = $('.arral').val();
        var outtip = $('#outtip').text();
        var reg = /^[\u4e00-\u9fa5]+$/;
        var myreg = /.*\..*/;
        var code = $("#code").val();
        var reg_phone = $('#reg_phone').val();
        if (amounts == "") {
            $.dialog({
                content: '请输入提现金额',
                button: ['好']
            });
            return;
        }
        if (reg.test(amounts)) {
            $.dialog({
                content: '请输入数字',
                button: ['好']
            });
            return;
        }
        var amount = parseInt(amounts);
        if (myreg.test(amounts)) {
            $.dialog({
                content: '请输入整数',
                button: ['好']
            });
            return;
        }
		if (amounts < 10) {
            $.dialog({
                content: '请提现10元以上',
                button: ['好']
            });
            return;
        }
        if (!REG.phone.test(reg_phone)) {
            $.dialog({
                content: '请输入正确的手机号码',
                button: ['好']
            });
            return false;
        }
        if (code == '') {
            $.dialog({
                content: '手机验证码不能为空',
                button: ['好']
            });
            return false;
        }

        if (amount >= 10) {
            var fact = amount - parseFloat(amount * 0.006).toFixed(2);//实际到账
            if (outtip < fact) {
                $.dialog({
                    content: '余额不足',
                    button: ['好']
                });
                return;
            }
        } else {
            if (outtip < amount) {
                $.dialog({
                    content: '余额不足',
                    button: ['好']
                });
                return;
            }
        }
        if (amount > 20000) {
            $.dialog({
                content: '最高提现金额2万元',
                button: ['好']
            });
            return;
        }
        $(this).addClass('on');
        var Present = $('.Present_location').val();
        var el = $.loading({
            content: '正在提现'
        });
        $.post(tixian_money, {amount: amount, openid: openid, arral: arral, code: code}, function (res) {
            $('.addBtn').removeClass('on');
            if (res.result_code == 'SUCCESS') {
                var DG = $.dialog({
                    content: res.return_msg,
                    button: ['好']
                });
               // $("#outtip").text(res.return_ext);
                el.hide();
                DG.on('dialog:action', function (e) {
                    document.location.href = Present + "&openid=" + openid;
                });
                return;
            } else if (res.result_code == 3) {
                $.dialog({
                    content: res.return_msg,
                    button: ['好']
                });
                el.hide();
                return;
            } else if (res.result_code == 4) {
                $.dialog({
                    content: res.return_msg,
                    button: ['好']
                });
                el.hide();
                return;
            } else {
                $.dialog({
                    content: res.return_msg,
                    button: ['好']
                });
                el.hide();
                return;
            }
        }, 'json')
        //}
    })
})