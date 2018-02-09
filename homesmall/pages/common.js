/*function login_checks(){
    var r_url = require("../utils/url.js");
//获取应用实例
var app = getApp();
//判断登录状态是否已经过期
wx.checkSession({
  success: function(res){
    console.log(res);
  },
  fail: function(){
     r_url.weixin_login();
  }
});
Page({
  data: {
    userInfo: {},
    username:'',
    password:'',
    infomess:'',
    session_id:'',
  },
  onLoad: function () {
    var session_id = this.data.session_id;
    var that = this;
    wx.getStorage({
       key: 'session_id',   
       success: function(res) {
          that.setData({
            session_id:res.data
          });
        },
        fail:function(){
          r_url.weixin_login();
        }  
    });
    wx.request({
      url:r_url.check_session_id(),
      data:{session_id:session_id},
       header: {'content-type': 'application/x-www-form-urlencoded'}, 
      method:'POST',
      success: function(res) {
        if(res.statusCode == 500){
         wx.redirectTo({
             url:'login/login'
        });
        }
      },fail:function(res){
          console.log(res);
      }
    })
  },
   
});
}
module.exports = {
  login_checks: login_checks
}*/