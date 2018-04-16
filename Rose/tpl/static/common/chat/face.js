/**
 * Created by 星辉 on 2015/7/9.
 */

/*
 * 表情包
 * */
define(function(require, exports, module){
    var base = "/common/img/faceLib/";
    var face = {
        shaShiDi : [
            {img: base +"shaShiDi/1.png",name : "大笑"},
            {img: base +"shaShiDi/2.png",name : "可爱"},
            {img: base +"shaShiDi/3.png",name : "害羞"},
            {img: base +"shaShiDi/4.png",name : "惊呆"},
            {img: base +"shaShiDi/5.png",name : "流泪"},
            {img: base +"shaShiDi/6.png",name : "快哭了"},
            {img: base +"shaShiDi/7.png",name : "给力"},
            {img: base +"shaShiDi/8.png",name : "点赞"},
            {img: base +"shaShiDi/9.png",name : "礼物"},
            {img: base +"shaShiDi/10.png",name : "大爱"},
            {img: base +"shaShiDi/11.png",name : "偷笑"}

        ]
    };

    exports.getFace = function(name){
        return face[name]
    };
});