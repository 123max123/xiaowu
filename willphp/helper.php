<?php
/**
 * 框架助手函数库
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
defined('WPHP_URI') or die('Access Denied');
/**
 * 获取单例模式
 * @param string $class 类名
 * @param mixed $args 参数
 * @return object 返回实例
 */
function get_instance($class, $args = null) {
	static $instances = array();
	if (!isset($instances[$class])) {
		$instances[$class] = is_null($args)? new $class() : new $class($args);
	}
	return $instances[$class];
}
/**
 * 注册插件并运行
 * @param string $name 插件名称:勾子名称
 * @param mixed $args 参数
 * @return object 返回运行结果
 */
function hook($name, $args = null) {
	list($addons, $hook) = explode(':', $name);
	$class = strtr('/addons/'.$addons.'/'.ucfirst($addons).'Addons', '/', '\\');
	\wiphp\Hook::add($hook, $class);
	return \wiphp\Hook::run($hook, $args);
}
/**
 * 获取模型类
 * @param string $table 要操作的表名
 * @return object 返回模型对象
 */
function M($table = '') {
	static $model = array();
	if (!isset($model[$table])) {
		$model[$table] = new \wiphp\Model($table);
	}
	return $model[$table];
}
/**
 * 获取自定义模型类
 * @param string $name 自定义的模型名称
 * @return object 返回模型实例
 */
function D($name) {
	$class = strtr('/app/model/'.ucfirst($name).'Model', '/', '\\');
	return get_instance($class);
}
/**
 * 获取自定义Widget部件实例
 * @param string $name 自定义的Widget名称
 * @return object 返回Widget实例
 */
function W($name) {
	$class = strtr('/app/widget/'.ucfirst($name).'Widget', '/', '\\');
	return get_instance($class);
}
/**
 * 获取扩展类
 * @param string $name 扩展类名称
 * @param mixed $args 参数
 * @return object 返回扩展类对象
 */
function E($name, $args = null) {
	$class = strtr('/extend/'.ucfirst($name), '/', '\\');
	if (!class_exists($class, true)) {
		\wiphp\App::halt($class.'.php 扩展类不存在。');
	}
	return new $class($args);
}
/**
 * 获取自定义验证器实例
 * @param string $name 自定义的验证器名称
 * @param string $scene 验证器场景
 * @return object 返回验证器实例
 */
function validate($name, $scene = '*') {
	$class = strtr('/app/validate/'.ucfirst($name).'Validate', '/', '\\');
	return new $class($scene);
}
/**
 * 获取分页类
 * @param int $total 总页数
 * @param int $psize 每页记录数
 * @param int $pn 当前页码
 * @param string $purl 当前url
 * @return object 返回分页实例
 */
function P($total, $psize, $pn, $purl = '') {
	if ($purl != '') $purl = U($purl);
	return E('page', [$total, $psize, $pn, $purl]);
}
/**
 * 生成url
 * @param string $route 路由
 * @param array $vars 参数
 * @param string $method 类型
 * @return string 返回生成url
 */
function U($route='', $vars=[], $method = '*'){	
	if (filter_var($route, FILTER_VALIDATE_URL) !== false) {
		return $route;
	} else {
		$url = \wiphp\Route::getUrl($route, $vars, $method);
		return __URL__.$url;
	}	
}
/**
 * 获取或设置配置
 * @param string $name 配置名称
 * @param string $val 要设置的配置值
 * @return array|string 返回的配置
 */
function C($name = '', $val = '', $type = 'site') {
	if ($name == '') {
		return \wiphp\Config::get($name, $type);
	} elseif ('' === $val) {
		return \wiphp\Config::get($name, $type);
	} elseif (is_null($val)) {
		return \wiphp\Config::del($name, $type);
	} else {
		return \wiphp\Config::set($name, $val, $type);
	}
}
/**
 * 获取$_GET或$_POST请求参数
 * @param string $name 变量名
 * @param string $default 默认值
 * @param string $fns 过滤函数,过滤函数
 * @return string|array 返回请求参数
 */
function I($name = '', $default = '', $fns = '') {
	return \wiphp\App::getRequest($name, $default, $fns);
}
/**
 * 刷新页面缓存(根据路由)
 * @param string $route 路由
 * @param array $vars 参数
 * @return string boolean 返回操作结果
 */
function refresh_page($route = '', $vars = []) {
	$hash = \wiphp\App::getViewHash($route, $vars);
	return \wiphp\Cache::delShtml($hash);	
}
/**
 * 刷新页面缓存(根据vhash)
 * @param string $vhash 页面缓存hash
 * @return string boolean 返回操作结果
 */
function refresh_vhash($vhash) {
	return \wiphp\Cache::delShtml($vhash);	
}
/**
 * 快捷缓存操作
 * @param string $name 缓存名称
 * @param mixed $value 要设置的值
 * @param number $time 有效时间
 * @return boolean 返回缓存操作结果
 */
function cache($name, $value = '', $time = 0) {
	if (is_null($name)) {
		return \wiphp\Cache::clear();
	} elseif ('' === $value) {
		return \wiphp\Cache::get($name);
	} elseif (is_null($value)) {
		return \wiphp\Cache::del($name);
	} else {
		return \wiphp\Cache::set($name, $value, $time);
	}
}
/**
 * 快捷session操作
 * @param string $name 名称
 * @param string $value 要设置的值
 * @return boolean|mixed 返回操作结果
 */
function session($name, $value = '') {
	if (is_null($name)) {
		$_SESSION = [];
		if(isset($_COOKIE[session_name()])) {
			setCookie(session_name(), "", time()-42000, "/");
		}
		session_destroy();
	} else {
		$name = APP_NAME.'_'.$name;
		if ('' === $value) {
			return isset($_SESSION[$name])? $_SESSION[$name] : false;
		} elseif (is_null($value)) {
			if (isset($_SESSION[$name])) unset($_SESSION[$name]);
		} else {
			$_SESSION[$name] = $value;
		}
	}
}
/**
 * 快捷cookie操作
 * @param string $name 名称
 * @param string $value 要设置的值
 * @return boolean|mixed 返回操作结果
 */
function cookie($name, $value = '') {
	$name = APP_NAME.'_'.$name;
	if ('' === $value) {
		return isset($_COOKIE[$name])? $_COOKIE[$name] : false;
	} elseif (is_null($value)) {
		if (isset($_COOKIE[$name])) unset($_COOKIE[$name]);
	} else {
		$_COOKIE[$name] = $value;
	}
}
/**
 * 用应用名参与md5加密
 * @param string $str 要加密的字符串
 * @return string 加密后的字符串
 */
function app_md5($str = '') {
	return md5(APP_NAME.$str);
}
/**
 * 传变量到模板
 * @param string $name 变量名
 * @param mixed $value 值
 */
function assign($name, $value = null) {
	\wiphp\View::assign($name, $value);
}
/**
 * 显示模板
 * @param string $file 模板文件名
 * @param array $vars 传入变量
 * @return string 返回输出模板
 */
function view($file = '', $vars = []) {
	return \wiphp\View::fetch($file, $vars);
}
/**
 * 返回支持中文json
 * @param string $data 变量
 * @return string 返回json
 */
function json($data = '') {
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}
/**
 * 变量测试输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出
 * @param string $label 制表符
 * @param string $strict
 * @return string 返回输出的变量
 */
function dump($var, $echo = true, $label = '', $strict = true) {
	$label = ($label == '') ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	} else {
		return $output;
	}
}
/**
 * 转跳到url
 * @param string $url
 * @param number $time
 * @param string $msg
 */
function redirect($url, $time = 0, $msg = '') {
	$url = str_replace(array("\n", "\r"), '', $url);
	$msg = ($msg == '')? "系统将在{$time}秒之后自动跳转到{$url}！" : $msg;
	if (!headers_sent()) {
		if (0 === $time) {
			header('Location:'.$url);
		} else {
			header("refresh:{$time};url={$url}");
			echo $msg;
		}
		exit();
	} else {
		$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0) $str .= $msg;
		exit($str);
	}
}
/**
 * 获取IP
 * @return string 返回IP地址
 */
function get_ip() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown',$arr);
		if(false !== $pos) unset($arr[$pos]);
		$ip  =  trim($arr[0]);
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip  = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip  = $_SERVER['REMOTE_ADDR'];
	}
	$cip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
	return $cip;
}
/**
 * 生成token
 * @param string $name token名称
 * @return string 返回token值
 */
function get_token($name = '') {
	$name = !empty($name)? $name : 'token';
	if (!session($name)) {
		$token = md5(APP_NAME.time().rand(1,999));
		session($name, $token);
	} else {
		$token = session($name);
	}
	return $token;
}
/**
 * 验证token
 * @param string $token_name 名称
 * @return boolean 验证结果
 */
function valid_token($name = '') {
	$name = !empty($name)? $name : 'token';
	$token  = I($name);
	if (!empty($token) && session($name) && session($name) == $token) {
		session($name, null);
		return true;
	}
	return false;
}
/**
 * 验证验证码
 * @param mixid $captcha 验证码
 * @return boolean 返回验证结果
 */
function check_captcha($captcha, $name = 'captcha') {
	return E('captcha')->valid_captcha($captcha, $name);
}
/**
 * 验证邮箱
 * @param string $email 邮箱
 * @return boolean 返回验证结果
 */
function check_email($email) {
	return preg_match('/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i', $email);
}
/**
 * 验证url
 * @param string $url url地址
 * @return boolean 返回验证结果
 */
function check_url($url) {
	return preg_match('/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+/', $url);
}
/**
 * 验证手机号
 * @param string $phone 手机号
 * @return boolean 返回验证结果
 */
function check_phone($phone) {
	return preg_match('/^1[3456789]\d{9}$/', $phone);
}
/**
 * 验证数字
 * @param string $val 值
 * @return boolean 返回验证结果
 */
function check_int($val) {
	return ctype_digit($val);
}
/**
 * 验证用户名格式
 * @param string $username 用户名
 * @return boolean 返回验证结果
 */
function check_strname($username) {
	return preg_match('/^[A-Z0-9]{2,20}$/i', $username);
}
/**
 * 验证密码格式
 * @param string $userpwd 密码
 * @return boolean 返回验证结果
 */
function check_strpwd($userpwd) {
	return ctype_alnum($userpwd);
}
/**
 * 清除html代码
 * @param string $string 字符串
 * @return string 返回清除结果
 */
function clear_html($string){
	$string = strip_tags($string);
	$string = trim($string);
	$string = preg_replace('/\t/','',$string);
	$string = preg_replace('/\r\n/','',$string);
	$string = preg_replace('/\r/','',$string);
	$string = preg_replace('/\n/','',$string);
	return trim($string);
}
/**
 * 字符串截取
 * @param string $str 字符串
 * @param number $length 截取长度
 * @param number $start 开始位置
 * @param string $charset 字符集
 * @param string $suffix 是否显示...
 * @return string 返回截取结果
 */
function str_substr($str, $length, $start = 0, $charset = "utf-8", $suffix = false) {
	if(function_exists("mb_substr")) {
		$slice = mb_substr($str, $start, $length, $charset);
	} elseif (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);
		if(false === $slice) {
			$slice = '';
		}
	} else {
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join('', array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}
/**
 * 计算空间大小
 * @param string $total 字节数
 * @return string 返回空间大小
 */
function space_total($total) {
	$config = ['GB'=>1073741824,'MB'=>1048576,'KB'=>1024];
	foreach ($config as $unit => $byte) {
		if ($total > $byte) {
			return round($total / $byte).$unit;
		}
	}
	return $total.'B';
}
/**
 * 快速分页数据表
 * @param string $table 表名
 * @param array $map where条件
 * @param array $pset 分页设置psize：每页记录数;purl:分页URL
 * @param string $fields 字段
 * @param string $order 排序方式 
 * @return $ptotal 总数; $list:列表; $phtml：分页HTML
 */
function page_table($table, $map, $pset = [], $fields = '', $order = 'id DESC') {
	$model = M($table);
	$count = $model->where($map)->count();
	$list = [];
	$phtml = '';
	if ($count > 0) {
		$p = I('p', 1, 'intval');
		$psize = isset($pset['psize'])? $pset['psize'] : 10;
		$purl = isset($pset['purl'])? $pset['purl'] : '';
		$page = P($count, $psize, $p, $purl.'/p/{page}');
		$limit = $page->getLimit();
		$list = $model->where($map)->field($fields)->order($order)->limit($limit)->select();
		$phtml = $page->getHtml();
	}
	assign('ptotal', $count);
	assign('list', $list);
	assign('phtml', $phtml);
}
/**
 * 快速分页数组数据
 * @param array $data 数据
 * @param array $pset 分页设置psize：每页记录数;purl:分页URL
 * @return $ptotal 总数; $list:列表; $phtml：分页HTML
 */
function page_data($data, $pset) {
	$count = count($data);
	$list = [];
	$phtml = '';
	if ($count > 0) {
		$p = I('p', 1, 'intval');
		$psize = isset($pset['psize'])? $pset['psize'] : 10;
		$purl = isset($pset['purl'])? $pset['purl'] : '';
		$ptotal = ceil($count/$psize); //总分页数
		if ($p > $ptotal) $p = $ptotal;
		$page = P($count, $psize, $p, $purl.'/p/{page}');
		$phtml = $page->getHtml();
		$start = ($p - 1) * $psize; //开始
		$list = array_slice($data, $start, $psize);
	}
	assign('ptotal', $count);
	assign('list', $list);
	assign('phtml', $phtml);
}