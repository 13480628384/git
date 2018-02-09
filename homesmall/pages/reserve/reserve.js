var r_url = require('../../utils/url.js');
var current = require('../../utils/util.js');
Page({
  data: {
    counitylist:[],
    address:[],
    cururl: current.getCurrentPageUrl(),
    dates: current.getNowTime(),
    casArray: ['08:00','14:00'],
    casIndex:0,
    dataid:'',
    weuitextarea:'',
    id:'',
    price:''
  },
  // 获取该组件的id  
  radio: function (e) {
    this.setData({
      dataid: e.currentTarget.dataset.id
    })
  },
 //输入备注
 remarks:function(e){
   this.setData({
     weuitextarea: e.detail.value,
   }) 
 },
  //准确时间
  bindCasPickerChange: function (e) {
    this.setData({
      casIndex: e.detail.value
    })
  },
  //  点击日期组件确定事件  
  bindDateChange: function (e) {
    this.setData({
      dates: e.detail.value
    });
  },
  //点击提交预定事件
  changecheck: function(e){
      var time = '';
      if (this.data.casIndex == '0'){
        time = this.data.dates+' 08:00:00';
      } else {
        time = this.data.dates + " 14:00:00";
      }
      var array = this.data.counitylist;
      for (var i in array){
        if (array[i]['hour'] == time){
          wx.showToast({
            title: '该时间已被预约',
            icon: 'none',
            duration: 2000
          });
          return false;
        }
      }
      if (this.data.dataid == ''){
        wx.showToast({
          title: '请选择地址',
          icon: 'none',
          duration: 2000
        });
        return false;
      }
      var add_id = this.data.dataid;
      var select_time = time;
      var customer_mark = this.data.weuitextarea;
      var id = this.data.id;
      var price = this.data.price;
      //console.log(add_id+'---'+select_time+'----'+customer_mark+'---'+id+'---'+price);    
      wx.showLoading({
        title: '提交订单中...'
      });
      wx.request({
        url: r_url.reser_submit(),
        method: 'POST',
        data: { id: id, add_id: add_id, select_time: select_time, customer_mark: customer_mark,price:price},
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res) {
          console.log(res);
          wx.hideLoading();
          wx.navigateTo({
            url: '../accounting/accounting' + res.data.url
          })
        },
        fail: function (res) {
          console.log(res);
          wx.hideLoading();
        }
      });


  },
  onLoad: function (options) { //加载数据渲染页面
    this.fetchConferenceData(options);
    this.setData({
      id: options.id
    })
  },
  fetchConferenceData: function (options){
    var that = this;
    wx.showLoading({
          title: '加载中...'
        });
    wx.request({
      url:r_url.reserve(),
      method: 'POST',
      data: { id:options.id},
      header: { 
 'content-type':'application/x-www-form-urlencoded'
 },
         success: function(res){
           wx.hideLoading();
            if(res.data.code == 200){
              that.setData({
                counitylist:
                that.data.counitylist.concat(res.data.hour)
              });
              
              that.setData({
                address:
                that.data.address.concat(res.data.house)
              });
              
              that.setData({
                detail: {
                  "img": res.data.msg.img,
                  "price": res.data.msg.price,
                  "title": res.data.msg.title,
                  "samlltitle": res.data.msg.samlltitle,
                  "service": res.data.msg.service,
                  "id": res.data.msg.id
                }
              });
              that.setData({
                price: res.data.msg.price
              })
            }
         },
         fail: function(res) {
           wx.hideLoading();
         }
    });
  },
})