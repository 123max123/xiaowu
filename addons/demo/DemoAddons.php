<?php
/**
 * 插件演示
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-27
 */
namespace addons\demo;
class DemoAddons {
	public function test($args = '') {
		return '测试 hook(\'demo:test\', '.$args.');';
	}
}