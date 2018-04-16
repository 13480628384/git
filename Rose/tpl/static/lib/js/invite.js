$(function(){
	$.fn.extend({
        invite: function(option){
            var $Cover = null;
            option = $.extend({}, {
                'id' : 'mcover',
                'bg' : 'http://v.wapwei.com/tpl/static/wapweiui/media/img/guide.png'
            }, option);
            var id = option.id;
            //生成遮罩层
            function generate() {
                var html = '<div id="'+id+'" style="background-position:50%;'+
                           'background-size:contain;background-repeat:no-repeat;'+
                           'background-image:url('+option.bg+')'+
                           ';position: fixed;  z-index: 99099999;'+
                           'background-color: rgba(0, 0, 0, 0.8);left: 0;">'+
                           '</div>';
                    $(html).appendTo($('body'));
                    $Cover = $('#'+id);
                    $Cover.css({
                        'display':'none',
                        'width':$(window).width(),
                        'height':$(window).height(),
                        'top':0
                    });
                    $Cover.children('img').css({
                        'left':($(window).width()-$('#mcover').children('img').width())/2,
                        'top':'20px',
                        'display':'block'
                    });
            }
            $(this).click(function(){
                generate();
                $Cover.css({
                    'display':'block',
                    'width':$(window).width(),
                    'height':$(window).height(),
                    'top':0
                });
                $Cover.children('img').css({
                    'left':($(window).width()-$('#mcover').children('img').width())/2,
                    'top':'20px',
                    'display':'block'
                });
            });
            $(document).on('touchstart', '#'+id, function(){
                $(this).remove();
            });
        }
    });
});
