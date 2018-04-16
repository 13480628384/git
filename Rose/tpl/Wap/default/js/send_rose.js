/*******************************
 * @Copyright:玫瑰物联
 * @Creation date:2016.11.25
 *******************************/
var REG = {
    name: /^[a-zA-Z\u4e00-\u9fa5]{2,8}$/,
    phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/,
    passwd:/^[0-9]{6,8}$/,
    number:/^[1-9]|0$/,
    id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/,
    myReg:/^[\u4e00-\u9fa5]+$/,
    reg:/.*\..*/
}
Zepto(function($){
    var send_rose=$('.send_rose');
    send_rose.tap(function(){
        var submitReg=$('#J_submitReg').val();
        var quotient_id=$('#quotient_id').val();
        var scan_code=$('#scan_code').val();
        var weixin_alipay_type=$('#weixin_alipay_type').val();
        var user_id=$('#user_id').val();
        var nickname =$.trim($('.nickname').val());
        var total =$.trim($('.total').val());
        var content =$(".content").val();
        if(nickname==''){
            $.dialog({
                content:'用户昵称不能为空',
                button:['好']
            });
            return false;
        }
        if(total == 0 || !REG.number.test(total)){
            $.dialog({
                content:'赠送数量不能为0',
                button:['好']
            });
            return false;
        }
        if(content==''){
            $.dialog({
                content:'赠言不能为空',
                button:['好']
            });
            return false;
        }
        if(content.length>30){
            $.dialog({
                content:'30个字以内',
                button:['好']
            });
            return false;
        }
        var el=$.loading({
            content:'正在提交'
        });
        var DATA={
            content:content,
            total:total,
            nickname:nickname,
            quotient_id:quotient_id,
            user_id:user_id,
            scan_code:scan_code,
            weixin_alipay_type:weixin_alipay_type
        };
        $.post(submitReg,DATA,function(data){
            if(data.code==200){
                var DG = $.dialog({
                    content:'赠送成功',
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
    /*=====================购买生态红玫瑰 [[======================*/
    $('.buy_red_rose').tap(function(){
        var buyrose_number = $.trim(($('.buyrose_input').val()));
        var weixin_alipay_type=$('#weixin_alipay_type').val();
        var user_id=$('#user_id').val();
        var quotient_id=$('#quotient_id').val();
        var Alipay=$('#Alipay').val();
        if(buyrose_number <=0 ){
            $.dialog({
                content:'玫瑰不能低于0哦',
                button:['好']
            });
            return false;
        }
        if(buyrose_number > 100000 ){
            $.dialog({
                content:'玫瑰不能高于100000',
                button:['好']
            });
            return false;
        }
        if(buyrose_number < 10){
            $.dialog({
                content: '数量不能小于10个',
                button: ['好']
            });
            return false;
        }
        if(REG.reg.test(buyrose_number/10)){
            $.dialog({
                content: '请输入整数',
                button: ['好']
            });
            return false;
        }
        var DATA = {
            buyrose_number:buyrose_number,
            weixin_alipay_type:weixin_alipay_type,
            user_id:user_id,
            quotient_id:quotient_id
        };
        var el=$.loading({
            content:'正在支付'
        });
        //微信支付
        if(weixin_alipay_type == 'wechat'){
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else {
                    var jsApiParameters = null;
                    var out_trade_no = null;
                    var isGoToPay = false;
                    var Wechat = $('#Wechat').val();
                    $.ajax({
                        type: 'POST',
                        url: Wechat,
                        data: DATA,
                        dataType: "json",
                        timeout: 3000,
                        async: false,
                        success: function (data) {
                            el.hide();
                            if (data.code == 200) {
                                isGoToPay = true;
                                jsApiParameters = data.jsapi;
                                out_trade_no = data.out_trade_no;
                            } else {
                                $.dialog({
                                    content: data.error,
                                    button: ['好']
                                });
                                return false;
                            }
                        },
                        error: function (xhr, type) {
                            el.hide();
                            $.dialog({
                                content: '支付错误',
                                button: ['好']
                            });
                        }
                    });
                    if(isGoToPay){
                        var jsPs = eval('(' + jsApiParameters + ')');
                        WeixinJSBridge.invoke(
                            'getBrandWCPayRequest',
                            jsPs ,
                            function(res){
                                if(res.err_msg == "get_brand_wcpay_request:ok" ){
                                    var update = $('#update').val();
                                    $.ajax({
                                        type: 'POST',
                                        url: update,
                                        data: {
                                            "out_trade_no":out_trade_no,
                                            quotient_id:quotient_id,
                                            user_id:user_id
                                        },
                                        dataType: 'json',
                                        async:false,
                                        success: function(data){
                                            if(data.code == 200){
                                                $('.red_count').html(data.count);
                                            } else {
                                                $.dialog({
                                                    content: '支付错误',
                                                    button: ['好']
                                                });
                                                return false;
                                            }

                                        },
                                        error: function(xhr, type){
                                            $.dialog({
                                                content: '支付错误',
                                                button: ['好']
                                            });
                                            return false;
                                        }
                                    });

                                }
                            }
                        );
                    }
                }
            } else {
            post(Alipay,DATA);
        }
    });
    /*=====================购买生态红玫瑰 ]]======================*/
});