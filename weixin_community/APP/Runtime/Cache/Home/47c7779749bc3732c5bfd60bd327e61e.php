<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-CN" xml:lang="zh-CN">
<head>
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta charset="UTF-8" />
	<title>微社区</title>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/style_pl.css?v1.0" />
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/collect.css" />
</head>
<body>
		<div class="top">
			<div class="img">
				<img src="<?php echo ($userinfo["headimgurl"]); ?>" alt="头像1">
			</div>
			<div class="user">
				<ul>
					<li id="name"><span><?php echo str_substr($userinfo['nickname'],3); ?></span></li>
					<li id="lv"><div class="lv"><b>Lv 1</b></div>
				</ul>
				<div class="clear"></div>
				<div class="medal">
					<ul>
						<li><img src="__PUBLIC__/Home/img/icon-1.png" alt=""></li>
						<!--<li><img src="./images/icon-2.png" alt=""></li>
						<li><img src="./images/icon-3.png" alt=""></li>
						<li><img src="./images/icon-4.png" alt=""></li>-->
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="main-content">
			<div class="time">
				<div class="date">2016-06-29 13:30</div>
			</div>
			<div class="clear"></div>
			<div class="text-content"><span>
				<?php echo ($detail["text"]); ?>
			</span> </div>
		</div>
		<div class="clear"></div>
		<?php if(!empty($detail['pic'])){ ?>
		<div class="main-image">
			<ul>
				<?php $pic = explode(',',$detail['pic']); $pics = array(); foreach($pic as $p){ $pics[]['imgs'] = $p; } foreach($pics as $kpic=>$vpic){ ?>
				<li><img src="<?php echo $vpic['imgs']; ?>"></li>
				<?php } ?>
			</ul>
			<div class="clear"></div>
		</div>
		<?php } ?>
		<div class="contnent-nei">
			<div class="top1">
				<span></span>
				<div class="litext">
					全部评论
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="con">
			<ul class="append_comment">
				<?php if(is_array($reply)): $i = 0; $__LIST__ = $reply;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['fr_id'] == $detail['id']): ?><li>
							<div class="commentli-img">
								<img src="<?php echo ($v["headimgurl"]); ?>">
							</div>
							<div class="con-right">
								<?php echo ($v["re_name"]); ?><b class="back">LV 1</b>
							</div>
							<div class="clear"></div>
							<p><?php echo ($v["re_text"]); ?></p>
							<div class="xin">
								<span class="minute"><?php echo ($v["time_tran"]); ?></span>
								<tt class="rixin">
									<img src="__PUBLIC__/Home/img/icon-praise.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;
									<img src="__PUBLIC__/Home/img/icon-comment.png" alt="">
								</tt>
							</div>
							<div class="clear"></div>
						</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<!--<div class="more">没有更多了</div>-->
		</div>
		<div class="clear"></div>
		<div class="space-200"></div>
		<div class="clear"></div>
		<footer class="fotter">
				<ul>
					<li onclick="fen();"><img src="__PUBLIC__/Home/img/icon-transmit.png" alt=""><span>转发</span></li>
					<li class="Comment"><img src="__PUBLIC__/Home/img/icon-comment.png" alt="">评论</li>
					<li class="Click_Zan"><img src="__PUBLIC__/Home/img/icon-praise.png" alt="">赞</li>
				</ul>
		</footer>
	<div class="acc" style="display: none;">
		<img src="__PUBLIC__/Home/img/fen.png">
	</div>
		<!--评论发帖的 [[-->
		<div class="footer-import">
			<div class="import-input">
				<input type="text" class="Comment_input" value="">
			</div>
			<div class="send-btn" id="send-btn">
				发送
			</div>
		</div>
		<!--评论发帖的 ]]-->
</body>
<input type="hidden" class="openid" value="<?php echo ($openid); ?>">
<input type="hidden" class="create_openid" value="<?php echo ($create_openid); ?>">
<input type="hidden" class="id" value="<?php echo ($id); ?>">
<input type="hidden" class="value" value="<?php echo ($url); echo U('Index/index',array('group_id'=>$group_id,'device_command'=>$device_command,mchid=>$mchid));?>">
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/detail.js?v1.1"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/jsweixin1.0.js"></script>
<script>
	function fen(){
		$('.acc').css('display','block');
	}
	$('.acc img').click(function(){
		$('.acc img').css('display','none');
	});
	var QuasarConfig = {
		AjaxApiMap:{
			NotTreatment      :'<?php echo U("Comment");?>',
			Click_Zan          :'<?php echo U("click_zan_more");?>',
			Click_Friend      :'<?php echo U("click_friend");?>',
			Cancel				:'<?php echo U("cancel");?>'
		}
	};
	wx.config({
		debug: false,
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: '<?php echo $signPackage["timestamp"];?>',
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList: [
			'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ'
		]
	});
	wx.ready(function () {
		wx.onMenuShareTimeline({
			title: '广加智能微社区',
			link: $('.value').val(),
			imgUrl: 'http://7xlcfz.com1.z0.glb.clouddn.com/community_logo.png',
			success: function () {
				// 用户确认分享后执行的回调函数
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数
			}
		});
		//分享到朋友
		wx.onMenuShareAppMessage({
			title: '广加智能微社区',
			desc: '广加智能微社区',
			link: $('.value').val(),
			imgUrl: 'http://7xlcfz.com1.z0.glb.clouddn.com/community_logo.png',
			type: '',
			dataUrl: '',
			success: function () {

			},
			cancel: function () {

			}
		});

		//分享到朋友圈
		wx.onMenuShareTimeline({
			title: '广加智能微社区', // 分享标题
			link: $('.value').val(), // 分享链接
			imgUrl: 'http://7xlcfz.com1.z0.glb.clouddn.com/community_logo.png', // 分享图标
			success: function () {
				// 用户确认分享后执行的回调函数
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数
			}
		});

		//分享到QQ
		wx.onMenuShareQQ({
			title: '广加智能微社区', // 分享标题
			desc: '广加智能微社区', // 分享描述
			link: $('.value').val(), // 分享链接
			imgUrl: 'http://7xlcfz.com1.z0.glb.clouddn.com/community_logo.png', // 分享图标
			success: function () {
				// 用户确认分享后执行的回调函数
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数
			}
		});


	})
</script>
</html>