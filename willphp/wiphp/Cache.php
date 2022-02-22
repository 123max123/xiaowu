<?php
/**
 * 缓存处理
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Cache {
	private function __construct() {}
	private function __clone() {}	
	public static function set($name, $data, $_time = 0) {
		if (empty($data)) return false;
		$ntime = time();
		$expire = ($_time == 0)? 0 : $ntime + $_time;
		$cache = ['contents' => $data, 'expire' => $expire, 'mtime' => $ntime];
		$fname = self::getFileName($name);
		return self::cacheData($cache, $fname);
	}
	public static function get($name) {
		$fname = self::getFileName($name);
		if(!is_file($fname)) return false;		
		if (CACHE_TYPE == 1) {
			$data = include $fname;
		} else {
			$data = unserialize(file_get_contents($fname));
		}
		$ntime = time();
		if($data['expire'] == 0 || $ntime < $data['expire']) {
			return $data['contents'];
		}
		return false;
	}
	public static function del($name) {
		$fname = self::getFileName($name);
		if(is_file($fname)){
			return unlink($fname);
		}
		return false;
	}
	public static function clear() {
		$glob = @glob(PATH_CACHE.'/cache--'.APP_NAME.'-*');
		if(empty($glob)) return false;
		foreach ($glob as $file){
			if (is_file($file)) unlink($file);
		}
		self::clearShtml(); //and clearShtml
		return true;
	}
	public static function delShtml($hash) {
		$sfile = PATH_SHTML.'/'.$hash.'.shtml';
		if(is_file($sfile)){
			return unlink($sfile);
		}	
		return false;
	}
	public static function clearShtml() {
		$glob = @glob(PATH_SHTML.'/*.shtml');
		if(empty($glob)) return false;
		foreach ($glob as $file){
			if (is_file($file)) unlink($file);
		}
		return true;
	}
	private static function getFileName($name) {		
		return PATH_CACHE.'/cache--'.APP_NAME.'-m'.CACHE_TYPE.'_'.$name;
	}
	private static function cacheData($data, $file) {		
		if (CACHE_TYPE == 1) {
			$content = "<?php\nreturn ".var_export($data, true).";";
		} else {
			$content = serialize($data);
		}
		return file_put_contents($file, $content);
	}
}