<?php
namespace app\controller;
class ProfileController extends BaseController{
	public function info($uid){		
		$vo = M('member')->where('id', $uid)->find();
		assign('vo', $vo);
		return view();
	}
	public function index(){
		$this->checkLogin();
		$uid = $this->login_uid;
		$vo = M('member')->where('id', $uid)->find();		
		assign('vo', $vo);
		return view();
	}
	public function updprofile($req) {
		$this->checkLogin();
		$uid = $this->login_uid;
		if ($this->isPost()) {
			$validate = validate('member', 'update');
			if (!$validate->check($req) || !$validate->token()) {
				$this->error($validate->getError());
			}
			$data = $validate->getData();
			$r = M('member')->where('id='.$uid)->update($data);
			if ($r) session('nick_name', $data['nickname']);
			$this->_jump(['修改成功','修改失败'], $r, 'book/'.$this->bid);
		}
		$vo = M('member')->where('id', $uid)->find();
		assign('vo', $vo);
		return view();
	}
	public function updpwd($req) {
		$this->checkLogin();
		if ($this->isPost()) {
			$uid = $this->login_uid;
			$oldpwd = I('oldpwd');
			$newpwd = I('newpwd');
			$repwd = I('repwd');
			if (empty($oldpwd) || empty($newpwd) || empty($repwd)) {
				$this->error('输入不能为空');
			}
			if ($repwd != $newpwd) {
				$this->error('两次输入的新密码不一致');
			}
			$vo = M('member')->field('id,userpwd')->where('id='.$uid)->find();
			if ($vo) {
				if (md5($oldpwd) != $vo['userpwd']) {
					$this->error('旧密码输入不正确');
				}
				if (!valid_token()) $this->error('请勿重复提交');
				$r = M('member')->where('id='.$vo['id'])->setField('userpwd', md5($newpwd));
				$this->_jump(['修改成功','修改失败'], $r, 'book/'.$this->bid);
			} else {
				$this->error('用户不存在');
			}
		}
		return view();
	}
}