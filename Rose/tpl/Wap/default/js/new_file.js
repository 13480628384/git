$(function(){
	var a=100;
	$(".person_wallet_recharge .ul li").click(function(e){
		$(this).addClass("current").siblings("li").removeClass("current");
		$(this).children(".sel").show(0).parent().siblings().children(".sel").hide(0);
        var rose = $(this).attr('rose');
        $('#rose').val(rose);
	});
	//充值
	$(".botton").click(function(e){
		if(!$(".person_wallet_recharge .ul li").hasClass('current')){
		layer.open({
            content: '请选择金额',
            style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
            time:3
           });
           return false;
		}
		var money = $(".person_wallet_recharge ul li.current").attr('money');
		$('.money_xuan').html(money);
		$(".f-overlay").show();
		$(".addvideo").show();
	});
	$(".cal").click(function(e){
		$(".f-overlay").hide();
		$(".addvideo").hide();
	});
	//微信充值 start
	$('.weixin_pay').click(function () {
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', jsApiCall);
				document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			}
		}else {
			var jsApiParameters = null;
			var outTradeNo = null;//商户订单号
			var isGoToPay = false;
			var weixin = $('#weixin').val();
			var openid = $('#openid').val();
			var rose = $('#rose').val();//赠送玫瑰币
			var device_code = $('#device_code').val();
			var owner_id = $('#owner_id').val();
			var update = $('#update').val();
			var money_xuan = parseInt($('.money_xuan').html());
			$.ajax({
				type: 'POST',
				url: weixin,
				data: {"price": money_xuan,"rose":rose,"openid": openid, "device_code": device_code, "owner_id": owner_id},
				dataType: "json",
				timeout: 3000,
				async: false,
				success: function (data) {
					if (data.code == 200) {
						isGoToPay = true;
						jsApiParameters = data.jsapi;
						outTradeNo = data.out_trade_no;
					} else {
						layer.open({
							content: data.error,
							style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
							time: 3
						});
					}
				},
				error: function (xhr, type) {
					layer.open({
						content: '支付错误,请重新支付',
						style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
						time: 3
					});
				}
			});
			//判断是否调用支付控件
			if (isGoToPay) {
				var jsPs = eval('(' + jsApiParameters + ')');
				WeixinJSBridge.invoke('getBrandWCPayRequest',jsPs,
					function(res){
						if(res.err_msg == "get_brand_wcpay_request:ok" ){
							$.ajax({
								type: 'POST',
								url: update,
								data: {
									"out_trade_no":outTradeNo,
									"openid":openid,
									"device_code":device_code
								},
								dataType: 'json',
								async:false,
								success: function(data){
									if(data.code == 200){
										alert('充值成功');
										window.location.href=data.url;
									}else{
										layer.open({
											content: '支付失败',
											style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
											time:3
										});
									}
									canUpdateRemainsum1 = true;
								},
								error: function(xhr, type){
									layer.open({
										content: '支付错误,请重新支付',
										style: 'background:rgba(0,0,0,0.6); color:#fff; border:none;',
										time:3
									});
									canUpdateRemainsum1 = true;
								}
								});
						}
					}
				);
			}
		}
	});
	//微信充值 end
});
