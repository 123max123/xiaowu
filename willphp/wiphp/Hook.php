<?php
/**
 * Hook.php
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-26
 */
namespace wiphp;
class Hook {
	protected static $hook = [];
	private function __construct() {}
	private function __clone() {}
	public static function add($hook, $addons) {
		self::$hook[$hook] = $addons;
	}
	public static function run($hook, $args = null) {
		if(isset(self::$hook[$hook])){
			$addons = (new self::$hook[$hook]());
			return $addons->$hook($args);
		}
	}
}