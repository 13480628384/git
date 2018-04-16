<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="stylesheet" href="./tpl/Wap/default/css/public.css">
    <link rel="stylesheet" href="./tpl/Wap/default/css/vip_run.css?1">
    <title>生态商</title>
</head>
<body style="background: #f2f2f2">
<!--运营商 -->
<div class="vip_run">
    <div class="vip_run_top">
        <?php if($rose['headimgurl'] == null): ?><img src="./tpl/Wap/default/img/reg_1.png" alt="">
            <?php else: ?><img src="<?php echo ($rose["headimgurl"]); ?>" alt=""><?php endif; ?>
        <span>玫瑰ID：<?php echo ($rose["rose_id"]); ?></span>
    </div>
    <div class="height20"></div>
    <div class="vip_run_tab">
        <ul>
            <li class="no_open">运营商</li>
            <li class="adv">广告商</li>
            <li class="vip_run_tabli2 active">生态商</li>
        </ul>
    </div>
    <div class="height20"></div>
    <div class="vip_run_con">
        <ul>
            <li>
                <span class="vip_run_con_span1">昵称</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('update_vip',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <?php echo ($rose["nickname"]); ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">微信号</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('update_vip',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <?php if($rose['wechat_number'] == ''): ?>去绑定<?php else: echo ($rose["wechat_number"]); endif; ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">支付宝账号</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('update_vip',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <?php if($rose['alipay_number'] == ''): ?>去绑定<?php else: ?>
                        <?php echo ($rose["alipay_number"]); endif; ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">手机号码</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('update_vip',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>"><?php echo ($rose["phone"]); ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">邮箱</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('update_vip',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">
                    <?php if($rose['email'] == ''): ?>去绑定<?php else: ?>
                        <?php echo ($rose["email"]); endif; ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">云网余额</span>
                <a href="<?php echo U('yuncount',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$rose['id'],'scan_code'=>$scan_code));?>">
                <span class="vip_run_con_span2"> <?php if($rose['alipay_wechant_account'] == ''): ?>0<?php else: ?>
                    <?php echo ($rose["alipay_wechant_account"]); endif; ?>
                    <img class="vip_run_con_img2" src="./tpl/Wap/default/img/vip2.png" alt="">
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                </span>
                </a>
            </li>

            <li>
                <span class="vip_run_con_span1">红玫瑰</span>
                <a href="<?php echo U('give_list',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$rose['id'],'scan_code'=>$scan_code));?>">
                <span class="vip_run_con_span2"><?php if($rose['red_rose'] == ''): ?>0<?php else: ?>
                    <?php echo ($rose["red_rose"]); endif; ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                </span>
                </a>
            </li>
            <li>
                <span class="vip_run_con_span1">生态红玫瑰</span>
                <span class="vip_run_con_span2">
                    <?php if($rose["ecological_red_rose"] == ''): ?>0<?php else: ?>
                    <?php echo ($rose["ecological_red_rose"]); endif; ?>
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">玫瑰花瓶二维码</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('vash',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$rose['id'],'scan_code'=>$scan_code));?>">
                    <img class="vip_run_con_img3" src="./tpl/Wap/default/img/vip3.png" alt="">
                    <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <li>
                <span class="vip_run_con_span1">收到的红玫瑰</span>
                <span class="vip_run_con_span2">
                    <a href="<?php echo U('get_red',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'id'=>$rose['id'],'scan_code'=>$scan_code));?>">
                        <img class="vip_run_con_img3" src="./tpl/Wap/default/img/reg_3.png" alt="">
                        <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt="">
                    </a>
                </span>
            </li>
            <!--<li style="height: 3.75rem; line-height: 3.75rem; font-weight: bold;">
                <span class="vip_run_con_span1" >我的参与</span>
            </li>
            <li>
                <span class="vip_run_con_span1" style="padding-left: 1rem">星奈吉余额 </span>
                <span class="vip_run_con_span2">2000 <img class="vip_run_con_img2" src="./tpl/Wap/default/img/vip2.png" alt=""> <img class="vip_run_con_img1" src="./tpl/Wap/default/img/vip1.png" alt=""></span>
            </li>-->
            <li style="border:none"></li>
        </ul>
    </div>
    <?php if(is_array($rose_adv)): $k = 0; $__LIST__ = $rose_adv;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k; if($v['count'] == 2): ?><div class="vip_run_endp startp1 startp2" dataid="<?php echo ($v["id"]); ?>" dataurl="<?php echo ($v["url"]); ?>"><?php echo ($v["title"]); ?></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<input type="hidden" class="type" value="<?php echo ($type); ?>">
<script type="text/javascript" src="./tpl/Wap/default/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./tpl/Wap/default/js/font.js"></script>
<ul class="footer">
    <?php if($weixin_alipay_type == 'wechat'): ?><li data-url="<?php echo U('V_2WechantDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li>
        <?php else: ?>
        <li data-url="<?php echo U('V_2AlipayDollMachine/index',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">首页</li><?php endif; ?><!--<li data-url="<?php echo U('space',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type));?>">玫瑰空间</li>-->
    <li data-url="<?php echo U('V_2Rose/quotient',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">生态商</li>
    <li data-url="<?php echo U('V_2Rose/vip_personal',array('user_id'=>$user_id,'weixin_alipay_type'=>$weixin_alipay_type,'scan_code'=>$scan_code));?>">我的</li>
</ul>
<script type="text/javascript" charset="utf-8">
    $('.footer li').click(function(){
        location.href = $(this).attr('data-url');
    });
    var url = location.pathname + location.search;
    $("[data-url='"+url+"']").addClass('active');
</script>
</body>
<script>
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
    $('.adv').click(function(){
        var type=$('.type').val();
        if(type==1){
            alert('你还不是广告商');
            return false;
        }else if(type==2){
            window.location.href="<?php echo U('Adv/personal',array('weixin_alipay_type'=>$weixin_alipay_type,'user_id'=>$user_id,'scan_code'=>$scan_code));?>";
        }
    });
    $('.no_open').click(function(){
        alert('此功能还没开放');
    })
</script>
</html>