<?php
/**
 * 验证码类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-27
 */ 
namespace extend;
class Captcha {
	//生成图片
	public function create($len = 4, $mode = 0, $type = 'png', $width = 55, $height = 28, $verifyName = 'captcha') {		
		$randval = $this->randStr($len, $mode);
		session($verifyName, md5(strtolower($randval)));
		$width = ($len * 10 + 10) > $width ? $len * 10 + 10 : $width;		
		$im = imagecreatetruecolor($width, $height);
        $r = array(225, 255, 255, 223);
        $g = array(225, 236, 237, 255);
        $b = array(225, 236, 166, 125);	        	
        $key = mt_rand(0, 3);
        $backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
        imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        $stringColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        if ($mode == 0) {
            for ($i = 0; $i < 10; $i++) {
                imagearc($im, mt_rand(-10, $width), mt_rand(-10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $stringColor);
            }
        }
        for ($i = 0; $i < 25; $i++) {
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $stringColor);
        }
        for ($i = 0; $i < $len; $i++) {
            imagestring($im, 5, $i * 10 + 5, mt_rand(1, 8), $randval{$i}, $stringColor);
        }
		$this->output($im, $type); 		
	}
	//验证
	public function valid_captcha($captcha, $verifyName = 'captcha') {		
		if ($captcha && session($verifyName)) {
			return (md5($captcha) == session($verifyName));
		}
		return false;
	}	
	private function randStr($len = 4, $mode = 0){
		if ($mode == 0) {
            $chars = str_repeat('0123456789', 3);			
		} else {
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';		
		}		
		$string = '';
		while (strlen($string) < $len) {
			$string .= substr($chars, (rand() % strlen($chars)), 1);
		}
		return $string;
	}
    private function output($im, $type='png', $filename='') {
        header("Content-type: image/" . $type);
        $ImageFun = 'image' . $type;
        if (empty($filename)) {
            $ImageFun($im);
        } else {
            $ImageFun($im, $filename);
        }
        imagedestroy($im);
    }	
}