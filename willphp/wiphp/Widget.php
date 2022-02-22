<?php
/**
 * 框架部件基类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-26
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
abstract class Widget {
	protected $name = '';
	protected $time = 0;
	public function getData($sid = '', $options = []) {
		$name = $this->name.$sid;
		$data = Cache::get($name);
		if (!$data) {
			$data = $this->setData($sid, $options);
			if ($data) {
				Cache::set($name, $data, $this->time);
			}
		}
		return $data;
	}
	public function delData($sid = '') {
		return Cache::del($this->name.$sid);
	}
	abstract public function setData($sid = '', $options = []);
}