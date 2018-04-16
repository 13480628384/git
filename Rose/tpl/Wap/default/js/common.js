/*============post发送数据 [[==========*/
function post(URL, PARAMS) {
    var temp = document.createElement("form");
    temp.action = URL;
    temp.method = "post";
    temp.style.display = "none";
    for (var x in PARAMS) {
        var opt = document.createElement("textarea");
        opt.name = x;
        opt.value = PARAMS[x];
        // alert(opt.name)
        temp.appendChild(opt);
    }
    document.body.appendChild(temp);
    temp.submit();
    return temp;
}
/*============post发送数据 ]]==========*/
/*============用JS获取地址栏参数的方法 [[==========*/
function GetQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
/*============用JS获取地址栏参数的方法 ]]==========*/

var Public = {
    /**
     *   demo: Public.ajax({type:'post',url:'url',data:{a:'1',b:'2'},callback:function(data){}})
     */

    ajax:function(options){
        var self   = this;
        var result = null;
        if(!options.hasOwnProperty('type')){
            options.type = 'post';
        }
        if(!options.hasOwnProperty('url')){
            options.url = '';
        }
        if(!options.hasOwnProperty('data')){
            options.data = {};
        }
        if(!options.hasOwnProperty('callback')){
            options.callback = function(){
            };
        }
        if(!options.hasOwnProperty('async')){
            options.async = true;
        }
        $.ajax({
            type    :options.type,
            url     :options.url,
            data    :options.data,
            dataType:'json',
            async   :options.async,
            success :function(r){
                options.callback(r);
                result = r;
            },
            error   :function(){
            }
        });
        return result;
    },
    /**
     *   ==============================COOKIE================================
     *   ==============================COOKIE================================
     */

    CookieClass:function(){
        var self = this;
        /**
         * 设定Cookie
         * @param name 添加Cookie的名称
         * @param value 添加Cookie的值
         * @param expiresHours 添加Cookie的过期时间(单位：小时)
         * @param path 添加Cookie的域
         */
        this.setCookie = function(name, value, expiresHours, path){
            if(arguments.length == 1){
                Quasar._setError(-1, 11, '函数缺少必要参数', 'CookieClass/setCookie()');
                return false;
            }
            if(arguments.length == 2) expiresHours = 0;
            if(arguments.length == 3) path = '/';
            var cookieString = name+"="+encodeURI(value);
            // 判断是否设置过期时间
            if(expiresHours>0){
                var date = new Date();
                date.setTime(date.getTime()+expiresHours*3600*1000);
                cookieString = cookieString+"; expires="+date.toUTCString()+"; path="+path;
            }
            document.cookie = cookieString;
        };
        //noinspection JSUnusedGlobalSymbols
        /**
         * 获取Cookie
         * @param name 获取Cookie的名称
         *
         * @returns string|null|boolean 返回Cookie的值，无对应name的Cookie则返回null
         */
        this.getCookie = function(name){
            if(arguments.length<=0){
                Quasar._setError(-1, 11, '函数缺少必要参数', 'CookieClass/getCookie()');
                return false;
            }
            var strCookie = document.cookie;
            var arrCookie = strCookie.split("; ");
            for(var i = 0; i<arrCookie.length; i++){
                var arr = arrCookie[i].split("=");
                if(arr[0] == name) return decodeURI(arr[1]);
            }
            return null;
        };
        //noinspection JSUnusedGlobalSymbols
        /**
         * 删除Cookie
         * @param name 删除Cookie的名称
         */
        this.delCookie = function(name){
            if(arguments.length<=0){
                Quasar._setError(-1, 11, '函数缺少必要参数', 'CookieClass/delCookie()');
                return false;
            }
            var date = new Date();
            date.setTime(date.getTime()-10000);
            document.cookie = name+"=''; expires="+date.toUTCString();
        };
    },
    /*
     *   ==============================Array================================
     *
     *   ==============================Array================================
     */
    ArrayClass :function(){
        // 数组去重
        this.ArrayNoRepeat = function(arr){
            var res  = [];
            var json = {};
            for(var i = 0; i<arr.length; i++){
                if(!json[arr[i]]){
                    res.push(arr[i]);
                    json[arr[i]] = 1;
                }
            }
            return res;
        }
    },
    /*
     *   ==============================RegExp================================
     *   RegExp
     *   正则判断字符串是否邮箱、手机号码、电话、传真、汉字、数字、特殊字符
     *   ==============================RegExp================================
     */
    RegExpClass:function(){

        //验证字符串是否为email
        this.isEmail     = function(str){
            var emailReg = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*\.[\w-]+$/i;
            return emailReg.test(str);
        };
        //验证字符串是否为手机号码
        this.isMobile    = function(str){
            var patrn = /^((13[0-9])|(15[0-35-9])|(18[0,2,3,5-9]))\d{8}$/;
            return patrn.test(str);
        };
        //验证字符串是否为电话或者传真
        this.isTel       = function(str){
            var patrn = /^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/;
            return patrn.test(str);
        };
        //验证字符串是否为汉字
        this.isCN        = function(str){
            var p = /^[\u4e00-\u9fa5\w]+$/;
            return p.test(str);
        };
        //验证字符串是否为数字
        this.isNum       = function(str){
            var p = /^\d+$/;
            return p.test(str);
        };
        //验证字符串是否含有特殊字符
        this.isUnSymbols = function(str){
            var p = /^[\u4e00-\u9fa5\w \.,()，ê?。¡ê（ê¡§）ê?]+$/;
            return p.test(str);
        };
    },
}
//选择日期
function scrollDate(opt,callback){
    $(opt.obj).mobiscroll().date({
        theme: 'ios',     // Specify theme like: theme: 'ios' or omit setting to use default
        mode: 'Scroller',       // Specify scroller mode like: mode: 'mixed' or omit setting to use default
        display: 'bottom', // Specify display mode like: display: 'bottom' or omit setting to use default
        lang: "zh",       // Specify language like: lang: 'pl' or omit settring to use default
        onSelect: function (valueText, inst) {
            function _setVal(obj,date){
                $(obj).mobiscroll("setVal",date)
            }
            if(typeof callback === "function"){
                callback.call(this,{
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
function scrollDateAction (obj,callback) {
    scrollDate({
        "obj":obj,
        "minDate": new Date(2010,0,1,1),
        "maxDate" : new Date(2020,0,1,1)
    },function(data){
        var date = data.valueText;
        $(this).text(date);
        $(this).next().val(date);
        typeof callback === "function"?
            callback():
            null;
    })
}