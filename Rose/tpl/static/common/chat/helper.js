/**
 * helper函数
 * @author damon
 */
define(function(require, exports, module){
    var iBaseWidth = 300;
    var bAutoHide  = true;
    var iTimeout   = 2000;


    /*
     *  Time  时间格式化
     */
    exports.time = function(intTime, defaultShow, format){
        defaultShow = typeof(defaultShow) == 'undefined' ? '-:-' : defaultShow;
        var d = new Date(parseInt(intTime) * 1000)
        if (typeof(format) != 'undefined') {
            return d.Format(format);
        }
        if (!intTime) { return defaultShow }
        var m = d.getMinutes();
            m = m < 10 ? '0' + m : m;
        return d.getHours() + ':' + m;
    }

    exports.json = function(json, key, defaults){
        defaults = typeof(defaults) == 'undefined' ? null : defaults;
        return typeof(json[key]) == 'undefined' ? defaults : json[key];
    }

    exports.pageIn = function(obj,callback){
        $(obj).addClass("on");
        $(obj).animate({
            "left":0,
            "top":0
        },600,"easeOutQuart",function(){
            if(typeof callback === "function"){
                callback();
            }
        });
    }

    exports.goBack = function(){
        $('.pageBack').trigger('click')
    }

    exports.scrollDate = function(opt,callback){
      $(opt.obj).mobiscroll().date({
          theme: 'ios',     // Specify theme like: theme: 'ios' or omit setting to use default
          mode: 'Scroller',       // Specify scroller mode like: mode: 'mixed' or omit setting to use default
          display: 'bottom', // Specify display mode like: display: 'bottom' or omit setting to use default
          lang: "zh",       // Specify language like: lang: 'pl' or omit setting to use default
          onSelect: function (valueText, inst) {
              function _setVal(obj,date){
                  $(obj).mobiscroll("setVal",date)
              }
              if(typeof callback === "function"){
                  callback({
                      valueText:valueText,
                      inst:inst,
                      _setVal :_setVal
                  });
              }
          },
          minDate: opt.minDate,  // More info about minDate: http://docs.mobiscroll.com/2-14-0/datetime#!opt-minDate
          maxDate: opt.maxDate,   // More info about maxDate: http://docs.mobiscroll.com/2-14-0/datetime#!opt-maxDate
          stepMinute: 1  // More info about stepMinute: http://docs.mobiscroll.com/2-14-0/datetime#!opt-stepMinute
      });
   }



    exports.jssdk = function(action){
        var h = require('helper'),
            u = require('url'),
            wx = require('wx');
            h.ajax(
                u.get('jssdk'), {url:ugetclean(window.location.href)},
                function(json){
                    if (json.status == 0) {
                        var d = json.aData;
                        wx.config({
                            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                            appId: d.appId, // 必填，企业号的唯一标识，此处填写企业号corpid
                            timestamp: d.timestamp, // 必填，生成签名的时间戳
                            nonceStr: d.nonceStr, // 必填，生成签名的随机串
                            signature: d.signature,// 必填，签名，见附录1
                            jsApiList: ['getLocation', 'openLocation','uploadImage','chooseImage','previewImage','downloadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                        });

                        wx.ready(function(){
                            if (typeof(action) == 'function') {
                                action();
                            };
                        });
                        wx.error(function(res){
                        });
                        /*
                        if (typeof(action) == 'function') {
                            action();
                        };
                        */
                    }
                }
            );
    }

    /*
    图片预览
    */
    exports.jssdkPreviewImage = function(imgurls,cur){
        if(!cur){cur= ""}
        var h = require('helper'),
            wx = require('wx');
            wx.previewImage({
                current: cur, // 当前显示图片的http链接
                urls: imgurls // 需要预览的图片http链接列表
            });

    }
    exports.jssdkUploadImage = function(localIds,action){
        var h = require('helper'),
            u = require('url'),
            wx = require('wx');
        var images = {
            localId: localIds,    //
            serverId: []
        };
        var i = 0, length = images.localId.length;
        function upload() {
            wx.uploadImage({
                localId: images.localId[i],
                success: function (res) {
                    i++;
                    // alert('已上传：' + i + '/' + length);
                    images.serverId.push(res.serverId);
                    if (i < length) {
                        upload();
                    }else{
                        $.each(images.serverId,function(key,value){
                            h.ajax(u.get('getMedia'),{media_id:value},function(data){
                                action(data);
                            });
                        });

                    }
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        }
        upload();//上传
    }


    /*
     * 调用地图
     * 第二个参数是回调函数，有即执行，否则直接跳转页面
     */
    exports.baiduMap = function(address, callBack){
        address = $.trim(address);
        if ('' == address) { return false; };
        var url = 'http://api.map.baidu.com/geocoder/v2/?ak=ft9tCNRzY3LkR1z1hRAwyIC4&output=json&address=' + encodeURIComponent(address);
        //根据地点名称获取经纬度信息
         $.ajax({
             type: 'POST',
             url: url,
             dataType: 'JSONP',
             success: function (data) {
                 if (parseInt(data.status) == 0) {
                     // 获取到经纬度,先纬度后经度
                     var lng = data.result.location.lng,
                         lat = data.result.location.lat;
                    if (typeof(callBack) == 'function') {
                        callBack(lng, lat);
                    }else{
                         window.location.href = "http://api.map.baidu.com/marker?location="+lat+","+lng+"&title="+address+"&name="+address+"&content="+address+"&output=html&src=weiba|weiweb";
                    };
                 }
            }
         });
    }

    /*
     * 调用地图
     * 第二个参数是回调函数，有即执行，否则直接跳转页面
     */
    exports.getAddressBylatlng = function(lat, lng, callBack){
        if ('' == lat || '' == lng) { return false; };
        var url = 'http://api.map.baidu.com/geocoder/v2/?ak=ft9tCNRzY3LkR1z1hRAwyIC4&callback='+callBack+'&location='+lat+','+lng+'&output=json&pois=1';
	//根据地点名称获取经纬度信息
         $.ajax({
             type: 'POST',
             url: url,
             dataType: 'JSONP',
             success: function (data) {
                if (typeof(callBack) == 'function'){
                    callBack(data);
                }
            }
         });
    }
    /*
     *  msub 智能字符串截取
     */
    exports.msub = function(str, length, pad){
        var pad = typeof(pad) == 'undefined' ? '...' : pad
        if (!str) { return ''; }
        if (length >= str.length) {
            return str;
        }
        return str.substr(0, length) + pad;
    }


    /*
     *  Handlebars Compile
     *  Handlebars模板引擎编辑接口
     */
    exports.HCompile = function(id, data){
        var myTemplate = Handlebars.compile($("#"+id).html());
        return $(myTemplate(data));
    }


    /*
     * ajax封装方法
     * 为了快速实现简单的get和post请求
     */
    exports.ajax = function(url, data, callBackSuccess, callBackError, GetPost, requestType, async){
        var type     = (typeof(GetPost) == 'undefined' || GetPost == 1) ? 'POST' : 'GET';
        var dataType = typeof(requestType) == 'undefined' ? 'json' : requestType;
        var async    = typeof(async) == 'undefined' ? true : async;
        var _ajax_cache = typeof(data._ajax_cache) != 'undefined' && data._ajax_cache;//指定缓存
        $.ajaxSetup ({
             cache: false
        });
        $.ajax({
            url  : url,
            type : type,
            async: true,
            data : data,
            dataType : dataType,
            success: function(data){
                if(typeof(callBackSuccess) === "function"){
                    if (data.status === -999) {
                        window.location.href = data.sMsg;
                    }else{
                        callBackSuccess(data);
                    };
                }
            },
            error : function(){
                if(typeof(callBackError) === "function"){
                    callBackError();
                }
            },
            xhrFields: {
              withCredentials: true
           }
        });
    }

    /*
     * 设置cookie
     */
    exports.setCookie = function(c_name, value, expiredays)
    {
        var exdate = new Date()
        exdate.setDate(exdate.getDate() + expiredays)
        document.cookie = c_name + "=" + escape(value) +
            ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
    },

    /*
     * 获取cookie
     */
    exports.getCookie = function(c_name)
    {
        if (document.cookie.length > 0)
        {
            c_start = document.cookie.indexOf(c_name + "=")
            if (c_start != -1)
            {
                c_start = c_start + c_name.length + 1
                c_end = document.cookie.indexOf(";", c_start)
                if (c_end == -1)
                    c_end = document.cookie.length
                return unescape(document.cookie.substring(c_start, c_end))
            }
        }
        return ""
    };

    //for cache
    var _getCity_addComp = null;
    var _getCity_point   = null;
    exports.getCity = function(callback){
       if (_getCity_addComp && _getCity_point) {
          if(typeof callback === "function"){
              callback(_getCity_addComp,_getCity_point);
          }
          return;
       }
       var geolocation = new BMap.Geolocation(),
           geoc = new BMap.Geocoder();
       geolocation.getCurrentPosition(function(r){
           if(this.getStatus() == BMAP_STATUS_SUCCESS){
               var pt = r.point;
               geoc.getLocation(pt, function(rs){
                   var addComp = rs.addressComponents;
                   _getCity_addComp = addComp;
                   _getCity_point   = pt;
                   if(typeof callback === "function"){
                       callback(addComp,pt);
                   }
               });

           } else {
               alert('failed'+this.getStatus());
           }
       },{enableHighAccuracy: true});
    }

    formatDis = function(mi){
        var km = mi/1000;
        if (km < 1) return mi.toFixed(2) + 'm';
        return km.toFixed(2) + 'km';
    };

    exports.formatDis = formatDis;

    exports.getDis = function(lng1, lat1, lng2, lat2, fix){
        fix = typeof(fix) == 'undefined' ? 2 : fix;
        var dis = new BMap.Map().getDistance(
            new BMap.Point(lng1,lat1),
            new BMap.Point(lng2, lat2)
        );
        return dis.toFixed(fix);
    };


    getTextareaFocusPos = function(obj){
        var result = 0;
        if(obj.selectionStart || obj.selectionStart == 0){ //IE以外
            result = obj.selectionStart
        }else{ //IE
            var rng;
            if(obj.tagName == "TEXTAREA"){ //TEXTAREA
                rng = event.srcElement.createTextRange();
                rng.moveToPoint(event.x,event.y);
            }else{ //Text
                rng = document.selection.createRange();
            }
            rng.moveStart("character",-event.srcElement.value.length);
            result = rng.text.length;
        }
        return result;
    };
    exports.getTextareaFocusPos = getTextareaFocusPos;

    exports.insertTextArea = function(obj, cnt, callback){
        var pos = getTextareaFocusPos(obj);
        if (typeof(callback) == 'function') {
            callback(
                obj.value.substr(0,pos)+cnt+obj.value.substr(pos,obj.value.length),
                pos
            );
        }
    };

    ugetclean = function(url){
        var pos = url.indexOf('?');
        if (-1 != pos) {
            return url.substring(url, pos);
        }
        var pos = url.indexOf('#');
        if (-1 != pos) {
            return url.substring(url, pos);
        }
        //alert(url)
        return url;
    };
    exports.ugetclean = ugetclean;

    exports.scroll = function(obj){
        seajs.use(["iscroll"],function(){
            fH(obj);
            $(obj).find(".iScrollLoneScrollbar").remove();
            return new IScroll(obj, {
                scrollX: true ,
                scrollbars:true,
                interactiveScrollbars: true,
                shrinkScrollbars: 'scale',
                fadeScrollbars: true,
                MSPointerMove: function(){
                }
                });
            $(".iScrollLoneScrollbar").width(4);
            $(".iScrollIndicator").css({
                "border":"none"
            })
        });
    };

    fH = function(obj){
        $(function(){
            var h = $(obj).offset().top;
            h = $(window).height()-h;
            $(obj).height(h);
        });
    };
    exports.fH = fH;
});
