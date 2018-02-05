/*
 *
 *	设置cookie
 *
*/
function setCookie (name, value)
{
    var expdate = new Date();   //初始化时间
    expdate.setTime(expdate.getTime() + 240 * 60 * 1000);   //时间
    document.cookie = name+"="+value+";expires="+expdate.toGMTString()+";path=/weixinscan";
}
/*
 *
 *	获取cookie
 *
*/
function getCookie(c_name)
{
	if (document.cookie.length>0)
	  {
	  c_start=document.cookie.indexOf(c_name + "=")
	  if (c_start!=-1)
	    { 
	    c_start=c_start + c_name.length+1 
	    c_end=document.cookie.indexOf(";",c_start)
	    if (c_end==-1) c_end=document.cookie.length
	    return unescape(document.cookie.substring(c_start,c_end))
	    } 
	  }
	return ""
}
/*
 *
 *	删除cookie
 *
*/
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
var REG = {
    paper: /^[a-zA-Z]{4}[a-zA-Z0-9]*$/,
    num: /^[0-9]{10,25}\d*$/,
    passwd:/^[0-9]{6,8}$/,
    id:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/,
}
function geturl(str){
  var n = str.lastIndexOf("/");
  return str.substring(n+1);
}
$(document).ready(function(){
	var changeUrl=location.pathname;	
	var relast=geturl(changeUrl).replace(".php","");
    $("#"+relast).attr("class","current");
});


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
	    var login_url = url+'login_in_server';
	    var el=$.loading({
	        content:'正在登录'
	    });
	    var login_data = {login_name:username,password:password,user_type:user_type,weixin_no:123};
	    $.post(login_url,login_data,function(data){
	    if(data.result_code == 1){
	    		setCookie('username',JSON.parse(data.datas).login_name);
	    		setCookie('user_id',JSON.parse(data.datas).id);
	    		window.location.href="index.php";
	    }else{
	    	$.dialog({
		            content:'用户名或密码错误',
		            button:['我知道了']
		    });
	    }
	    el.hide();
	    },'json');
    });
 	var customer_name=$('#customer_name');
	var customer_phone=$('#customer_phone');
	var customer_project=$('#customer_project');
	var customer_mark=$('#customer_mark');
	var J_submitCustomer=$('#J_submitCustomer');
	var chooseProject=$('#J_chooseProject');
	var dialogChooseProject=$('.dialog-choose-project');
	var J_submitProjectChoose=$('#J_submitProjectChoose');
	var J_cancelProjectChoose=$('#J_cancelProjectChoose');
	var accountSubmitFixed=$('.account-submit-fixed');

	//项目选择
	chooseProject.tap(function(){
		dialogChooseProject.removeClass('fadeOutDown').show();
		setTimeout(function(){
			accountSubmitFixed.show();
		},600);
		$('html,body').css('overflow','hidden');
	});
	//关闭
	J_cancelProjectChoose.tap(function(){
		dialogChooseProject.addClass('fadeOutDown').hide();
		accountSubmitFixed.hide();
		$('html,body').css('overflow','auto');
	});

	var dcpuiform=$('.dcp-main .ui-form');
	var dcpformitem=$('.dcp-main .ui-form-item');
	dcpformitem.tap(function(){

		var input=$(this).find('input');
       //alert(input);
       // $("#customer_project").val(input.val());
		if(input.prop('checked')){
			//return false;
			input.prop('checked',false);
		}else{
			var checked=dcpuiform.find(':checked');
			for(var i=0;i<checked.length;i++){
            	checked[i].checked = false;
        	}
			input.prop('checked',true);
		}
	});
	//确定选择
	J_submitProjectChoose.tap(function(){
		var checked=dcpuiform.find(':checked');
		var arr=[];
		checked.each(function(){
			arr.push($(this).val());
		});
		customer_project.val(arr);
		J_cancelProjectChoose.trigger('tap');
	});
	/*
		*添加群组信息
		*
	*/
	var add_name = $('#add_name');
	add_name.tap(function(){
		var add_url = url+"add_device_group_info";
		var user_id = getCookie('user_id');
		var group_name = $.trim($('.group_name').val());
		if(!group_name){
			$.dialog({
		            content:'请填写群组名称',
		            button:['我知道了']
		    });
		    return false;
		}
		var el=$.loading({
	        content:'正在提交'
	    });
	    $.post(add_url,{user_id:user_id,device_group_name:group_name},function(reg){
		    if(reg.result_code==1){
	    		var DG=$.dialog({
		            content:'恭喜您，提交成功！',
		            button:['我知道了']
			    });
			    DG.on('dialog:action',function(e){
			        document.location.href="group.php";
		        });
		    }else{
	    		$.dialog({
		            content:'网络错误，请重试',
		            button:['我知道了']
		        });
		    }
	    el.hide();
	    },'json');
	});
	/*
	*群组名称修改
	*
	*/
	var group_update = $('#group_update');
	var update_url = url+"update_device_group_info";
	var user_id = getCookie('user_id');
	group_update.tap(function(){
	    var group_id = $('.group_id').val();
		var group_name = $.trim($('.group_name').val());
		if(!group_name){
			$.dialog({
		            content:'请填写群组名称',
		            button:['我知道了']
		    });
		    return false;
		}
		var el=$.loading({
	        content:'正在提交'
	    });
	    $.post(update_url,{user_id:user_id,device_group_id:group_id,device_group_name:group_name},function(reg){
		    if(reg.result_code==1){
	    		var DG=$.dialog({
		            content:'恭喜您，修改成功！',
		            button:['我知道了']
			    });
			    DG.on('dialog:action',function(e){
			        document.location.href="group.php";
		        });
		    }else{
	    		$.dialog({
		            content:'网络错误，请重试',
		            button:['我知道了']
		        });
		    }
	    el.hide();
	    },'json');
	});
	/*
	*  更改群组内信息
	*/
	var save_update = $('#save_update');
	save_update.tap(function(){
		var group_id    = $('#device_group_id').val();//原组名的id
		var group_code  = $('#group_code').val();//组内名称
		var status      = $('.status').val();//是否有效
		var ords      = $('#ords').val();//排序
		var device_command  = $('#device_command').val();//排序
		var user_id = getCookie('user_id');//用户id
		//alert(group_id+','+group_code+','+status+','+ords+','+device_command);
		var DATA = {
			user_id : user_id,
			group_id : group_id,
			group_code : group_code,
			status : status,
			device_command : device_command,
			ords:ords
		}
		if(group_code==''){
			$.dialog({
		            content:'请输入名称',
		            button:['我知道了']
		    });
		    return false;
		}
		if(group_code.length>10){
			$.dialog({
		            content:'名称不能超出10个字哦',
		            button:['我知道了']
		    });
		    return false;
		}
		if(!ords){
			$.dialog({
		            content:'请输入排序号',
		            button:['我知道了']
		    });
		    return false;
		}
		if(ords.length>5){
			$.dialog({
		            content:'排序号不能超出5位哦',
		            button:['我知道了']
		    });
		    return false;
		}
		var el=$.loading({
	        content:'正在保存'
	    });
	    var SAVEURL = url+"update_device_group";
	    $.post(SAVEURL,DATA,function(data){
	    	if(data.result_code==1){
	    		var DG=$.dialog({
		            content:'恭喜您，保存成功！',
		            button:['我知道了']
			    });
			    DG.on('dialog:action',function(e){
			        document.location.href="group.php";
		        });
		    }else{
	    		$.dialog({
		            content:'网络错误，请重试',
		            button:['我知道了']
		        });
		    }
	    	el.hide();
	    },'json');
	})
});