<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>微社区</title>
    <meta charset="utf-8">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta content="eric.wu" name="author">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css?0" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css?011621" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/index.css?v1.3" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/touch.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/style.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/site.css?1001" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/photoswipe.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/default-skin.css" />
    </head>
    <body>
    <div class="back">
        <img src="__PUBLIC__/Home/img/back.png">
    </div>
    <div class="message-top">
        <ul>
            <li><img src="<?php if($headimgurl==''){echo '__PUBLIC__/Home/img/bg.png';}else{echo $headimgurl;} ?>"></li>
            <li>昵称:<?php if($nickname==''){echo '玩家';}else{echo str_substr($nickname,3);} ?></li>
            <li>等级:LV1</li>
            <!--<li>积分:<?php if($user_integral==''){echo '0';}else{echo $user_integral;} ?></li>-->
            <li>余额:<b class="total_all"><?php echo ($total); ?></b></li>
        </ul>
    </div>
    <div class="all-content">
    <div class="clear"></div>
    <div class="message-content-top">
        <div class="content-left">
            <div class="letter"><?php if($group_code['group_code'] == null): ?>A<?php else: echo ($group_code["group_code"]); endif; ?></div>
            <div class="tishi">点击启动</div>
            <div class="content-title-price"><img src="__PUBLIC__/Home/img/icon_01.png"><tt class="tt"><?php if($group_code['pay_price']==''){echo 1;}else{echo $group_code['pay_price'];} ?>元</tt></div>
        </div>
        <div class="content-right">
            <?php if(empty($group_code['give_coin'])){ ?>
            <div class="media-6"></div>
            <div class="button-top">
                <div class="c-left">
                    3次<!--送<label class="number_send">1--></label>
                </div>
                <div class="c-right">
                    &nbsp;&nbsp;<img src="__PUBLIC__/Home/img/icon_01.png"><tt class="tt1">3</tt>元
                </div>
            </div>
            <div class="media-6"></div>
            <div class="button-top">
                <div class="c-left">
                    5次<!--送<label class="number_send">2--></label>
                </div>
                <div class="c-right">
                    &nbsp;&nbsp;<img src="__PUBLIC__/Home/img/icon_01.png"><tt class="tt1">5</tt>元
                </div>
            </div>
            <div class="media-6"></div>
            <div class="button-top">
                <div class="c-left">
                    10次<!--送<label class="number_send">3--></label>
                </div>
                <div class="c-right">
                    <img src="__PUBLIC__/Home/img/icon_01.png"><tt class="tt1">10</tt>元</div>
            </div>
            <?php }else{ ?>
        <?php if(is_array($give_coin_array)): $k = 0; $__LIST__ = $give_coin_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="media-6"></div>
            <div class="button-top">
                <div class="c-left">
                    <?php echo ($v["number"]["0"]); ?><!--次送<label class="number_send"><?php echo ($v["number"]["1"]); ?>--></label>
                </div>
                <div class="c-right">
                    <?php if($k == 1 or $k == 2): ?>&nbsp;&nbsp;<?php endif; ?><img src="__PUBLIC__/Home/img/icon_01.png"><tt class="tt1"><?php echo ($v["number"]["0"]); ?></tt>元
                </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <?php } ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="tab-yj tab-customer">
        <ul class="tab-yj-nav">
            <li class="active total">今日榜单<div class="tab-total">人数<b><?php echo ($count); ?></b></div></li>
            <li>历史榜单</li>
        </ul>
        <div class="tab-yf-box">
            <div class="tab-yf-null">
                <div class="tab-yf-box1">
                    <?php if($qianwu>$houliu){ ?>
                        <?php if(in_array($openid,$yiwei)){ ?>
                            <?php if(is_array($total_ranking_1)): $i = 0; $__LIST__ = $total_ranking_1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-yf-box-main f12">
                                    <div class="tab-yf-box-main-name">
                                        <div>
                                            <div class="number <?php if($v['openid']==$openid){echo 'my';} ?>">NO.<?php echo ($v["user_count"]); ?></div>
                                            <img src="<?php echo ($v["headimgurl"]); ?>">
                                            <div class="name"><?php echo (str_substr($v["nickname"],7)); ?>（<?php echo ($v["city"]); ?>）</div>
                                        </div>
                                    </div>
                                    <div class="tab-yf-box-main-time">
                                        <div>
                                            战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                                        </div>
                                    </div>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php if(!empty($total_ranking)){ ?>
                        <p class="new" style="color:#ff6e35;font-weight: bold">···</p>
                        <?php } ?>
                        <?php if(is_array($total_ranking_2)): $i = 0; $__LIST__ = $total_ranking_2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-yf-box-main f12">
                                <div class="tab-yf-box-main-name">
                                    <div>
                                        <div class="number <?php if($v['openid']==$openid){echo 'my';} ?>">NO.<?php echo ($v["user_count"]); ?></div>
                                        <img src="<?php echo ($v["headimgurl"]); ?>">
                                        <div class="name"><?php echo (str_substr($v["nickname"],7)); ?>（<?php echo ($v["city"]); ?>）</div>
                                    </div>
                                </div>
                                <div class="tab-yf-box-main-time">
                                    <div>
                                        战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                                    </div>
                                </div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php }else{ ?>
                            <?php if(is_array($no_array)): $i = 0; $__LIST__ = $no_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="tab-yf-box-main f12">
                                    <div class="tab-yf-box-main-name">
                                        <div>
                                            <div class="number">NO.<?php echo ($v["user_count"]); ?></div>
                                            <img src="<?php echo ($v["headimgurl"]); ?>">
                                            <div class="name"><?php echo (str_substr($v["nickname"],7)); ?>（<?php echo ($v["city"]); ?>）</div>
                                        </div>
                                    </div>
                                    <div class="tab-yf-box-main-time">
                                        <div>
                                            战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                                        </div>
                                    </div>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php } ?>
                    <?php }else{ ?>
                        <?php if(is_array($new_one_array_1)): $k = 0; $__LIST__ = $new_one_array_1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="tab-yf-box-main f12">
                                    <div class="tab-yf-box-main-name">
                                        <div>
                                            <div class="number <?php if($v['openid']==$openid){echo 'my';} ?>">NO.<?php echo ($v["user_count"]); ?></div>
                                            <img src="<?php echo ($v["headimgurl"]); ?>">
                                            <div class="name"><?php echo (str_substr($v["nickname"],7)); ?>（<?php echo ($v["city"]); ?>）</div>
                                        </div>
                                    </div>
                                    <div class="tab-yf-box-main-time">
                                        <div>
                                            战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                                        </div>
                                    </div>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php if(!empty($total_ranking)){ ?>
                            <p class="new" style="color:#ff6e35;font-weight: bold">···</p>
                        <?php } ?>
                        <?php if(is_array($new_one_array_2)): $k = 0; $__LIST__ = $new_one_array_2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="tab-yf-box-main f12">
                                <div class="tab-yf-box-main-name">
                                    <div>
                                        <div class="number <?php if($v['openid']==$openid){echo 'my';} ?>">NO.<?php echo ($v["user_count"]); ?></div>
                                        <img src="<?php echo ($v["headimgurl"]); ?>">
                                        <div class="name"><?php echo (str_substr($v["nickname"],7)); ?>（<?php echo ($v["city"]); ?>）</div>
                                    </div>
                                </div>
                                <div class="tab-yf-box-main-time">
                                    <div>
                                        战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                                    </div>
                                </div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php } ?>
                </div>
                </div>
            </div>
        <div class="tab-yf-box" style="display:none">
            <div class="tab-yf-null">
                <div class="tab-yf-box1">
                    <?php if(is_array($ranking)): $k = 0; $__LIST__ = $ranking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="tab-yf-box-main f12">
                        <div class="tab-yf-box-main-name">
                            <div>
                                <div class="number">NO.<?php echo ($k); ?></div>
                                <img src="<?php echo ($v["headimgurl"]); ?>">
                                <div class="name"><?php echo (str_substr($v["nickname"],5)); ?>（<?php echo ($v["city"]); ?>）</div>
                            </div>
                        </div>
                        <div class="tab-yf-box-main-time">
                            <div>
                                战斗力 <b class="total-num"><?php echo ($v["count"]); ?></b>
                            </div>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <!--玩家攻略评论  [[-->
    <aside class="player">
        <div class="player-left">赫赫战功</div>
        <!--<div class="player-center">最火</div>-->
        <div class="player-right"><img src="__PUBLIC__/Home/img/write.png">捷报</div>
    </aside>
    <!--玩家攻略评论  ]]-->
    <!--评论内容  [[-->
    <div class="clear"></div>
    <section class="community">
        <?php if(is_array($friend_info)): $i = 0; $__LIST__ = $friend_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><div class="comm-total">
            <div class="com-top"><b class="c-name"><?php echo ($value["nickname"]); ?></b>  - <?php echo (str_substr($value["create_date"],10)); ?> </div>
            <div class="com-middle">
                <?php echo ($value["text"]); ?>
            </div>
            <!--图片显示 [[-->
            <?php if(!empty($value['pic'])){ ?>
            <ul class="pics">
                <li class="img_pics">
                    <div class="demo-gallery">
                <?php $pic = explode(',',$value['pic']); $pics = array(); foreach($pic as $p){ $pics[]['imgs'] = $p; } $count = count($pics); foreach($pics as $kpic=>$vpic){ if($kpic<=2){ ?>
                        <a href="<?php echo ($vpic["imgs"]); ?>" <?php if($count == 1): ?>class='big';<?php endif; ?>  data-size="1600x1068" data-med="<?php echo ($vpic["imgs"]); ?>" data-med-size="1024x1024">
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
                    <img src="__PUBLIC__/Home/img/coummity.png">&nbsp;&nbsp;<?php echo ($value["community_total"]); ?>
                </li>
            </ul>
            <div class="clear"></div>
        <?php if($value['is_on'] == 1): ?><div class="schoolmate-main-right-praise">
                <div class="praise-text">
                    <?php if(is_array($reply)): $i = 0; $__LIST__ = $reply;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['fr_id'] == $value['id']): ?><div class="praise-text-main comments" friendinfo_id="<?php echo ($v["fr_id"]); ?>" replybuyer_id="<?php echo ($v["openid"]); ?>">
                                <span class="color2377c5" id="names"><?php echo ($v["re_name"]); ?></span> <?php if($v['reply_name'] != $value['nickname']): ?>回复
                                <span class="color2377c5" id="name"><?php echo ($v["reply_name"]); ?></span><?php endif; ?>
                                <span>:<?php echo ($v["re_text"]); ?></span>
                            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div><?php endif; ?>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </section>
        <!--评论发帖的 [[-->
        <div class="footer-import">
            <div class="import-input">
                <input type="text" id="content" value="" onkeyup="contentspl()">
            </div>
            <div class="send-btn" id="send-btn">
                发送
            </div>
        </div>
        <!--评论发帖的 ]]-->
    <!--评论内容  ]]-->
    <button type="button" class="loading"><span class="more">点击加载更多</span><img style="display: none;margin: auto;" class="loading_animate" src="__PUBLIC__/Home/img/three-dots.svg" width="40"/></button>
        <p class="bottoms"></p>
    <!--底部菜单栏  [[-->
    <menu class="ucenter-menu">
        <!--<footer class="footer">温馨提示：若扣费未成功，10分钟内重新扫码自动退币。<p class="kefu">联系客服</p></footer>-->
        <ul>
            <li><i class="icon-weixin"></i><br>微信支付</li>
            <?php if(empty($is_openid)){ ?>
            <li onclick="window.location.href='<?php echo U('get_openid',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'"><i class="icon-doubi"></i><br>玩家中心</li>
            <?php }else{ ?>
            <li onclick="window.location.href='<?php echo U('Personal/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'"><i class="icon-doubi"></i><br>玩家中心</li>
            <?php } ?>

            <?php if(empty($is_openid)){ ?>
            <li onclick="window.location.href='<?php echo U('get_openid',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command));?>'"><i class="icon-mian"></i><br>免费逗币</li>
            <?php }else{ ?>
            <li onclick="window.location.href='<?php echo U('Nomoney/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'"><i class="icon-mian"></i><br>免费逗币</li>
            <?php } ?>

            <?php if(empty($is_openid)){ ?>
            <li onclick="window.location.href='<?php echo U('get_openid',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'"><i class="icon-comm"></i><br>根据地</li>
            <?php }else{ ?>
            <li onclick="window.location.href='<?php echo U('Community/index',array(openid=>$openid,group_id=>$group_id,device_command=>$device_command,mchid=>$mchid));?>'"><i class="icon-comm"></i><br>根据地</li>
            <?php } ?>
        </ul>
    </menu>
    </div>
    <!--底部菜单栏  ]]-->
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
    <input type="hidden" class="dataiduser" value="">
    <input type="hidden" class="friendinfo_id" value="">
    <input type="hidden" class="strate_url_of" value="<?php echo U('reply');?>">
    <input type="hidden" class="jinzhi" value="0">
    <input type="hidden" class="is_openid" value="<?php echo ($is_openid); ?>">
    <input type="hidden" class="pl_url" value="<?php echo U('pl_url');?>">
    </body>
    <input type="hidden" class="user_ajax_community" value="<?php echo U('user_ajax');?>">
    <input type="hidden" class="nurl" value="<?php echo U('get_openid');?>">
    <input type="hidden" class="hot_index" value="<?php echo U('index',array('hot'=>1));?>">
    <input type="hidden" class="click_friend" value="<?php echo U('click_friend');?>">
    <input type="hidden" class="click_zan_more" value="<?php echo U('click_zan_more');?>">
    <input type="hidden" class="cancel" value="<?php echo U('cancel');?>">
    <input type="hidden" class="community_more" value="<?php echo U('community_more');?>">
    <input type="hidden" class="community_add" value="<?php echo U('community_add');?>">
    <input type="hidden" class="icon_img" value="__PUBLIC__/Home/img/icon_04.png">
    <input type="hidden" class="xin" value="__PUBLIC__/Home/img/icon_05.png?100">
    <input type="hidden" class="comm_img" value="__PUBLIC__/Home/img/coummity.png">
    <input type="hidden" class="group_code" value="<?php echo ($group_code["online_status"]); ?>">
    <input type="hidden" class="mchid" value="<?php echo ($mchid); ?>">
    <input type="hidden" class="type" value="<?php echo ($type); ?>">
    <input type="hidden" class="sc_small" value="<?php echo ($sc_small); ?>">
    <input type="hidden" class="group_id" value="<?php echo ($group_id); ?>">
    <input type="hidden" class="openid" value="<?php echo ($openid); ?>">
    <input type="hidden" class="device_command" value="<?php echo ($group_code["device_command"]); ?>">
    <input type="hidden" class="device_id" value="<?php echo ($group_code["device_id"]); ?>">
    <script type="text/javascript" src="__PUBLIC__/Home/js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/touch.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/index.js?v1.1"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/community.js?v1.0"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/photoswipe.min.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/photoswipe-ui-default.min.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/snf1yod.js"></script>
  <script type="text/javascript" src="__PUBLIC__/Home/js/photo.js"></script>
<script>
    /*var useragent = navigator.userAgent;
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        var opened = window.open('about:blank', '_self');
        opened.opener = null;
        opened.close();
    }*/
   /*function contentspl(){
       var content = $('#content').val();
       var strate_url_of = $('.strate_url_of').val();
       $.post(strate_url_of,{add_title:content},function(data){
           if(data.code==200){
               $('.jinzhi').val(1);
           }else{
               $('.jinzhi').val(0);
           }
       },'json');
   }*/
    </script>
</html>