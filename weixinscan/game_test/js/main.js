	//是否判断可加载
	var canUpdateRemainsum = true;
	
	//调用微信JS api 支付
	function jsApiCall( jsApiParameters,outTradeNo)
	{
		
		var jsPs = eval('(' + jsApiParameters + ')');
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			jsPs ,
			function(res){
				//WeixinJSBridge.log(res.err_msg);
				 if(res.err_msg == "get_brand_wcpay_request:ok" ){
					 //更新支付明细
					 //返回支付的汇总记录						  
					$.ajax({
					  type: 'POST',
					  url: 'update_pay_info.php',
					  data: {"app_id":jsPs.appId,	
							"out_trade_no":outTradeNo,
							"open_id":$("#openId").val()},
					  dataType: 'text',			  
					  async:false,
					  success: function(data){		
						  var addPrice = $('#current_payprice').val();
						  var curPrice = $("#balances").html();//余额
						  var nedPrice = parseInt(curPrice)+parseInt(addPrice);
						  $("#balances").html(""+nedPrice);
						  canUpdateRemainsum = true;  
						 		  
					  },
					  error: function(xhr, type){				
						$.dialog({
							content : '充值错误,请重新点击充值',
							title: "alert",
							width: 600,
							time : 2000
						});
						 canUpdateRemainsum = true;
					  }
					});					
											
				 }										 
			}
		);
	}
	
	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
			var jsApiParameters =  null;
			var outTradeNo =null;//商户订单号
			
			var curPrice = $('#current_payprice').val();
			var isGoToPay = false;
			$.ajax({
			  type: 'POST',
			  url: 'wxpay/JsApiParameters.php',
			  data: {"total_price":curPrice,"open_id":$("#openId").val()},
			  dataType: "json",
			  timeout: 3000,
			  async:false,
			  success: function(data){
				  
				  if(0 != data){
					isGoToPay = true;
					jsApiParameters = data.jsApiParameters;	
					outTradeNo = data.outTradeNo;
				  }				  
			  },
			  error: function(xhr, type){				
				$.dialog({
                    content : '充值错误,请重新充值',
                    title: "alert",
					width: 600,
                    time : 2000
                });
			  }
			});	

			$.dialog({
                    content : '加载中...',
                    title: "load",					
                    time : 500
            });
			//判断是否调用支付控件
			if(isGoToPay){				
				 jsApiCall(jsApiParameters,outTradeNo);
			}
			
		   
		}
	}
	var clicktag = 0;  
	
	
	Zepto(function($) { 
	
		//处理移动端 click 事件 300 毫秒延迟		
		FastClick.attach(document.body);
		
		
		//默认充值金额
		$(".feilei .cash").first().addClass('this'); 
		
		
		
		
		//默认选择设备
		var defaultDevicecommand=$("#defaultDevicecommand").val();	
		
		$("#launch > li[dcid='"+defaultDevicecommand+"']").addClass("lion one");
		
		//充值选择
		$(".cash").tap(
			function(){
				var sv = $(this).attr('sv'); 
				$("#current_payprice").val(sv);
				//样式				
				$(".cash").removeClass('this');
				$(this).addClass('this');				
			}
		);
		
		
		//广告动态跳转
		$("#adurl_div").tap(
			function(){
				var ad_url = $("#adv_url").val();
				var current_di_id = $("#launch > li[class='lion']").attr('did');
				//alert(ad_url +current_di_id);
				window.location.href= ad_url + current_di_id;				
			}		
		);
		
		
		//支付
		$("#wxpayid").tap(function(){


			var isok = $('#isok').val();
			if(isok==1){
				alert('设备不在线，请勿充值');
				return false;
			}

			canUpdateRemainsum = false;
			
			callpay();		
		});
		
		//启动设备
		$("#launch > li").tap(function(){

		var zimu=$(this).attr("cd");
		var c = $('#zimu'+zimu).html();
		var numbers = parseInt(c)+1;
		$('#zimu'+zimu).html(numbers);
		var three = $('#zimu'+zimu).html();
			$(this).addClass('lion').siblings().removeClass('lion one');
		if(three==2 || $(this).is(".one") ||$(this).is(".onete")){

			
		$('#zimu'+zimu).html(0);



			canUpdateRemainsum = false;

	
			//设置默认样式						
			$("#launch > li").removeClass('lion');
			// 点击那个就给那个添加一个lion(选中的样式)
			$(this).addClass('one lion');
			

			
			//防止反复提交,时间限制2秒内
			if(clicktag==1){								
				return ;				
			}			
			if(clicktag==0){
				clicktag=1;
				setTimeout(function () { clicktag = 0 }, 1500); 
			}	
		
			
			//判断是否需要充值
			var cPrice = $("#balances").html();			
			var price = $(this).attr('dprice'); 
			price = parseInt(price);
			if(parseInt(cPrice)<price){ 
				$.dialog({
                    content : '额度不足,请充值',
                    title: "ok",
					width: 600,
                    time : 2000
                });					
				return ;
			}			
			
			var device_command = $(this).attr('dcid');
			var device_id = $(this).attr('did');
			var nos = $(this).attr('cd');

			
			
			
				
			//1.判断设备在线--------start---------------
			var isOnline= false;			
			//获取在线状态
			$.ajax({
			  type: 'POST',
			  url: 'is_device_online.php',
			  data: {"device_command":device_command},
			  dataType: 'text',
			  timeout: 3000,
			  async:false,
			  success: function(data){				  
				isOnline = data;				 		  
			  },
			  error: function(xhr, type){				
				isOnline = false;	
			  }
			});	
			
			//不在线，不启动
			if(isOnline==false){				
				$.dialog({
                    content : nos+'临时维护中,请点击其他字母启动',
                    title: "ok",
					width: 600,
                    time : 2000
                });					
				return ;
			}	
			//1.判断设备在线--------end---------------

		
			
			//2.发送启动指令及添加启动消费记录-----start-------			
			var send_url = "devicecommand/sendcommand_recordpayinfo.php";				
			$.ajax({
			  type: 'POST',
			  url: send_url,
			  data: {device_command:device_command,device_id: device_id,"app_id":$("#appId").val(),"open_id":$("#openId").val(),price:price},
			  dataType: 'text',
			  async : false,					  
			  success: function(data){
				
				if(data!=0){
					$.dialog({
						content :nos+ '已启动,请准备完美操作',
						title: "ok",
						width: 600,
						time : 2000
					});
					var curPrice = $("#balances").html();//余额
					var nedPrice = parseInt(curPrice)-(price);//扣除余额
					$("#balances").html(""+nedPrice); 	//启动后显示余额
						// 如果启动过就添加一个	 没有启动就不添加	
					$(this).addClass('onete');			
					
				} else if(data == 2){
					$.dialog({
						content : '额度不足,请充值',
						title: "alert",
						width: 600,
						time : 2000
					});
				} else {
					$.dialog({
						content : nos+'临时维护中,请点击其他字母启动',
						title: "alert",
						width: 600,
						time : 2000
					});
					
				}
			  },
			  error: function(xhr, type,error){						
				$.dialog({
					content : nos+'临时维护中,请点击其他字母启动',
					title: "alert",
					width: 600,
					time : 2000
				});
			  }
			});
			
			//2.发送启动指令及添加启动消费记录-----end-------
			canUpdateRemainsum = true;
}else if(three==1){
//如果第一次点击的时候当前点击的类以及有lion
	$(this).siblings().children('tt').html(0);

	}

		
		});
			
			
		 //加载用户余额			
		$.ajax({
		  type: 'POST',
		  url: 'load_user_remainsum.php',
		  data: {"app_id":$("#appId").val(),"open_id":$("#openId").val()},
		  dataType: 'text',
		  async:false,
		  success: function(data){
			  if(data == '-1'){
				 $.dialog({
					content : '请重新扫描进入',
					title: "alert",
					width: 600,
					time : 2000
				});
				return ;
			  }
			  $("#balances").html(data);		  
		  },
		  error: function(xhr, type){				
			$.dialog({
				content : '请重扫码加载余额',
				title: "alert",
				width: 600,
				time : 2000
			});
		  }
		});	
		
		
		
		//定时轮询加载余额（根据canUpdateRemainsum判断）
		
		function update_remainsum(){
			if(canUpdateRemainsum){
				$.ajax({
				  type: 'POST',
				  url: 'load_user_remainsum.php',
				  data: {"app_id":$("#appId").val(),"open_id":$("#openId").val()},
				  dataType: 'text',
				  async:false,
				  success: function(data){
					 // $("#balances").html(data);		  
				  },
				  error: function(xhr, type){				
					// do nothing
				  }
				});	
				
			}
		}
		setInterval("update_remainsum()",1000*60);//60秒一次执行
			
        
    }); 