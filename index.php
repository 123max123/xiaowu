<?php
/**
 * 应用入口文件
 * @copyright 2020-2021 
 * @author NoMind<>
 * @version WillPHPv2
 * @since 2021-09-06
 */
header('Content-type: text/html; charset=utf-8'); //设置编码
version_compare(PHP_VERSION, '5.6', '<') and die('WillPHP requires PHP 5.6 or newer.'); //判断PHP版本
define('WPHP_URI', strtr(realpath(__DIR__),'\\', '/')); //物理路径
//判断是否安装
if(!file_exists(WPHP_URI."/install/install.lock")){
	header('Location:install.php');
	exit();
}
define('WPHP_PATH', WPHP_URI.'/willphp'); //框架路径
define('APP_NAME', 'willgbook'); //应用名称
//define('APP_MODE', 2); //1.单应用模式(默认);2.多应用模式
//define('URL_MODEL', 2); //1.普通模式(默认);2.隐藏index.php(伪静态)
//define('URL_SUFFIX', ''); //U()生成URL自动添加的后缀(默认后缀为.html)
//define('THEME_ON', true); //开启多主题(默认关闭)
//define('CACHE_TYPE', 2); //文件缓存类型 1.phpfile(默认);2.serialize
//define('SESSION_ON', false); //关闭session(默认自动开启)
//define('APP_DEBUG', true); //开启调试(默认关闭)

require WPHP_PATH.'/willphp.php'; 