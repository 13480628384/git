<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>根据地</title>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/index.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/community_index.css?v1.2" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/style.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/site.css?10156" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/photoswipe.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/default-skin.css" />
</head>
<body>
<div class="message-tops">
   <aside class="top-left"><img src="__PUBLIC__/Home/img/community_logo.png"></aside>
   <aside class="top-center">话题 :&nbsp;2364&nbsp;&nbsp;关注: &nbsp;12354<p>全国娃娃机互动交流平台</p></aside>
   <aside class="top-right"><div class="rem"><b>＋</b>关注</div></aside>
</div>
<!--广告 [[-->
<div class="advertisement">
    <ul>
        <li><b>置顶</b>最强抓娃娃攻略</li>
        <li><b>置顶</b>抓不到娃娃，一定是你方法不对</li>
        <li><b>置顶</b>【微公益】娃娃太多没处去怎么办</li>
    </ul>
</div>
<div class="clear"></div>
<ul class="top_pai_li">
    <li><a href="<?php echo U('index',array('new'=>1,'openid'=>$create_openid,'group_id'=>$group_id,'device_command'=>$device_command,'mchid'=>$mchid));?>">最新</a></li>
    <li><a href="<?php echo U('index',array('new'=>2,'openid'=>$create_openid,'group_id'=>$group_id,'device_command'=>$device_command,'mchid'=>$mchid));?>">最热</a></li>
    <li><a href="<?php echo U('index',array('new'=>3,'openid'=>$create_openid,'group_id'=>$group_id,'device_command'=>$device_command,'mchid'=>$mchid));?>">区域</a></li>
    <li style="border-right:none;"><a href="<?php echo U('index',array('new'=>4,'openid'=>$create_openid,'group_id'=>$group_id,'device_command'=>$device_command,'mchid'=>$mchid));?>">置顶</a></li>
</ul>
<div class="clear"></div>
<!--广告 ]]-->
<div class="all-contents">
    <!--底部菜单栏  [[-->
    <menu class="ucenter-menu">
        <ul>
            <li onclick="window.location.href='<?php echo U('Index/index',array(openid=>$create_openid,group_id=>$group_id,device_command=>$device_command));?>'"><i class="icon-weixin"></i><br>微信支付</li>
            <li onclick="window.location.href='<?php echo U('Personal/index',array(openid=>$create_openid,group_id=>$group_id,device_command=>$device_command));?>'"><i class="icon-doubi"></i><br>玩家中心</li>
            <li onclick="window.location.href='<?php echo U('Nomoney/index',array(openid=>$create_openid,group_id=>$group_id,device_command=>$device_command));?>'"><i class="icon-mian"></i><br>免费逗币</li>
            <li><i class="icon-comm"></i><br>根据地</li>
        </ul>
    </menu>
    <!--底部菜单栏  ]]-->
    <!--评论内容  [[-->
    <div class="clear"></div>
    <section class="community" style="margin-top: 6px;">
        <?php if(is_array($friend_info)): $i = 0; $__LIST__ = $friend_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><div class="comm-total">
                <div class="com-top"><b class="c-name"><?php echo ($value["nickname"]); ?></b>  - <?php echo (str_substr($value["create_date"],10)); ?> </div>
                <div class="com-middle" onclick="window.location.href='<?php echo U(detail,array(id=>$value[id],openid=>$value[create_by],create_openid=>$create_openid,mchid=>$mchid,device_command=>$device_command,group_id=>$group_id));?>'">
                    <?php echo ($value["text"]); ?>
                </div>
                <!--图片显示 [[-->
                <?php if(!empty($value['pic'])){ ?>
                <ul class="pics">
                    <li class="img_pics">
                        <div class="demo-gallery">
                            <?php $pic = explode(',',$value['pic']); $pics = array(); foreach($pic as $p){ $pics[]['imgs'] = $p; } foreach($pics as $kpic=>$vpic){ if($kpic<=2){ ?>
                            <a href="<?php echo ($vpic["imgs"]); ?>" data-size="1600x1068" data-med="<?php echo ($vpic["imgs"]); ?>" data-med-size="1024x1024">
                                <img src="<?php echo $vpic['imgs']; ?>">
                            </a>
                            <?php }} ?>
                        </div>
                    </li>
                </ul>
                <?php } ?>
                <div class="clear"></div>
                <!--图片显示 ]]-->
                <ul class="com-bottom">
                    <li class="bottom-two" data="<?php echo ($value["id"]); ?>"><img class="bxin" src="__PUBLIC__/Home/img/icon_04.png">&nbsp;&nbsp;<tt class="po"><?php echo ($value["praise"]); ?></tt></li>
                    <li class="bottom-one" replybuyer_id="<?php echo ($value["create_by"]); ?>" friendinfo_id="<?php echo ($value["id"]); ?>">
                        <img src="__PUBLIC__/Home/img/coummity.png">&nbsp;&nbsp;<?php echo ($value["community_total"]); ?></li>
                </ul>
                <div class="clear"></div>
                <!--<?php if($value['is_on'] == 1): ?><div class="schoolmate-main-right-praise">
                        <div class="praise-text">
                            <?php if(is_array($reply)): $i = 0; $__LIST__ = $reply;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['fr_id'] == $value['id']): ?><div class="praise-text-main comments" friendinfo_id="<?php echo ($v["fr_id"]); ?>" replybuyer_id="<?php echo ($v["openid"]); ?>">
                                        <span class="color2377c5" id="names"><?php echo ($v["re_name"]); ?></span> <?php if($v['reply_name'] != $value['nickname']): ?>回复
                                        <span class="color2377c5" id="name"><?php echo ($v["reply_name"]); ?></span><?php endif; ?>
                                        <span>:<?php echo ($v["re_text"]); ?></span>
                                    </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div><?php endif; ?>-->
            </div><?php endforeach; endif; else: echo "" ;endif; ?>

        <!--评论内容  ]]-->
    </section>
    <button type="button" class="loading"><span class="more">点击加加载更多</span><img style="display: none;margin: auto;" class="loading_animate" src="__PUBLIC__/Home/img/three-dots.svg" width="40"/></button>
    <p class="bottoms"></p>
</div>

<!--图片放大-->
<div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">

        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip">
                </div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
            <div class="pswp__caption">
                <div class="pswp__caption__center">
                </div>
            </div>
        </div>
    </div>
</div>
<img src="__PUBLIC__/Home/img/guangjia3.gif" style="display: none;" class="imgs">
</body>
<input type="hidden" class="community_more" value="<?php echo U('community_more');?>">
<input type="hidden" class="community_add" value="<?php echo U('community_add');?>">
<input type="hidden" class="dataiduser" value="">
<input type="hidden" class="friendinfo_id" value="">
<input type="hidden" class="strate_url_of" value="<?php echo U('Community/com_reply');?>">
<input type="hidden" class="jinzhi" value="0">
<input type="hidden" class="is_openid" value="<?php echo ($is_openid); ?>">
<input type="hidden" class="pl_url" value="<?php echo U('Index/pl_url');?>">
</body>
<input type="hidden" class="user_ajax_community" value="<?php echo U('Community/user_ajax');?>">
<input type="hidden" class="nurl" value="<?php echo U('get_openid');?>">
<input type="hidden" class="click_friend" value="<?php echo U('Community/click_friend');?>">
<input type="hidden" class="click_zan_more" value="<?php echo U('Community/click_zan_more');?>">
<input type="hidden" class="community_more" value="<?php echo U('community_more');?>">
<input type="hidden" class="community_add" value="<?php echo U('community_add');?>">
<input type="hidden" class="cancel" value="<?php echo U('cancel');?>">
<input type="hidden" class="icon_img" value="__PUBLIC__/Home/img/icon_04.png">
<input type="hidden" class="xin" value="__PUBLIC__/Home/img/icon_05.png?100">
<input type="hidden" class="comm_img" value="__PUBLIC__/Home/img/coummity.png">
<input type="hidden" class="group_code" value="<?php echo ($group_code["online_status"]); ?>">
<input type="hidden" class="mchid" value="<?php echo ($mchid); ?>">
<input type="hidden" class="type" value="<?php echo ($type); ?>">
<input type="hidden" class="sc_small" value="<?php echo ($sc_small); ?>">
<input type="hidden" class="group_id" value="<?php echo ($group_id); ?>">
<input type="hidden" class="openid" value="<?php echo ($create_openid); ?>">
<input type="hidden" class="new" value="<?php echo ($new); ?>">
<script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/community_wei.js?v1.2"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/photoswipe.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/photoswipe-ui-default.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/snf1yod.js"></script>
<script type="text/javascript" src="__PUBLIC__/Home/js/photo.js"></script>
<script>
   var useragent = navigator.userAgent;
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        var opened = window.open('about:blank', '_self');
        opened.opener = null;
        opened.close();
    }
</script>
</html>