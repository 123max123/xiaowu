<?php
/**
 * 控制器跳转 Trait
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-07-26
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
trait Jump {
	protected $isAjax = false;
	/**
	 * 操作成功跳转
	 * @access protected
	 * @param  mixed $msg 提示信息
	 * @param  string $url 跳转的URL地址
	 * @return void
	 */
	protected function success($msg = '', $url = null) {
		$url = is_null($url)? '' : U($url);
		$result = ['status' => 1, 'info' => $msg, 'url' => $url];
		if ($this->isAjax()) {
			exit(json($result));
		}
		view('public:jump', $result);
		exit();
	}
	/**
	 * 操作错误跳转
	 * @access protected
	 * @param  mixed $msg 提示信息
	 * @param  string $url 跳转的URL地址
	 * @return void
	 */
	protected function error($msg = '', $url = null) {
		$url = is_null($url)? 'javascript:history.back(-1);' : U($url);
		$result = ['status' => 0, 'info' => $msg, 'url' => $url];
		if ($this->isAjax()) {
			exit(json($result));
		}
		view('public:jump', $result);
		exit();
	}
	/**
	 * 操作跳转合同并
	 * @access protected
	 * @param mixed $info 提示信息
	 * @param number $result 操作结果
	 * @param string $url 跳转的URL地址
	 * @return void
	 */
	protected function _jump($info, $result = 0, $url = null) {		
		$msg = [];
		if (is_array($info)) {
			$msg = $info;
		} else {
			$msg[0] = $msg[1] = $info;
		}
		if ($result) {
			$this->success($msg[0], $url);
		} else {
			$this->error($msg[1]);
		}
	}
	/**
	 * URL重定向
	 * @access protected
	 * @param  string $url 跳转的URL表达式
	 * @return void
	 */
	protected function _url($url) {
		redirect(U($url));
	}
	/**
	 * 是否是POST提交
	 * @return bool
	 */
	protected function isPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : false;
	}
	/**
	 * 是否是Ajax提交
	 * @return bool
	 */
	protected function isAjax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}
		return $this->isAjax;
	}	
}