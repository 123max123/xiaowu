<?php
/**
 * 框架模型类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-06
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Model {
	protected $options = [];
	protected $name = '';
	protected $pk = 'id';
	protected $db = false;
	protected $method = ['table','field','where','order','limit', 'sql'];
	
	public function __construct($table = '') {
		$this->db = Db::factory();
		if ($table != '') {
			$this->name = $table;
			$this->options['table'] = $table;
		}
	}
	//开启事务
	public function startTrans() {
		$this->db->startTrans(false);
	}
	//事务回滚
	public function rollback() {
		$this->db->rollback();
	}
	//提交事务
	public function commit() {
		$this->db->commit();		
	}
	//返回上次执行的sql
	public function getLastSql() {
		return $this->db->getLastSql();
	}
	public function __destruct() {
		$this->db = false;
	}
	public function __call($method, $args) {
		$method = strtolower($method);
		if (in_array($method, $this->method)) {
			if ($method == 'sql') {
				$this->options['sql'] = 1;
			} elseif ($method == 'where' && !empty($args[0])) {
				if (is_array($args[0]) && count($args[0]) != count($args[0], 1)) {
					foreach ($args[0] as $arg) {
						$this->options[$method][] = $arg;
					}
				} else {
					$this->options[$method][] = $args;
				}
			} else {
				$this->options[$method] = $args[0];
			}
			return $this;
		}
	}
	public function __get($property) {
		if (isset($this->options[$property])) return $this->options[$property];
	}
	public function __set($property, $value) {
		if (in_array($property, $this->method)) {
			$this->options[$property] = $value;
		}
	}
	//直接执行sql,返回影响条数
	public function exectute($sql) {
		return $this->db->exectute($sql);
	}
	//执行sql,返回数据集
	public function getResult($sql) {
		$result = $this->db->getAll($sql);
		if (isset($result[0]['Variable_name'])) {
			$data = [];
			foreach ($result as $re) {				
				$data[$re['Variable_name']] = $re['Value'];		
			}
		} else {
			$data = $result;
		}
		return $data;
	}
	//对sql语句中特殊字符转义
	public function cleanString($string) {
		return $this->db->cleanString($string);
	}
	//清除选项
	protected function clearOptions() {
		$this->db->isSql = isset($this->options['sql'])? true : false;
		$table = $this->options['table'];
		$this->options = [];
		$this->options['table'] = $table;
	}
	//记录条数
	public function count() {
		$sql = ParseSql::getCountSql($this->options);
		$this->clearOptions();
		return $this->db->getCount($sql);
	}
	//单条记录
	public function find($pk = '') {
		$this->options['limit'] = 1;
		if (!empty($pk)) $this->options['field'] = $pk;
		$sql = ParseSql::getQuerySql($this->options);
		$this->clearOptions();
		return $this->db->getOne($sql);
	}
	//多条记录
	public function select() {
		$sql = ParseSql::getQuerySql($this->options);
		$this->clearOptions();
		return $this->db->getAll($sql);
	}
	//获取field
	public function getField($field) {
		$this->options['field'] = $field;
		$sql = ParseSql::getQuerySql($this->options);
		$this->clearOptions();
		if(strpos($field, ',')) {
			$data = $this->db->getAll($sql);
			$fdata = [];
			if (!empty($data)) {
				$fields = explode(',', $field);
				list($k, $v) = $fields;
				$n = (count($fields) > 2)? true : false;
				foreach ($data as $val) {
					$fdata[$val[$k]] = $n? $val : $val[$v];
				}
			}
			return $fdata;
		} else {
			$data = $this->db->getOne($sql);
			if (isset($data[$field])) {
				return $data[$field];
			}
			return '';
		}
	}
	//删除操作
	public function del() {
		$sql = ParseSql::getDelSql($this->options);
		$this->clearOptions();
		return $this->db->exectute($sql);
	}
	//添加
	public function add($data) {
		if (!empty($data)) {
			$sql = ParseSql::getSaveSql($this->options, $data);
			$this->clearOptions();
			$this->db->exectute($sql);
			return $this->db->getLastId();
		}
		return false;
	}
	//更新
	public function update($data) {
		if (!empty($data)) {
			$pk = $this->pk;
			if (isset($this->options['where']) || isset($data[$pk])) {
				$sql = ParseSql::getSaveSql($this->options, $data, $pk);
				$this->clearOptions();
				return $this->db->exectute($sql);
			}
		}
		return false;
	}
	//保存
	public function save($data, $pk = '') {
		if (!empty($data)) {
			$pk = ($pk == '')? $this->pk : $pk;
			$sql = ParseSql::getSaveSql($this->options, $data, $pk);
			if (isset($this->options['where']) || isset($data[$pk])) {
				$this->clearOptions();
				return $this->db->exectute($sql);
			} else {
				$this->clearOptions();
				$this->db->exectute($sql);
				return $this->db->getLastId();
			}
		}
		return false;
	}
	//设置field
	public function setField($field, $value) {
		if (!isset($this->options['table'], $this->options['where'])) return false;
		$sql = ParseSql::getSaveSql($this->options, [$field => $value]);
		$this->clearOptions();
		return $this->db->exectute($sql);
	}
	//设置field + 1
	public function setInc($field, $num = 1) {
		$value = $field.'+'.intval($num);
		$sql = ParseSql::getFieldSql($field, $value, $this->options);
		$this->clearOptions();
		return $this->db->exectute($sql);
	}
	//设置field - 1
	public function setDec($field, $num = 1) {
		$value = $field.'-'.intval($num);
		$sql = ParseSql::getFieldSql($field, $value, $this->options);
		$this->clearOptions();
		return $this->db->exectute($sql);
	}
	//统计字段总和
	public function sum($field) {
		$this->options['field'] = $field;
		$sql = ParseSql::getTotalSql($this->options, 'sum');
		return $this->db->getCount($sql);
	}
	//统计最小值
	public function min($field) {
		$this->options['field'] = $field;
		$sql = ParseSql::getTotalSql($this->options, 'min');
		return $this->db->getCount($sql);
	}
	//统计最大值
	public function max($field) {
		$this->options['field'] = $field;
		$sql = ParseSql::getTotalSql($this->options, 'max');
		return $this->db->getCount($sql);
	}
	//统计平均值
	public function avg($field) {
		$this->options['field'] = $field;
		$sql = ParseSql::getTotalSql($this->options, 'avg');
		return $this->db->getCount($sql);
	}
}