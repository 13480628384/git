<?php
class MsgModel extends Model{
	protected $_validate = array(
			array('name','require','必须有名称'),
			array('content','require','必须有内容'),
		);
}