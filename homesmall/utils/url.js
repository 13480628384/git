var host_url = 'https://m.homewaps.com/public/index/';
//apid.chw.com  www
//用户注册
function resigter(){
    var url=host_url+"XApi/resigter";
    return url; 
}
//用户登录
function login(){
    var url = host_url+"Login/login";
    return url; 
}
//检查用户信息是否一致
function checksession(){
    var url = host_url+"Login/checksession";
    return url;
}
//用户中心
function personal(){
    var     url=host_url+"Personal/index";
    return url; 
}
//获取登录session_key，openid
function get_login(){
    var     url=host_url+"Login/index";
    return url; 
}
//login登录
function weixin_login(){
    wx.login({
      success: function(res) {
        if (res.code) {
          //发起网络请求
          wx.request({
            url: get_login(),
            data: {
              code: res.code
            },
            success:function(res){
              wx.setStorage({
                    key: 'session_key',
                    data: res.data,
                    success: function(res) {
                      console.log(res);
                    }
                  })
            }
          });
        } else {
          console.log('获取用户登录态失败！' + res.errMsg)
        }
      }
    });
}
//检查手机号码登录id
function check_session_id(){
   return host_url+'Login/check_session_id';
}
//提交日记记录
function submit_news(){
   return host_url+'xiao/submit_news';
}
//上传本地图片到服务器
function upload(){
  return host_url +'xiao/upload_image';
}
//首页获取商品列表
function Community_home_list(){
  return host_url +'xiao/Community_home_list';
}
//我的订单
function my_customr(){
  var url = host_url +"xiao/customer";
    return url;
}
//我的订单详情
function details() {
  var url = host_url + "xiao/details";
  return url;
}
//预定详情
function yuding(){
  return host_url+ 'xiao/yuding';
}
//预定
function reserve(){
  return host_url+'xiao/reserve';
}
//订单提交
function reser_submit(){
  return host_url + 'xiao/reser_submit';
}
//暴露窗口
module.exports = {
  resigter: resigter,
  personal:personal,
  get_login:get_login,
  login:login,
  weixin_login:weixin_login,
  checksession:checksession,
  check_session_id:check_session_id,
  submit_news:submit_news,
  details: details,
  Community_home_list:Community_home_list,
  my_customr: my_customr,
  yuding:yuding,
  reserve:reserve,
  reser_submit:reser_submit
}