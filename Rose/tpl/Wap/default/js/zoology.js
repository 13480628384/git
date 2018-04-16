$(".giveroseli1").click(function() {
		$(".giveroseli1 img").attr("src","./tpl/Wap/default/img/st6.png");
		$(".giveroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".giveroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".giveroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
		// 清零
		$(".giverose_input").val(0);

});

$(".giveroseli2").click(function() {
		$(".giveroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".giveroseli2 img").attr("src","./tpl/Wap/default/img/st7.png");
		$(".giveroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".giveroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".giverose_input").val();
		//自加10
		if(oldValue==''){
			oldValue=0;
		}
        oldValue=parseInt(oldValue)+10;
        //将增加后的值付给原控件
        $(".giverose_input").val(oldValue);


});

$(".giveroseli3").click(function() {
		$(".giveroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".giveroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".giveroseli3 img").attr("src","./tpl/Wap/default/img/st8.png");
		$(".giveroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".giverose_input").val();
		//自加10
		if(oldValue==''){
			oldValue=0;
		}
		oldValue=parseInt(oldValue)+100;
        $(".giverose_input").val(oldValue);

});

$(".giveroseli4").click(function() {
		$(".giveroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".giveroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".giveroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".giveroseli4 img").attr("src","./tpl/Wap/default/img/st9.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".giverose_input").val();
		//自加10
		if(oldValue==''){
			oldValue=0;
		}
		oldValue=parseInt(oldValue)+1000;
        //将增加后的值付给原控件
        $(".giverose_input").val(oldValue);
});

$(".buyroseli1").click(function() {
		$(".buyroseli1 img").attr("src","./tpl/Wap/default/img/st6.png");
		$(".buyroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".buyroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".buyroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
			// 清零
		$(".buyrose_input").val(0);
});

$(".buyroseli2").click(function() {
		$(".buyroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".buyroseli2 img").attr("src","./tpl/Wap/default/img/st7.png");
		$(".buyroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".buyroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".buyrose_input").val();
		//自加10

		if(oldValue==''){
			oldValue=0;
		}
		oldValue=parseInt(oldValue)+10;
        //将增加后的值付给原控件
        $(".buyrose_input").val(oldValue);
});

$(".buyroseli3").click(function() {
		$(".buyroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".buyroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".buyroseli3 img").attr("src","./tpl/Wap/default/img/st8.png");
		$(".buyroseli4 img").attr("src","./tpl/Wap/default/img/st4.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".buyrose_input").val();
		if(oldValue==''){
			oldValue=0;
		}
		oldValue=parseInt(oldValue)+100;
        //将增加后的值付给原控件
        $(".buyrose_input").val(oldValue);
});

$(".buyroseli4").click(function() {
		$(".buyroseli1 img").attr("src","./tpl/Wap/default/img/st1.png");
		$(".buyroseli2 img").attr("src","./tpl/Wap/default/img/st2.png");
		$(".buyroseli3 img").attr("src","./tpl/Wap/default/img/st3.png");
		$(".buyroseli4 img").attr("src","./tpl/Wap/default/img/st9.png");
		//取出现在的值，并使用parseInt转为int类型数据
		var oldValue=$(".buyrose_input").val();
		//自加10
		if(oldValue==''){
			oldValue=0;
		}
		oldValue=parseInt(oldValue)+1000;
        //将增加后的值付给原控件
        $(".buyrose_input").val(oldValue);
});