<?php
/**
 * 配置处理
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Config {
	public static $name = 'config';
	private static $config = []; 	
	private function __construct() {}
	private function __clone() {}	
	//配置初始化
	public static function init() {			
		if (!is_dir(PATH_CACHE)) mkdir(PATH_CACHE, 0777, true);
		if (!is_dir(PATH_LOG)) mkdir(PATH_LOG, 0777, true);
		if (!is_dir(PATH_VIEWC)) mkdir(PATH_VIEWC, 0777, true);
		if (!is_dir(PATH_SHTML)) mkdir(PATH_SHTML, 0777, true);	
		$config = Cache::get(self::$name);
		if (!$config || APP_DEBUG) {
			$files = ['app', 'database', 'filter', 'route', 'site'];
			foreach ($files as $fileName) {
				$config[$fileName] = self::load($fileName);
			}
			Cache::set(self::$name, $config, 0);
		}
		self::$config = $config;		
		$theme = isset($config['site']['theme'])? $config['site']['theme'] : 'default';		
		define('__THEME__', $theme);
		define('THEME_PATH', (THEME_ON)? PATH_VIEW.'/'.$theme : PATH_VIEW);	
	}	
	//加载配置文件
	public static function load($fileName) {
		$files = [WPHP_URI.'/config/'.$fileName.'.php', APP_PATH.'/config/'.$fileName.'.php'];
		$config = [];
		foreach ($files as $file) {
			if (file_exists($file)) {
				$temp = require $file;
				if (is_array($temp)) {
					$config = array_merge($config, $temp);
				}
			}
		}
		return $config;
	}
	//清除配置缓存
	public static function clear() {
		Cache::del(self::$name);
	}
	//获取配置
	public static function get($name = '', $type = 'site') {		
		if ($name == '') {
			return isset(self::$config[$type])? self::$config[$type] : '';
		}
		return isset(self::$config[$type][$name])? stripslashes(self::$config[$type][$name]) : '';
	}
	//设置配置
	public static function set($name, $val = '', $type = 'site') {			
		self::$config[$type][$name] = $val;		
		return $val;
	}
	//删除配置
	public static function del($name, $type = 'site') {
		if (isset(self::$config[$type][$name])) unset(self::$config[$type][$name]);
		return '';
	}	
}