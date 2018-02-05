<?php
class ProductModel extends Model{
	protected $_validate = array( //自动验证。
		array('title','titleValidate','标题不能为空!',1,'callback'),//回调
		array('content','require','内容不能为空!'), 
		array('add_time',array(1,2),'值的范围不正确！',1,'in'),
	);
	protected $_auto = array ( 
		array('add_time','time',3,'function'),
	);
	protected function titleValidate($data){ //回调函数
		if(trim($data)==''){
			return false;
		}else{
			return true;
		}
	}
}