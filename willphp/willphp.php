<?php
/**
 * 常量定义与主程序引入
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
defined('WPHP_URI') or die('Access Denied');
date_default_timezone_set('PRC'); //设置默认时区
define('START_TIME', microtime(true)); //开始时间
define('WPHP_VER', 'v2.2.3'); //
defined('URL_MODEL') or define('URL_MODEL', 1); //默认普通URL模式
define('__WPHP__', 'WillPHP '.WPHP_VER); //框架全称
define('__ROOT__', rtrim(strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/'), '/')); //网站根目录
define('__PUBLIC__', __ROOT__.'/public'); //公共资源目录
define('__URL__', (URL_MODEL == 2)? str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) : $_SERVER['SCRIPT_NAME']); //基础URL
defined('APP_NAME') or define('APP_NAME', str_replace('.','',basename($_SERVER['SCRIPT_FILENAME']))); //默认应用名称
defined('APP_MODE') or define('APP_MODE', 1); //默认单应用模式
defined('THEME_ON') or define('THEME_ON', false); //默认关闭多主题
defined('APP_DEBUG') or define('APP_DEBUG', false); //默认关闭调试
defined('CACHE_TYPE') or define('CACHE_TYPE', 1); //默认数据缓存类型 1.phpfile;2.serialize
defined('URL_SUFFIX') or define('URL_SUFFIX', '.html'); //默认URL后缀.html
defined('SESSION_ON') or define('SESSION_ON', true); //默认开启session
if (SESSION_ON && !session_id()) session_start(); //判断开启session
define('PATH_CORE', WPHP_PATH.'/wiphp'); //定义框架核心路径
define('PATH_ADDONS', WPHP_URI.'/addons'); //定义插件路径
define('PATH_EXTEND', WPHP_URI.'/extend'); //定义扩展类库路径
define('APP_PATH', (APP_MODE == 1)? WPHP_URI.'/app' : WPHP_URI.'/app/'.APP_NAME); //定义应用目录
define('PATH_VIEW', APP_PATH.'/view'); //定义模板文件路径
define('PATH_RUNTIME', (APP_MODE == 1)? WPHP_URI.'/runtime' : WPHP_URI.'/runtime/'.APP_NAME); //定义编译运行路径
define('PATH_CACHE', PATH_RUNTIME.'/data'); //定义数据缓存路径
define('PATH_LOG', PATH_RUNTIME.'/log'); //定义错误日志路径
define('PATH_VIEWC', PATH_RUNTIME.'/viewc'); //定义模板编译路径
define('PATH_SHTML', PATH_RUNTIME.'/shtml'); //定义模板缓存路径
require WPHP_PATH.'/helper.php'; //载入助手函数库
if (file_exists(WPHP_URI.'/app/common.php')) require WPHP_URI.'/app/common.php'; //载入用户函数库
require PATH_CORE.'/App.php'; //载入框架主程序
\wiphp\App::start(); //运行框架