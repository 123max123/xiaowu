<?php
namespace app\controller;
class MemberController extends BaseController{
	protected $ulevel = [1=>'会员','管理','后台'];
	public function __construct(){
		parent::__construct();
		$this->checkSysAdmin();
	}	
	public function index(){			
		$map = $pset = [];
		$pset['psize'] = 5;
		$pset['purl'] = 'member/index';
		$username = I('username');
		if ($username != '') {
			$map[] = ['username', 'like', '%'.$username.'%'];
			$pset['purl'] .= '/username/'.urlencode($username);
		}
		page_table('member', $map, $pset);		
		assign('ulevel', $this->ulevel);
		return view();
	}
	//添加
	public function add($req) {
		if ($this->isPost()) {
			$validate = validate('member', 'reg');
			if (!$validate->check($req) || !$validate->token()) {
				$this->error($validate->getError());
			}
			$data = $validate->getData();
			$r = M('member')->add($data);
			$this->_jump(['添加成功','添加失败'], $r, 'member/index');
		}
		return view();
	}
	//编辑
	public function edit($id, $req) {
		if ($this->isPost()) {
			$uid = $this->login_uid;
			if ($id == 1 && $uid != 1) {
				$this->error('你没有权限修改此用户');
			}
			if ($id == 1 && $req['level'] < 3) {
				$this->error('不能修改管理员的后台身份');
			}
			$validate = validate('member', 'update');
			if (!$validate->check($req) || !$validate->token()) {
				$this->error($validate->getError());
			}
			$data = $validate->getData();
			if (empty($data['userpwd'])) {
				unset($data['userpwd']);
			} else {
				$data['userpwd'] = md5($data['userpwd']);
			}
			$r = M('member')->where('id='.$id)->update($data);
			$this->_jump(['修改成功','修改失败'], $r, 'member/index');
		}
		$vo = M('member')->where('id', $id)->find();
		assign('vo', $vo);		
		assign('ulevel', $this->ulevel);
		return view();
	}
	//启用或停用单个
	public function status_id($id = 0) {
		$r = false;
		$act = '停用';
		if ($id > 1) {
			$r = M('member')->where('id', $id)->where('status', 1)->setField('status', 0);
			if (!$r) {
				$act = '启用';
				$r = M('member')->where('id', $id)->where('status', 0)->setField('status', 1);
			}
		}
		$this->_jump([$act.'成功','操作失败'], $r, 'member/index');
	}
	//删除单个
	public function del_id($id = 0) {
		$r = false;
		if ($id > 1) {
			$r = M('member')->where('id', $id)->where('status', 0)->del();
		}
		$this->_jump(['删除成功','删除失败，请停用后再删除'], $r, 'member/index');
	}
	//批量删除
	public function del_ids($ids) {
		if (empty($ids)) {
			$this->error('请选择id');
		}
		$r = M('member')->where('id','<>', 1)->where('status', 0)->where('id', 'in', $ids)->del();
		$this->_jump(['删除成功','删除失败'], $r, 'member/index');
	}
	//批量启用
	public function open_ids($ids) {
		if (empty($ids)) {
			$this->error('请选择id');
		}
		$r = M('member')->where('status', 0)->where('id', 'in', $ids)->setField('status', 1);
		$this->_jump(['启用成功','启用失败'], $r, 'member/index');
	}
	//批量停用
	public function stop_ids($ids) {
		if (empty($ids)) {
			$this->error('请选择id');
		}
		$r = M('member')->where('id','<>', 1)->where('status', 1)->where('id', 'in', $ids)->setField('status', 0);
		$this->_jump(['停用成功','停用失败'], $r, 'member/index');
	}
}