<?php
namespace app\controller;
class GbookController extends BaseController{
	public function index(){
		$bid = I('bid');
		$url = 'gbook/index';
		session('gbook_bid', $bid);		
		$map = [];
		$map['bid'] = $bid;
		$map['status'] = 1;
		$gbook = M('gbook');
		$count = $gbook->where($map)->count();
		$list = [];
		$phtml = '';
		$pz = 0;
		if ($count > 0) {
			$p = I('p', 1, 'intval');
			$psize = 10;
			$purl = $url.'/bid/'.$bid;
			$page = P($count, $psize, $p, $purl.'/p/{page}');
			$limit = $page->getLimit();
			$orderby = ($bid > 0)? 'istop DESC,good DESC,id ASC' : 'istop DESC,good DESC,id DESC';
			$list = $gbook->where($map)->order($orderby)->limit($limit)->select();
			$phtml = $page->getHtml();
			list($pz,) = explode(',', $limit);
		}
		if ($bid == 0) {
			$an = W('danye')->getData('angbook');			
			$site_an = $an['content'];
		} else {
			$site_an = C('site_about');
		}		
		assign('site_an', $site_an);
		assign('bid', $bid);
		assign('ptotal', $count);
		assign('list', $list);
		assign('phtml', $phtml);
		assign('pz', $pz);
		//$admin = M('member')->where('level','>', 1)->getField('id,nickname');
		//assign('admin', $admin);	
		$vfile = ($bid > 0)? 'ping' : 'index';
		return view($vfile);
	}
	//添加留言
	public function add($req) {		
		if ($this->isPost()) {			
			$uid = $this->login_uid;
			if ($uid > 0) {
				//用户留言
				$mvo = M('member')->where('id', $uid)->field('id,nickname,qq,email')->find();
				$req['uid'] = $uid;
				$req['name'] = $mvo['nickname'];
				$req['qq'] = $mvo['qq'];
				$req['email'] = $mvo['email'];
			}				
			$validate = validate('gbook', 'add');
			if (!$validate->check($req) || !$validate->token()) {
				$this->error($validate->getError());
			}
			$data = $validate->getData();
			$r = M('gbook')->add($data);
			$act = ($this->bid > 0)? '评论' : '留言';
			$this->_jump([$act.'成功',$act.'失败'], $r, 'book/'.$this->bid);
		}
		return view();
	}
	//回复留言
	public function replay($gid = 0) {
		$this->checkAdmin();
		if ($this->isPost()) {			
			$rcontent = I('rcontent', '', 'clear_html');
			$data = [];
			$data['rcontent'] = $rcontent;
			if (empty($rcontent)) {
				$data['rtime'] = 0;
				$data['ruid'] = 0;
			} else {
				$data['rtime'] = time();
				$data['ruid'] = $this->login_uid;
				$data['rname'] = session('nick_name');
			}
			$r = M('gbook')->where('id', $gid)->update($data);
			$this->_jump(['回复成功', '回复失败'], $r, 'book/'.$this->bid);
		}
		$this->error('非法操作');
	}
	//删除留言
	public function del($id = 0) {
		$this->checkAdmin();
		if ($this->isAjax()) {
			$r = M('gbook')->where('id', $id)->del();
			$this->_jump(['删除成功','删除失败'], $r, 'book/'.$this->bid);
		}
		$this->error('非法操作');
	}
	//点赞
	public function good($gid) {
		if ($this->isAjax()) {
			$ip = ip2long(get_ip());
			$isgood = M('voteip')->where('bid', $gid)->where('ip', $ip)->find();
			if ($isgood) {
				$this->error('您已点赞');
			} else {
				$data = [];
				$data['bid'] = $gid;
				$data['ip'] = $ip;
				$data['ctime'] = time();
				$r = M('voteip')->add($data);
				$r = M('gbook')->where('id='.$gid)->setInc('good');
				$this->_jump(['点赞成功','点赞失败'], $r, 'book/'.$this->bid);
			}
		}
	}
	//置顶
	public function status_top($gid) {		
		$this->checkAdmin();		
		$act = '置顶';	
		$r = M('gbook')->where('id', $gid)->where('istop', 0)->setField('istop', 1);
		if (!$r) {
			$act = '取消置顶';
			$r = M('gbook')->where('id', $gid)->where('istop', 1)->setField('istop', 0);
		}		
		$this->_jump([$act.'成功','操作失败'], $r, 'book/'.$this->bid);		
	}
}