/**
 * Created by Samphay on 2015/6/18.
 */
define(function(require, exports, module){

    var h        = require('helper'),
        u        = require('url'); 
    require('msg'); 

    /*
     获取用户信息
    */
    exports.getuser = function (userid,handle){
        __init(userid,handle);
    }

    /*
     初始化页面，初始化数据
    */
    function __init(userid,handle){
        //获取通讯录列表
        h.ajax(u.get('getmember'),{wx_userid:userid},function(data){
            if(data.status == 0){
                $("#user_avatar").attr('src',data.aData.avatar);
                if(data.aData.name != null){
                    $("#detail_username").text(data.aData.name);
                }else{
                    $("#detail_username").empty();
                }
                if(data.aData.wx_userid != null){
                    $("#userid").text(data.aData.wx_userid);
                    $("#btnChat").attr('userid',data.aData.wx_userid);
                    $("#btnChat").attr('username',data.aData.name);
                    $("#btnChat").attr('avatar',data.aData.avatar);
                    $("#btnChat").attr('login_time',data.aData.login_time);
                    $("#btnChat").attr('device_type',data.aData.device_type);
                    $("#btnChat").attr('network',data.aData.network);
                }else{
                    $("#userid").empty();
                }
                if(data.aData.mobile != null){
                    $("#mobile").text(data.aData.mobile);
                    $("#mobile_a").attr("href","tel:"+data.aData.mobile);
                }else{
                    $("#mobile").empty();
                    $("#mobile_a").attr("href","");
                }
                if(data.aData.weixinid){
                    $("#weixinid").text(data.aData.weixinid);
                }else{
                    $("#weixinid").empty();
                }
                if(data.aData.email){
                    $("#email").text(data.aData.email);
                }else{
                    $("#email").empty();
                }
                if(data.aData.position){
                    $("#position").text(data.aData.position);
                }else{
                    $("#position").empty();
                }
                var department = '';
                $.each(data.aData.department,function(i,o){
                    department += $(o).get(0).name+" ";
                });
                $("#department").empty().text(department);
                pageIn(".memberDetail",function(){
                    typeof(handle)==="function"? handle(): function(){};
                });
            }else{
                return msg.alert('呜~获取通讯录信息失败啦');
            }
        });

    }

     /*
      获取用户信息
    */
    exports.getuserinfo = function (data,callback){
        h.ajax(u.get('getmember'),{wx_userid:data.getSenderUserId()},function(ret){
            if(ret.status == 0){
                callback(data.getSenderUserId(),data.getContent(),ret.aData.avatar);
            }else{
                return msg.alert('收到消息,呜~获取ta的信息失败啦');
            }
        });
    }



});