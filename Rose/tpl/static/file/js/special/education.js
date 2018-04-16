/* 
* @Author: Administrator
* @Date:   2015-09-17 16:51:13
<<<<<<< .mine
* @Last Modified by:   wapwei
* @Last Modified time: 2015-10-15 18:44:24
=======
* @Last Modified by:   Administrator
* @Last Modified time: 2015-09-29 12:17:31
>>>>>>> .r6618
*/

$(function(){
	var header = $(".header").height();
	var arrow = $(".header-name").height()
	$(".header-name").css('bottom',-arrow-10);
	//添加试题
	$(".add").click(function(){
		var letter = $(".letter:last").html();
		var ml5 = $(".ml5:last").html();
		var ml5str2 = ml5.charCodeAt();
		var ml5str3 = String.fromCharCode(ml5str2+1);
		var str2 = letter.charCodeAt();
		var  str3 = String.fromCharCode(str2+1);
		$(".test-right-main").append('<div class="test-right-content"><span class="letter">'+str3+'</span><div class="test-right-content-input"><input type="text" placeholder="请填写内容"></div></div>')
		$(".table").append('<div class="check-tab"><div class="course-tab-ul-li-check1 ml10 mt3 icon-uniE6F8"></div><span class="ml5">'+ml5str3+'</span></div>')
	})
	$(".del").click(function(){
		$(".test-right-content:last").remove();
		$(".check-tab:last").remove();
	})
	//下拉菜单
	$(".classify").click(function(){
		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$(this).next('.classify-content').removeClass('selector')
			$(this).find("span:first").removeClass('icon-triangle-down');
			$(this).find("span:first").addClass('icon-triangle-right')
		}else{
			$(this).addClass('on');
			$(this).find("span:first").removeClass('icon-triangle-right');
			$(this).next('.classify-content').addClass('selector')
			$(this).find("span:first").addClass('icon-triangle-down')
		}
	})
	//弹出框
	$(".btn-student").click(function(){
		$(".Mask").addClass('is-visible');
	})
	$(".cancel").click(function(){
		$(".Mask").removeClass('is-visible')
	})
	$(".btn-schedule").click(function(){
		$("#curriculum").addClass('is-visible')
	})
	$(".btn-capacity").click(function(){
		$("#course").addClass('is-visible')
	})
	//全选
	$("#Ten").click(function(){
		if($(this).hasClass('on')){
			$(".Ten").removeClass('on');
			$(".check").removeClass('on')
			$(this).removeClass('on')
		}else{
			$(".Ten").addClass('on');
			$(this).addClass('on')
			$(".check").addClass('on')
		}
	})
	//下拉框
	$(".circle").click(function(){
		$(this).parents(".topic-main").find(".examination").slideToggle('slow')
	})

	$(".test-del").click(function(){
		$(".course-tab-ul-li-check.ml10.check-li.icon-uniE6F8").parents(".topic-main").remove();
	})
	//单选
	$(".checkAll").click(function(){
		if($(this).hasClass('on')){
			$(".check-li").removeClass('icon-uniE6F8');
			$(this).removeClass('on')
		}else{
			$(".check-li").addClass('icon-uniE6F8');
			$(this).addClass('on')
		}
	})
	//tab键
	$(".course-tab-ul-li1").click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	})
	$(".classify-tab").click(function(){
		$(this).addClass('on').siblings().removeClass('on')
	})
	//删除
	$(".minus3").click(function(){
		$(this).parents(".topic-main").remove();
	})
	$(document).on('click','.check-tab',function(){
		$(this).addClass('on').siblings().removeClass('on');
	})
	$("#tai").click(function(){
		$(this).next(".supervise-input-down").addClass('block')
	})
	$(".down-li").click(function(){
		var text = $(this).html();
		$(".tai").html(text);
		$(this).parents(".supervise-input-down").removeClass('block');
	})
	$(".header-user-name").click(function(event) {
		$(".header-name").addClass('on');
	})
	$(".header-name-ul-li").click(function(){
		var text = $(this).html();
		$(".name-header").html(text);
		$(".header-name").removeClass('on');
	})
	$(".left-ul-li").click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	})
	$(".student-bottom-center-ul-li").click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	})
	// $(".left").height($(window).height()-header);
	//表格右边线
	$(".tab-title-top:last-child").css("border-right","0");
	$(".tab-title-top1:last-child").css("border-right","0");
	$(".tab-title-content:last-child").css("border-bottom","0");
	$(".top-center:last-child").css("border-right","0")
	$(".top-conter:nth-child(5n)").css("border-right","0");
	$(".col-md-4.top-center1:nth-child(3n)").css("border-right","0");
	$(".col-md-4.top-conter1:nth-child(3n)").css("border-right","0");
	$(".tab-title-top1:nth-child(5)").css("line-height","30px");
	// 添加学员
	$(".check").click(function(){
		var name =$(this).parents(".user-main").find(".user-title").find("#name").html();
		var time = $(this).parents(".user-main").find(".user-title").find(".time").html();
		var class_id = $(this).parents(".user-main").find(".user-title").find('#class_id').val();
		var img = $(this).parents(".user-main").find(".management-img ").find("img").attr("src");
		var data = $(this).parents(".user-main").attr("id");
		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$('#f_' + data).remove();
		}else{
			$(this).addClass('on');
			$(".management-user").append('<div class="management-user-main as" id=f_'+data+'><p class="management-img"><img src='+img+'><span class="icon-minus3 minus"></span></p><input type="hidden" id="classid" value='+class_id+' dataid='+class_id+'><p>'+name+'</p><p class="time">'+time+'</p></div>');
		}
		$(".minus").click(function(){
			$(this).parents('#f_' + data).remove();
			$('#'+data).find('.check').removeClass('on');
		})
	})
	$("#condition").click(function(){
		$(".supervise-input-down").toggleClass('block');
	})
	$(document).on('click','.down-li-bootom',function(){
		$(this).parents(".supervise-input-down").removeClass('block');
		$("[name=condition]").val($(this).html());
	})
	$(".course-tab-ul-li-check").click(function(){
		if($(this).parents('.noclass').length){
			return;
		}
		$(this).toggleClass('icon-uniE6F8');
	})
	$(".minus").click(function(){
		$(this).parents('.management-user-main').remove(); 
	})
	//注册
	/*
	$(".btn-student").click(function(){
		var teacher = $("[name=teacher]").val();
		var student = $("[name=student]").val();
		var time = $("[name=time]").val();
		var tel = $("[name=tel]").val();
		var condition = $("[name=condition]").val();
		if(teacher==''){
			msg.alert("老师学号不能为空");
			return false;
		}
		if(student==''){
			msg.alert("学生学号不能为空");
			return false;
		}
		if(time==''){
			msg.alert("入学时间不能为空");
			return false;
		}
		if(tel==''){
			msg.alert("手机号码不能为空")
			return false;
		}else{
			if(tel.length!=11){
				msg.alert("手机格式错误,请输入正确的格式");
				return false;
			}
		}
		if(condition==''){
			msg.alert("请填写状态");
		}
		  $(".btn-student").html("确认中.....");
		  // setTimeout("#'", 3000);
	})*/
})