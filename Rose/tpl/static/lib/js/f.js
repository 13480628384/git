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
