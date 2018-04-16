<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
	<link rel="stylesheet" href="./tpl/Wap/default/css/public.css?v1">
	<link rel="stylesheet" href="./tpl/Wap/default/css/frozen.css">
	<link rel="stylesheet" href="./tpl/Wap/default/css/mobi.css">
	<link rel="stylesheet" href="./tpl/Wap/default/css/adv.css?25">
<title>广告</title>
</head>
<body style="background: #f2f2f2">
<div class="adv">
		<div class="adv-top">
			<div class="adv-top-left">
				<?php if($rose['headimgurl'] == null): ?><img class="adv-top-left-pic" src="./tpl/Wap/default/img/reg_1.png" alt="">
					<?php else: ?><img class="adv-top-left-pic" src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
			<p class="adv-top-left-p">
			 <img src="./tpl/Wap/default/img/hmg7.png" alt="" align="absmiddle"><span class="count"><?php echo intval($rose['yellow_rose']); ?></span>
			</php>
		  	</div>
			<div class="adv-top-right">
				<ul>
					<li class="adv-top-right-img1" datanumber="200" dataprice="20"><img src="./tpl/Wap/default/img/hmg1.png" alt=""></li>
					<li class="adv-top-right-img2" datanumber="1000" dataprice="100"><img src="./tpl/Wap/default/img/hmg2.png" alt=""></li>
					<li class="adv-top-right-img3" datanumber="5000" dataprice="500"><img src="./tpl/Wap/default/img/hmg3.png" alt=""></li>
					<li class="adv-top-right-img4" datanumber="10000" dataprice="1000"><img src="./tpl/Wap/default/img/hmg4.png" alt=""></li>
					<li class="adv-top-right-img5" datanumber="50000" dataprice="5000"><img src="./tpl/Wap/default/img/hmg5.png" alt=""></li>
					<li class="buy_yellow"><img src="./tpl/Wap/default/img/hmg6.png" alt=""></li>
				</ul>
		  	</div>		
		</div>
		<div style="height: 1.5rem; background: #f2f2f2"></div>
		<div class="adv-con">
			<h2>自动发布网页导流广告</h2>
			<p><span>广告标题</span><input type="text" class="title" placeholder="20字以内"></p>
			<p><span></span><img class="ggimg click_pic_div" src="./tpl/Wap/default/img/hmg9.png" alt=""></p>
			<p><span>网页链接</span><input type="text" class="url"></p>
			<h6>注:发布封页广告请联系微信号QQ号含吸粉/折扣售 货/广告赠品发放/发放优惠券等</h6>
			<div class="adv-con-buttonimg"><img class="submits" src="./tpl/Wap/default/img/hmg8.png" alt=""></div>
		</div>
</div>
<input type="hidden" class="weixin_alipay_type" value="<?php echo ($weixin_alipay_type); ?>">
<input type="hidden" class="image" value="">
<input type="hidden" class="price" value="20">
<input type="hidden" class="weixin_buy_yellow" value="<?php echo U('V_2WechatPay/weixin_buy_yellow');?>">
<input type="hidden" class="alipay_buy_yellow" value="<?php echo U('Alipay/alipay_buy_yellow');?>">
<input type="hidden" class="weixin_buy_yellow_update" value="<?php echo U('V_2WechatPay/weixin_buy_yellow_update');?>">
<input type="hidden" class="number" value="200">
<input type="hidden" class="adv_ajax" value="<?php echo U('adv_ajax');?>">
<input type="hidden" class="quotient_id" value="<?php echo ($rose["id"]); ?>">
<input type="hidden" class="user_id" value="<?php echo ($user_id); ?>">
<input type="hidden" class="scan_code" value="<?php echo ($scan_code); ?>">
<input type="hidden" class="send_id" value="<?php echo ($send_id); ?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/vase.js?2"></script>
<script src="./tpl/Wap/default/js/zepto.js"></script>
<script src="./tpl/Wap/default/js/frozen.js"></script>
<script src="./tpl/Wap/default/js/jsweixin1.0.js"></script>
<script src="./tpl/Wap/default/js/common.js"></script>
<script>
	//上传图片
	wx.config({
		debug: false,
		appId: '<?php echo ($signPackage["appId"]); ?>',
		timestamp: <?php echo ($signPackage["timestamp"]); ?>,
		nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
		signature: '<?php echo ($signPackage["signature"]); ?>',
		jsApiList: [
			'chooseImage',
			'uploadImage',
			'downloadImage',
			'previewImage'
		]
	});
	wx.ready(function () {
		var images = {
			localId: [],    //
			serverId: []
		};
		$(".click_pic_div").click(function(e) {
			var a1=$(this);
			var id = $('#uid').val();
			wx.chooseImage({
				count: 1,
				success: function (res) {
					images.localId = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片45
					var i = 0, length = images.localId.length;
					images.serverId = [];
					function upload() {
						wx.uploadImage({
							localId: images.localId[i],
							success: function (res) {
								i++;
								images.serverId.push(res.serverId);
								if (i < length) {
									upload();
								}else{
									var  url="<?php echo U('V_2RoseAjax/adv_update_img');?>";
									$.post(url,{imgs:encodeURIComponent(images.serverId),id:id},function(data){
										a1.attr('src',data);
										$('.image').val(data);
									});
								}
							},
							fail: function (res) {
								alert(JSON.stringify(res));
							}
						});
					}
					upload();//上传
				}
			});
		})
	})
	Zepto(function($){
		//添加导流广告
		$('.submits').tap(function(){
			var title = $.trim($('.title').val());
			var image = $.trim($('.image').val());
			var quotient_id = $.trim($('.quotient_id').val());
			var user_id = $.trim($('.user_id').val());
			var scan_code = $.trim($('.scan_code').val());
			var adv_ajax = $.trim($('.adv_ajax').val());
			var weixin_alipay_type = $.trim($('.weixin_alipay_type').val());
			var url = $.trim($('.url').val());
			if(title==''){
				 $.dialog({
					content:'标题不能为空',
					button:['好']
				});
				return false;
			}
			if(title.length > 20){
				 $.dialog({
					content:'标题不能超过20个字',
					button:['好']
				});
				return false;
			}
			if(image==''){
				$.dialog({
					content:'请上传图片',
					button:['好']
				});
				return false;
			}
			if(url==''){
				$.dialog({
					content:'请输入链接地址',
					button:['好']
				});
				return false;
			}
			var DATA = {
				title:title,
				image:image,
				quotient_id:quotient_id,
				user_id:user_id,
				scan_code:scan_code,
				weixin_alipay_type:weixin_alipay_type,
				url:url
			}
			var el=$.loading({
				content:'正在提交'
			});
			$.post(adv_ajax,DATA,function(data){
				el.hide();
				if(data.code == 200){
					var DG = $.dialog({
						content:data.msg,
						button:['好']
					});
					DG.on('dialog:action',function(e){
						document.location.href=data.url;
					});
				} else {
					$.dialog({
						content:data.msg,
						button:['好']
					});
				}
			},'json')
		});
		//购买黄玫瑰
		$('.buy_yellow').tap(function(){
			var number = $('.number').val();
			var quotient_id = $('.quotient_id').val();
			var user_id = $('.user_id').val();
			var weixin_alipay_type = $('.weixin_alipay_type').val();
			var price = $('.price').val();
			var DATA = {
				quotient_id: quotient_id,
				user_id: user_id,
				number: number,
				weixin_alipay_type: weixin_alipay_type,
				price:price
			}
			if (weixin_alipay_type == 'wechat') {
				if (typeof WeixinJSBridge == "undefined") {
					if (document.addEventListener) {
						document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
					} else if (document.attachEvent) {
						document.attachEvent('WeixinJSBridgeReady', jsApiCall);
						document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
					}
				} else {
					var jsApiParameters = null;
					var out_trade_no = null;
					var isGoToPay = false;
					var weixin_buy_yellow = $('.weixin_buy_yellow').val();
					$.ajax({
						type: 'POST',
						url: weixin_buy_yellow,
						data: DATA,
						dataType: "json",
						timeout: 3000,
						async: false,
						success: function (data) {
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
							$.dialog({
								content: '支付错误',
								button: ['好']
							});
						}
					});
					if (isGoToPay) {
						var jsPs = eval('(' + jsApiParameters + ')');
						WeixinJSBridge.invoke(
								'getBrandWCPayRequest',
								jsPs,
								function (res) {
									if (res.err_msg == "get_brand_wcpay_request:ok") {
										var weixin_buy_yellow_update = $('.weixin_buy_yellow_update').val();
										$.ajax({
											type: 'POST',
											url: weixin_buy_yellow_update,
											data: {
												"out_trade_no": out_trade_no,
												quotient_id: quotient_id,
												user_id: user_id
											},
											dataType: 'json',
											async: false,
											success: function (data) {
												if (data.code == 200) {
													$('.count').html(data.count);
												} else {
													$.dialog({
														content: '支付错误',
														button: ['好']
													});
													return false;
												}

											},
											error: function (xhr, type) {
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
				var Alipay = $('.alipay_buy_yellow').val();
				post(Alipay, DATA);
			}
		});
	});

	$(".adv-top-right-img1").click(function() {
		$('.number').val($(this).attr('datanumber'));
		$('.price').val($(this).attr('dataprice'));
		$(".adv-top-right-img1 img").attr("src","./tpl/Wap/default/img/hmg11.png");
		$(".adv-top-right-img2 img").attr("src","./tpl/Wap/default/img/hmg2.png");
		$(".adv-top-right-img3 img").attr("src","./tpl/Wap/default/img/hmg3.png");
		$(".adv-top-right-img4 img").attr("src","./tpl/Wap/default/img/hmg4.png");
		$(".adv-top-right-img5 img").attr("src","./tpl/Wap/default/img/hmg5.png");
	});
	$(".adv-top-right-img2").click(function() {
		$('.number').val($(this).attr('datanumber'));
		$('.price').val($(this).attr('dataprice'));
		$(".adv-top-right-img2 img").attr("src","./tpl/Wap/default/img/hmg12.png");
		$(".adv-top-right-img1 img").attr("src","./tpl/Wap/default/img/hmg1.png");
		$(".adv-top-right-img3 img").attr("src","./tpl/Wap/default/img/hmg3.png");
		$(".adv-top-right-img4 img").attr("src","./tpl/Wap/default/img/hmg4.png");
		$(".adv-top-right-img5 img").attr("src","./tpl/Wap/default/img/hmg5.png");
	});
	$(".adv-top-right-img3").click(function() {
		$('.number').val($(this).attr('datanumber'));
		$('.price').val($(this).attr('dataprice'));
		$(".adv-top-right-img3 img").attr("src","./tpl/Wap/default/img/hmg13.png");
		$(".adv-top-right-img1 img").attr("src","./tpl/Wap/default/img/hmg1.png");
		$(".adv-top-right-img2 img").attr("src","./tpl/Wap/default/img/hmg2.png");
		$(".adv-top-right-img4 img").attr("src","./tpl/Wap/default/img/hmg4.png");
		$(".adv-top-right-img5 img").attr("src","./tpl/Wap/default/img/hmg5.png");
	});

	$(".adv-top-right-img4").click(function() {
		$('.number').val($(this).attr('datanumber'));
		$('.price').val($(this).attr('dataprice'));
		$(".adv-top-right-img4 img").attr("src","./tpl/Wap/default/img/hmg14.png");
		$(".adv-top-right-img1 img").attr("src","./tpl/Wap/default/img/hmg1.png");
		$(".adv-top-right-img3 img").attr("src","./tpl/Wap/default/img/hmg3.png");
		$(".adv-top-right-img2 img").attr("src","./tpl/Wap/default/img/hmg2.png");
		$(".adv-top-right-img5 img").attr("src","./tpl/Wap/default/img/hmg5.png");
	});

	$(".adv-top-right-img5").click(function() {
		$('.number').val($(this).attr('datanumber'));
		$('.price').val($(this).attr('dataprice'));
		$(".adv-top-right-img5 img").attr("src","./tpl/Wap/default/img/hmg15.png");
		$(".adv-top-right-img1 img").attr("src","./tpl/Wap/default/img/hmg1.png");
		$(".adv-top-right-img3 img").attr("src","./tpl/Wap/default/img/hmg3.png");
		$(".adv-top-right-img2 img").attr("src","./tpl/Wap/default/img/hmg2.png");
		$(".adv-top-right-img4 img").attr("src","./tpl/Wap/default/img/hmg4.png");
	});
</script>
</body>
</html>