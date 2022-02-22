<?php
/**
 * 初始化应用
 * @copyright 2020-2021 WillPHP
 * @author NoMind<24203741@qq.com/113344.com>
 * @version WillPHPv2
 * @since 2021-09-06
 */
namespace wiphp;
defined('WPHP_URI') or die('Access Denied');
class Build {
	private function __construct() {}
	private function __clone() {}
	public static function run() {
		$lock = WPHP_URI.'/app/build.lock';
		if (!is_writable($lock)) {
			if (!touch($lock)) {
				exit('应用目录不可写，请手动生成app目录，并设置为权限为0777！');
			}
			cache(null);
			if (!file_exists(WPHP_URI.'/app/common.php')) {
				file_put_contents(WPHP_URI.'/app/common.php', "<?php\n//自定义函数文件");
			}
			if (!is_dir(PATH_RUNTIME)) mkdir(PATH_RUNTIME, 0777, true);
			if (!is_dir(APP_PATH)) mkdir(APP_PATH, 0755, true);	
			$vpath = (THEME_ON)? PATH_VIEW.'/default' : PATH_VIEW;
			$dirs = array();
			$dirs[] = APP_PATH.'/config';
			$dirs[] = APP_PATH.'/controller';
			$dirs[] = APP_PATH.'/model';
			$dirs[] = APP_PATH.'/widget';
			$dirs[] = APP_PATH.'/validate';
			$dirs[] = PATH_VIEW;
			$dirs[] = $vpath.'/index';
			$dirs[] = $vpath.'/public';
			$dirs[] = $vpath.'/abc';
			foreach ($dirs as $dir) {
				if(!is_dir($dir)) mkdir($dir, 0755, true);
			}
			//首页模板
			$v_index = file_get_contents(WPHP_PATH.'/tpl/index.tpl');
			file_put_contents($vpath.'/index/index.html', $v_index);
			//转跳模板
			$v_jump = file_get_contents(WPHP_PATH.'/tpl/jump.tpl');
			file_put_contents($vpath.'/public/jump.html', $v_jump);
			//演示模板
			$v_abc = file_get_contents(WPHP_PATH.'/tpl/abc.tpl');
			file_put_contents($vpath.'/abc/abc.html', $v_abc);
			//默认控制器
			$c_index = "<?php\nnamespace app\\controller;\nclass IndexController{\n\tpublic function index(){\n\t\treturn view();\n\t}\n}";
			file_put_contents(APP_PATH.'/controller/IndexController.php', $c_index);
			//空控制器和空操作
			$c_empty = "<?php\nnamespace app\\controller;\nclass EmptyController{\n\tuse \\wiphp\\Jump;\n\tpublic function empty(){\n\t\t\$this->error(__MODULE__.'->'.__ACTION__.'() 不存在。');\n\t}\n}";
			file_put_contents(APP_PATH.'/controller/EmptyController.php', $c_empty);
			//API控制器
			$c_api = "<?php\nnamespace app\\controller;\nclass ApiController{\n\tuse \\wiphp\\Jump;\n\tpublic function captcha(){\n\t\treturn E('captcha')->create();\n\t}\n\tpublic function clear(){\n\t\tcache(null);\n\t\t\$this->_jump('清除缓存成功', 1, 'index/index');\n\t}\n}";
			file_put_contents(APP_PATH.'/controller/ApiController.php', $c_api);
			//基控制器
			$c_base = "<?php\nnamespace app\\controller;\nabstract class BaseController{\n\tuse \\wiphp\\Jump;\n\tpublic function __construct(){}\n}";
			file_put_contents(APP_PATH.'/controller/BaseController.php', $c_base);
			//演示Abc控制器
			$c_abc = "<?php\nnamespace app\\controller;\nclass AbcController extends BaseController{\n\tpublic function abc(){\n\t\treturn view();\n\t}\n}";
			file_put_contents(APP_PATH.'/controller/AbcController.php', $c_abc);
			//演示路由配置
			$route = "<?php\nreturn [\n\t'/demo' => ['abc/abc', '*'],\n];";
			file_put_contents(APP_PATH.'/config/route.php', $route);			
			unlink($lock);
		}
	}
}