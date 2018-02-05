/**
 * Created by 1195 on 2016-7-19.
 */
$(function(){
	PLACEORDER_NEW.bindEvent();  //绑定事件
});
var PLACEORDER_NEW = {
	//事件绑定
	bindEvent             :function(){
		var self = this;
		//点赞事件
		$('.car_stock').on('touchend', '.reduce', function(){

		});
	},
	/* ajax 请求 返回数据 */
	post                  :function(url, data){
		var self = this;
		var result = null;
		$.ajax({
			type      :'post',
			url       :url,
			data      :data,
			dataType  :'json',
			async     :false,
			beforeSend:function(){
				//未完成加载
				$('#loading').show();
			},
			success   :function(r){
				result = r;
			},
			complete  :function(){
				//完成加载
				$('#loading').hide();
			},
			error     :function(){
				console.log(url+'网络错误');
				var txt = url+'网络错误';
				self.promptTips(txt);
			}
		});
		return result;
	}
}


