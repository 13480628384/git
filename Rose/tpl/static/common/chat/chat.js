/**
 * Created by Samphay on 2015/6/18.
 */
define(function(require, exports, module){
    require("iscrollProbe");
    require("islider");
    var h        = require('helper'),  
        u        = require('url');
    var chatScroll,
        countMsg = 0,
        scrollEndHandle = null;
    function scroll_(callBack){
        chatScroll.on("scrollEnd",function(){
            typeof(callBack)==="function"?callBack():null;
        })
    }
    /*初始化页面*/
    function pageCss(obj){
        $(obj).css({
            "left":"0",
            "top" :"100%"
        })
    }
    function pageIn(obj,callback){
        $(obj).addClass("on");
        $(obj).animate({
            "left":0,
            "top":0
        },600,"easeOutQuart",function(){
            if(typeof callback === "function"){
                callback();
            }
        });
    }
    function pageOut(obj){
        $(obj).animate({
            "left":0,
            "top":"100%"
        },360,"easeInQuart",function(){
//        scroll(".box");
        });
        $(obj).removeClass("on");
    }
    function pageInit(){
        if($("#chatPage").hasClass("on")){
            pageOut("#chatPage");
        }else{
            pageIn("#chatPage");
        }
    }
    function msgTip(num){
        if(!num) num = "";
        return "<div id='_msgTips'>"+num+"</div>"
    }
    function showMsgTips(num){
        //num=num>99?99:num;

        if($("#_msgTips").length<1){
            $("#chatPage").append(msgTip(num));
        }else{
            $("#_msgTips").html(num).fadeIn();
        }
        $("#_msgTips").touch(function(){
            $("#_msgTips").fadeOut(function(){
                $("#_msgTips").html(0);
            });
            chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),500,null,true);
        })
    }
    //exports.backPage = pageInit;
    exports.inPage = function(callback){
        pageIn("#chatPage");
        $(".pageBack").hide();
    };
    exports.outPage = function(){
        pageOut("#chatPage");
        $("#chatScroll .scrollContent").empty();
        $(".pageBack").show();
    };
    exports.chat = function(to_wx_userid,from_wx_userid,callBack){
        if($(".chatPage").length>0){
            __getHistoryMsg(to_wx_userid,from_wx_userid);
            typeof callBack == "function" ? callBack(chatScroll) : function(){};
        }else{
            $.get("../../common/view/chatPage.html",{},function(data){
                $("body").append(data);
                pageCss("#chatPage");
                pageInit();
                chatScroll = new IScroll("#chatScroll",{
                    scrollbars:true,
                    probeType: 1,
                    interactiveScrollbars: true,
                    shrinkScrollbars: 'scale',
                    fadeScrollbars: true,
                    bounceTime : 800
                    //momentum : true,
                    /*MSPointerMove: function(){
                    }*/
                });
                __getHistoryMsg(to_wx_userid,from_wx_userid);
                $(".iScrollLoneScrollbar").width(3);
                $(".iScrollIndicator").css({
                    "border":"none"
                });
                if($('.cItem').length>0){
                    chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),0,null,true);
                }
                //console.log(chatScroll)
                typeof callBack == "function" ? callBack(chatScroll) : function(){};
                /*chatScroll.on("scroll",function(){
                    if(this.y>100){
                        console.log(1)
                        chatScroll.on("scrollEnd",function(){
                            alert(1)
                        });
                        this.off("scroll");
                    }
                })*/
            });
        }
    };

    /*
     初始化获取历史消息
    */
    function __getHistoryMsg(from_wx_userid,to_wx_userid) {
        h.ajax(u.get('getchatlist'),{from_wx_userid:from_wx_userid,to_wx_userid:to_wx_userid},function(data){
            if(data.status == 0){
                var html = '';
                $.each(data.aData,function(i,o){
                    if(data.aData[i].from_wx_userid == from_wx_userid){
                        html +=fromMessage_(data.aData[i].content,data.aData[i].from_wx_headimgurl);
                    }else if(data.aData[i].to_wx_userid == to_wx_userid){
                        html +=toMessage_(data.aData[i].content,data.aData[i].to_wx_headimgurl);
                    }else if(data.aData[i].from_wx_userid == to_wx_userid){
                        html +=toMessage_(data.aData[i].content,data.aData[i].from_wx_headimgurl);
                    }else if(data.aData[i].to_wx_userid == from_wx_userid){
                        html +=toMessage_(data.aData[i].content,data.aData[i].to_wx_headimgurl);
                    }
                });
                console.log();

                $("#chatScroll").find(".scrollContent").append(html);
                chatScroll.refresh();
                if($(html).hasClass("from")){
                    if(chatScroll.y - chatScroll.maxScrollY <250){
                        countMsg = 0;
                        $("#_msgTips").fadeOut(function(){
                            $("#_msgTips").html(countMsg);
                        });
                        chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),100,null,true);
                    }else{
                        countMsg++;
                        //console.log(countMsg);
                        showMsgTips(countMsg);
                    }
                }else{
                    chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),100,null,true);
                }
                

            }else{
                return msg.alert('呜~获取历史信息失败啦');
            }
        });

        
        
    }

    exports.myChatScroll = function(callback){
        typeof callback === "function" ? callback(chatScroll) :null;
    };
    /*
    * 系统信息提示
    * 默认显示时间
    * */
    exports.beforeMessage = function(msg){
        if(!msg) msg = new Date().Format("yyyy-MM-dd hh:mm:ss")
        return ' <div class="beforeItem">' +
            msg +
            '</div>'
    };

    /*
     * 发送的信息模板
     * msg:信息内容
     * img:头像地址
    * */
    var toMessage_ = function(msg,img){
        if(!img)img = "";
        if(!msg) return;
        return '<div class="cItem to">' +
            '<div class="cLogo">' +
            '<img src="'+img+'">' +
            '</div>' +
            '<div class="myWord">'+msg+'</div>' +
            '</div>';
    };
    exports.toMessage = toMessage_;
    /*
     * 收到的信息模板
     * msg:信息内容
     * img:头像地址
     * */
    var fromMessage_ = function(msg,img){
        if(!img)img = "";
        if(!msg) return;
        return '<div class="cItem from">' +
            '<div class="cLogo">' +
            '<img src="'+img+'">' +
            '</div>' +
            '<div class="myWord">'+msg+'</div>' +
            '</div>';
    };
    exports.fromMessage = fromMessage_;

    /*
    * 信息模板输出
    * */
    exports.showMessage = function(msg){
        /*var printf = function(msg){
                var img = $(msg).find("img").attr("src"),
                    word = $(msg).text();
                if($(msg).hasClass("to")){
                    return toMessage_(word,img);
                }else if($(msg).hasClass("from")){
                    return fromMessage_(word,img);
                }
            };*/
        function faceData(face){
            var data = {};
            $.each(face,function(i,o){
                data[o.name] = "<img width='48px' src='"+ o.img + "' >";
            })
            return data
        }
        var printf = function(msg){
            var img = $(msg).find("img").attr("src"),
                word = $(msg).text().reMBrace(faceData(test));
            if($(msg).hasClass("to")){
                return toMessage_(word,img);
            }else if($(msg).hasClass("from")){
                return fromMessage_(word,img);
            }
        };
        $("#chatScroll").find(".scrollContent").append(printf(msg));
        chatScroll.refresh();
        if($(msg).hasClass("from")){
            if(chatScroll.y - chatScroll.maxScrollY <250){
                countMsg = 0;
                $("#_msgTips").fadeOut(function(){
                    $("#_msgTips").html(countMsg);
                });
                chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),100,null,true);
            }else{
                countMsg++;
                //console.log(countMsg);
                showMsgTips(countMsg);
            }
        }else{
            chatScroll.scrollToElement(document.querySelector('.cItem:last-child'),100,null,true);
        }
    };



    /*
    * 发送时的加载条
    * */
    exports.sendLoading = function(){
        $(".cItem.to").eq(-1).find(".myWord").before().waiting(function(this_){
            this_.css({
                "position" : "absolute",
                "top": "10px",
                "left" : "-70%"
            });
            /*
            typeof callBack == "function" ? callBack(this_) : setTimeout(function(){
                //this_.remove();
            },500);
            */
            return this_;

        },18);
    };

    /*exports.scrollAjax = function(){
        chatScroll.on("scroll",function(){
           if(this.y>100){
               console.log(1)
           }
        })
    };*/

    /*
    *
    * */
    var faceHtml = function(img,name){
        return '<a faceName="[' +
            name +
            ']">' +
            '<div class="faceItem" faceName = "[' +
            name +
            ']">' +
            '<div class="face">' +
            '<img src="' +
                img +
            '" alt="' +
            name +
            '"/>' +
            '</div><div class="faceName">' +
            name +
            '</div>' +
            '</div></a>'
    };

    /*
    * 表情组合，输出json
    * */
    exports.face = function(data,num){
        if(!num)num=8;
        var face_ = [],
            faceWrap = [],
            faceData = "";
        $.each(data,function(i,o){
            face_.push(faceHtml(o.img, o.name));
        });
        for(var a = 0 ; a<=Math.round(face_.length/num);a++){
            for(var b = 0; b<num; b++){
                if(typeof face_[num*a+b] ==="string"){
                    faceData += face_[num*a+b];
                }
            }
            if(!faceData == ""){
                faceWrap.push({"content":faceData});
            }
            faceData = ""
        }
        return faceWrap;
    };


//face(test);



});