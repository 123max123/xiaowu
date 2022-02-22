<?php
namespace app\controller;
class SingleController{
	use \wiphp\Jump;
	//单页面
	public function page($pn = 'about') {		
		$vo = M('danye')->where('pageid', $pn)->find();
		if (!$vo) {
			$this->error('页面不存在');
		}
		assign('vo', $vo);
		return view();	
	}
}