<?php
namespace app\controller;
class EmptyController{
	use \wiphp\Jump;
	public function empty(){
		$this->error(__MODULE__.'->'.__ACTION__.'() 不存在。');
	}
}