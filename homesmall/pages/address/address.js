var r_url = require('../../utils/url.js');
var current = require('../../utils/util.js');
Page({
  data: {
    cururl: current.getCurrentPageUrl(),
    is_on:1,
    name:'',
    phone:'',
    area:'',
    detail:'',
  },
  onLoad: fun   
  },
  checkboxChange:function(e){
    if (e.detail.value == true){
      this.setData({
        is_on: 1
      })
    } else {
      this.setData({
        is_on: 0
      })
    }
  },
  inputusername: function (e) {
    this.setData({
      name: e.detail.value,
    })
  },
  inputphone: function (e) {
    this.setData({
      phone: e.detail.value,
    })
  },
  inputarea: function (e) {
    this.setData({
      area: e.detail.value,
    })
  },
  detail:function(e){
    this.setData({
      detail:e.detail.value
    })
  },
  //添加地址
  showTopTips:function(e){
    if (this.data.name.length == 0 || this.data.phone.length == 0) {
      wx.showModal({
        content: '温馨提示：手机号码和电话不能为空',
        showCancel: false,
        success: function (res) {
          
        }
      });
      return;
    }
    if (this.data.detail.length == 0){
      wx.showModal({
        content: '温馨提示：地址不能为空',
        showCancel: false,
        success: function (res) {

        }
      });
      return;
    }
    var name = this.data.name;
    var phone = this.data.phone;
    var area = this.data.area;
    var detail = this.data.detail;
    var is_de = this.data.is_on;

    var REG = {
      phonec: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[0|1|2|3|4|5|6|7|8|9][0-9]{9}$)/
    }
    if (!REG.phonec.test(phone)) {
      wx.showModal({
        content: '温馨提示：手机号码格式错误',
        showCancel: false,
        success: function (res) {

        }
      });
      return;
    }
    wx.showLoading({
      title: '加载中...'
    });
    wx.request({
      url: r_url.address(),
      data: { phone: phone,name: name, area: area, detail: detail,is_de:is_de},
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      method: 'POST',
      success: function (res) {
        console.log(res);
        if (res.data.code == 200) {
          wx.showToast({
            title: res.data.msg,
            icon: 'success',
            duration: 2000
          });
        } else {
          wx.showModal({
            content: res.data.msg,
            showCancel: false,
            success: function (res) {

            }
          });
        }
        wx.hideLoading();
      },
      fail: function (e) {
        wx.hideLoading();
      }
    });  
  }
})