/**
 * Created by my on 2014/12/4.
 * User:訾超
 */
var getLocation_Loading = 0;
var locationAddress = '';//存储定位后的地址详情字符串

function baiduGetLocaltion(){
    if(getLocation_Loading){
        alert("正在定位，请稍候...");
        return false;
    }
    getLocation_Loading = 1;
    $(".online").text('正在定位，请稍后…');
    $(".local").text('正在定位，请稍后…');
    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(function(r){

        //convertor(113.885888,22.57396,1,0);
       if(this.getStatus() == BMAP_STATUS_SUCCESS){
            //alert('您的位置：'+r.point.lng+','+r.point.lat);
            convertor(r.point.lng, r.point.lat,1,0);
        }
        else {
            getLocation_Loading = 0;
           // alert("定位失败，请重试！");
            $(".online").text('定位失败，请刷新重试！');
            $(".local").text('定位失败，请刷新重试！');
        }
    },{enableHighAccuracy: true})
}

//坐标校准
var convertor=function(long,Lati,type,revise){
    //var xx = 116.397428;
    //var yy = 39.90923;
    if(revise){
        var gpsPoint = new BMap.Point(long,Lati);
        //需要校准
        BMap.Convertor.translate(gpsPoint,0,function(point){
            //alert(point.lng + "," + point.lat);
            //获取地理位置信息
            getLocation(point.lng,point.lat,type);
        });
    }
    else
    {
        getLocation(long,Lati,type);
    }
};
//alert(getLocation(point.lng,point.lat,type));
//根据坐标获取地理位置信息 后台
var getLocation=function(long,Lati,type){


    var point = new BMap.Point(long,Lati);
    var gc = new BMap.Geocoder();
    gc.getLocation(point, function(rs){
        var addComp = rs.addressComponents;
        console.log(addComp);
        $('.online').html(addComp.city);
        $('.local').html(addComp.province + "" + addComp.city + "" + addComp.district + "" + addComp.street + "" + addComp.streetNumber);
    });




    var long = long;
    var Lati = Lati;
    var url = $("#url").val();
    $.post(url,{long:long,lati:Lati}, function(data){
            if(data.status == 1){
                //console.log(data);
                $(".online").text(data.info);
		$(".local").text(data.info);
                var str = '';
                $(data.data).each(function(i,o){
                   str += '<option value="'+o.id+'">'+o.online_name+'-'+o.online_zone+'-距'+o.distance+'km</option>'
                });
                $(".s").append(str);
            }
        },
        'json'
    );
};