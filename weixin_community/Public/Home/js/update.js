
Zepto(function($) {
    $('.update_personal').tap(function(){
        if($('.update_personal').hasClass('on')){
            return false;
        }
        var nickname = $('.nickname').val();
        var openid = $('.openid').val();
        var headimgurl = $('.headimgurl').attr('data');
        if(nickname==''){
            new TipBox({type: 'tip', str:"昵称不能为空哦", setTime: 1500});
            return false;
        }
        var personal = $('.personal').val();
        $('.update_personal').addClass('on');
        $.post(personal,{openid:openid,nickname:nickname,headimgurl:headimgurl},function(data){
            if(data.code==200){
                new TipBox({type: 'success', str:"修改成功", setTime: 1500});
            }else{
                new TipBox({type: 'error', str:"修改失败", setTime: 1500});
            }
            $('.update_personal').removeClass('on');
        },'json')
    });
});
/*图片上传*/
var images = {
    localId: [],
    serverId: []
};
var num = 0;
$(".add_img").click(function() {
    wx.chooseImage({
        count: 1,
        success: function (res) {
            var localIds = res.localIds;
            images.localId = res.localIds;
            var i = 0, length = images.localId.length;
            images.serverId = [];
            function upload() {
                wx.uploadImage({
                    localId: images.localId[i],
                    success: function (res) {
                        i++;
                        if (num >=1) {
                            $(".add_img").hide();
                        }
                        images.serverId.push(res.serverId);
                        if (i < length) {
                            upload();
                        }else{
                            var  url=$('.add_img_upload').val();
                            $.post(url,{imgs:encodeURIComponent(images.serverId)},function(data){
                                num++;
                                var leng=data.imgs.length;
                                $.each(data.imgs, function(e,t){
                                    $('.head-portrait').html(
                                        "<img src='"+t+"' class='headimgurl' data='"+t+"' />"
                                    );
                                });
                            },'json');
                        }
                    },
                    fail: function (res) {
                        alert(JSON.stringify(res));
                    }
                });
            }
            upload();
        }
    });
});