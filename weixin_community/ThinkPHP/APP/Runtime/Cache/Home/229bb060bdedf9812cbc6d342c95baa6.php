<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/frozen.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Home/css/mobi.css" />
  </head>

  <body>
    <div class="container">
      <form class="form-signin-login">
        <h2 class="form-signin-heading" style="font-size: 20px; text-align: center;margin-bottom: 40px">登录</h2>
        <input type="text" id="inputEmail" class="form-control username login-username-scan" placeholder="用户名" style="    margin-bottom: 5px;">
        <input type="password" id="inputPassword" class="form-control password login-password-scan" placeholder="密码" required>
        <div class="checkbox">
          
        </div>
        <button class="btn btn-lg btn-primary btn-block login-login-scan" type="button">登录</button>
      </form>
    </div>
    <script type="text/javascript" src="__PUBLIC__/Home/js/zepto.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Home/js/frozen.js"></script>
  </body>
  <script>
    Zepto(function($){
        /*
   *
   *   登录
   *
  */
  $('.login-login-scan').tap(function(){
      var username  = $('.username').val();
      var password  = $('.password').val();
      if(!username){
        $.dialog({
                content:'请输入姓名',
                button:['我知道了']
        });
        return false;
      }
      if(!password){
        $.dialog({
                content:'请输入密码',
                button:['我知道了']
        });
        return false;
      }
      var user_type = 3;
      var el=$.loading({
          content:'正在登录'
      });
      var login_data = {login_name:username,password:password,user_type:user_type,weixin_no:123};
      $.post("<?php echo U('check_login');?>",login_data,function(data){
      if(data.msg == 1){
          window.location.href="<?php echo U('Index/index');?>";
      }else{
        $.dialog({
                content:'用户名或密码错误',
                button:['我知道了']
        });
      }
      el.hide();
      },'json');
    });
    });
  </script>
</html>