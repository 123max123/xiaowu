<?php
namespace app\controller;
class ApiController{
	use \wiphp\Jump;
	public function captcha(){
		return E('captcha')->create();
	}
	public function clear(){
		cache(null);
		$this->_jump('清除缓存成功', 1, 'admin/index');
	}
	//编辑器上传图片
	public function editorupload() {
		header("content-type:text/html;charset=utf-8");
		$exts = array('jpg','jpeg','png');
		$types = array('image/jpeg', 'image/png');
		$uploaded_name = $_FILES['file']['name'];
		$uploaded_ext = substr($uploaded_name, strrpos($uploaded_name, '.') + 1);
		$uploaded_ext = strtolower($uploaded_ext);		
		$uploaded_size = $_FILES['file']['size'];
		$uploaded_type = $_FILES['file']['type'];
		$uploaded_tmp = $_FILES['file']['tmp_name'];	
		$target_path = './public/upload/';
		$target_file = md5(uniqid().$uploaded_name).'.'.$uploaded_ext;
		$temp_file = ((ini_get('upload_tmp_dir') == '') ? (sys_get_temp_dir()) : (ini_get('upload_tmp_dir')));
		$temp_file .= DIRECTORY_SEPARATOR.md5(uniqid().$uploaded_name).'.'.$uploaded_ext;
		$link = 'Your image was not uploaded.';
		if (in_array($uploaded_ext, $exts) && $uploaded_size < 100000 && in_array($uploaded_type, $types) && getimagesize($uploaded_tmp)) {
			if($uploaded_type == 'image/png') {
				$img = imagecreatefrompng($uploaded_tmp);
				imagesavealpha($img, true);
				imagepng($img, $temp_file, 9);
			} else {
				$img = imagecreatefromjpeg($uploaded_tmp);
				imagejpeg($img, $temp_file, 100);
			}
			imagedestroy($img);
			if(rename($temp_file, (getcwd().DIRECTORY_SEPARATOR.$target_path.$target_file))) {
				$port = ($_SERVER["SERVER_PORT"] != '80')? ':'.$_SERVER["SERVER_PORT"] : '';
				$link = 'http://'.$_SERVER['SERVER_NAME'].$port.__ROOT__.'/public/upload/'.$target_file;
			} else {
				$link = 'Your image was not uploaded.';
			}
			if (file_exists($temp_file)) @unlink($temp_file);
		}
		exit($link);
	}
}