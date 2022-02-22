<?php
/**
 * 验证器基类
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-27
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
abstract class Validate {
	protected $nowScene = ''; //当前场景 *:验证所有	
	protected $allScene = []; //场景设置[场景名称=>[验证字段,验证字段...]]
	protected $table = ''; //表名(用于唯一验证)
	protected $rule = []; //验证规则[字段名=>验证规则@场景|验证规则@场景...]
	protected $info = []; //验证提示[字段名=>验证提示|验证提示...] 
	protected $data = []; //验证数据
	protected $error = ''; //错误信息
	protected $tokenError = 'token validation failed.'; //token错误提示	
	protected $auto = []; //自动填充[字段名@场景=>函数or值]
	protected $noFields = []; //去除字段[字段1,字段2...]
	protected $inFields = []; //保存字段[字段1,字段2...]
	//初始化场景
	public function __construct($scene = '*') {	
		$this->nowScene = isset($this->allScene[$scene])? $scene : '*';
	}
	//开始验证
	public function check($data = []) {
		$this->data = array_merge($this->data, $data);
		if (empty($this->data)) return false;
		$scene = $this->nowScene;
		foreach ($this->rule as $field => $rule) {
			if (isset($this->data[$field])) {				
				if ($scene == '*' || in_array($field, $this->allScene[$scene])) {
					$check = $this->parseRule($field, $this->data[$field]);
					if ($check['status'] == 0) {
						$this->error = $check['info'];
						return false;
					}
				}
			}			
		}
		return true;
	}
	//验证token
	public function token($name = 'token') {		
		if (valid_token($name)) {
			return true;	
		}		
		$this->error = $this->tokenError;		
		return false;
	}
	//获取数据
	public function getData() {
		$data = $this->parseAuto();
		$data = array_merge($this->data, $data);
		$data = $this->parseNoFields($data);
		$data = $this->parseInFields($data);
		return $data;
	}
	//处理自动完成
	protected function parseAuto() {
		$data = [];
		foreach ($this->auto as $name => $fn) {
			//场景$scene 字段$field			
			if (strpos($name, '@')) {
				list($field, $scene) = explode('@', $name);	
			} else {
				$field = $name;
				$scene = '*';
			}			
			if ($scene == $this->nowScene || $scene == '*') {
				$arg = isset($this->data[$field])? $this->data[$field] : '';
				if (is_numeric($fn)) {
					$data[$field] = $fn;
				} elseif (method_exists($this, $fn)) {
					$data[$field] = $this->$fn($arg);
				} elseif (function_exists($fn)) {
					$data[$field] = $fn($arg);
				} else {
					$data[$field] = isset($this->data[$fn])? $this->data[$fn] : $fn;
				}
			}
		}
		return $data;
	}
	//字段去除
	protected function parseNoFields($data) {
		foreach ($this->noFields as $field) {
			if (isset($data[$field])) unset($data[$field]);
		}
		return $data;
	}
	//保存字段
	protected function parseInFields($data) {		
		if (!empty($this->inFields)) {
			$save = [];
			foreach ($this->inFields as $field) {
				if (isset($data[$field])) $save[$field] = $data[$field];
			}
			return $save;
		} 	
		return $data;
	}
	//获取提示
	public function getError() {
		return $this->error;
	}
	//解析验证
	protected function parseRule($name, $value) {
		$check = ['status' => 1, 'info' =>'' ];
		$data = $this->data;
		$rule = $this->rule[$name];
		$info = $this->info[$name];
		if (strpos($rule, '|')) {
			$rule = explode('|', $rule);
			$info = explode('|', $info);
		} else {
			$rule = [$rule];
			$info = [$info];
		}	
		foreach ($rule as $k => $ru) {
			//场景$scene 规则$fn
			if (strpos($ru, '@')) {
				list($fn, $scene) = explode('@', $ru);
			} else {
				$fn = $ru;
				$scene = '*';
			}			
			if ($scene == $this->nowScene || $scene == '*') {
				$check['info'] = $info[$k];
				$method = 'check_'.$fn;
				if ($fn == 'require' && empty($value)) {
					$check['status'] = 0;
				} elseif (method_exists($this, $method) && !empty($value)) {
					$check['status'] = ($this->$method($value, $name))? 1 : 0;
				} elseif (function_exists($method) && !empty($value)) {
					$check['status'] = ($method($value, $name))? 1 : 0;
				} elseif (isset($data[$fn]) && $value != $data[$fn]) {
					$check['status'] = 0;
				}
				if ($check['status'] == 0) break;
			}
		}		
		return $check;
	}
	//唯一验证
	protected function check_unique($val, $field) {
		$isFind = M($this->table)->where($field, $val)->find();
		return !$isFind? true : false;
	}
}	