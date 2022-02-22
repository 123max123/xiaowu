<?php
namespace app\validate;
use wiphp\Validate;
class GbookValidate extends Validate {
	protected $table = 'gbook';
	protected $noFields = ['token','captcha']; //去除字段
	protected $inFields = []; //保存字段
	//场景设置[场景名称=>[验证字段,验证字段...]]
	protected $allScene = [
			'add' => ['name', 'qq', 'pcontent', 'captcha'],
	];
	//验证规则[字段名=>验证规则@场景|验证规则...]
	protected $rule = [
			'name' => 'require',
			'qq' => 'int',
			'pcontent' => 'require',
			'captcha' => 'require|int|captcha',
	];
	//验证提示[字段名=>验证提示|验证提示...]
	protected $info = [
			'name' => '请输入昵称',
			'qq' => 'QQ号码必须是数字',
			'pcontent' => '请输入内容',
			'captcha' => '验证码必须|验证码必须是数字|验证码错误',
	];
	//自动填充[字段名@场景=>函数or值]
	protected $auto = [
			'name' => 'clear_html',
			'pcontent' => 'clear_html',
			'ctime' => 'time',
			'ip' => 'get_ip_int',
			'status' => 1,
	];
}