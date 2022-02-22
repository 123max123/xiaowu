<?php
namespace app\controller;
class SiteController extends BaseController{
	public function __construct(){
		parent::__construct();
		$this->checkSysAdmin();
	}	
	public function index($req){			
		$site = M('site');
		if ($this->isPost()) {
			if (empty($req['cname']) || empty($req['ckey']) || $req['cvalue'] == '') {
				$this->error('输入不能为空');
			}
			if (isset($req['id']) && $req['ckey'] == 'del') {
				$r = $site->where('id', $req['id'])->del();
			} else {
				$r = $site->save($req);
			}
			$this->_jump(['操作成功', '操作失败'], $r ,'site/index');
		}
		$conf = $site->order('id ASC')->select();
		assign('conf', $conf);
		return view();
	}
	public function confsave() {		
		$conf = M('site')->order('id ASC')->field('ckey,cvalue')->select();
		$config = [];
		foreach ($conf as $c) {
			$config[$c['ckey']] = $c['cvalue'];
		}
		$config = var_export($config, true);
		file_put_contents(WPHP_URI.'/app/config/site.php', "<?php\nreturn ".$config.';');
		$this->_jump('生成配置文件成功', 1, 'site/index');
	}
	public function del_id($id = 0) {
		$r = M('site')->where('id', $id)->del();
		$this->_jump(['删除成功','删除失败'], $r, 'site/index');
	}
}