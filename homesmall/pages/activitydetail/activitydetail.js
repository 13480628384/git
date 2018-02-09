var r_url = require('../../utils/url.js');
Page({
  data: {
    activitydata:{},
  },
  onLoad: function (options) {
        wx.showLoading({
          title: '加载中...'
        });
        var that = this;
    wx.request({
      url: r_url.details(),
      method: 'POST',
      data:{id:options.id},
      header: { 
 'content-type':'application/x-www-form-urlencoded'
 }, 
         success: function(res){
           wx.hideLoading();
            if(res.data.code == 200){
                that.setData({
                  activitydata:{
                    "status": res.data.msg.status,
                    "price": res.data.msg.price,
                    "remarks": res.data.msg.remarks,
                    "out_trade_no": res.data.msg.out_trade_no,
                    "name": res.data.add.name,
                    "phone": res.data.add.phone,
                    "details": res.data.add.details
                  }
                })
            }
         },
         fail: function(res) {
           wx.hideLoading();
         }
    });
  },
})
