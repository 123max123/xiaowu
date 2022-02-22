<?php
/**
 * Mysqli数据库驱动
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-08-27
 */
namespace wiphp\db;
use wiphp\Db;
use wiphp\Config;
use wiphp\App;
use wiphp\Debug;
defined('WPHP_URI') or die('Access Denied');
class DbMysqli extends Db {
	protected static $instance = null;
	protected $mysqli = false;
	protected $result = null; //结果集
	protected $numRows = 0;  //条数
	protected $lastId = 0; //上一条insert的id
	protected $lastSql = ''; //上一条sql语句
	public $isSql = false; //是否只返回sql语句
	/**
	 * 获取单例模式
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * 构造函数
	 */
	private function __construct(){
		$this->conn();
	}
	private function __clone(){}
	/**
	 * 连接数据库，从配置文件中读取配置信息
	 */
	public function conn() {
		$conf = Config::get('','database');
		$this->mysqli = new \mysqli($conf['db_host'].':'.$conf['db_port'], $conf['db_user'], $conf['db_pwd'], $conf['db_name']);
		if ($this->mysqli->connect_errno) {
			$this->mysqli = false;
			App::halt('Database connect failed!');
		} else {
			$this->mysqli->set_charset($conf['db_charset']);
		}
	}
	//开启事务
	public function startTrans() {
		$this->mysqli->autocommit(false);
	}
	//事务回滚
	public function rollback() {
		$this->mysqli->rollback();
	}
	//提交事务
	public function commit() {
		$this->mysqli->commit();
		$this->mysqli->autocommit(true);
	}
	/**
	 * 发送query查询
	 * @param string $sql sql语句
	 * @return mixed 返回result结果
	 */
	public function query($sql){
		if (APP_DEBUG) Debug::log($sql, 'sql');
		if ($this->isSql) return $sql;
		$this->lastSql = $sql;
		$this->result = $this->mysqli->query($sql);
		if ($this->result) {
			$this->numRows = $this->result->num_rows;
			return $this->result;
		} else {
			$errsql = (APP_DEBUG)? '<p>'.$sql.'</p>' : '';
			App::halt('SQL execution failed!'.$errsql);
		}
	}
	/**
	 * 执行query insert/update
	 * @param string $sql sql语句
	 * @return int 影响的行数
	 */
	public function exectute($sql) {
		if (APP_DEBUG) Debug::log($sql, 'sql');
		if ($this->isSql) return $sql;
		$this->lastSql = $sql;
		$this->result = $this->mysqli->query($sql);
		if ($this->result) {
			$this->numRows = $this->mysqli->affected_rows;
			$this->lastId = $this->mysqli->insert_id;
			return $this->numRows;
		} else {
			$errsql = (APP_DEBUG)? '<p>'.$sql.'</p>' : '';
			App::halt('SQL execution failed! '.$errsql);
		}
	}
	/**
	 * 获取多行数据
	 * @param string $sql sql语句
	 * @return array
	 */
	public function getAll($sql){
		if ($this->isSql) return $sql;
		$this->query($sql);
		if ($this->result && $this->numRows > 0) {
			$data = [];
			while($row = $this->result->fetch_assoc()){
				$data[] = $row;
			}
			return $data;
		} else {
			return [];
		}
	}
	/**
	 * 获取单行数据
	 * @param string $sql sql语句
	 * @return array
	 */
	public function getOne($sql){
		if ($this->isSql) return $sql;
		$this->query($sql);
		if ($this->result && $this->numRows > 0) {
			return $this->result->fetch_assoc();
		} else {
			return [];
		}
	}
	/**
	 * 获取记录数  count(*)
	 * @param string $sql sql语句
	 * @return int
	 */
	public function getCount($sql){
		if ($this->isSql) return $sql;
		$this->query($sql);
		$count  = $this->result->fetch_row();
		return $count[0];
	}
	/**
	 * 返回上一条insert语句产生的id
	 */
	public function getLastId(){
		return $this->lastId;
	}
	/**
	 * 返回上一条sql语句
	 */
	public function getLastSql(){
		return $this->lastSql;
	}
	/**
	 * 对sql语句中特殊字符转义
	 * @param string $string
	 * @return string
	 */
	public function cleanString($string) {
		if (empty($string)) return '';
		if (ini_get('magic_quotes_gpc')) $string = stripslashes($string);
		return $this->mysqli->real_escape_string(trim($string));
	}
	/**
	 * 析构函数
	 */
	public function __destruct() {
		if (is_array($this->result)) $this->result->free();
		if ($this->mysqli) {
			$this->mysqli->close();
			$this->mysqli = false;
		}
	}
}