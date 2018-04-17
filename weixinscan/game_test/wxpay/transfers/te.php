<!DOCTYPE html>
<html lang="ch" manifest="">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>1242134</title>
	<meta http-equiv="Cache-Control" content="max-age=0">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum- scale=1.0, maximum-scale=1.0,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/play.css?9656501">
	<link rel="stylesheet" type="text/css" href="css/zepto.alert.css?6145">
	<link rel="stylesheet" type="text/css" href="css/mobi.css">
</head>
<body>
			<button type="button" id="paymoney" style="width:100px;height:100px">提现</button>
</body>
<script type="text/javascript" src="js/zepto.min.js?223"></script>
<script type="text/javascript" src="js/frozen.js"></script>
<script type="text/javascript" src="js/zepto.alert.js?1233"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js?4324"></script>
<script type="text/javascript" src="js/fastclick.min.js"></script>
<script>
Zepto(function($) { 
//支付
		$("#paymoney").tap(function(){
			callpay();		
		});
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
			$.ajax({
			  type: 'POST',
			  url: 'test.php',
			  data: {},
			  dataType: 'text',
			  timeout: 3000,
			  async:false,
			  success: function(data){
				  if(0 != data){
					jsApiParameters = data;
				  }				  
			  },
			  error: function(xhr, type){
				$.dialog({
                    content : '提现异常',
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
			if(jsApiParameters != null){
				 jsApiCall(jsApiParameters);
			}
			
		   
		}
	}
	
	
	function jsApiCall( jsApiParameters)
	{
		var jsPs = eval('(' + jsApiParameters + ')');
		var device_command = $('#device_command').val();//2016-03-31 author chw
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			jsPs ,
			function(res){
				 if(res.err_msg == "get_brand_wcpay_request:ok" ){
					 alert(111);					
				 }										 
			}
		);
	}

})
</script>
</html>