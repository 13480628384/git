<?php
class IndexAction extends BaseAction
{
	public function index()
	{
		$this->display();
	}
	public function upload_chw(){
		$_POST['imgs'] = urldecode($_POST['imgs']);
		$img=explode(',',$_POST['imgs']);
		print_r($img);
	}
}
?>