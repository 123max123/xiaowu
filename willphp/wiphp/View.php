<?php
/**
 * 框架视图类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */ 
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class View {
	private static $vars = [];
	private function __construct() {}
	private function __clone() {}
	public static function assign($name, $value = null) {
		if ($name != '') self::$vars[$name] = $value;
	} 
	private static function getViewFile($file = '') {
		$path = __MODULE__;
		if ($file == '') {
			$file = __ACTION__;
		} elseif (strpos($file, ':')) {
			list($path, $file) = explode(':', $file);
		} elseif (strpos($file, '/')) {
			$path = '';
		}
		$path = strtolower($path);
		$pfile = ltrim($path.'/'.$file, '/').'.html';		
		if (!THEME_ON) {
			$vfile = PATH_VIEW.'/'.$pfile;
		} else {
			$vfile = PATH_VIEW.'/'.__THEME__.'/'.$pfile;
			if (file_exists($vfile)) {
				$vfile = PATH_VIEW.'/default/'.$pfile;
			}
		}
		return $vfile;			
	}	
	public static function fetch($file = '', $vars = []) {
		if (!empty($vars)) self::$vars = array_merge(self::$vars, $vars);			
		$viewfile = self::getViewFile($file);
		if (file_exists($viewfile)) {
			array_walk_recursive(self::$vars, 'self::parseVars'); //处理输出
			define('__RUNTIME__', round((microtime(true) - START_TIME) , 4));	
			Template::render($viewfile, self::$vars);
		} else {
			App::halt($file.' 模板文件不存在。');
		}
	}	
	//删除反斜杠
	private static function parseVars(&$value, $key) {
		$value = stripslashes($value);
	}
}