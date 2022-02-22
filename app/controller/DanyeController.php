<?php
namespace app\controller;
class DanyeController extends BaseController{
	public function __construct(){
		parent::__construct();
		$this->checkSysAdmin();
	}	

	public function index(){		
		$map = $pset = [];
		$pset['psize'] = 10;
		$pset['purl'] = 'danye/index';
		page_table('danye', $map, $pset, '', 'id ASC');		
		return view();
	}
	//添加
	public function add($req) {
		if ($this->isPost()) {
			if (!valid_token()) $this->error('请刷新页面后再操作');
			$data = [];
			$data['pageid'] = clear_html($req['pageid']);
			$data['pname'] = clear_html($req['pname']);
			$data['ptitle'] = clear_html($req['ptitle']);
			$data['thumb'] = $req['thumb'];
			$data['content'] = $req['content'];
			$data['uptime'] = time();
			$r = M('danye')->add($data);
			$this->_jump(['添加成功','添加失败'], $r, 'danye/index');
		}
		return view();
	}
	//编辑
	public function edit($id, $req) {
		if ($this->isPost()) {
			if (!valid_token()) $this->error('请刷新页面后再操作');
			$data = [];
			$data['pageid'] = clear_html($req['pageid']);
			$data['pname'] = clear_html($req['pname']);
			$data['ptitle'] = clear_html($req['ptitle']);
			$data['thumb'] = $req['thumb'];
			$data['content'] = $req['content'];
			$data['uptime'] = time();
			$r = M('danye')->where('id', $req['id'])->update($data);
			if ($r) W('danye')->delData($data['pageid']);
			$this->_jump(['修改成功','修改失败'], $r, 'danye/index');
		}
		$vo = M('danye')->where('id', $id)->find();
		assign('vo', $vo);
		return view();
	}
	//删除单个
	public function del_id($id = 0) {
		$r = M('danye')->where('id', $id)->del();
		$this->_jump(['删除成功','删除失败'], $r, 'danye/index');
	}
	//批量删除
	public function del_ids($ids) {
		if (empty($ids)) {
			$this->error('请选择id');
		}
		$r = M('danye')->where('id', 'in', $ids)->del();
		$this->_jump(['删除成功','删除失败'], $r, 'danye/index');
	}
}