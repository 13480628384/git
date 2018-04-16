/*******************************
 * @Copyright:玫瑰物联
 * @Creation date:2016.12.13
 *******************************/
var REG = {
    name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
    phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
    passwd:/^[0-9]{6,8}$/,
    number:/^[1-9]|0$/,
    email: /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/,
    id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/
}
Zepto(function($){
    var anniu=$('.anniu');
    anniu.tap(function(){
        var submitReg= $('#J_submitReg').val();
        var uid= $('#uid').val();
        var user_id= $('#user_id').val();
        var scan_code= $('#scan_code').val();
        var weixin_alipay_type= $('#weixin_alipay_type').val();
        var nickname = $.trim($('.nickname').val());
        var wechat_number = $.trim($('.wechat_number').val());
        var alipay_number = $(".alipay_number").val();
        var phone = $(".phone").val();
        var email = $(".email").val();
        if(nickname==''){
            $.dialog({
                content:'用户昵称不能为空',
                button:['好']
            });
            return false;
        }
        if(!REG.phone.test(phone)){
            $.dialog({
                content:'请输入正确的电话号码',
                button:['好']
            });
            return false;
        }
        if(!REG.email.test(email)){
            $.dialog({
                content:'请输入正确的邮箱',
                button:['好']
            });
            return false;
        }
        var el=$.loading({
            content:'正在提交'
        });
        var DATA={
            alipay_number:alipay_number,
            wechat_number:wechat_number,
            nickname:nickname,
            phone:phone,
            email:email,
            uid:uid,
            scan_code:scan_code,
            weixin_alipay_type:weixin_alipay_type,
            user_id:user_id
        };
        $.post(submitReg,DATA,function(data){
            if(data.code==200){
                var DG = $.dialog({
                    content:'绑定成功',
                    button:['好']
                });
                DG.on('dialog:action',function(e){
                    document.location.href=data.url;
                });
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