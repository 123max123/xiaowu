<?php
/**
 * 框架模板引擎
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-16
 */ 
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Template {
	private function __construct() {}
	private function __clone() {}	

	public static function render($viewfile, $vars = []) {		
		$shtml_open = Config::get('shtml_open', 'app');
		$vhash = App::getViewHash();
		$vars['vhash'] = $vhash;
		if (!$shtml_open || basename($viewfile) == 'jump.html') {
			self::renderTo($viewfile, $vars);
		} else {	
			$sfile = PATH_SHTML.'/'.$vhash.'.shtml';
			$shtml_time = Config::get('shtml_time', 'app');
			$ftimeok = true;
			if ($shtml_time > 0) {
				$ntime = time();
				$ftime = file_exists($sfile)? filemtime($sfile) : 0;
				$ftimeok = $ftime > ($ntime - $shtml_time);
			}
			if (is_file($sfile) && $ftimeok) {
				include $sfile;
			} else {
				ob_start();
				self::renderTo($viewfile, $vars);
				$content = ob_get_contents();
				file_put_contents($sfile, $content);
			}
		}
	}	
	public static function renderTo($viewfile, $vars = []) {
		$m = strtolower(__MODULE__);
		$cfile = 'view-'.$m.'_'.basename($viewfile).'.php';
		if (basename($viewfile) == 'jump.html') {
			$cfile = 'view-jump.html.php';
		}
		$cfile = PATH_VIEWC.'/'.$cfile;
		if (APP_DEBUG || !file_exists($cfile) || filemtime($cfile) < filemtime($viewfile)) {
			$strs = self::compile(file_get_contents($viewfile), $vars);
			file_put_contents($cfile, $strs);
		}
		extract($vars);
		include $cfile;
	}
	private static function compile($strs, $vars = []) {
		$varn = '([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)';
		$keyn = '([a-zA-Z0-9_\x7f-\xff]*)';
		$strs = preg_replace_callback('/\{\s*include\s+[\"\']?(.+?)[\"\']?\s*\}/i',function ($match) {
			return file_get_contents(THEME_PATH.'/'.$match[1]);
		}, $strs);
			if (preg_match('/\{\s*include\s+[\"\']?(.+?)[\"\']?\s*\}/i', $strs)) {
				$strs = preg_replace_callback('/\{\s*include\s+[\"\']?(.+?)[\"\']?\s*\}/i',function ($match) {
					return file_get_contents(THEME_PATH.'/'.$match[1]);
				}, $strs);
			}
			$pats = [
					'/__(\w+)__/i',
					'/\{\s*\$'.$varn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\.'.$keyn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\[[\'"]?'.$keyn.'[\'"]?\]\s*\}/i',
					'/\{\s*if\s*(.+?)\s*\}/i',
					'/\{\s*\/(foreach|if)\s*\}/i',
					'/\{\s*(else if|elseif)\s*(.+?)\s*\}/i',
					'/\{\s*else\s*\}/i',
					'/\{\s*foreach\s+\$'.$varn.'\s+as\s+\$'.$varn.'\s*\}/i',
					'/\{\s*foreach\s+\$'.$varn.'\s+as\s+\$'.$varn.'\s*=>\s*\$'.$varn.'\s*\}/i',
					'/\{\s*empty\s+\$'.$varn.'\s*\}/i',
					'/\{\s*:'.$varn.'\((.*?)\)\s*\}/i',
					'/\{\s*:'.$varn.'\((.*?)\)\->'.$varn.'\((.*?)\)\s*\}/i',
					'/\{\s*var\s+\$'.$varn.'\s*=\s*(.+?)\s*\}/i',
					'/\{\s*\$'.$varn.'\|'.$varn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\.'.$varn.'\|'.$varn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\[[\'"]'.$varn.'[\'"]\]\|'.$varn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\|'.$varn.'=(.+?)\s*\}/i',
					'/\{\s*\$'.$varn.'\.'.$varn.'\|'.$varn.'=(.+?)\s*\}/i',
					'/\{\s*\$'.$varn.'\[[\'"]'.$varn.'[\'"]\]\|'.$varn.'=(.+?)\s*\}/i',
					'/\{\s*\$'.$varn.'\.'.$keyn.'\.'.$keyn.'\s*\}/i',
					'/\{\s*\$'.$varn.'\[[\'"]?'.$keyn.'[\'"]?\]'.'\[[\'"]?'.$keyn.'[\'"]?\]\s*\}/i',
			];
			$reps = [
					'<?php echo __\\1__; ?>',
					'<?php echo $\\1; ?>',
					'<?php echo $\\1[\'\\2\']; ?>',
					'<?php echo $\\1[\'\\2\']; ?>',
					'<?php if (\\1) {?>',
					'<?php }?>',
					'<?php }elseif (\\2) {?>',
					'<?php }else {?>',
					'<?php foreach((array)$\\1 as $\\2) { ?>',
					'<?php foreach((array)$\\1 as $\\2 => $\\3) { ?>',
					'<?php } if (empty($\\1)) { ?>',
					'<?php echo \\1(\\2); ?>',
					'<?php echo \\1(\\2)->\\3(\\4); ?>',
					'<?php $\\1 = \\2; ?>',
					'<?php echo \\2($\\1); ?>',
					'<?php echo \\3($\\1[\'\\2\']); ?>',
					'<?php echo \\3($\\1[\'\\2\']); ?>',
					'<?php echo \\2($\\1,\\3); ?>',
					'<?php echo \\3($\\1[\'\\2\'],\\4); ?>',
					'<?php echo \\3($\\1[\'\\2\'],\\4); ?>',
					'<?php echo $\\1[\'\\2\'][\'\\3\']; ?>',
					'<?php echo $\\1[\'\\2\'][\'\\3\']; ?>',
			];
			$strs = preg_replace($pats, $reps, $strs);
			return $strs;	
	}
}