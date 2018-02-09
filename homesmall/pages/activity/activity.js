// pages/activity/activity.js
var r_url = require('../../utils/url.js');
var current = require('../../utils/util.js');
var wxMarkerData = []; 
Page({
  data:{
    counitylist:[],
    cururl: current.getCurrentPageUrl(),
  },
  onLoad:function(options){
    var that = this;
    this.fetchConferenceData();
  },
  fetchConferenceData: function () {
    var that = this;
    const page = that.data.page;
    wx.showLoading({
      title: '加载中...'
    });
    wx.request({
      url: r_url.my_customr(),
      method: 'POST',
      data: { n: page },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        wx.hideLoading();
        if (res.data.code == 200) {
          that.setData({
            counitylist:
            that.data.counitylist.concat(res.data.msg)
          });
        }
      },
      fail: function (res) {
        wx.hideLoading();
      }
    });
    that.setData({
      page: that.data.page + 1
    });
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})