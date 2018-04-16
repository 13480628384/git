/**
 * Created by 星辉 on 2015/8/19.
 */
define(function(require, exports){
    require("msg");
    /*
    *   滑动删除（存在bug,会影响滚动）(ps: 滚动已解决，可以滚了，阿弥陀佛！By Samphay 2015/08/24 9:22)
    * */
    exports.slideAction = function(mClass,aClass){
        $(aClass).each(function(){
            $(this).css({
                lineHeight : $(this).parents().height()+"px"
            })
        });
        var w = 0,
            initCss = {
                transition: "none"
            },
            moveCss = {
                transition: "all 300ms cubic-bezier(0.86, 0, 0.07, 1)"
            };
        var option = {
            start : function(){
                w = $(this).parent().find(aClass).width();
                var $parent = $(this).parents();
                $parent.siblings().find(mClass).css({
                    transition: "all 300ms cubic-bezier(0.86, 0, 0.07, 1)",
                    transform : "translate3d(0,0,0)"
                }).removeClass("on");
            },
            move : function(e){
                //e.stopPropagation();
                if(Math.abs(this.x)>10){
                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                }else{
                    $(window).scroll(function(ev){
                        ev.stopPropagation();
                        ev.preventDefault();
                    })
                }
                var mx = 0;
                var This = $(this);
                if(!$(this).hasClass("on")){
                    mx = this.x<(0-w-10)?(0-w-10):this.x;
                    if(mx>10 ){
                        mx = 10;
                    }
                }else{
                    mx = 0 - (w - this.x) >10?10:0 - (w - this.x);
                    if(mx<(0-w-10)){
                        mx = (0-w-10);
                    }
                }
                $(mClass).css(initCss);
                /*requestAnimationFrame(function(){
                    This.css({
                        transform : "translate3d("+mx+"px,0,0)"
                    })
                });*/
                This.css({
                    transform : "translate3d("+mx+"px,0,0)"
                })

            },
            end : function(){
                $(mClass).css(moveCss);
                if(!$(this).hasClass("on")){
                    if(this.x<(-10) /*&& Math.abs(this.y)<40*/){
                        $(this).css({
                            transform : "translate3d(-"+w+"px,0,0)"
                        }).addClass("on");
                        var This = $(this);
                        $(document).one("touchstart",function(e){
                            e.stopPropagation();
                            e.preventDefault();
                            This.css({
                                transition: "all 300ms cubic-bezier(0.86, 0, 0.07, 1)",
                                transform : "translate3d(0,0,0)"
                            }).removeClass("on")
                        })
                    }else{
                        $(this).css({
                            transform : "translate3d(0,0,0)"
                        });
                    }
                }else{
                    if(this.x>(0-w+10) /*&& Math.abs(this.y)<40*/){
                        $(this).css({
                            transform : "translate3d(0,0,0)"
                        }).removeClass("on");
                    }else{
                        $(this).css({
                            transform : "translate3d(-"+w+"px,0,0)"
                        });
                    }
                }
            }
        };
        $(mClass).swapp(option)
    };

    /*
    *   轮播图 (这个插件似乎没有延时加载)
    * */
    exports.iSlider = function(obj,data,option){
        /*
        * 请求iSlider.js
        * */
        require("islider");
        var opt = {
            type: 'pic',
            data: data,
            dom: document.getElementById(obj),
            isLooping: true,
            isAutoplay : true,
            isDebug : false,
            isVertical: false,
            duration: 2000
        };
        $.extend(opt,option);
        return new iSlider(opt);
    };

    /*
     * ajax封装方法
     * 为了快速实现简单的get和post请求
     */
    exports.ajax = function(url, data, callBackSuccess, callBackError, GetPost, requestType, async){
        var type     = (typeof(GetPost) == 'undefined' || GetPost == 1) ? 'POST' : 'GET';
        var dataType = typeof(requestType) == 'undefined' ? 'json' : requestType;
        var async    = typeof(async) == 'undefined' ? true : async;
        var _ajax_cache = typeof(data._ajax_cache) != 'undefined' && data._ajax_cache;//指定缓存
        $.ajaxSetup ({
            cache: false
        });
        $.ajax({
            url  : url,
            type : type,
            async: async,
            data : data,
            dataType : dataType,
            success: function(data){
                if(typeof(callBackSuccess) === "function"){
                    if (data.status === -999) {
                        window.location.href = data.sMsg;
                    }else{
                        callBackSuccess(data);
                    }
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown){
                if(typeof(callBackError) === "function"){
                    callBackError(XMLHttpRequest, textStatus, errorThrown);
                }
            },
            xhrFields: {
                withCredentials: true
            }
        });
    };

    /*
    * 向下滚动分页加载
    * */
    var page = $('#page').val() ? $('#page').val() : 1,
        finished = false;
    exports.scrollPage = function(obj,url,data,callback, callBackError, GetPost, requestType, async){
        var loading = $("<div>"),
            scrollPaging = null;
        var marginButton = parseInt($(obj).children().eq(0).css("margin-bottom"));
        /*if(!url){
            return false;
        }*/
        if(($(obj)).length<1){return false;}
        $(window).scroll(function(e){
            if($(window).scrollTop() - $(document).height() + $(window).height() > (0-100)){
                if(scrollPaging){
                    //console.log("加载中") ;
                    return false;
                }
                if($(obj).attr('finished') == 1){
                    return false;
                }
                scrollPaging = setTimeout(function(){
                    loading.css({
                        position : "relative",
                        width : "100%",
                        bottom : marginButton - 10,
                        textAlign : "center",
                        color : "#ccc",
                        textShadow: "0 0 1px rgba(200,200,200,.1)"
                    }).html("正在加载···").appendTo($(obj)).waiting(null,12,{top:"2px",left:"-40px"});
                    data.page = page;
                    exports.ajax(
                            url,
                            data,
                            function(datas){
                                typeof(callback) === "function" ? callback.call($(obj),datas) : null;
                                page++;
                                if ($.trim(datas).length == 0) {
                                    $(obj).attr('finished', 1);
                                }
                                $("img").lazyload();
                                loading.remove();
                                //console.log("ok");
                                clearTimeout(scrollPaging);
                                scrollPaging = null;
                            }, callBackError, GetPost, requestType, async);
                    /*
                    * 以下的setTimeout是测试代码，模拟ajax
                    * */
                    /*
                    setTimeout(function(){
                        var data = $("#dataTemp").html();
                        typeof(callback) === "function" ? callback.call($(obj),data) : null;
                        $("img").lazyload();
                        loading.remove();
                        //console.log("ok");
                        clearTimeout(scrollPaging);
                        scrollPaging = null;
                    },3000)
                    */
                },1)
            }
        })
    };

    /*
    *   复选框
    * */
    exports.checkBox = function(pClass,iClass,onClass,n){
        function checkN(){
            var i = 0 ;
            $(pClass).each(function(){
                if($(this).find(iClass).hasClass(onClass)){
                    i++;
                }
            });
            return i;
        }
        if(!n) n = 1;
        if(typeof onClass === "Number"){
            n = onClass;
            onClass = "on";
        }
        if(!onClass) onClass = "on";

        $(pClass).touch(function(){
            if(!$(this).find(iClass).hasClass(onClass)){
                if(checkN()<n && n>1){
                    $(this).attr("check",true)
                        .find(iClass)
                        .addClass(onClass);
                }
                if(n == 0){
                    $(this).attr("check",true)
                        .find(iClass)
                        .addClass(onClass);
                }
                if(n == 1){
                    $(pClass).each(function(){
                        if($(this).find(iClass).hasClass(onClass)){
                            $(this).removeAttr("check");
                            $(this).find(iClass).removeClass(onClass);
                        }
                    });
                    $(this).attr("check",true)
                        .find(iClass)
                        .addClass(onClass);
                }
            }else{
                $(this).removeAttr("check").find(iClass)
                    .removeClass(onClass);
            }
        })

    };

    exports.alert = msg.alert;

    exports.tips = msg.tips;

    exports.confirm = msg.confirm;

    /*function bMap(callback) {
        callback = typeof(callback) === "function" ?
            callback:
            null;
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://api.map.baidu.com/api?v=2.0&ak=2WQAlmlNeRT29pY8vTqCN7kO&callback="+callback;
        document.body.appendChild(script);
    }*/

    /*
    * via BaiduMap to get Address and Point's lat&&lng
    * */
    function getCity(callback){
        var geolocation = new BMap.Geolocation();
        var geoc = new BMap.Geocoder();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                geoc.getLocation(r.point, function(rs){
                    var addComp = rs.addressComponents;
                    typeof (callback) === "function"?
                        callback(addComp, r.point):
                        null;
                });
            }
            else {
                alert('failed'+this.getStatus());
            }
        },{enableHighAccuracy: true})
    }

    /*
    *   省市区三级联动
    *   province,city,area,三个分别是省市区的select框的ID, 有auto将会自动获取省市区
    *
    * */
    exports.area = function(province,city,area,auto){
        var $loadingP = $("<option>").text("正在获取你的省份");
        var $loadingC = $("<option>").text("正在获取你的城市");
        var $loadingA = $("<option>").text("正在获取你的地区");
        require("area");
        var $p = $("#"+province),
            $c = $("#"+city),
            $a = $("#"+area);
        if(auto){
            $loadingP.appendTo($p);
            $loadingC.appendTo($c);
            $loadingA.appendTo($a);
            getCity(function(ad,pt){
                var p = ad.province,
                    c = ad.city,
                    a = ad.district;
                $p.empty();
                $c.empty();
                $a.empty();
                new PCAS(province, city, area, p, c, a);
            })
        }else{
            new PCAS(province, city, area, '', '', '');
        }
    };

    /*
    *   滑动切换选项卡
    * */
    exports.slideTap = function(obj,callback){
        require("touchSlide");
        var opt = {
            slideCell : obj,
            titCell   : ".slideNav li",
            mainCell  : ".slideBox",
            endFun    : function(){
                typeof callback == "function"?
                    callback():
                    null;
                console.log($(".slideNav").position().top)
                window.scrollTo($(".slideNav").position().top,0);
                $(".slideBox ul").animate({
                    opacity : 1
                },200);
                $("img").lazyload()
            }
        };
        TouchSlide(opt);
    }

});
