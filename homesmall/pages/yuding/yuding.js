//index.js
//获取应用实例
var r_url = require("../../utils/util.js");
var url = require("../../utils/url.js");
var WxParse = require('../../wxParse/wxParse.js');
var app = getApp();
Page({
  data: {
    detail:{},
    cuulr: r_url.getCurrentPageUrl()
  },
  onLoad: function (options) {
    //数据加载
    wx.showLoading({
      title: '加载中...'
    });
    var that = this;
    wx.request({
      url: url.yuding(),
      method: 'POST',
      data: { id: options.id },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        wx.hideLoading();
        if (res.data.code == 200) {
        
          that.setData({
            detail: {
              "img": res.data.msg.img,
              "price": res.data.msg.price,
              "title": res.data.msg.title,
              "samlltitle": res.data.msg.samlltitle,
              "service": res.data.msg.service,
              "id":res.data.msg.id
            }
          });
          //html文本
          var article = res.data.msg.service;
          WxParse.wxParse('article', 'html', article, that, 0);
        }
      },
      fail: function (res) {
        wx.hideLoading();
      }
    });
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})