<?php
namespace app\widget;
use wiphp\Widget;
class DanyeWidget extends Widget {
	protected $name = 'danye';
	protected $time = 0;
	public function setData($sid = '', $options = []) {
		$data = M('danye')->where('pageid', $sid)->find();
		return $data;
	}
}