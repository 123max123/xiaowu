<?php
/**
 * 模型sql解析
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class ParseSql {
	private static $expAll = ['=','<>','>','>=','<','<=','EXP','LIKE','NOT LIKE','BETWEEN','NOT BETWEEN','IN','NOT IN'];
	private function __construct() {}
	private function __clone() {}
	//获取Query Sql
	public static function getQuerySql($options) {
		if (!isset($options['table'])) return '';
		if (!isset($options['field'])) $options['field'] = '';
		$table = self::_parse_table($options['table']);
		$field = self::_parse_field($options['field']);
		$where = isset($options['where'])? self::_parse_where($options['where']) : '';
		$order = isset($options['order'])? self::_parse_order($options['order']) : '';
		$limit = isset($options['limit'])? self::_parse_limit($options['limit']) : '';
		return 'SELECT '.$field.' FROM '.$table.$where.$order.$limit;
	}
	//获取Count Sql
	public static function getCountSql($options) {
		if (!isset($options['table'])) return '';
		$table = self::_parse_table($options['table']);
		$where = isset($options['where'])? self::_parse_where($options['where']) : '';
		return 'SELECT count(*) FROM '.$table.$where;
	}
	//获取统计 Sql
	public static function getTotalSql($options, $type = '') {
		if (!isset($options['table'])) return '';
		$table = self::_parse_table($options['table']);
		$field = $options['field'];
		$where = isset($options['where'])? self::_parse_where($options['where']) : '';
		$total_type = ['sum', 'min', 'max', 'avg'];
		if (in_array($type, $total_type)) {
			return 'SELECT '.$type.'('.$field.') FROM '.$table.$where;
		} else {
			return 'SELECT count('.$field.') FROM '.$table.$where;
		}		
	}
	//获取Del Sql
	public static function getDelSql($options) {
		if (!isset($options['table'], $options['where'])) return '';
		$table = self::_parse_table($options['table']);
		$where = self::_parse_where($options['where']);
		return 'DELETE FROM '.$table.$where;
	}
	//获取Save Sql
	public static function getSaveSql($options, $data, $pk = 'id') {
		if (!isset($options['table'])) return '';
		$table = self::_parse_table($options['table']);
		if (isset($options['where']) || isset($data[$pk])) {
			if (!isset($options['where'])) {
				$options['where'][] = [$pk, $data[$pk]];
			}
			if (isset($data[$pk])) unset($data[$pk]);
			$dataset = self::_parse_dataset($data);
			$where = self::_parse_where($options['where']);
			return 'UPDATE '.$table.$dataset.$where;
		} else {
			$fields = self::_parse_field(array_keys($data));
			$values = implode(',', self::_parse_value($data));
			return "INSERT INTO {$table} ($fields) VALUES ($values)";
		}
	}
	//获取setInc,setDec Sql
	public static function getFieldSql($field, $value, $options) {
		if (!isset($options['table'], $options['where'])) return '';
		$table = self::_parse_table($options['table']);
		$where = self::_parse_where($options['where']);
		$field = self::_parse_key($field);
		$dataset = ' SET '.$field.'='.$value;
		return 'UPDATE '.$table.$dataset.$where;
	}
	//对sql字符转义
	public static function clean($value) {
		return M()->cleanString($value);
	}
	//解析table
	public static function _parse_table($table) {
		$table_pre = Config::get('table_pre', 'database');
		return empty($table)? '' : '`'.$table_pre.$table.'`';
	}
	//解析order
	private static function _parse_order($order) {
		return empty($order)? '' : ' ORDER BY '.$order;
	}
	//解析limit
	private static function _parse_limit($limit) {
		return empty($limit)? '' : ' LIMIT '.$limit;
	}
	//解析单个field
	private static function _parse_key($field) {
		return '`'.$field.'`';
	}
	//解析value
	private static function _parse_value($value) {
		if (is_numeric($value)) return $value;
		return is_array($value) ? array_map('self::_parse_value', $value) : '\''.self::clean($value).'\'';
	}
	//解析fields
	private static function _parse_field($fields) {
		if ($fields == '') return '*';
		if (is_string($fields) && strpos($fields, ',')) {
			$fields = explode(',', $fields);
		}
		if (is_array($fields)) {
			$fields = array_map('self::_parse_key', $fields);
			return implode(',', $fields);
		} else {
			return self::_parse_key($fields);
		}
	}
	//解析要更的dataset
	private static function _parse_dataset($data) {
		$set = array();
		foreach ($data as $k => $v) {
			if (is_array($v) && $v[0] == 'exp' && isset($v[1])) {
				if(is_scalar($v[1])) {
					$set[] = self::_parse_key($k).'='.self::clean($v[1]);
				}
			} else {
				$v = self::_parse_value($v);
				if(is_scalar($v)) {
					$set[] = self::_parse_key($k).'='.$v;
				}
			}
		}
		return ' SET '.implode(',', $set);
	}
	//解析where表达式
	public static function _parse_where($where) {
		if (empty($where)) return '';
		$map = $and = [];
		$i = 1;
		foreach ($where as $wz) {
			$arg_nums = count($wz); //参数数量
			$exp = false; //获取的表达式
			if ($arg_nums == 1) {
				if (is_array($wz[0])) {
					
					$arr = [];
					foreach ($wz[0] as $k => $v) {
						$getexp = self::getExp($k, $v);
						if ($getexp) $arr[] = $getexp;
					}
					$exp = implode(' AND ', $arr);
				} else {
					$exp = $wz[0];
				}
			} elseif ($arg_nums == 2) {
				$exp = self::getExp($wz[0], $wz[1]);
			} elseif ($arg_nums >= 3 && in_array(strtoupper($wz[1]), self::$expAll)) {
				$exp = self::getExp($wz[0], $wz[2], $wz[1]);
			}
			if ($exp) $map[$i] = $exp;
			if (isset($map[$i])) $and[$i] = isset($wz[3]) && strtoupper($wz[3]) == 'OR' ? 'OR' : 'AND';
			$i ++;
		}
		$str = self::getExpLink($map, $and);
		return ' WHERE '.$str;
	}
	//获取解析后的表达式
	private static function getExp($key, $value, $exp = '=') {
		$exp = strtoupper($exp);
		$val = false;
		if (in_array($exp, ['=','<>','>','>=','<','<='])) {
			$val = self::_parse_value($value);
			if (is_scalar($val)) {
				return self::_parse_key($key).$exp.$val;
			}
		}
		if ($exp == 'EXP') {
			return self::_parse_key($key).' '.$value;
		}
		if ($exp == 'IN' || $exp == 'NOT IN') {
			if (is_array($value)) {
				$val = implode(',', $value);
			} elseif (strpos($value, ',')) {
				$val = $value;
			}
			if ($val) return self::_parse_key($key).' '.$exp.' ('.$val.')';
		}
		if ($exp == 'BETWEEN' || $exp == 'NOT BETWEEN') {
			if (is_array($value)) {
				$val = implode(' AND ', $value);
			} elseif (strpos($value, ',')) {
				$val = implode(' AND ', explode(',', $value));
			}
			if ($val) return '('.self::_parse_key($key).' '.$exp.' '.$val.')';
		}
		if ($exp == 'LIKE' || $exp == 'NOT LIKE') {
			$val = '\''.$value.'\'';
			return self::_parse_key($key).' '.$exp.' '.$val;
		}
		return false;
	}
	//获取连接后的表达式
	private static function getExpLink($map, $and) {
		$str = '';
		$count = count($and);
		$and[$count] = 'AND'; //设置最后一个为and
		for ($i=1;$i<=$count;$i++) {
			$left = $right = $link = '';
			if ($and[$i] == 'OR' && $i == 1) {
				$left = '(';
			} elseif ($and[$i] == 'OR' && $i < $count && $and[$i-1] != 'OR') {
				$left = '(';
			}
			if ($i > 1 && $and[$i-1] == 'OR' && $and[$i] != 'OR') {
				$right = ')';
			}
			if ($i > 1 && $i <= $count) {
				$link = ' '.$and[$i-1].' ';
			}
			$str .= $link.$left.$map[$i].$right;
		}
		return $str;
	}
}