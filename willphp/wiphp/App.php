<?php
/**
 * 框架主程序类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */ 
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
final class App {
	private static $classMap = ['wiphp'=>PATH_CORE, 'app'=>APP_PATH, 'extend'=>PATH_EXTEND, 'addons'=>PATH_ADDONS]; //自动加载路径配置	
	private static $request = []; //$_GET或$_POST请求参数	
	//开始框架
	public static function start() {
		spl_autoload_register('\wiphp\App::autoload'); //自动加载
		set_error_handler('\wiphp\App::errorHandler'); //错误处理
		set_exception_handler('\wiphp\App::exceptionHandler'); //异常处理			
		if(!is_dir(APP_PATH.'/controller')) {
			Build::run(); //创建应用
		}
		Config::init(); //配置初始化
		self::parseRequest(); //处理请求
		self::dispatch(); //分发请求
		if (APP_DEBUG) Debug::show(); //显示调试信息
	}	
	//自动加载
	public static function autoload($class) {
		$end = strpos($class, '\\');
		if (false !== $end) {
			$map = substr($class, 0, $end);
			if (isset(self::$classMap[$map])) {
				$fname = substr($class, strlen($map)).'.php';
				$file = strtr(self::$classMap[$map].$fname, '\\', '/');
				if (is_readable($file)) include $file;
			}
			if (APP_DEBUG) Debug::log($class, 'inc');
		}
	}
	//错误提示
	public static function halt($info) {
		$html = file_get_contents(WPHP_PATH.'/tpl/halt.tpl');
		$html = str_replace('__INFO__', $info, $html);
		$html = str_replace('__WPHP__', __WPHP__, $html);
		exit($html);
	}
	//错误处理
	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		$info = '['.date('Y-m-d H:i:s').'] file: '.basename($errfile).' line: '.$errline.' Error: '.$errstr;
		if (APP_DEBUG) {
			Debug::log($info, 'info');
		} else {
			$file = PATH_LOG.'/log_'.APP_NAME.'_'.basename($errfile,'.php').'_'.$errline.'_'.date('Ymd').'.log';
			if (!file_exists($file)) file_put_contents($file, $info);
			if (($errno != E_NOTICE) && ($errno < 2048)) self::halt('Website error,Please contact webmaster.');
		}
	}
	//异常处理
	public static function exceptionHandler($error) {
		$errstr = $error->getMessage();
		$errfile = $error->getFile();
		$errline = $error->getLine();
		$info = '['.date('Y-m-d H:i:s').'] file: '.basename($errfile).' line: '.$errline.' Error: '.$errstr;
		if (APP_DEBUG) {
			Debug::log($info, 'info');
			Debug::show();
		} else {
			$file = PATH_LOG.'/log_'.APP_NAME.'_'.basename($errfile,'.php').'_'.$errline.'_'.date('Ymd').'.log';
			if (!file_exists($file)) file_put_contents($file, $info);
			self::halt($errstr);
		}
	}
	//处理请求
	private static function parseRequest() {
		$analysis = Route::parseUrl();		
		$params = $analysis['param'];		
		array_walk_recursive($params, 'self::parseParam');
		self::$request = $params;	
		$route = explode('/', trim($analysis['route'], '/'));		
		define('__MODULE__', $route[0]);
		define('__ACTION__', $route[1]);		
	}
	//处理请求参数
	private static function parseParam(&$value, $key) {
		$filters = Config::get('', 'filter');
		if (!is_numeric($key) && in_array($key, array_keys($filters))) {
			$fn = $filters[$key];
			if (function_exists($fn)) {
				$value = $fn($value);
			}
		}
		if (!get_magic_quotes_gpc() && !is_array($value)) {
			$value = addslashes(trim($value));
		}
	}	
	//get shtml hash
	public static function getViewHash($route = '', $params = []) {
		if ($route == '') {
			$route = __MODULE__.'/'.__ACTION__;
			$params = I();
		}
		$file = U($route, $params);
		$file = str_replace(__URL__, '', $file);
		if ($file == '/') {
			$file = '/index/index'.URL_SUFFIX;
		}
		return md5($file);
	}
	//显示当前 shtml
	private static function showShtml() {	
		$shtml_open = Config::get('shtml_open', 'app');
		if ($shtml_open && __ACTION__ != 'jump') {
			$vhash = self::getViewHash();		
			$sfile = PATH_SHTML.'/'.$vhash.'.shtml';
			$shtml_time = Config::get('shtml_time', 'app');
			$ftimeok = true;
			if ($shtml_time > 0) {
				$ntime = time();
				$ftime = file_exists($sfile)? filemtime($sfile) : 0;
				$ftimeok = $ftime > ($ntime - $shtml_time);
			}
			if (is_file($sfile) && $ftimeok) {				
				include $sfile;			
				if (APP_DEBUG) {
					Debug::log($vhash.'.shtml', 'inc');
					Debug::log('页面缓存 | 已开启(可在config/app.php里设置shtml_open为0关闭)。', 'tip');
					Debug::show();
				}
				exit();
			}
		}
	}
	//分发请求
	private static function dispatch() {	
		self::showShtml(); //显示页面缓存
		$module = ucfirst(__MODULE__);
		$class = strtr('app/controller/'.$module.'Controller', '/', '\\');
		$method = __ACTION__;
		if (!method_exists($class, $method)) {
			$class = 'app\controller\EmptyController';
			$method = 'empty';
		}
		if (!method_exists($class, $method)) self::halt($module.'Controller->'.$method.'() does not exist.');
		$action = new \ReflectionMethod($class, $method);
		$args = [];
		foreach ($action->getParameters() as $arg) {
			$name = $arg->getName();
			$default = $arg->isOptional()? $arg->getDefaultValue() : '';
			$args[] = isset(self::$request[$name])? self::$request[$name] : $default;
		}
		$res = (new $class)->$method(...$args);
		if (is_scalar($res)) {
			echo $res;
		} elseif (is_null($res)) {
			return;
		} else {
			echo json_encode($res, JSON_UNESCAPED_UNICODE);
			exit();
		}
	}
	//根据请求参数获到值
	public static function getRequest($name = '', $default = '', $fns = '') {
		$req = self::$request['req'];
		$val = $default;
		if (empty($name)) {
			$val = $req;
		} elseif (isset($req[$name])) {
			$val = $req[$name];
		}
		if ($fns && !empty($val)) {
			$fns = explode(',', $fns);
			foreach ($fns as $fn) {
				if(function_exists($fn)) {
					$val = is_array($val) ? array_map($fn, $val) : $fn($val);
				}
			}
		}
		return $val;
	}
}