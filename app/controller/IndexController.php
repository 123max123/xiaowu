<?php
namespace app\controller;
class IndexController extends BaseController{
	public function index(){
		C('debug_close', 1);
		return view();
	}
	public function about() {
		$vo = W('danye')->getData('about');
		if (!$vo) {
			$this->error('页面不存在');
		}
		assign('vo', $vo);
		$tg = 'http://'.$_SERVER['SERVER_NAME'].__URL__.'/book/2.html';
		assign('tg', $tg);
		return view();	
	}
}