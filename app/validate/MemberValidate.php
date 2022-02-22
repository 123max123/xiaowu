<?php
namespace app\validate;
use wiphp\Validate;
class MemberValidate extends Validate{
	protected $table = 'member';
	protected $noFields = ['token','captcha','repass']; //去除字段
	protected $inFields = []; //保存字段
	//场景设置[场景名称=>[验证字段,验证字段...]]
	protected $allScene = [
			'login' => ['username', 'userpwd', 'captcha'], //登录
			'reg' => ['username', 'nickname', 'qq', 'email', 'userpwd', 'repass'], //注册用户
			'update' => ['nickname', 'qq', 'email', 'repass'], //更新用户	
	];
	//验证规则[字段名=>验证规则@场景|验证规则...]
	protected $rule = [
			'username' => 'require|strname|unique@reg',
			'userpwd' => 'require|strpwd',
			'repass' => 'userpwd',
			'captcha' => 'require|captcha@login',
			'nickname' => 'require',
			'qq' => 'int',
			'email' => 'email',
	];	
	//验证提示[字段名=>验证提示|验证提示...]
	protected $info = [
			'username' => '用户名不能为空|用户名格式不正确|用户名已存在',
			'userpwd' => '密码不能为空|密码格式不正确',
			'repass' => '两次输入的密码不一致',
			'captcha' => '验证码不能为空|验证码不正确',
			'nickname' => '昵称不能为空',
			'qq' => 'QQ必须是数字',
			'email' => '邮箱格式不正确',	
	];
	//自动填充[字段名@场景=>函数or值]
	protected $auto = [
			'userpwd@reg' => 'md5', 
			'ctime@reg' => 'time',			
			'status@reg' => 1,
			'level@reg' => 1,
 	];
}