<?php
function get_ip_int() {
	$ip = get_ip();
	return ip2long($ip);
}
//格式化IP
function format_ip($ip) {
	if (ctype_digit($ip)) {
		$ip = long2ip($ip);
	}	
	return preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/is',"$1.$2.*.$4",$ip);
}
//格式时间
function format_date($time){
	$t=time()-$time;
	$f=array(
			'31536000'=>'年前',
			'2592000'=>'个月前',
			'604800'=>'星期前',
			'86400'=>'天前',
			'3600'=>'小时前',
			'60'=>'分钟前',
			'1'=>'秒前'
	);
	foreach ($f as $k=>$v)    {
		if (0 !=$c=floor($t/(int)$k)) {
			return $c.$v;
		}
	}
}
//默认显示
function def($val, $def = '') {
	return !empty($val)? $val : $def;
}
//是否是手机端
function is_mobile() {	
	$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
	$mobile_browser = '0';
	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
		$mobile_browser++;
	if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
		$mobile_browser++;
	if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
		$mobile_browser++;
	if(isset($_SERVER['HTTP_PROFILE']))
		$mobile_browser++;
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
	$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda','xda-'
	);
	if(in_array($mobile_ua, $mobile_agents))
		$mobile_browser++;
	if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
		$mobile_browser++;
	// Pre-final check to reset everything if the user is on Windows
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
		$mobile_browser=0;
	// But WP7 is also Windows, with a slightly different characteristic
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
		$mobile_browser++;
	if($mobile_browser>0)
		return 1;
	else
		return 0;
}