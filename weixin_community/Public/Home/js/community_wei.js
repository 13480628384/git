Zepto(function($) {
    $('.rem').tap(function(){
        $('.imgs').css('display','block');
        $('.imgs').css('position','fixed');
        $('.imgs').css('top','0');
        $('.imgs').css('left','0');
        $('.imgs').css('z-index','2000');
        $('.imgs').css('opacity','0.9');
    });
    $('.imgs').tap(function(){
        $('.imgs').css('display','none');
    });
    //跳转到回复评论
    $(".bottom-one").on('click',function(){
        var strate_url_of = $('.strate_url_of').val();
        var replybuyer_id = $(this).attr('replybuyer_id');
        var userid = $('.openid').val();
        var is_openid = $('.is_openid').val();
        var friendinfo_id = $(this).attr('friendinfo_id');
        if(is_openid==''){
            var nurl = $('.nurl').val();
            window.location.href=nurl;
        }else{
            window.location.href=strate_url_of+"&replybuyer_id="+replybuyer_id+"&friendinfo_id="+friendinfo_id+"&userid="+userid;
        }
    });
    //回复评论
    $('.praise-text-main').tap(function(){
        var strate_url_of = $('.strate_url_of').val();
        var replybuyer_id = $(this).attr('replybuyer_id');
        var userid = $('.openid').val();
        var is_openid = $('.is_openid').val();
        var friendinfo_id = $(this).attr('friendinfo_id');
        if(is_openid==''){
            var nurl = $('.nurl').val();
            window.location.href=nurl;
        }else{
            window.location.href=strate_url_of+"&replybuyer_id="+replybuyer_id+"&friendinfo_id="+friendinfo_id+"&userid="+userid;
        }
    });
    //发送评论
    $('#send-btn').tap(function(){
        var replybuyer_id = $('.dataiduser').val();
        var userid = $('.openid').val();
        var friendinfo_id = $('.friendinfo_id').val();
        var content = $('#content').val();
        var jinzhi = $('.jinzhi').val();
        var pl_url = $('.pl_url').val();
        if(!content){
            new TipBox({type:'tip',str:'请输入评论内容',clickDomCancel:true,setTime:1000});
        }
        if(jinzhi==1){
            new TipBox({type:'tip',str:'请输入合法内容',clickDomCancel:true,setTime:1000});
        }
        $.post(pl_url,{replybuyer_id:replybuyer_id,userid:userid,friendinfo_id:friendinfo_id,content:content},function(data){
            if(data.code==200){
                $('#content').val('');
                $('.footer-import').removeClass('on');
                new TipBox({type: 'success', str: '回复成功', setTime: 1500});
            }
        },'json');
    });
    /*点赞 [[*/
    $('.bottom-two').tap(function(){
        /*if($(this).hasClass('on')){
           return false;
        }*/
        var This = $(this);
        var openid = $('.openid').val();
        var xin = $('.xin').val();
        var po = $(this).find('.po');
        var click_zan_more = $('.click_zan_more').val();
        var cancel = $('.cancel').val();
        var click_friend_url = $('.click_friend').val();
        var frid = $(this).attr('data');
        var icon_img = $('.icon_img').val();
        var zan = false;
        $.ajax({
            type:'post',
            url:click_zan_more,
            data:{openid:openid,id:frid},
            dataType:'json',
            async:false,
            success:function(data){
                if(data.code==200){
                    zan = true;
                    //new TipBox({type: 'tip', str: '你赞过了哦', setTime: 1500});
                }
            },
            error:function(type,error){
                alert(error);
            }
        });
        if(zan==false) {
            //This.addClass('on');
            $.post(click_friend_url, {openid: openid, id: frid}, function (data) {
                if (data.code == 200) {
                    This.find('img').attr('src', xin);
                    po.html(data.all);
                }
            }, 'json');
        }else{
            //取消赞
            var cancel = $('.cancel').val();
            $.post(cancel,{openid:openid,id:frid},function(data){
                if(data.code==200){
                    //This.addClass('on');
                    This.find('img').attr('src',icon_img);
                    po.html(data.all);
                }
            },'json');
        }
    });
    //加载更多
    var nums = 2;
    var count = 11;
    var user_ajax_community_url = $('.user_ajax_community').val();
    var icon_img = $('.icon_img').val();
    var comm_img = $('.comm_img').val();
    var is_openid = $('.is_openid').val();
    var openid = $('.openid').val();
    var news = $('.new').val();
    var group_id = $('.group_id').val();
    $('.loading').tap(function () {
        if($('.loading').hasClass('on')){
            return false;
        }
        $('.loading').addClass('on');
        $('.more').html('');
        $('.loading_animate').css('display','block');
        $.post(user_ajax_community_url, {group_id:group_id,new:news,page: nums,userid:openid,is_openid:is_openid}, function (data) {
            $('.loading').removeClass('on');
            $('.more').html('点击加载更多');
            $('.loading_animate').css('display','none');
            if($.trim(data).length>0){
                $('.community').append(data);
            }else{
                $(".loading").html("已加载全部");
            }
        }, 'html');
        nums++;
    });
});
