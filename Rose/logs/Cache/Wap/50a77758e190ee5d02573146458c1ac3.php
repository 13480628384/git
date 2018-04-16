<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/start.css">
    <title>深圳玫瑰物联</title>
</head>
<body style="background: #f2f2f2">
<!-- 启动页面 -->
<div class="start">
    <?php if($IS_IF == 1 AND $group_adv != null): ?><div class="starttop">
            <div class="starttop_zi">
                <p>关注微信号</p>
                <h2>免费启动设备</h2>
                <span>长按3秒识别二维码</span>
            </div>
            <img src="<?php echo ($group_adv["qr_url"]); ?>" alt="玫瑰云网">
        </div><?php endif; ?>
    <div class="startbottom">
        <div class="startheight20"></div>
        <div class="startbottom_img">
            <?php if($weixin_alipay_type == 'wechat'): ?><a href="<?php echo U('V_2WechantDollMachine/index', array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <img src="./tpl/Wap/default/img/button2.gif" alt="">
                </a>
            <?php else: ?>
                <a href="<?php echo U('V_2AlipayDollMachine/index', array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <img src="./tpl/Wap/default/img/button2.gif" alt="">
                </a><?php endif; ?>
        </div>
        <div class="startheight20"></div>
        <?php if(is_array($rose_adv)): $k = 0; $__LIST__ = $rose_adv;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($v['count'] == 2): ?><div class="startp1 startp2" dataid="<?php echo ($v["id"]); ?>" dataurl="<?php echo ($v["url"]); ?>"><?php echo ($v["title"]); ?></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        <div class="startheight20"></div>
        <div class="startp1 startp3">深圳玫瑰物联技术有限公司</div>
    </div>
</div>
<ul class="footer">
    <?php if($weixin_alipay_type == 'wechat'): ?><li data-url="<?php echo U('V_2WechantDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li>
    <?php else: ?>
        <li data-url="<?php echo U('V_2AlipayDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li><?php endif; ?>
        <!--<li data-url="<?php echo U('space',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type));?>">玫瑰空间</li>-->
    <?php if($rose != null): ?><li data-url="<?php echo U('V_2Rose/quotient',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">生态商</li>
        <li data-url="<?php echo U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">我的</li>
    <?php else: ?>
        <li data-url="<?php echo U('V_2RoseAjax/p_my',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">生态商</li>
        <li data-url="<?php echo U('V_2RoseAjax/p_my',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">我的</li><?php endif; ?>
</ul>
</body>
<script src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script src="./tpl/Wap/default/js/font.js"></script>
<script>
    $('.footer li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    $("[data-url='"+url+"']").addClass('active');
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