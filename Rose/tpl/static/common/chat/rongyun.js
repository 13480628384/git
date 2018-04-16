/*
融云IM服务
*/
define(function(require,exports,module){

     var h        = require('helper'),
      user        = require('user'),    
         u        = require('url');
         require('RongIMClient');



     /*
     初始化连接
     */

     function __getImToken(callback){
       	h.ajax(u.get('getImtoken'),{},function(data){
         		if(data.status == 0){
         			  callback(data.aData);
         		}else{
                console.log('融云初始化init失败');
            }
       	});
     }

     /*
     同步聊天内容至服务器
     */
     function __sync(from_wx_userid,to_wx_userid,content,type){
     	  console.log(from_wx_userid+"————"+to_wx_userid+"——————"+content+"——————"+type);
        h.ajax(u.get('chatadd'),{from_wx_userid:from_wx_userid,to_wx_userid:to_wx_userid,content:content,type:type},function(data){
          if(data.status == 0){
              console.log('同步聊天成功');
          }else{
              console.log('同步聊天失败');
          } 
        });
     }

     /*
      登录
     */
     function __login(userid){
        var device_type = deviceType();
        var network = netType();    
        h.ajax(u.get('messageseverlogin'),{device_type:device_type,network:network,userid:userid},function(data){
          if(data.status == 0){
              console.log('登录34team服务器成功');
          }else{
              console.log(data);
          } 
        });
     }


     exports.init = function(reciveCallback,connectCallback){
     	  __getImToken(function(data){
     		RongIMClient.init(data.appkey);
     		RongIMClient.setConnectionStatusListener({
            	onChanged: function (status) {
		                console.log(status.toString(), new Date())
		        }
		    });
	        RongIMClient.getInstance().setOnReceiveMessageListener({
	            onReceived: function (data) {
                 user.getuserinfo(data,reciveCallback);
	               __sync(data.getSenderUserId(),localStorage.getItem("userId"),data.getContent(),data.getMessageType());
	            }
	        });

	        RongIMClient.connect(data.token,{
                onSuccess: function (userId) {
                    // 此处处理连接成功。
                    connectCallback("Login successfully:"+userId,RongIMClient.getInstance());
                    localStorage.setItem("userId",userId);
                    __login(userId);
                    console.log("Login successfully." + userId);
                },
                onError: function (errorCode) {
                    // 此处处理连接错误。
                    var info = '';
                    switch (errorCode) {
                           case RongIMClient.callback.ErrorCode.TIMEOUT:
                                info = '超时';
                                break;
                           case RongIMClient.callback.ErrorCode.UNKNOWN_ERROR:
                                info = '未知错误';
                                break;
                           case RongIMClient.ConnectErrorStatus.UNACCEPTABLE_PROTOCOL_VERSION:
                                info = '不可接受的协议版本';
                                break;
                           case RongIMClient.ConnectErrorStatus.IDENTIFIER_REJECTED:
                                info = 'appkey不正确';
                                break;
                           case RongIMClient.ConnectErrorStatus.SERVER_UNAVAILABLE:
                                info = '服务器不可用';
                                break;
                           case RongIMClient.ConnectErrorStatus.TOKEN_INCORRECT:
                                info = 'token无效';
                                break;
                           case RongIMClient.ConnectErrorStatus.NOT_AUTHORIZED:
                                info = '未认证';
                                break;
                           case RongIMClient.ConnectErrorStatus.REDIRECT:
                                info = '重新获取导航';
                                break;
                           case RongIMClient.ConnectErrorStatus.PACKAGE_ERROR:
                                info = '包名错误';
                                break;
                           case RongIMClient.ConnectErrorStatus.APP_BLOCK_OR_DELETE:
                                info = '应用已被封禁或已被删除';
                                break;
                           case RongIMClient.ConnectErrorStatus.BLOCK:
                                info = '用户被封禁';
                                break;
                           case RongIMClient.ConnectErrorStatus.TOKEN_EXPIRE:
                                info = 'token已过期';
                                break;
                           case RongIMClient.ConnectErrorStatus.DEVICE_ERROR:
                                info = '设备号错误';
                                break;
                    }
                    connectCallback("login fail errcode:"+errorCode+",info :"+info,RongIMClient.getInstance());
                    console.log("失败:" + info);
                }
            });

     	});
     	
     }


      /*
      发送消息
      */
      exports.sendMessage = function(ConnectedRongIMClient,fromId,targetId,content,callback){
      	 var msg = new RongIMClient.TextMessage();
		 // 设置消息内容
		 msg.setContent(content);
		 var conversationtype = RongIMClient.ConversationType.PRIVATE; // 私聊
		 var targetId = targetId; // 目标 Id
		 ConnectedRongIMClient.sendMessage(conversationtype, targetId, msg, null, {
		                // 发送消息成功
		                onSuccess: function () {
		                    callback('success','发送成功');
                        console.log(fromId+':'+targetId+'--'+content+'--success');
		                    __sync(fromId,targetId,content,'TextMessage');
                        __sendWexinMessage(fromId,targetId,content,'TextMessage');
		                },
		                onError: function (errorCode) {
		                    var info = '';
		                    switch (errorCode) {
		                        case RongIMClient.callback.ErrorCode.TIMEOUT:
		                            info = '超时';
		                            break;
		                        case RongIMClient.callback.ErrorCode.UNKNOWN_ERROR:
		                            info = '未知错误';
		                            break;
		                        case RongIMClient.SendErrorStatus.REJECTED_BY_BLACKLIST:
		                            info = '在黑名单中，无法向对方发送消息';
		                            break;
		                        case RongIMClient.SendErrorStatus.NOT_IN_DISCUSSION:
		                            info = '不在讨论组中';
		                            break;
		                        case RongIMClient.SendErrorStatus.NOT_IN_GROUP:
		                            info = '不在群组中';
		                            break;
		                        case RongIMClient.SendErrorStatus.NOT_IN_CHATROOM:
		                            info = '不在聊天室中';
		                            break;
		                        default :
		                            info = x;
		                            break;
		                    }
                        console.log(fromId+':'+targetId+'--'+content+'--error');
		                    var ret = __sendWexinMessage(fromId,targetId,content,'TextMessage');
		                    if(ret){
                            console.log(fromId+':'+targetId+'--'+content+'--weixin--success');
		                    	  callback('success','融云发送失败,已发送至微信');
  		                	}else{
  		                		  callback('error','系统繁忙');
  		                	}
		                }
		            }
		        );


      }

      /*
      如果不在线推送微信企业号消息
      */
      function __sendWexinMessage(from_wx_userid,to_wx_userid,content,type){
        	h.ajax(u.get('sendwxmessage'),{from_wx_userid:from_wx_userid,to_wx_userid:to_wx_userid,content:content,type:type},function(data){
            if(data.status == 0){
                console.log('微信聊天发送成功');
            }else{
                console.log('微信聊天发送失败');
            } 
          });
      }

      /*
      拉取历史消息
      */
      exports.getHistoryMessages  = function(){
      	  return true;
      }




});