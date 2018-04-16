var REG = {
    name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
    phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
    passwd:/^[0-9]{6,8}$/,
    id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/
}
Zepto(function($){
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
            }else if(data.code==300){
                $.dialog({
                    content:'手机号码还没注册',
                    button:['好']
                });
                return false;
            } else {
                $.dialog({
                    content:'验证码发送失败',
                    button:['好']
                });
                return false;
            }
        },'json')
    })
    var reg_phone=$('#reg_phone');
    var submitReg=$('#J_submitReg');
    submitReg.tap(function(){
        var phone =$.trim(reg_phone.val());
        var code =$("#code").val();
        var openid =$("#openid").val();
        if(phone==''){
            $.dialog({
                content:'手机号不能为空',
                button:['好']
            });
            return false;
        }
        if(!REG.phone.test(phone)){
            $.dialog({
                content:'请输入正确的手机号码',
                button:['好']
            });
            return false;
        }
        if(code==''){
            $.dialog({
                content:'手机验证码不能为空',
                button:['好']
            });
            return false;
        }
        var el=$.loading({
            content:'正在提交'
        });
        var DATA={
            phone:phone,
            code:code,
            openid:openid
        };
        $.post($('#submitUrl').val(),DATA,function(data){
            if(data.code==200){
                document.location.href=data.url;
            }else if(data.code==500){
                $.dialog({
                    content:data.error,
                    button:['好']
                });
            }else{
                $.dialog({
                    content:'网络错误，请重试',
                    button:['好']
                });
            }
            el.hide();
        },'json');
    });
});