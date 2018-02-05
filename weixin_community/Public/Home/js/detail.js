Zepto(function($) {
    function fen(){
        $('.acc').css("display", "block");
    }
    $('.acc img').tap(function () {
        $('.acc').css("display", "none");
    });
});
/**
 * Created by 1195 on 2016-7-19.
 */
$(function(){
    PLACEORDER_NEW.bindEvent();  //绑定事件
});
var PLACEORDER_NEW = {
    //事件绑定
    bindEvent             :function(){
        var self = this;
        $('.Click_Zan').click(function(){
            var id = $('.id').val();
            var create_openid = $('.create_openid').val();
            var IF=false;
            var result = PLACEORDER_NEW.post(QuasarConfig.AjaxApiMap.Click_Zan,{id:id,openid:create_openid});
            if(result.code==200){
                IF=true;
                //new TipBox({type:'tip',str:'你已经赞过了',clickDomCancel:true,setTime:1500});
            }
            if(IF==false){
                var result1 = PLACEORDER_NEW.post(QuasarConfig.AjaxApiMap.Click_Friend,{id:id,openid:create_openid});
                if(result1.code==200){
                    new TipBox({type:'success',str:'点赞成功',clickDomCancel:true,setTime:1500});
                }
            }else{
                var result2 = PLACEORDER_NEW.post(QuasarConfig.AjaxApiMap.Cancel,{id:id,openid:create_openid});
                if(result2.code==200){
                    new TipBox({type:'success',str:'取消点赞',clickDomCancel:true,setTime:1500});
                }
            }
        });
        //评论事件
        $('.Comment').on('click', function(){
            var $inputBox = $(".footer-import");
            var $input = $inputBox.find("input");
            $inputBox.addClass('on');
            $('.fotter').css('display','none');
        });
        //取消评论
        $('.append_comment').click(function(){
            $('.footer-import').removeClass('on');
            $('.fotter').css('display','block');
        });
        //评论事件
        $('.send-btn').on('click',function(){
            if($('.send-btn').hasClass('on')){
                return false;
            }
            var Comment_Input = $('.Comment_input').val();
            var Openid = $('.openid').val();
            var id = $('.id').val();
            var create_openid = $('.create_openid').val();
            if(Comment_Input==''){
                new TipBox({type:'tip',str:'请填写评论',clickDomCancel:true,setTime:1500});
            }
            $('.send-btn').html('发送中').addClass('on');
            var result = PLACEORDER_NEW.post(QuasarConfig.AjaxApiMap.NotTreatment,{create_openid:create_openid,id:id,Comment_Input:Comment_Input,Openid:Openid});
            if(result.code==200){
                $('.footer-import').removeClass('on');
                $('.fotter').css('display','block');
                $('.send-btn').html('发送').removeClass('on');
                $('.append_comment').append(
                '<li>'+
                '<div class="commentli-img">'+
                    '<img src="'+result.reply.headimgurl+'">'+
                    '</div>'+
                    '<div class="con-right">'+
                    result.reply.re_name+'<b class="back">LV 1</b>'+
                '</div>'+
                '<div class="clear"></div>'+
                    '<p>'+result.reply.re_text+'</p>'+
                '<div class="xin">'+
                    '<span class="minute">'+result.reply[0]+'</span>'+
                '<tt class="rixin">'+
                    '<img src="/weixin_community/Public/Home/img/icon-praise.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;'+
            '<img src="/weixin_community/Public/Home/img/icon-comment.png" alt="">'+
                    '</tt>'+
                    '</div>'+
                    '<div class="clear"></div>'+
                    '</li>'
                );
            }
        })
    },
    /* ajax 请求 返回数据 */
    post                  :function(url, data){
        var self = this;
        var result = null;
        $.ajax({
            type      :'post',
            url       :url,
            data      :data,
            dataType  :'json',
            async     :false,
            beforeSend:function(){
                //$('.send-btn').html('发送中');
            },
            success   :function(r){
                result = r;
            },
            complete  :function(){
                //完成加载
                //$('.send-btn').html('发送');
            },
            error     :function(){
                console.log(url+'网络错误');
                var txt = url+'网络错误';
            }
        });
        return result;
    }
}


