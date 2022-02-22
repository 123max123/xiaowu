<?php
namespace app\controller;
class LoginController extends BaseController{	
	//登录
	public function login($req){			
		if ($this->isPost()) {
			$validate = validate('member', 'login');
			if (!$validate->check($req)) {
				$this->error($validate->getError());
			}
			$fields = 'id,username,userpwd,nickname,level,status';
			$vo = M('member')->field($fields)->where('username', $req['username'])->find();
			if (!$vo) {
				$this->error('用户不存在');
			}
			if (md5($req['userpwd']) != $vo['userpwd']) {
				$this->error('密码不正确');
			}
			if ($vo['status'] == 0) {
				$this->error('用户已停用');
			}
			if (!valid_token()) {
				$this->error('请忽重复提交');
			}
			session('user_id', $vo['id']);
			session('cplevel', $vo['level']);
			session('user_name', $vo['username']);
			session('nick_name', $vo['nickname']);
			$this->_jump('登录成功', 1, 'book/'.$this->bid); 
		}
		$from = I('from', '');
		assign('from', $from);		
		return view();
	}
	//注册
	public function reg($req) {
		if ($this->isPost()) {
			$validate = validate('member', 'reg');
			if (!$validate->check($req) || !$validate->token()) {
				$this->error($validate->getError());
			}
			$data = $validate->getData();
			$r = M('member')->add($data);
			$this->_jump(['注册成功','注册失败'], $r, 'login/login/from/'.$data['username']);
		}
		return view();
	}
	//退出
	public function logout() {
		session(null);
		$this->_jump('退出成功', 1, $_SERVER['HTTP_REFERER']);
	}
}