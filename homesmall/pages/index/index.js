var r_url = require('../../utils/url.js');
var current = require('../../utils/util.js');
Page({
  data: {
    counitylist:[], //商品列表
    scrolltop:null, //滚动位置
    cururl: current.getCurrentPageUrl(),
    page:0
  },
  onLoad: function () { //加载数据渲染页面
    //console.log(current.getCurrentPageUrl());
    this.fetchConferenceData();
  },
  fetchConferenceData:function(){
    var that = this;
    const page = that.data.page;
    wx.showLoading({
          title: '加载中...'
        });
    wx.request({
      url:r_url.Community_home_list(),
      method: 'POST',
      data:{n:page},
      header: { 
 'content-type':'application/x-www-form-urlencoded'
 }, 
         success: function(res){
           wx.hideLoading();
            if(res.data.code == 200){
                that.setData({
                counitylist:
                that.data.counitylist.concat(res.data.msg)
              });
            }
         },
         fail: function(res) {
           wx.hideLoading();
         }
    });
    that.setData({
      page:that.data.page+1
    });
  },
  /*scrollHandle:function(e){ //滚动事件
    this.setData({
      scrolltop:e.detail.scrollTop
    })
  },*/
  goToTop:function(){ //回到顶部
    this.setData({
      scrolltop:0
    })
  },
  scrollLoading:function(res){ //滚动加载
  console.log(res);
    this.fetchConferenceData();
  },
  /*onPullDownRefresh:function(){ //下拉刷新
    this.setData({
      page:0,
      counitylist:[]
    })
    this.fetchConferenceData();
    console.log(1);
    wx.stopPullDownRefresh();
  }*/
})