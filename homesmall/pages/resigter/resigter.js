//index.js
//获取应用实例
var r_url = require("../../utils/url.js");
var app = getApp();
//判断登录状态是否已经过期
wx.checkSession({
  success: function(res){
    //console.log(res);
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
    session_key:''
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    var that = this;
    wx.getStorage({
       key: 'session_key',   
       success: function(res) {
          that.setData({
            session_key:res.data
          });
        },
        fail:function(){
          r_url.weixin_login();
        }  
    }) 
  },
  
  //点击注册提交
  resigter:function(){
    if(this.data.username.length == 0 || this.data.password.length == 0){
      this.setData({
        infomess:'温馨提示：手机号码和密码不能为空',
      });
      return;
    }
    var phone = this.data.username;
    var password = this.data.password;
    var avatarUrl = this.data.userInfo.avatarUrl;
    var nickName = this.data.userInfo.nickName;
    var session_key = this.data.session_key;
     var REG = {
        phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/
    }
    if(!REG.phone.test(phone)){
            this.setData({
              infomess:'温馨提示：手机号码格式错误',
            });
        return;
    }
     wx.showLoading({
      title: '加载中...'
    });

    wx.request({
      url: r_url.resigter(),
      data: {phone:phone,password:password,avatarUrl:avatarUrl,nickName:nickName,session_key:session_key},
      header: {'content-type': 'application/x-www-form-urlencoded'}, 
      method:'POST',
      success: function(res) {
        if(res.data.code == 200){
          wx.showToast({
            title: res.data.msg,
            icon: 'success',
            duration: 2000
          });
          setTimeout(function(){
            wx.navigateTo({
             url:'../login/login'
            })
          },2000)
        }else{
            wx.showModal({
              title: '提示',
              content: res.data.msg,
              success: function(res) {
               
              }
            })
        }
        wx.hideLoading();
      },
      fail:function(){
          wx.hideLoading();
      }
    });
  },
  //用户名输入获取值
  inputusername: function(e) {
        this.setData({  
          username:e.detail.value,
          infomess:'', 
        }) 
    },
    //密码输入获取值
    inputpassword: function(e) {
        this.setData({  
          password:e.detail.value,
          infomess:'', 
        }) 
    },

    
});
