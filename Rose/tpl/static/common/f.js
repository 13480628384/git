/*
 * 调用地图
 * 第二个参数是回调函数，有即执行，否则直接跳转页面
 */
function baiduMap(address, callBack){
    address = $.trim(address);
    if ('' == address) { return false; };
    var url = 'http://api.map.baidu.com/geocoder/v2/?ak=ft9tCNRzY3LkR1z1hRAwyIC4&output=json&address='
             + encodeURIComponent(address);
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

function getMapAddress(locat, callBack){
    var url = 'http://api.map.baidu.com/geocoder/v2/?ak=ft9tCNRzY3LkR1z1hRAwyIC4&output=json'
             + '&coordtype=bd09ll' + '&location=' + locat + '&pois=0';
    //根据地点名称获取经纬度信息
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'JSONP',
        success: function (data) {
            if (parseInt(data.status) == 0) {
                // 获取到经纬度,先纬度后经度
               if (typeof(callBack) == 'function') {
                   callBack(data.result.addressComponent);
               }
            }
       }
    });
}


/*
 * ajax封装方法
 * 为了快速实现简单的get和post请求
 */
function ajax(url, data, callBackSuccess, callBackError, GetPost, requestType){
    var type = (typeof(GetPost) == 'undefined' || GetPost == 1) ? 'POST' : 'GET';
    var dataType = typeof(requestType) == 'undefined' ? 'json' : requestType;
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
                callBackSuccess(data);
            }
        },
        error : function(){
            if(typeof(callBackError) === "function"){
                callBackError();
            }
        }
    });
}

/*
* 创建cookie
* */

function setCookie(c_name,value,expiredays)
{
    var cookieString=c_name+"="+escape(value);
    //判断是否设置过期时间
    if(expiredays>0){
        var date=new Date();
        date.setTime(date.getTime+expiredays*3600*1000);
        cookieString=cookieString+"; expire="+date.toGMTString();
    }
    document.cookie=cookieString;
}

/*
* 获取cookie值
* */

function getCookie(name){
    var strCookie=document.cookie;
    var arrCookie=strCookie.split("; ");
    for(var i=0;i<arrCookie.length;i++){
        var arr=arrCookie[i].split("=");
        if(arr[0]==name)return arr[1];
    }
    return "";
}

/*
*删除cookie值
* */

function deleteCookie(c_name){
    var date=new Date();
    date.setTime(date.getTime()-10000);
    document.cookie=name+"=v; expire="+date.toGMTString();
}

function isPhone(phone){
    if(phone.length != 11){
        return false;
    }else if(!/^0?1[3|4|5|8][0-9]\d{8}$/.test(phone)){
        return false;
    }
    return true;
}

function isApple(){
		var agent = navigator.userAgent.toLowerCase();
		return agent.match(/iphone/i) || agent.match(/ipad/i);
	}
