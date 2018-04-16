<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/me.css">
    <title>个人中心</title>
</head>
<body>
<div class="web_aboutme">
    <!-- 头部用户信息部分 -->
    <div class="web_aboutme_top">
        <div class="web_aboutme_top_top">
            <div class="web_aboutme_top_top_all">
                <?php if($rose['headimgurl'] == null): ?><img class="web_aboutme_top_top_img" src="./tpl/Wap/default/img/reg_1.png" alt=""><?php else: ?>
                    <img class="web_aboutme_top_top_img" src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
                <p>微信号：<?php if($rose['wechat_number'] == ''): ?>玫瑰用户<?php else: echo ($rose["wechat_number"]); endif; ?></p>
            </div>
        </div>
        <div class="web_aboutme_top_bottom">
            <div class="web_aboutme_top_bottom_left">
                <div class="web_aboutme_top_bottom_left_all">
                    <img class="web_aboutme_top_bottom_left_img" src="./tpl/Wap/default/img/reg_2.png" alt="">
                    <span><?php if($all == ''): ?>0<?php else: echo intval($all); endif; ?></span>
                </div>
            </div>
            <div class="web_aboutme_top_bottom_right">
                <div class="web_aboutme_top_bottom_right_all">
                    <img class="web_aboutme_top_bottom_right_img" src="./tpl/Wap/default/img/reg_3.png" alt="">
                    <span><?php echo intval($rose['red_rose']); ?></span>
                </div>
            </div>
        </div>

    </div>
    <div class="web_aboutme_end"></div>
    <!-- 内容部分 -->
    <div class="web_aboutme_vip">
        <h2>会员权益</h2>
        <p>1.玫瑰云网会员,可免申请开通生态商户,可申请运营商与广告商功能。</p>
        <p>2.玫瑰云网会员,永久不用缴纳任何会员费用。</p>
        <p>3.享受名义股东身份,在会员存续期可享受平台的盈利分红。</p>
    </div>
    <?php if(is_array($rose_adv)): $k = 0; $__LIST__ = $rose_adv;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($v['count'] == 2): ?><div class="startp1 startp2 web_aboutme_vip web_aboutme_vip2" dataid="<?php echo ($v["id"]); ?>" dataurl="<?php echo ($v["url"]); ?>">
                <h3><?php echo ($v["title"]); ?></h3>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    <div class="web_aboutme_vip web_aboutme_vip2">
        <h3>注册玫瑰云网，享受更多服务！</h3>
        <img class="binding" src="./tpl/Wap/default/img/button.png" alt="">
    </div>
</div>
</body>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script>
    //注册
    $('.binding').click(function(){
        window.location.href="<?php echo U('Bind/binding',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>";
    });
    //导流广告
    $('.startp1').click(function(){
        if($(this).hasClass('on')){
            return false;
        }
        var id = $(this).attr('dataid');
        var dataurl = $(this).attr('dataurl');
        var user_id = "<?php echo ($user_id); ?>";
        $(this).addClass('on');
        $.post("<?php echo U('V_2RoseAjax/add_adv');?>",{id:id,user_id:user_id},function(data){
            if(data.code==200){
                window.location.href=dataurl;
            }else{
                alert('此广告无效');
            }
            $('.startp1').removeClass('on');
        },'json')
    });
</script>
</html>