<?php
/**
 * 框架调试
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Debug {
	private static $debug = [];	
	private function __construct() {}
	private function __clone() {}
	//记录调试信息
	public static function log($info, $type = 'info') {
		if (in_array($type, ['inc', 'sql', 'info', 'tip'])) {
			if ($type == 'inc') {
				$sysClass = ['wiphp\Route','wiphp\Config','wiphp\Debug','wiphp\Db','wiphp\Cache','wiphp\ParseSql','wiphp\Widget','wiphp\Validate','wiphp\Template','wiphp\Hook'];
				if (!in_array($info, $sysClass)) self::$debug[$type][] = $info;
			} else {
				self::$debug[$type][] = $info;
			}
		}
	}	
	//显示调试信息
	public static function show() {		
		if (C('debug_close') == 1) return;
		$total_inc = $total_sql = $total_err = 0;
		if (isset(self::$debug['inc'])) {
			$total_inc = '<span style="color:green;">'.count(self::$debug['inc']).'</span>';
		}
		if (isset(self::$debug['sql'])) {
			$total_sql = '<span style="color:green;">'.count(self::$debug['sql']).'</span>';
		}
		if (isset(self::$debug['info'])) {
			$total_err = '<span style="color:red;">'.count(self::$debug['info']).'</span>';
		}
		$title = __MODULE__.'-&gt'.__ACTION__.'()';
		$runtime = round((microtime(true) - START_TIME) , 4);
		$shtml = '<style type="text/css">';
		$shtml .= '.wpdebug{position:fixed;top:0;right:0;z-index:9999;width:230px;line-height:20px;padding:5px 10px;font-size:14px;background-color:#F5F5F5;color:#888;border:1px dashed #666666;}';
		$shtml .= '.wpdebug p{margin:0;padding:0;}';
		$shtml .= '.wpclose{float:right;color:green;}';
		$shtml .= '#wpdebugbox{position:fixed;z-index:9999;min-width:430px;height:auto;padding:10px 10px 0 10px;background:#F5F5F5;border:1px dashed #999;right:0;top:63px;display:none;font-size:12px;line-height:20px;color: #888;}';
		$shtml .= '#wpdebugbox p{margin:0;line-height:20px;}#wpdebugbox ul{margin:0 0 0 15px;padding:0}.wpdebug_header{font-weight:bold;font-size:14px;border-bottom:1px dashed #999;height:25px;margin-bottom:5px;}';
		$shtml .= '.wpdebug_footer{font-size:14px;border-top:1px dashed #999;height:25px;margin:5px 0;padding-top:5px;overflow: hidden;}';
		$shtml .= '.wpdebug_footer a{color:#666;}</style>';
		$shtml .= '<div class="wpdebug"><div class="wpdebug_header">'.$title.' | '.$runtime.'s<a href="#" onclick="this.parentNode.parentNode.style.display=\'none\'" class="wpclose">[x]</a></div>';
		$shtml .= '<p>inc('.$total_inc.') | sql('.$total_sql.') | err('.$total_err.')<a href="#" onclick="document.getElementById(\'wpdebugbox\').style.display=\'block\'" class="wpclose">view...</a></p></div>';
		$shtml .= '<div id="wpdebugbox"><div class="wpdebug_header">Debug: '.$title.'<a href="#" onclick="this.parentNode.parentNode.style.display=\'none\'" class="wpclose">[x]</a></div>';
		if (isset(self::$debug['inc'])) {
			$shtml .= '<p>[include]</p><ul>';
			foreach (self::$debug['inc'] as $class) {
				$shtml .= '<li>'.$class.'</li>';
			}
			$shtml .= '</ul>';
		}
		if (isset(self::$debug['sql'])) {
			$shtml .= '<p>[sql]</p><ul>';
			foreach (self::$debug['sql'] as $sql) {
				$shtml .= '<li>'.$sql.'</li>';
			}
			$shtml .= '</ul>';
		}
		if (isset(self::$debug['info'])) {
			$shtml .= '<p>[info]</p><ul>';
			foreach (self::$debug['info'] as $err) {
				$shtml .= '<li>'.$err.'</li>';
			}
			$shtml .= '</ul>';
		}
		if (isset(self::$debug['tip'])) {
			$shtml .= '<p>[tips]</p><ul>';
			foreach (self::$debug['tip'] as $tip) {
				$shtml .= '<li>'.$tip.'</li>';
			}
			$shtml .= '</ul>';
		}
		$shtml .= '<div class="wpdebug_footer">&copy;'.date('Y').' 113344.com | <a href="http://www.113344.com" target="_blank">'.__WPHP__.'</a></div></div>';
		echo $shtml;
		exit();
	}
}