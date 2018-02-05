Zepto(function($) {
    /*$('.praise-text').tap(function(){
        var next = $(this).next('praise-text-main');
        alert($(this).children().length);
    })*/
    //跳转到回复评论
    $(".bottom-one").on('click',function(){
        /*$(".footer-import").toggleClass('on');
        if($('.footer-import').hasClass('on')){
            $('.bottoms').css('height','115px');
            $('.dataiduser').val($(this).attr('replybuyer_id'));
            $('.friendinfo_id').val($(this).attr('friendinfo_id'));
        }else{
            $('#content').val('');
            $('.bottoms').css('height','60px');
        }*/
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
});
