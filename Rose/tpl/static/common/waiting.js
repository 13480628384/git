/**
 * Created by 星辉 on 2015/5/25.
 */
(function($){
    $.fn.beforeLoading = function(){

    }
}(jQuery))
;(function($){
    $.fn.waiting = function(callback,size,position){
        if(!size){
            size = 12
        }
        var loading = "data:image/gif;base64,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";
        var img = '<div class="__waiting__" ' +
            'style ="position:absolute;z-index:2; width:100%;height:14px;text-align:center;">' +
            '<img  src='+loading+' alt="" width="'+size+'" ' +
            '/> '+
            '</div>';
        if(position) $(".__waiting__").css(position);
        $(this).each(function(){
            $(this).prepend(img);
            $(this).find('.__waiting__').find('img').css('width', size + 'px');
            if(typeof callback === "function"){
                callback($(this));
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
}(jQuery))
;(function($){
    $.fn.tapColor=function(){
        return $(this).each(function(){
            $(this).on("touchstart",function(e){
//               e = e || window.event;
                var t = window.event.touches[0].pageY,
                    l = window.event.touches[0].pageX;
                /* if($("#__touching__").length<1){
                 $("body").append("<div id='__touching__' class='circle'></div>");
                 }
                 $("#__touching__").css({
                 "height":"1px",
                 "width" : "1px",
                 "position": "fixed",
                 "top" : t,
                 "left" :l,
                 "z-index":"99",
                 "opacity":"1",
                 "box-shadow" :"0 0 2px rgba(0,0,0,.4)"
                 //                    "background-color":"rgba(0,0,0,.4)"
                 });*/
//                console.log(window.event.touches[0]);
                $(this).css("opacity","1").animate({
                    "opacity":".4"
                },0);
                $(this).on("touchend",function(){
                    var THIS = $(this);
                    THIS.animate({
                        "opacity":1
                    },100);
                    /* $("#__touching__").animate({
                     "height":"40px",
                     "width" : "40px",
                     "top": (t-20),
                     "left":(l - 20),
                     "opacity":"0"
                     },500,function(){
                     $("#__touching__").remove();
                     });*/
                })
            })
        })
    }
}(jQuery));

(function($){
    $.fn.copy = function(opt){
        var cop = {};
        $(this).each(function(i,o){
            cop[i] = $(this).html();
        });
        return cop
    }
}(jQuery));

/*
* swap 滑动事件 ，
* This为触发对象本身
* {
*    start :function(This,x,y){},   //按下去触发的方法  x,y 为按下去的坐标点
*    move : function(This,x,y){},   //移动时触发的方法  x,y 为移动后相对按下去的坐标点
*    end : function(This,x,y){}     //离开时触发的方法  x,y 同move
* }
* */
(function($){
    $.fn.swap = function(opt){
        var option = {
            start :function(){},
            move : function(){},
            end : function(){}
        };
        $.extend(option,opt);
        return $(this).each(function(i,o){
            var tsx = 0,tsy = 0,tmx = 0,tmy = 0, tex = 0,tey = 0,st= 0,et=0;
            $(this).on("touchstart",function(e){
                st = e.timeStamp;
                tsx = window.event.touches[0].pageX;
                tsy = window.event.touches[0].pageY;
                option.start($(this),tsx,tsy);
            });
            $(this).on("touchmove",function(e){
                //console.log(e.timeStamp);
                tmx = window.event.touches[0].pageX;
                tmy = window.event.touches[0].pageY;
                tex = tmx-tsx ;
                tey = tmy-tsy;
                option.move($(this),tex,tey);
            });
            $(this).on("touchend",function(e){
                et = (e.timeStamp - st);
                //console.log(et)
                if(et<1200){
                    option.end($(this),tex,tey);
                }
                tsx = 0; tsy = 0; tmx = 0; tmy = 0; tex = 0; tey = 0; st= 0; et=0;
            });

        })
    }
}(jQuery));

/*
* 仿click事件，无法触发未来事件，依赖上面的swap
* */
(function($){
    $.fn.tap = function(callback){
        if(!callback || typeof callback != "function"){
            callback = function(){};
        }
        var te = true;
        return $(this).each(function(i,o){
            $(this).swap({
                "move" : function(This,x,y){
                    if(Math.abs(y)>20){
                        te=false
                    }
                },
                "end" : function(This,x,y){
                    if(te){
                        callback(This);
                    }
                    te = true;
                }
            })
        })
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
        /*if(!localStorage["__samphay__"+name]){
            this.save.createTime = date.Format("yyyy-MM-dd hh:mm:ss");
            this.save.createTimeStamp = date.getTime();
        }else{
            this.save= localStorage["__samphay__"+name];
        }*/
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

/*(function($){

    $.fn.localData = function(name,obj){
        if(obj){
            var save = {},date = new Date();
            save.type = typeof obj;
            save.saveTime = date.Format("yyyy-MM-dd hh:mm:ss");
            save.timeStamp = date.getTime();
            save.data = obj;
            localStorage.setItem(name,JSON.stringify(save));
        }else{
            return JSON.parse(localStorage.getItem(name));
        }
    };
}(jQuery));*/

/*阻止返回键起作用，原理：循环添加历史记录*/
(function(){
    var stateObject = {};
    var title = "Wow Title";
    var newUrl = window.location.href+"";
    var c = 0;
    history.pushState(stateObject,title,newUrl);
    window.addEventListener('popstate', function(event) {
        c++;
        if(c>1){
            seajs.use("msg",function(){
                msg.confirm("亲，想走了?",function(){
                    WeixinJSBridge.call('closeWindow');
                });
                /*if(c<12){
                    if(c>8){
                        msg.tips("亲，不用点了");
                    }else{
                        msg.tips("嗯，手机没有坏，返回键是没效果的，想推出，就按叉叉吧！");
                    }
                }*/
            });
        }
        //console.log(history);
        var stateObject = {};
        var title = "Wow Title";
        var newUrl = window.location.href+"";
        history.pushState(stateObject,title,newUrl);
        //return false;
    });
})();

/*
 * 去px,提取数字
 */
/*
function numstr(str){
    if(typeof(str)==="string"){
        var l = str.length,
            num = str.substr(0,l-2);
        return Number(num)
    }
}*/
String.prototype.nopx= function(){
    return Number(this.substr(0,this.length-2))
};

Number.prototype.fixNum = function(n){
    if(!n)n=2;
    return Number(this.toFixed(n))
};

Number.prototype.AA = function(){
    return this>9?this:"0"+this;
};

Date.prototype.Format = function (fmt) { //author: meizz
    if(!fmt) fmt = 'yyyy-MM-dd';
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
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
    $.fn.getPage = function(opt){
        if(!opt) opt = "html";
        function getHtml(html,callback){
            var url = "view/"+html+".html";
            $.get(url,function(data){
                if(typeof callback ==="function"){
                    callback(data);
                }
            })
        }
        return $(this).each(function(ii,oo){
            var This = $(this);
           var html = $(this).attr(opt).split(",");
            $.each(html,function(i,o){
               if(!This.attr(o)){
                   getHtml(o,function(data){
                       This.attr(o,data);
                   });
               }
            });
        })
    };
    $.fn.pageGo = function(option,callback){
        var opt = {
            "class" : "on",
            "animate" : {"z-index":2},
            "duration" : 800,
            "ease"  : "easeOutQuart",
            "delay" : null
        };
        $.extend(true,opt,option);
        //console.log(opt);
        return $(this).each(function(i,o){
            $(this).toggleClass(opt.class);
            $(this).animate(opt.animate,opt.duration,opt.ease,typeof callback =="function"?callback:null);
        })
    };
}(jQuery));

(function($){
    $.fn.documentReady = function(callback){
        var ct = setInterval(function(){
            if(document.readyState == "complete"){
                clearInterval(ct);
                typeof callback == "function" ?callback():function(){};
            }
        },1)
    }
}(jQuery));



var debugWX = function(){
    var html = '<div id="__reflash__" style="color:#fff;box-shadow:0 0 4px rgba(0,0,0,.6);z-index:999999;position: fixed;top: 38px;left: 38px;text-align:center;line-height: 38px;width: 38px;height: 38px;border-radius:50%;-webkit-border-radius:50%;background-color:rgba(243,86,86,.6)">刷新</div>';
    $(function(){
        $("body").append(html);
        $("#__reflash__").click(function(){
            window.location.reload();
        })
    })
};

debugWX();