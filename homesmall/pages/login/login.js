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
    session_key:'',
  },
  onLoad: function () {
    var session_key = this.data.session_key;
    var that = this;
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    });
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
    });
    wx.request({
      url:r_url.checksession(),
      data:{session_key:session_key},
       header: {'content-type': 'application/x-www-form-urlencoded'}, 
      method:'POST',
      success: function(res) {
        if(res.statusCode == 200){
        /*  wx.redirectTo({
             url:'../personal/personal'
        });*/
        } else {
          console.log('checksesson');
        }
      },fail:function(res){
          console.log(res);
      }
    })
  },
  
  //点击登录提交
  login:function(){
    if(this.data.username.length == 0 || this.data.password.length == 0){
      this.setData({
        infomess:'温馨提示：手机号码和密码不能为空',
      });
      return;
    }
    var phone = this.data.username;
    var password = this.data.password;
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
      title: '登录中...'
    });

    wx.request({
      url: r_url.login(),
      data: {phone:phone,password:password,session_key:session_key},
      header: {'content-type': 'application/x-www-form-urlencoded'}, 
      method:'POST',
      success: function(res) {
        if(res.data.code == 200){
          wx.setStorage({
            key: 'session_id',
            data: res.data.message,
            success: function(res) {
            }
          });
          wx.showToast({
            title: res.data.msg,
            icon: 'success',
            duration: 2000
          });
          setTimeout(function(){
            wx.switchTab({
              url:'../index/index'
            })
          },1000);
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
