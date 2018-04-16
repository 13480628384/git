/**
 * Created by Samphay on 2015/5/25.
 */
/*阻止iphone 默认滑动*/
// document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
//document.addEventListener('touchmove', function (e) {    e.preventDefault();}, false)
//document.addEventListener('touchmove', function (e) {    e.preventDefault();}, false)

;(function($){
    $.fn.waiting = function(callback,size,position, extend){
        if(!size){
            size = 12
        }
        var loading = "data:image/gif;base64,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";
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
}(jQuery))
;(function($){
    $.fn.tapColor=function(){
        return $(this).each(function(){
            $(this).on("touchstart",function(e){
                var t = window.event.touches[0].pageY,
                    l = window.event.touches[0].pageX;

                $(this).css("opacity","1").animate({
                    "opacity":".4"
                },0);
                $(this).on("touchend",function(){
                    var THIS = $(this);
                    THIS.animate({
                        "opacity":1
                    },100);
                })
            })
        })
    }
}(jQuery));

/*(function($){
    $.fn.copy = function(opt){
        var cop = {};
        $(this).each(function(i,o){
            cop[i] = $(this).html();
        });
        return cop
    }
}(jQuery));*/


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
    $.fn.swapp = function(opt){
        var option = {
            start :function(){},
            move : function(){},
            end : function(){}
        };
        $.extend(option,opt);
        return $(this).each(function(i,o){
            //console.log(arguments);
            var tsx = 0,tsy = 0,tmx = 0,tmy = 0, tex = 0,tey = 0,st= 0,et=0;
            $(this).on("touchstart",function(e){
                st = e.timeStamp;
                tsx = window.event.touches[0].pageX;
                tsy = window.event.touches[0].pageY;
                option.start($(this),tsx,tsy);
            });
            $(this).on("touchmove",function(e){
                tmx = window.event.touches[0].pageX;
                tmy = window.event.touches[0].pageY;
                tex = tmx-tsx ;
                tey = tmy-tsy;
                option.move($(this),tex,tey);
            });
            $(this).on("touchend",function(e){
                et = (e.timeStamp - st);
                if(et<1200){
                    option.end($(this),tex,tey);
                }
                tsx = 0; tsy = 0; tmx = 0; tmy = 0; tex = 0; tey = 0; st= 0; et=0;
            });
        })
    }
}(jQuery));

/*
* 仿click事件，无法触发未来事件，依赖上面的swapp
* */
(function($){
    $.fn.tap = function(callback){
        if(!callback || typeof callback != "function"){
            callback = function(){};
        }
        var te = true;
        return $(this).each(function(i,o){
            $(this).swapp({
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
            });
        }
        var stateObject = {};
        var title = "Wow Title";
        var newUrl = window.location.href+"";
        history.pushState(stateObject,title,newUrl);
    });
})();

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
/*
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
        return $(this).each(function(i,o){
            $(this).toggleClass(opt.class);
            $(this).animate(opt.animate,opt.duration,opt.ease,typeof callback =="function"?callback:null);
        })
    };
}(jQuery));*/

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
debugWX();

function deviceType(){
    var u = navigator.userAgent;
    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
        return "Android"
    } else if (u.indexOf('iPhone') > -1) {
        return "iPhone"
    }else if (u.indexOf('iPad') > -1) {
        return "iPad"
    } else if (u.indexOf('Windows Phone') > -1) {
        return "Windows Phone"
    }else{
        return "WEB"
    }
}

function netType(){
    var nu = navigator.userAgent.split(" "),
        nus = {},
        nub = {};
    for(var i in nu){
        nus[i] = nu[i].split("/")
    }
    for(var ii in nus){
        nub[nus[ii][0]] = nus[ii][1]
    }
    //var type = typeof nub.NetType =="undefined"?"在线":nub.NetType+"在线";
    return typeof nub.NetType =="undefined"?"在线":nub.NetType+"在线";
}

/*
* $().chat();
* 聊天插件，此方法可以在任意位置调用聊天界面出来。
* 此方法依赖 common/js/lib/chat.js
* */
var isrongyunlogin = null;

(function($){
    $.fn.chat = function(option){
        var isrongyunLogin = null;
        function chatting(to_wx_userid,to_wx_username,to_wx_avatar,network,device_type,login_time,from_wx_userid,from_wx_avatar){
            seajs.use(["chat","rongyun"],function(c,rongyun){
                $("body").data('to_wx_userid',to_wx_userid);
                c.inPage();
                c.chat(to_wx_userid,from_wx_userid,function(a){
                    $(".deviceType").html(device_type);        //显示自己手机类型
                    /*if((parseInt(login_time)+10*60) < Date.parse(new Date())){
                        $(".netType").html(network);              //显示自己的网络类型（ps:此处应该用微信jsdk那个可能会更准确）
                    }else{
                        $(".netType").html('');
                    }*/
                    $(".netType").html('正在连线..');
                    $("#to_wx_username").text(to_wx_username);
                    $("#chatPage").find(".goBackWrap").tap(function(){
                        c.outPage();
                    });

                    /*
                      融云消息
                    */
                    if(isrongyunlogin == null){
                        console.log('初始化融云对象');
                        rongyun.init(reciveCallback,connectCallback);
                    }else{
                        console.log('已初始化过融云对象');
                        $(".netType").html('连接成功');
                    }

                    function reciveCallback (sendderUserid,msg,avatar) {
                        console.log(sendderUserid+'=='+msg+':'+avatar);
                        if(to_wx_userid == sendderUserid){
                            c.showMessage(c.fromMessage(msg,avatar));
                        }
                    }


                    function sendMessageCallback(ret){
                        if(ret == 'success'){
                            $(".cItem.to").eq(-1).find(".myWord").before().find(".__waiting__").remove();
                        }
                        $("#chatMsg").blur().val("");
                    }
                    function connectCallback(data,RongIMClient){
                        if(RongIMClient){
                            $(".netType").html('连接成功');
                        }
                        isrongyunlogin = RongIMClient;
                        $(function(){
                            $(".btnReply").tap(function(){
                                if($(".netType").html() == '正在连线..'){
                                    msg.alert('正在连接系统,请稍后..');
                                    return false;
                                }
                                var msgstr = $.trim($("#chatMsg").val());
                                if(!msgstr==""){
                                    var html = c.toMessage(msgstr,from_wx_avatar) ;
                                    c.showMessage(html);
                                    c.sendLoading();
                                    rongyun.sendMessage(RongIMClient,from_wx_userid,$("body").data('to_wx_userid'),msgstr,sendMessageCallback);
                                }else{
                                    msg.alert('请输入聊天内容');
                                    return false;
                                }

                            });
                            
                        });
                    }





                })
            });
        }
        var opt = {
            debug : false
        };
        $.extend(true,opt,option);

        $(this).each(function(i,o){
            //$(this).tap(function(){
            //    console.log(option);
                if(!$(".chatPage").length>0){
                    $("body").waiting(function(this_){
                        chatting(option.to_wx_userid,
                            option.to_wx_username,
                            option.to_wx_avatar,
                            option.network,
                            option.device_type,
                            option.login_time,
                            option.from_wx_userid,
                            option.from_wx_avatar
                            );
                        var at = setInterval(function(){
                            if($(".chatPage").length>0){
                                $("body").waitingDone();
                                clearInterval(at)
                            }
                        },100)
                    },24,{
                        "top" : "45%"
                    })
                }else{
                    chatting(option.to_wx_userid,
                            option.to_wx_username,
                            option.to_wx_avatar,
                            option.network,
                            option.device_type,
                            option.login_time,
                            option.from_wx_userid,
                            option.from_wx_avatar);
                }
            //});
        })
    }
})(jQuery);
/*

/*分页模块*/
function pageCss(obj){
    $(obj).css({
        "left":"0",
        "top" :"100%"
    })
}

function pageIn(obj,callback,b){
    $(".pageBack").hide(0);
    pageCss(obj);
   /* $(obj).on("touchmove",function(e){
        window.event.preventDefault();
        window.event.stopPropagation();
    });*/
    $(obj).addClass("on");
    $(obj).on("touchmove",function(e){
        e.preventDefault();
    });
    $(obj).show(0).animate({
        "left":0,
        "top":0,
        "z-index" : "2"
    },600,"easeOutQuart",function(){
        back(obj,{"id" : id});
        if(!b){
            if(typeof callback === "function"){
                callback();
            }
        }
    });
    if(b){
        if(typeof callback === "function"){
            callback();
        }
    }
    var id = "34team_"+obj.substr(1,obj.length);

}
function pageOut(obj){
    if($(obj).length<1) return;
    $(obj).animate({
        "left":0,
        "top":"100%"
    },360,"easeInQuart",function(){
    }).hide(0);
    $(obj).removeClass("on");
    var id = obj.substr(1,obj.length);
    $("#34team_"+id).remove();
}


function boxCss(obj){
    $(obj).css({
        "bottom":0-$(obj).height(),
        "display":"none",
        "z-index":"9"
    })
}

function boxIn(obj,callback){
    boxCss(obj);
    blur();
    $(obj).addClass("on").fadeIn(0);
    $(obj).addClass("bluring");
    $(obj).animate({
        "left":0,
        "bottom":0
    },600,"easeOutBack",function(){
        if(typeof callback === "function"){
            callback($(obj));
        }
    });

}

function boxOut(obj,callback){
    //console.log(obj);
    $(obj).removeClass("bluring");
    $(obj).animate({
        "left":0,
        "bottom":-$(obj).height()
    },400,"easeInBack",function(){
        //console.log($(obj).attr("style"))
        $(obj).removeClass("on").fadeOut(0);
        noBlur();
        if(typeof callback === "function"){
            callback();
        }
        //$(obj).removeClass("on");
    });
}


function blur(callback){
    $(function(){

        var blur = '<div id="__blur__"></div>';
        $("body").append(blur);

        $("#__blur__").css({
            "position":"fixed",
            "top":"0",
            "left":"0",
            "width":"100%",
            "height":"100%",
            "z-index":"8"
        }).on("touchmove",function(e){
            e.preventDefault();
            });
        typeof callback === "function"?callback():function(){};

    })
}

function noBlur(){
    $(function(){
        $("#__blur__").remove();
    })
}

function blurAct(callback){
    //var i = 0;
    $(document).on("touchend","#__blur__",function(e){
        //i++;
        //alert(123);
        e.stopPropagation();
        e.preventDefault();
        if(typeof callback === "function"){
            callback();
            //noBlur();
            //$(this).off("click");
        }
    })
}

/*
* back 返回按钮插件
* usage： back("myFunction()") || back(function(){myFunction()}) 都可以执行myFunction;
*        如果back();就执行返回上一页历史记录
* */
var back = function(action,option){
    $(function(){
        var opt = {
            class : "pageBack"
        };
        $.extend(true,opt,option);
        /* if(opt.remove){
            $("."+opt.class).remove();
        }*/
        $("body").append("<div class='"+opt.class+"' id= '"+opt.id+"'></div>");
        $(document).on("touchend","."+opt.class,function(event){
            event.preventDefault();
            event.stopPropagation();
            window.event.stopPropagation();
            window.event.preventDefault();
            $(".pageBack").show(0);
            typeof(action) === "function"
                ? action(event) :
                typeof(action) === "string"
                ? pageOut(action) : history.back();
        })
    });
};

/*
* 悬浮按钮
*
* */

var logoMenu = function(opt){
    if($(".fMenu").length>0)return;
    function fBlur(){
        $(function(){
            var blur = '<div id="__menublur__"></div>';
            $("body").append(blur);
            $("#__menublur__").css({
                "position":"fixed",
                "top":"0",
                "left":"0",
                "width":"100%",
                "height":"100%",
                "z-index":"8"
            })
        })
    }
    function nofBlur(){
        $(function(){
            $("#__menublur__").remove();
        })
    }
    function fBlurAct(){
        $(document).on("touchend","#__menublur__",function(e){
            e.stopPropagation();
            e.preventDefault();
            $(".fMenu").trigger("touchend");
        })
    }
    function fMenu(img){
        if(img){
            img = "style='background-image:url("+img+")'";
        }else{
            img = "";
        }
        return  '<div class="fMenu">' +
                    '<div class="header circle m2 __tapColor__" >' +
                         '<i class="logoBg" ' +
                            img
                            +
                         '></i>' +
                    '</div>' +
                    '<div class="content fMenuContent">' +
                    ' </div>' +
                '</div>'
    }
    function fMenuContent(aClass,iClass){
        return  '<div class="item '+aClass+' circle __tapColor__">' +
                    '<span class="'+iClass+' f18"></span>' +
                '</div>'
    }
    function showfMenu(opt){
        $(function(){
            $("body").append(fMenu());
            var html = "";
            $.each(opt,function(i,o){
                html += fMenuContent(o.aClass, o.iClass);
                $(document).on("touchend","."+ o.aClass,function(){
                    if($(o.action).length<1){return}
                    pageIn(o.action,function(){
                        typeof(o.actionCallBack) === "function" ? o.actionCallBack():function(){};
                    });
                });
            });
            $(".fMenuContent").html(html);
            fBlurAct();
        })
    }
    showfMenu(opt);
    /*浮动按钮*/
    $(function(){
        var tt = null;
        $(".fMenu").on("touchend",function(){
            if(tt){
                return;
            }
            tt = 1;
            if(!$(this).hasClass("on")){
                $(this).addClass("on");
                fBlur();
                $(this).css({
                    "z-index":"9"
                });
                $(".fMenu .item").each(function(i,o){
                    var bottom = $(this).height();
                    $(this).fadeIn(0).animate({
                        "bottom":(parseInt(bottom)+14)*(i+1)
                    },200*i+200>1000?1000:(200*i+200),"easeOutBack");
                });
                setTimeout(function(){
                    tt = null;
                },400)
            }else{
                $(this).removeClass("on");
                nofBlur();
                setTimeout(function(){
                    tt = null;
                },400);
                $(this).css({
                    "z-index":"0"
                });
                $(".fMenu .item").each(function(i,o){
                    $(this).animate({
                        "bottom":0
                    },100*i+100>1000?1000:(100*i+100),"easeInBack").fadeOut(0);
                })
            }
        })
    });

};

//logoMenu(menu);
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

(function($){
    $.fn.pageIn = function(obj,callBack){
        return $(this).each(function(i,o){
            var This = $(this);
            This.tap(function(THIS){
                if(typeof obj === "boolean" || !obj){
                    if(!obj){
                        seajs.use("msg",function(){
                            callBack = typeof(callBack) === "string" ? callBack : "此功能暂未开放,敬请期待！";
                            msg.tips(callBack);
                        });
                    }
                    return false;
                }
                pageIn(obj,function(){
                    typeof callBack === "function" ? callBack(This):function(){};
                })
            })
        })
    };
    $.fn.boxIn = function(obj,callBack){
        var i = 0;
        return $(this).each(function(i,o){
            var This = $(this);
            This.click(function(){

                if(This.hasClass("on")){
                    This.removeClass("on");
                    boxOut(obj);

                }else{
                    This.addClass("on");

                    //typeof(callBack)==="function"?callBack($(obj)):function(){};
                    boxIn(obj,function(){
                        typeof(callBack)==="function"?callBack(This):function(){};
                    });
                    i++;
                    if(i>1){
                        return;
                    }
                    blurAct(function(){

                        This.removeClass("on");

                        boxOut(obj);
                    })
                }
            });
        })
    }
})(jQuery);
