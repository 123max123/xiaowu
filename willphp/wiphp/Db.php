<?php
/**
 * 数据库基类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-26
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
abstract class Db {
	/**
	 * 根据数据库驱动类型返回驱动实例
	 * @param string $db_type 数据库驱动类型
	 * @return object 数据库驱动实例
	 */
	public static function factory() {
		$db_type = Config::get('db_type', 'database');
		$db_type = ucfirst($db_type);
		$class = strtr('wiphp/db/Db'.$db_type, '/', '\\');
		$called = call_user_func(array($class, 'getInstance'));
		if (!$called) {
			App::halt('db/Db'.$db_type.'.php 数据库驱动不存在。');
		}
		return $called;
	}
	/**
	 * 获取单例模式
	 */
	abstract public static function getInstance();
	/**
	 * 连接数据库，从配置文件中读取配置信息
	 */
	abstract public function conn();
	/**
	 * 发送query查询
	 * @param string $sql sql语句
	 * @return mixed 返回result结果
	 */
	abstract public function query($sql);
	/**
	 * 执行query insert/update
	 * @param string $sql sql语句
	 * @return int 影响的行数
	 */
	abstract public function exectute($sql);
	/**
	 * 获取多行数据
	 * @param string $sql sql语句
	 * @return array
	 */
	abstract public function getAll($sql);
	/**
	 * 获取单行数据
	 * @param string $sql sql语句
	 * @return array
	 */
	abstract public function getOne($sql);
	/**
	 * 获取记录数  count(*)
	 * @param string $sql sql语句
	 * @return int
	 */
	abstract public function getCount($sql);
	/**
	 * 返回上一条sql语句
	 */
	abstract public function getLastSql();
	/**
	 * 返回上一条insert语句产生的id
	 */
	abstract public function getLastId();
	/**
	 * 对sql语句中特殊字符转义
	 * @param string $string
	 * @return string
	 */
	abstract public function cleanString($string);
}