<?php
/**
 * Page.php
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021年7月21日 上午11:17:42
 */ 
namespace extend;
class Page {
	private $total = 0;	//总记录数
	private $psize = 1; //每页数量
	private $nowpn = 1; //当前页号
	private $purl = ''; //url
	private $ptotal = 0; //总页数
	private $showpn = 5; //显示页码数
	private $options = array('home'=>'首页','end'=>'尾页','up'=>'上一页','down'=>'下一页','pre'=>'上n页','next'=>'下n页','header'=>'条记录','pg'=>'页','theme'=>1);
	private $_repl = array('%total%','%header%','%page%','%ptotal%','%pg%','%home%','%up%','%pre%','%links%','%next%','%down%','%end%');
	private $_html = '[%total% %header%] [%page%/%ptotal% %pg%] %home% %up% %pre% %links% %next% %down% %end%';
	
	public function __construct($pset = array()) {
		$this->total = max(0, intval($pset[0]));
		$this->psize = max(1, intval($pset[1]));
		$this->nowpn = max(1, intval($pset[2]));
		$this->purl = ($pset[3])? $pset[3] : '?p={page}';
		if ($this->total > 0) {
			$this->ptotal = ceil($this->total/$this->psize);
			if ($this->nowpn > $this->ptotal) $this->nowpn = $this->ptotal;
		}
	}
	private function __clone() {}
	public function setHtml($html) {
		$this->_html = $html;
	}
	public function setConf($name, $value) {
		if (array_key_exists($name, $this->options)) {
			$this->options[$name] = strip_tags($value);
		} elseif ($name == 'showpn') {
			$this->showpn = max(2, intval($value));
		}
	}
	//获取limit
	public function getLimit() {
		$start = $this->psize * ($this->nowpn - 1);
		return $start.','.$this->psize;
	}
	//处理链接
	protected function _html_link($name, $pn) {
		if ($pn > 0) {
			$url = str_replace('{page}', $pn, $this->purl);
			return '[<a href="'.$url.'">'.$name.'</a>]';
		}
		return '';
	}
	//获取pagehtml
	public function getHtml($class = 'pager', $avt = 'selected') {
		$home = $end = $up = $down = $pre = $next = '';
		//首页
		$home = $this->_html_link($this->options['home'], 1);
		if ($this->nowpn < $this->ptotal) {
			//尾页
			$end = $this->_html_link($this->options['end'], $this->ptotal);
		}
		if ($this->nowpn > 1) {
			//上一页
			$up = $this->_html_link($this->options['up'], $this->nowpn-1);
		}
		if ($this->nowpn < $this->ptotal) {
			//下一页
			$down = $this->_html_link($this->options['down'], $this->nowpn+1);
		}
		$cpn = ceil($this->ptotal/$this->showpn); //总分组页数
		$npn = ceil($this->nowpn/$this->showpn); //当前分组页数
	
		if ($npn > 1) {
			//上5页
			$pre_n = str_replace('n', $this->showpn, $this->options['pre']);
			$pre = $this->_html_link($pre_n, $this->nowpn-$this->showpn);
		}
		if ($npn < $cpn) {
			$nextpn = $this->nowpn + $this->showpn;
			if ($nextpn > $this->ptotal) $nextpn = $this->ptotal;
			if ($this->nowpn < $this->ptotal) {
				//下5页
				$next_n = str_replace('n', $this->showpn, $this->options['next']);
				$next = $this->_html_link($next_n, $nextpn);
			}
		}
		$links = '';
		for ($i=1; $i<=$this->showpn; $i++) {
			$pn = ($npn - 1) * $this->showpn + $i;
			if ($pn != $this->nowpn){
				if ($pn <= $this->ptotal) {
					$links .= $this->_html_link($pn, $pn);
				} else {
					break;
				}
			} elseif ($this->ptotal > 1) {
				$links .= '[<a href="#" class="'.$avt.'">'.$pn.'</a>]';
			}
		}
		$pdata = array($this->total,$this->options['header'],$this->nowpn,$this->ptotal,$this->options['pg'],$home,$up,$pre,$links,$next,$down,$end);
		$html = str_replace($this->_repl, $pdata, $this->_html);
		if ($this->options['theme'] == 1) {
			$html = str_replace(array('[',']'),' ',$html);
			return '<div class="'.$class.' cl">'.$html.'</div>';
		} elseif ($this->options['theme'] == 2) {
			$html = str_replace(array('[',']'),array('<li>','</li>'),$html);
			return '<ul class="'.$class.' cl">'.$html.'</ul>';
		}
		return '<div class="'.$class.' cl">'.$html.'</div>';
	}	
}