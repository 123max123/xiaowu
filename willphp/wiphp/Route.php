<?php
/**
 * 路由处理
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Route {
	private static $rules = [
			'get' => [],
			'post' => [],
			'put' => [],
			'delete' => [],
			'*' => []
	];
	private function __construct() {}
	private function __clone() {}	
	public static function rule($rule, $route = '', $type = '*') {
		$rule = strtolower($rule);
		$rule = '/' == substr($rule, 0, 1)? $rule : '/'. $rule;
		$route = strtolower($route);
		$type = strtolower($type);
		if ('*' == $type) {
			foreach (self::$rules as $key => $value) {
				self::$rules[$key][$rule] = $route;
			}
		} else {
			self::$rules[$type][$rule] = $route;
			self::$rules['*'][$rule] = $route;
		}
	}
	public static function __callStatic($func, $arguments) {
		$arguments[] = $func;
		self::rule(...$arguments);
	}	
	public static function parseUrl() {		
		if (!isset($_SERVER['PATH_INFO']) && $_SERVER['QUERY_STRING']) {
			$_GET['m'] = empty($_GET['m']) ? 'index' : strtolower($_GET['m']);
			$_GET['a'] = empty($_GET['a']) ? 'index' : strtolower($_GET['a']);
			$m = $_GET['m'];
			$a = $_GET['a'];
			unset($_GET['m'], $_GET['a']);
			$query = http_build_query($_GET);
			$url = $_SERVER['SCRIPT_NAME']."/{$m}/{$a}/".str_replace(array("&", "="), "/", $query);
			header("Location:".$url);
		}			
		$config = Config::get('', 'route');
		foreach ($config as $rule => $set) {
			self::rule($rule, $set[0], $set[1]);
		}
		$pathinfo = isset($_SERVER['PATH_INFO']) ? preg_replace('/\/+/','/',strtolower($_SERVER['PATH_INFO'])) : '/';
		$pathinfo = str_replace(strrchr($pathinfo, '.'), '',$pathinfo);
		$analysis = ['status' => 404];
		$mothod = strtolower($_SERVER['REQUEST_METHOD']);
		if (isset(self::$rules[$mothod][$pathinfo])) {
			$analysis['status'] = 200;
			$analysis['rule'] = $pathinfo;
			$analysis['route'] = self::$rules[$mothod][$pathinfo];
		} else {
			foreach (self::$rules[$mothod] as $rule => $route) {
				if (substr($rule, -1) == '$' && substr_count($rule, '/') != substr_count($pathinfo, '/')) {
					continue;
				}
				$reg = '/^'.str_replace('/', '\/', preg_replace('/:[a-z]+(?=\/|\$|$)/', '\S+', $rule)).'/';
				if (!preg_match($reg, $pathinfo)) {
					continue;
				}
				$analysis['status'] = 200;
				$analysis['rule'] = $rule;
				$analysis['route'] = $route;
				break;
			}
		}
		if (!isset($analysis['route'])) {
			$path_list = explode('/', trim($pathinfo, '/'));
			$module = empty($path_list[0])? 'index' : strtolower($path_list[0]);
			$action = empty($path_list[1])? 'index' : strtolower($path_list[1]);
			$analysis['rule'] = $analysis['route'] = '/'.$module.'/'. $action;
		}
		if (isset($analysis['route'])) {
			$param = [];
			$rule_list = explode('/', $analysis['rule']);
			$path_list = explode('/', $pathinfo);
			if (strpos($analysis['rule'], ':') !== false) {
				foreach ($rule_list as $key => $value) {
					if (substr($value, 0, 1) == ':') {
						$param[trim($value, ':$')] = $path_list[$key];
					}
				}
			}
			for($i = count($rule_list); $i < count($path_list); $i += 2) {
				if (isset($path_list[$i + 1])) {
					$param[$path_list[$i]] = $path_list[$i + 1];
				}
			}
			$analysis['param'] = self::getParams($param);
			$analysis['param']['req'] = $analysis['param'];
		}
		return $analysis;
	}
	public static function getParams($param = []) {
		return array_merge($param, $_GET, $_POST);
	}
	public static function getUrl($route = '', $vars = [], $method = '*') {		
		$route = strtolower(trim($route, '/'));	
		if (strpos($route, '?')) {
			list($route, $params) = explode('?', $route);				
			parse_str($params, $vars); 			
		}
		$path = explode('/', $route);
		$pathcount = count($path);
		if ($pathcount > 2) {
			$route = $path[0].'/'.$path[1];
			array_shift($path);
			array_shift($path);	
			for($i = 0; $i < ($pathcount - 2); $i += 2){
				$vars[$path[$i]] = isset($path[$i + 1]) ? $path[$i + 1] : '';
			}			
		}	
		$method = strtolower($method);
		$rule = array_search($route, self::$rules[$method]);		
		$suffix = URL_SUFFIX;
		if (!$rule) {
			$param = '';
			if ($vars) {
				$query = http_build_query($vars);
				$param = '/'.str_replace(['&', '='], '/', $query);
			}
			return '/'.$route.$param.$suffix;
		}
		$rule_list = explode('/', trim($rule, '/'));
		if (strpos($rule, ':') !== false) {
			foreach ($rule_list as $key => $value) {
				if (strpos($value, ':') !== false) {
					$rule_list[$key] = isset($vars[trim($value, ':$')])? $vars[trim($value, ':$')] : '';
					if (isset($vars[trim($value, ':$')]))
						unset($vars[trim($value, ':$')]);
				}
			}
		}
		if (strpos($rule, '$') == false && $vars != '') {
			foreach ($vars as $key => $value) {
				$rule_list[] = $key;
				$rule_list[] = $value;
			}
		}
		$rule = rtrim('/' . implode('/', $rule_list), '$');
		if (substr($rule, -1) == '/') $suffix = '';
		return $rule.$suffix;
	}
}