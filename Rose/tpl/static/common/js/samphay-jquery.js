/**
 * Created by Samphay on 2015/5/25.
 */
/*阻止iphone 默认滑动
*
* 在每个页面按需使用吧（samphay）
* */
//document.addEventListener('touchmove', function (e) {    e.preventDefault();}, false)

;(function($){
    $.fn.waiting = function(callback,size,position, extend){
        if(!size){
            size = 12
        }
        var loading = "data:image/gif;base64,R0lGODlhgACAAKIAAP///93d3bu7u5mZmQAA/wAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFBQAEACwCAAIAfAB8AAAD/0i63P4wygYqmDjrzbtflvWNZGliYXiubKuloivPLlzReD7al+7/Eh5wSFQIi8hHYBkwHUmD6CD5YTJLz49USuVYraRsZ7vtar7XnQ1Kjpoz6LRHvGlz35O4nEPP2O94EnpNc2sef1OBGIOFMId/inB6jSmPdpGScR19EoiYmZobnBCIiZ95k6KGGp6ni4wvqxilrqBfqo6skLW2YBmjDa28r6Eosp27w8Rov8ekycqoqUHODrTRvXsQwArC2NLF29UM19/LtxO5yJd4Au4CK7DUNxPebG4e7+8n8iv2WmQ66BtoYpo/dvfacBjIkITBE9DGlMvAsOIIZjIUAixliv9ixYZVtLUos5GjwI8gzc3iCGghypQqrbFsme8lwZgLZtIcYfNmTJ34WPTUZw5oRxdD9w0z6iOpO15MgTh1BTTJUKos39jE+o/KS64IFVmsFfYT0aU7capdy7at27dw48qdS7eu3bt480I02vUbX2F/JxYNDImw4GiGE/P9qbhxVpWOI/eFKtlNZbWXuzlmG1mv58+gQ4seTbq06dOoU6vGQZJy0FNlMcV+czhQ7SQmYd8eMhPs5BxVdfcGEtV3buDBXQ+fURxx8oM6MT9P+Fh6dOrH2zavc13u9JXVJb520Vp8dvC76wXMuN5Sepm/1WtkEZHDefnzR9Qvsd9+/wi8+en3X0ntYVcSdAE+UN4zs7ln24CaLagghIxBaGF8kFGoIYV+Ybghh841GIyI5ICIFoklJsigihmimJOLEbLYIYwxSgigiZ+8l2KB+Ml4oo/w8dijjcrouCORKwIpnJIjMnkkksalNeR4fuBIm5UEYImhIlsGCeWNNJphpJdSTlkml1jWeOY6TnaRpppUctcmFW9mGSaZceYopH9zkjnjUe59iR5pdapWaGqHopboaYua1qije67GJ6CuJAAAIfkEBQUABAAsCgACAFcAMAAAA/9Iutz+ML5Ag7w46z0r5WAoSp43nihXVmnrdusrv+s332dt4Tyo9yOBUJD6oQBIQGs4RBlHySSKyczVTtHoidocPUNZaZAr9F5FYbGI3PWdQWn1mi36buLKFJvojsHjLnshdhl4L4IqbxqGh4gahBJ4eY1kiX6LgDN7fBmQEJI4jhieD4yhdJ2KkZk8oiSqEaatqBekDLKztBG2CqBACq4wJRi4PZu1sA2+v8C6EJexrBAD1AOBzsLE0g/V1UvYR9sN3eR6lTLi4+TlY1wz6Qzr8u1t6FkY8vNzZTxaGfn6mAkEGFDgL4LrDDJDyE4hEIbdHB6ESE1iD4oVLfLAqPETIsOODwmCDJlv5MSGJklaS6khAQAh+QQFBQAEACwfAAIAVwAwAAAD/0i63P5LSAGrvTjrNuf+YKh1nWieIumhbFupkivPBEzR+GnnfLj3ooFwwPqdAshAazhEGUXJJIrJ1MGOUamJ2jQ9QVltkCv0XqFh5IncBX01afGYnDqD40u2z76JK/N0bnxweC5sRB9vF34zh4gjg4uMjXobihWTlJUZlw9+fzSHlpGYhTminKSepqebF50NmTyor6qxrLO0L7YLn0ALuhCwCrJAjrUqkrjGrsIkGMW/BMEPJcphLgDaABjUKNEh29vdgTLLIOLpF80s5xrp8ORVONgi8PcZ8zlRJvf40tL8/QPYQ+BAgjgMxkPIQ6E6hgkdjoNIQ+JEijMsasNY0RQix4gKP+YIKXKkwJIFF6JMudFEAgAh+QQFBQAEACw8AAIAQgBCAAAD/kg0PPowykmrna3dzXvNmSeOFqiRaGoyaTuujitv8Gx/661HtSv8gt2jlwIChYtc0XjcEUnMpu4pikpv1I71astytkGh9wJGJk3QrXlcKa+VWjeSPZHP4Rtw+I2OW81DeBZ2fCB+UYCBfWRqiQp0CnqOj4J1jZOQkpOUIYx/m4oxg5cuAaYBO4Qop6c6pKusrDevIrG2rkwptrupXB67vKAbwMHCFcTFxhLIt8oUzLHOE9Cy0hHUrdbX2KjaENzey9Dh08jkz8Tnx83q66bt8PHy8/T19vf4+fr6AP3+/wADAjQmsKDBf6AOKjS4aaHDgZMeSgTQcKLDhBYPEswoA1BBAgAh+QQFBQAEACxOAAoAMABXAAAD7Ei6vPOjyUkrhdDqfXHm4OZ9YSmNpKmiqVqykbuysgvX5o2HcLxzup8oKLQQix0UcqhcVo5ORi+aHFEn02sDeuWqBGCBkbYLh5/NmnldxajX7LbPBK+PH7K6narfO/t+SIBwfINmUYaHf4lghYyOhlqJWgqDlAuAlwyBmpVnnaChoqOkpaanqKmqKgGtrq+wsbA1srW2ry63urasu764Jr/CAb3Du7nGt7TJsqvOz9DR0tPU1TIA2ACl2dyi3N/aneDf4uPklObj6OngWuzt7u/d8fLY9PXr9eFX+vv8+PnYlUsXiqC3c6PmUUgAACH5BAUFAAQALE4AHwAwAFcAAAPpSLrc/m7IAau9bU7MO9GgJ0ZgOI5leoqpumKt+1axPJO1dtO5vuM9yi8TlAyBvSMxqES2mo8cFFKb8kzWqzDL7Xq/4LB4TC6bz1yBes1uu9uzt3zOXtHv8xN+Dx/x/wJ6gHt2g3Rxhm9oi4yNjo+QkZKTCgGWAWaXmmOanZhgnp2goaJdpKGmp55cqqusrZuvsJays6mzn1m4uRAAvgAvuBW/v8GwvcTFxqfIycA3zA/OytCl0tPPO7HD2GLYvt7dYd/ZX99j5+Pi6tPh6+bvXuTuzujxXens9fr7YPn+7egRI9PPHrgpCQAAIfkEBQUABAAsPAA8AEIAQgAAA/lIutz+UI1Jq7026h2x/xUncmD5jehjrlnqSmz8vrE8u7V5z/m5/8CgcEgsGo/IpHLJbDqf0Kh0ShBYBdTXdZsdbb/Yrgb8FUfIYLMDTVYz2G13FV6Wz+lX+x0fdvPzdn9WeoJGAYcBN39EiIiKeEONjTt0kZKHQGyWl4mZdREAoQAcnJhBXBqioqSlT6qqG6WmTK+rsa1NtaGsuEu6o7yXubojsrTEIsa+yMm9SL8osp3PzM2cStDRykfZ2tfUtS/bRd3ewtzV5pLo4eLjQuUp70Hx8t9E9eqO5Oku5/ztdkxi90qPg3x2EMpR6IahGocPCxp8AGtigwQAIfkEBQUABAAsHwBOAFcAMAAAA/9Iutz+MMo36pg4682J/V0ojs1nXmSqSqe5vrDXunEdzq2ta3i+/5DeCUh0CGnF5BGULC4tTeUTFQVONYAs4CfoCkZPjFar83rBx8l4XDObSUL1Ott2d1U4yZwcs5/xSBB7dBMBhgEYfncrTBGDW4WHhomKUY+QEZKSE4qLRY8YmoeUfkmXoaKInJ2fgxmpqqulQKCvqRqsP7WooriVO7u8mhu5NacasMTFMMHCm8qzzM2RvdDRK9PUwxzLKdnaz9y/Kt8SyR3dIuXmtyHpHMcd5+jvWK4i8/TXHff47SLjQvQLkU+fG29rUhQ06IkEG4X/Rryp4mwUxSgLL/7IqFETB8eONT6ChCFy5ItqJomES6kgAQAh+QQFBQAEACwKAE4AVwAwAAAD/0i63A4QuEmrvTi3yLX/4MeNUmieITmibEuppCu3sDrfYG3jPKbHveDktxIaF8TOcZmMLI9NyBPanFKJp4A2IBx4B5lkdqvtfb8+HYpMxp3Pl1qLvXW/vWkli16/3dFxTi58ZRcChwIYf3hWBIRchoiHiotWj5AVkpIXi4xLjxiaiJR/T5ehoomcnZ+EGamqq6VGoK+pGqxCtaiiuJVBu7yaHrk4pxqwxMUzwcKbyrPMzZG90NGDrh/JH8t72dq3IN1jfCHb3L/e5ebh4ukmxyDn6O8g08jt7tf26ybz+m/W9GNXzUQ9fm1Q/APoSWAhhfkMAmpEbRhFKwsvCsmosRIHx444PoKcIXKkjIImjTzjkQAAIfkEBQUABAAsAgA8AEIAQgAAA/VIBNz+8KlJq72Yxs1d/uDVjVxogmQqnaylvkArT7A63/V47/m2/8CgcEgsGo/IpHLJbDqf0Kh0Sj0FroGqDMvVmrjgrDcTBo8v5fCZki6vCW33Oq4+0832O/at3+f7fICBdzsChgJGeoWHhkV0P4yMRG1BkYeOeECWl5hXQ5uNIAOjA1KgiKKko1CnqBmqqk+nIbCkTq20taVNs7m1vKAnurtLvb6wTMbHsUq4wrrFwSzDzcrLtknW16tI2tvERt6pv0fi48jh5h/U6Zs77EXSN/BE8jP09ZFA+PmhP/xvJgAMSGBgQINvEK5ReIZhQ3QEMTBLAAAh+QQFBQAEACwCAB8AMABXAAAD50i6DA4syklre87qTbHn4OaNYSmNqKmiqVqyrcvBsazRpH3jmC7yD98OCBF2iEXjBKmsAJsWHDQKmw571l8my+16v+CweEwum8+hgHrNbrvbtrd8znbR73MVfg838f8BeoB7doN0cYZvaIuMjY6PkJGSk2gClgJml5pjmp2YYJ6dX6GeXaShWaeoVqqlU62ir7CXqbOWrLafsrNctjIDwAMWvC7BwRWtNsbGFKc+y8fNsTrQ0dK3QtXAYtrCYd3eYN3c49/a5NVj5eLn5u3s6e7x8NDo9fbL+Mzy9/T5+tvUzdN3Zp+GBAAh+QQJBQAEACwCAAIAfAB8AAAD/0i63P4wykmrvTjrzbv/YCiOZGmeaKqubOu+cCzPdArcQK2TOL7/nl4PSMwIfcUk5YhUOh3M5nNKiOaoWCuWqt1Ou16l9RpOgsvEMdocXbOZ7nQ7DjzTaeq7zq6P5fszfIASAYUBIYKDDoaGIImKC4ySH3OQEJKYHZWWi5iZG0ecEZ6eHEOio6SfqCaqpaytrpOwJLKztCO2jLi1uoW8Ir6/wCHCxMG2x7muysukzb230M6H09bX2Nna29zd3t/g4cAC5OXm5+jn3Ons7eba7vHt2fL16tj2+QL0+vXw/e7WAUwnrqDBgwgTKlzIsKHDh2gGSBwAccHEixAvaqTYcFCjRoYeNyoM6REhyZIHT4o0qPIjy5YTTcKUmHImx5cwE85cmJPnSYckK66sSAAj0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gwxZJAAA7LyogIHx4R3YwMHwzNjY3YzY4MzBmOTBmNjgzODNmN2ViN2E0OWQ0MTEyMCAqLw==";
        var img = '<div class="__waiting__" ' +
            'style ="position:absolute;z-index:99; width:100%;height:14px;text-align:center;">' +
            '<img  src='+loading+' alt="" width="'+size+'" ' +
            '/> '+
            '</div>';
        //window正居中
        //if (typeof(extend.winCenter) != 'undefined' && extend.winCenter) {
        if (extend && extend.hasOwnProperty('winCenter')) {
            position.top = ($(window).height() - size)/2;
        }
        $(this).each(function(){
            $(this).prepend(img);
            $(this).find('.__waiting__').find('img').css('width', size + 'px');
            if(position) $(".__waiting__").css(position);
            if(typeof callback === "function"){
                callback($(this).find('.__waiting__'));
            }
        });
    };
    $.fn.waitingDone = function(callback){
        $(this).each(function(){
            $(this).find(".__waiting__").remove();
            if(typeof callback === "function"){
                callback();
            }
        })
    }
}(jQuery));

/*
* swapp 滑动事件 ，
* This为触发对象本身
* {
*    start :function(This,x,y){},   //按下去触发的方法  x,y 为按下去的坐标点
*    move : function(This,x,y){},   //移动时触发的方法  x,y 为移动后相对按下去的坐标点
*    end : function(This,x,y){}     //离开时触发的方法  x,y 同move
* }
* */
(function($){
    $.fn.swapp = function(opt/*,xy*/){
        var option = {
            start :function(){},
            move : function(){},
            end : function(){}
        };
        $.extend(option,opt);
        return $(this).each(function(i,o){
            var tsx = 0,tsy = 0,tmx = 0,tmy = 0, tex = 0,tey = 0,st= 0,et=0;
            $(this).on("touchstart",function(e){
                /*e.stopPropagation();
                e.preventDefault();*/
                st = e.timeStamp;
                tsx = window.event.touches[0].pageX;
                tsy = window.event.touches[0].pageY;
                this.x = tsx;
                this.y = tsy;
                option.start.call(this);
            });
            $(this).on("touchmove",function(e){
                /*e.stopPropagation();
                e.preventDefault();*/
                tmx = window.event.touches[0].pageX;
                tmy = window.event.touches[0].pageY;
                tex = tmx-tsx ;
                tey = tmy-tsy;
                this.x = tex;
                this.y = tey;
                option.move.call(this,e);
            });
            $(this).on("touchend",function(e){
                /*e.stopPropagation();
                e.preventDefault();*/
                et = (e.timeStamp - st);
                if(et<1200){
                    this.x = tex;
                    this.y = tey;
                    option.end.call(this);
                }
                tsx = 0; tsy = 0; tmx = 0; tmy = 0; tex = 0; tey = 0; st= 0; et=0;
            });
        })
    }
}(jQuery));

/*
* 仿click事件，无法触发未来事件
* */
(function($){
    $.fn.tap = function(callback){
        var tt = null,
            t1 = 0,
            t2 = 0,
            t3 = 0,
            t4 = 0,
            This ;
        return $(this).each(function(){
            $(this).on("touchstart",function(e){
                This = $(this);
                t1 = window.event.touches[0].pageY;
                t3 = window.event.touches[0].pageX;
                if(tt){
                    return;
                }
                $(this).off("touchend");
                $(this).addClass("_on_");
                $(this).on("touchmove",function(e){
                    t2 = window.event.touches[0].pageY;
                    t4 = window.event.touches[0].pageX;
                });
                $(this).on("touchend",function(e){
                    if(t2==0 || t4==0){
                        t2 = t1 = t3 = t4 = 0;
                    }
                    if(Math.abs(t2-t1)>10 || Math.abs(t4-t3)>10){
                        t2 = t1 = t3 = t4 = 0;
                        return;
                    }
                    if(tt){
                        return;
                    }
                    tt = 1;
                    $(this).removeClass("_on_");
                    typeof(callback)=== "function"?callback.call(this):function(){};
                    tt = null;
                })
            });
        });
    }
}(jQuery));



/*localStorage插件
*
* obj不填时，就返回名称为name的本地储存的值；若无name的本地储存则返回为空；
*
* 用法localData(name,[obj])
*
* */

var localData = function(name,obj){
    if(obj){
        var date = new Date();
        this.save = {};
        if(!localStorage.getItem("__samphay__"+name)){
            this.save.creatTime = date.Format("yyyy-MM-dd hh:mm:ss");
        }else{
            this.save.creatTime = JSON.parse(localStorage.getItem("__samphay__"+name)).creatTime;
        }
        this.save.saveTime = date.Format("yyyy-MM-dd hh:mm:ss");
        this.save.saveTimeStamp = date.getTime();
        this.save.type = typeof obj;
        this.save.data = obj;
        localStorage.setItem("__samphay__"+name,JSON.stringify(this.save));
    }else{
        return JSON.parse(localStorage.getItem("__samphay__"+name));
    }
};
var clearLocalData = function(name){
    if(!name){
        var reg =new RegExp("__samphay__");
        for(var i in localStorage){
            if(reg.test(i)){
                localStorage.removeItem(i)
            }
        }
    }else{
        localStorage.removeItem("__samphay__"+name);
    }
};

/*
 * 去px,提取数字
 */
String.prototype.nopx= function(){
    return Number(this.substr(0,this.length-2))
};

Number.prototype.fixNum = function(n){
    if(!n)n=2;
    return Number(this.toFixed(n))
};

String.prototype.fixNum = function(n){
    if(!n)n=2;
    return Number(Number(this).toFixed(n))
};

Number.prototype.AA = function(){
    return this>9?this:"0"+this;
};

Date.prototype.Format = function (fmt) { //author: meizz
    if(!fmt) fmt = 'yyyy-MM-dd';
    var week = [
        "日",
        "一",
        "二",
        "三",
        "四",
        "五",
        "六"
    ];
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "W+" : week[this.getDay()],
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};

/*百度地图*/
(function(){
    window.BMap_loadScriptTime = (new Date).getTime();
    document.write('<script type="text/javascript" src="http://api.map.baidu.com/getscript?v=2.0&ak=2WQAlmlNeRT29pY8vTqCN7kO&services=&t=20150605180935"></script>');
})();

(function($){
    $.fn.readyFn = function(fn){
        var oFn = null;
        if(window.onload){
            oFn = window.onload;
            window.onload = function(){
                oFn();
                typeof fn =="function"?fn():function(){};
            }
        }else{
            window.onload = function(){
                typeof fn =="function"?fn():function(){};
            }
        }
    }
}(jQuery));


/*添加刷新按钮*/
var debugWX = function(){
    //alert(window.navigator.platform);
    if(window.navigator.platform=="Win32"||window.navigator.platform=="iPhone") return;
    var html = '<div id="__reflash__" style="color:#fff;box-shadow:0 0 4px rgba(0,0,0,.6);z-index:999999;position: fixed;top: 38px;left: 38px;text-align:center;line-height: 38px;width: 38px;height: 38px;border-radius:50%;-webkit-border-radius:50%;background-color:rgba(243,86,86,.6)">刷新</div>';
    $(function(){
        $("body").append(html);
        $("#__reflash__").click(function(){
            window.location.reload();
        })
    })
};
/*开启*/
//debugWX();
jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend( jQuery.easing,
    {
        easeInBack: function (x, t, b, c, d, s) {
            if (s == undefined) s = 0.270158;
            return c*(t/=d)*t*((s+1)*t - s) + b;
        },
        easeOutBack: function (x, t, b, c, d, s) {
            if (s == undefined) s = 0.270158;
            return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
        }
    })
function boxCss(obj){
    $(obj).css({
        "bottom":0-$(obj).height(),
        "display":"none",
        "z-index":"9",
        "position" : "fixed"
    }).on("touchmove",function(e){
        e.preventDefault();
        e.stopPropagation();
    })
}

function boxIn(obj,callback){
    boxCss(obj);
    blur(function(){
        boxOut(obj)
    });
    $(obj).addClass("on").fadeIn(0);
    $(obj).addClass("bluring");
    $(obj).animate({
        "left":0,
        "bottom":0
    },400,"easeOutBack",function(){
        if(typeof callback === "function"){
            callback($(obj));
        }
    });

}

function boxOut(obj,callback){
    $(obj).removeClass("bluring");
    $(obj).animate({
        "left":0,
        "bottom":-$(obj).height()
    },300,"easeInBack",function(){
        $(obj).removeClass("on").fadeOut(0);
        if(typeof callback === "function"){
            callback();
        }
    });
}


function blur(callback){
    var blur = $("<div>");
    blur.attr("id","__blur__");
    blur.css({
        "position":"fixed",
        "top":"0",
        "left":"0",
        "width":"100%",
        "height":"100%",
        "background-color" : "rgba(0,0,0,.5)",
        "z-index":"8"
    }).on("touchmove",function(e){
        e.preventDefault();
    }).on("touchend",function(){
        typeof callback === "function"?callback():function(){};
        blur.remove();
    }).appendTo($("body"));
}


(function($){
    $.fn.extend({
        "insert":function(value){
            if(!value) return;
            var dthis=$(this)[0];
            if(document.selection){
                //$(dthis).focus();
                var fus=document.selection.createRange();
                fus.text=value;
                //$(dthis).focus();
            }else if(dthis.selectionStart||dthis.selectionStart=='0'){
                var start=dthis.selectionStart;
                var end=dthis.selectionEnd;
                dthis.value=dthis.value.substring(0,start)+ value+ dthis.value.substring(end,dthis.value.length);
                dthis.selectionStart = dthis.selectionEnd = start+value.length;
                $(dthis).blur();
            }else{
                this.value+=value;
                //this.focus();
            }
            return $(this);
        }
    }
    )
}
)(jQuery);


/*
*  By Samphay
*
* */
(function($){
    $.fn.touch = function(callback){
            var tt = null,
                t1 = 0,
                t2 = 0,
                t3 = 0,
                t4 = 0,
                obj = this.selector,
                This/*,
                ii = 0*/;
            $(document).on("touchstart",obj,function(e){
                /*e.stopPropagation();
                e.preventDefault();*/
                $(this).off("touchend");
                This = $(this);
                //console.log(window.event)
                t1 = window.event.touches[0].pageY;
                t3 = window.event.touches[0].pageX;

                if(tt){
                    return false;
                }

                $(this).addClass("_on_");
                $(this).on("touchmove",function(e){
                    /*e.stopPropagation();
                    e.preventDefault();*/
                    t2 = window.event.touches[0].pageY;
                    t4 = window.event.touches[0].pageX;

                });
                $(this).on("touchend",function(e){
                    /*e.stopPropagation();
                    e.preventDefault();*/
                    //alert(ii++);
                    $(this).removeClass("_on_");
                    if(t2==0 || t4==0){
                        t2 = t1 = t3 = t4 = 0;
                    }
                    if(Math.abs(t2-t1)>10 || Math.abs(t4-t3)>10){
                        t2 = t1 = t3 = t4 = 0;
                        return false;
                    }
                    if(tt){
                        return false;
                    }
                    tt = 1;
                    var touchEvent = e;
                    touchEvent.type = "touch";
                    typeof(callback)=== "function"?callback.call(this,touchEvent):function(){};
                    tt = null;
                })
            });
        };
})(jQuery);

/*
* "[a]".reMBrace //正则替换[a]里面的内容。
* "[[a]]".reDMBrace //正则替换[[a]]里面的内容。
* "{a}".reLBrace //正则替换{a}里面的内容。
* "{{a}}".reDLBrace //正则替换{{a}}里面的内容。
* */
String.prototype.reMBrace = function(data){
    var reg = new RegExp("\\[([^\\[\\]]*?)\\]", 'igm');
    return this.replace(reg,function(i,k){return data[k]})
};
String.prototype.reDMBrace = function(data){
    var reg = new RegExp("\\[{2}([^\\[\\]]*?)\\]{2}", 'igm');
    return this.replace(reg,function(i,k){return data[k]})
};
String.prototype.reDLBrace = function(data){
    var reg = new RegExp("\\{{2}([^\\[\\]]*?)\\}{2}", 'igm');
    return this.replace(reg,function(i,k){return data[k]})
};
String.prototype.reLBrace = function(data){
    var reg = new RegExp("\\{([^\\[\\]]*?)\\}", 'igm');
    return this.replace(reg,function(i,k){return data[k]})
};

/*
var _console = console;
console = {
    log:function(obj){
        var type = typeof(obj);
        switch(type){
            case "function":obj=String(obj);
            case "object" : obj = JSON.stringify(obj,null,4);
            default : obj = obj;
        }
        alert(type+":"+obj);
    }
};
*/



/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2015 Mika Tuupola Samphay
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.7.0-dev_samphay_Version
 *
 * Fix : fit other element ,not only IMG;(_Samphay)
 */
(function($) {
    $.fn.lazyload = function(options) {
        var settings = {
            threshold       : 0,
            failure_limit   : 0,
            event           : "scroll",
            effect          : "show",
            container       : window,
            skip_invisible  : true,
            effectspeed     : 0
        };

        if(options) {
            /* Maintain BC for a couple of version. */
            if (null !== options.failurelimit) {
                options.failure_limit = options.failurelimit;
                delete options.failurelimit;
            }

            $.extend(settings, options);
        }

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        var elements = this;
        if (0 == settings.event.indexOf("scroll")) {
            $(settings.container).bind(settings.event, function(event) {
                var counter = 0;
                elements.each(function() {
                    if (settings.skip_invisible && !$(this).is(":visible")) return;
                    if ($.abovethetop(this, settings) ||
                        $.leftofbegin(this, settings)) {
                        /* Nothing. */
                    } else if (!$.belowthefold(this, settings) &&
                        !$.rightoffold(this, settings)) {
                        $(this).trigger("appear");
                    } else {
                        if (++counter > settings.failure_limit) {
                            return false;
                        }
                    }
                });

                /* Remove image from array so it is not looped next time. */
                var temp = $.grep(elements, function(element) {
                    return !element.loaded;
                });
                elements = $(temp);

            });
        }

        this.each(function() {
            var self = this;
            self.loaded = false;
            /*if(self.nodeName == "IMG"){
                if(!$(self).hasClass("lazyImg")){
                    var img = $(self).attr("src");
                    if(typeof($(self).attr("original"))){
                        $(self).data("original",img);
                        $(self).attr("src","../../common/img/header.png");
                        $(self).addClass("lazyImg");
                    }
                }
            }*/
            /* When appear is triggered load original image. */
            $(self).one("appear", function() {
                if (!this.loaded) {
                    if(!$(self).data("original") == ""){
                        if(self.nodeName == "IMG"){
                            $(self)
                                .bind("load", function() {
                                    $(self)
                                        .hide()
                                        .attr("src", $(self).data("original"))
                                        [settings.effect](settings.effectspeed);
                                    self.loaded = true;
                                })
                                .attr("src", $(self).data("original"));
                        }else{
                            var img = $(self).data("original"),
                                ID = "__img__"+new Date().getTime();
                            $("<img/>").attr("id",ID).attr("src",img).hide().appendTo($(self));
                            $("#"+ID).bind("load",function(){
                                $(self)
                                    .hide()
                                    .css({
                                        "background-image": "url("+img+")",
                                        "background-size" : "100%"
                                    })
                                    [settings.effect](settings.effectspeed);
                                self.loaded = true;
                            });
                        }
                    }
                }
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if (0 != settings.event.indexOf("scroll")) {
                $(self).bind(settings.event, function(event) {
                    if (!self.loaded) {
                        $(self).trigger("appear");
                    }
                });
            }
        });

        /* Check if something appears when window is resized. */
        $(window).bind("resize", function(event) {
            $(settings.container).trigger(settings.event);
        });

        /* Force initial check if images should appear. */
        $(settings.container).trigger(settings.event);

        return this;

    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).height() + $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top + $(settings.container).height();
        }
        return fold <= $(element).offset().top - settings.threshold;
    };

    $.rightoffold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).width() + $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left + $(settings.container).width();
        }
        return fold <= $(element).offset().left - settings.threshold;
    };

    $.abovethetop = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top;
        }
        return fold >= $(element).offset().top + settings.threshold  + $(element).height();
    };

    $.leftofbegin = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left;
        }
        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };
    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() */

    $.extend($.expr[':'], {
        "below-the-fold" : function(a) { return $.belowthefold(a, {threshold : 0, container: window}) },
        "above-the-fold" : function(a) { return !$.belowthefold(a, {threshold : 0, container: window}) },
        "right-of-fold"  : function(a) { return $.rightoffold(a, {threshold : 0, container: window}) },
        "left-of-fold"   : function(a) { return !$.rightoffold(a, {threshold : 0, container: window}) }
    });

})(jQuery);

function doFunction(handle){
    typeof(handle) === "function" ?
    handle():
    null;
}

/*
 *   表单提交
 * */
(function($){
    $.fn.submitBtn = function(option,checkTypeExtends){
        var checkTypeExtend = {};
        if(checkTypeExtends){
            $.each(checkTypeExtends,function(i,o){
                checkTypeExtend[i] = function(val){
                    var reg = o.rule;
                    if(o.rule instanceof RegExp){
                        if(!reg.test(val)){
                            this.errorCount++;
                            return this.Error(o.errorTip)
                        }
                    }
                }
            });
        }
        var opt = {
            tipText : "正在提交···",
            testData : {
            },
            success : function(){},
            error : function(text){
                alert(text);
            }
        };
        function Error(text){
            doFunction(opt.error(text));
        }
        function Success(){
            doFunction(opt.success());
        }
        function checkData(data){
            var checkType = {
                errorCount : 0,
                Error : function(text){
                    doFunction(opt.error(text));
                },
                Success : function(){
                    doFunction(opt.success());
                },
                "phone" : function(val){
                    var reg = /^1[\d]{10}$/;
                    if(!reg.test(val)){
                        this.errorCount++;
                        return this.Error("手机号码格式不正确！");
                    }
                },
                "email" : function(val){
                    var reg = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;
                    if(!reg.test(val)){
                        this.errorCount++;
                        return this.Error("邮箱格式不正确！");
                    }
                }
            };
            $.extend(checkType,checkTypeExtend);
            $.each(data,function(i,o){
                var $obj = $(i),
                    val = $obj[0].nodeName=="INPUT"?
                        val = $.trim($obj.val()):
                        $obj[0].nodeName=="SELECT"?
                            val = $.trim($obj.find("option:selected").val()):
                            $.trim($obj.text());
                //console.log(o.checkBoxFn())
                if(o.extend){
                    o.extend.call(checkType);
                }
                //console.log(val)
                if(o.require){
                    if(val == ""){
                        checkType.errorCount++;
                        return Error(o.require);
                    }else{
                        if(o.compare){
                            //console.log(o.compare)
                            if(o.compare == val){
                                checkType.errorCount++;
                                return Error(o.require);
                            }
                        }
                    }
                }
                if(o.type){
                    typeof(checkType[o.type]) === "function"?
                        checkType[o.type](val):
                        null;
                }
                if(o.length){
                    if(o.length.min ){
                        if(val.length < o.length.min){
                            checkType.errorCount++;
                            return Error("最少填写"+ o.length.min +"个字符");
                        }
                    }
                    if(o.length.max){
                        if(val.length > o.length.max){
                            checkType.errorCount++;
                            return Error("最多只能填写"+ o.length.max +"个字符");
                        }
                    }
                }

            });
            if(checkType.errorCount>0){
                return false;
            }else{
                return true;
            }
        }
        $.extend(opt,option,true);
        $(this).touch(function(){
            if($(this).hasClass("_submitting_")){
                Error(opt.tipText);
                return false;
            }
            if(checkData(opt.testData)){
                $(this).addClass("_submitting_").css({
                    opacity : ".5"
                }).text(opt.tipText);
                Success();
            }
        })
    }
})(jQuery);


//评论
function reply(opt){
    var $div = $(document.createElement("DIV")),
        $blur = $(document.createElement("DIV")),
        $textArea = $(document.createElement("TEXTAREA")),
        $foot = $(document.createElement("DIV")),
        $ok = $(document.createElement("SPAN")),
        $cancel = $(document.createElement("SPAN")),
        btnCss = {
            display:"inline-block",
            border : "1px solid #c5c5c5",
            borderRadius : "6px",
            WebkitBorderRadius : "6px",
            padding : "8px 12px",
            color : "5b5b5b",
            margin : "0 6px"
        };
    var fn = {
        close : function(){
            $div.animate({
                "left":0,
                "bottom":-$div.height()
            },300,"easeInBack",function(){
                $blur.fadeOut(0,function(){
                    $blur.remove();
                })
            });
        }
    };
    $cancel.html("取消")
        .on("click",function(e){
            fn.close();
        })
        .css(btnCss)
        .appendTo($foot);
    $ok.html("提交")
        .on("click",function(e){
            var text = $textArea.val();
            this.close = fn.close;
            this.obj = opt.This;
            typeof(opt.ok)=== "function" ?
                opt["ok"].call(this,text) :
                null;
        })
        .css(btnCss)
        .appendTo($foot);
    $textArea.on("click",function(e){
        e.preventDefault();
        e.stopPropagation();
    }).attr("placeholder","说点什么吧").css({
        width : "88%",
        height : "160px",
        margin : "0 auto 16px",
        display : "block",
        padding : "8px",
        border : "1px solid #c5c5c5"
    }).appendTo($div);
    $div.on("click",function(e){
        e.preventDefault();
        e.stopPropagation();
    })
        .hide().addClass("replyWrap").css({
            padding : "18px 0",
            backgroundColor : "#fff",
            position : "absolute",
            bottom : "0",
            width : "100%"
        });
    $foot.css({
        textAlign : "center"
    }).appendTo($div);
    $blur.on("click",function(e){
        fn.close();
    })
        .css({
            position:"fixed",
            height : "100%",
            width : "100%",
            top : "0",
            left : "0",
            zIndex : 11,
            backgroundColor : "rgba(0,0,0,0)",
            transition:"all 0.2s",
            transitionTimingFunction:"ease-out",
            WebkitTransition:"all 0.2s",
            WebkitTransitionTimingFunction:"ease-out"
        }).append($div);
    $div.css({
        "bottom":"-300px",
        display : "block"
    });
    $("body").append($blur);
    $div.animate({
        "left":0,
        "bottom":0
    },400,"easeOutBack",function(){
        $blur.css({
            backgroundColor : "rgba(0,0,0,.6)"
        })
    });
    //return $blur;
}
(function($){
    $.fn.reply = function(callback){
        $(document).on('click',this.selector,function(e){
            e.preventDefault();
            e.stopPropagation();
            var This = this;
            reply({
                ok : callback,
                This : This
            })
        })
    }
})(jQuery);