<?php
namespace app\controller;
abstract class BaseController{
	use \wiphp\Jump;
	protected $login_uid = 0;
	protected $bid = 0;
	
	public function __construct(){
		$this->login_uid = session('user_id')? session('user_id') : 0;	
		$this->bid = session('gbook_bid')? session('gbook_bid') : 0;
	}
	protected function checkLogin() {
		if (!session('user_id')) {
			$this->_url('login/login');
		}
	}
	protected function checkAdmin() {
		if (session('cplevel') < 2) {
			$this->error('只有管理员才能进行操作');
		}
	}
	protected function checkSysAdmin() {
		if (session('cplevel') != 3) {
			$this->error('只有系统管理员才能进行操作');
		}
	}
}