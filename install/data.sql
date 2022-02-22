DROP TABLE IF EXISTS `wp_danye`;
CREATE TABLE IF NOT EXISTS `wp_danye` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pageid` varchar(16) NOT NULL DEFAULT '',
  `pname` varchar(100) NOT NULL,
  `ptitle` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `uptime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='单页表';
INSERT INTO `wp_danye` (`id`, `pageid`, `pname`, `ptitle`, `content`, `thumb`, `uptime`) VALUES
(1, 'about', '关于', '关于', '一鱼留言评论系统', '', 1631079216),
(2, 'angbook', '留言公告', '公告', '一鱼留言评论系统v1.2发布了  交流QQ群： 325825297', '', 1631086445);
DROP TABLE IF EXISTS `wp_gbook`;
CREATE TABLE IF NOT EXISTS `wp_gbook` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `qq` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `ip` int(11) NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL DEFAULT '',
  `pcontent` varchar(255) NOT NULL DEFAULT '',
  `rcontent` varchar(255) NOT NULL DEFAULT '',
  `ruid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `rname` varchar(60) NOT NULL DEFAULT '',
  `ctime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rtime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `good` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `istop` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='留言表';
INSERT INTO `wp_gbook` (`id`, `bid`, `uid`, `name`, `qq`, `email`, `url`, `ip`, `address`, `pcontent`, `rcontent`, `ruid`, `rname`, `ctime`, `rtime`, `good`, `istop`, `status`) VALUES
(1, 0, 1, '无念', '24203741', '', ' ', 2130706433, '', '第一条留言。', ' ', 0, '', 1631086113, 0, 0, 0, 1);
DROP TABLE IF EXISTS `wp_member`;
CREATE TABLE IF NOT EXISTS `wp_member` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT ' ',
  `userpwd` varchar(64) NOT NULL DEFAULT ' ',
  `nickname` varchar(60) NOT NULL DEFAULT ' ',
  `about` varchar(120) NOT NULL DEFAULT '',
  `headimg` varchar(100) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT ' ',
  `level` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `isadmin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ctime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `adminname` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';
INSERT INTO `wp_member` (`id`, `username`, `userpwd`, `nickname`, `about`, `headimg`, `qq`, `email`, `level`, `isadmin`, `status`, `ctime`) VALUES
(1, 'admin', 'c4ca4238a0b923820dcc509a6f75849b', '无念', '管理员', '', '', '', 3, 1, 1, 1626854240);
DROP TABLE IF EXISTS `wp_site`;
CREATE TABLE IF NOT EXISTS `wp_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(50) NOT NULL,
  `ckey` char(15) NOT NULL,
  `cvalue` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='配置表';
INSERT INTO `wp_site` (`id`, `cname`, `ckey`, `cvalue`) VALUES
(1, '网站标题', 'site_title', '一鱼留言评论系统'),
(2, '关键词', 'site_kw', '一鱼留言本,willphp,php框架,PHP留言本'),
(3, '网站描述', 'site_desc', '一个用willphp框架开发的留言评论系统'),
(4, '默认公告', 'site_about', '一鱼留言评论系统v1.2发布了，交流QQ群： 325825297'),
(5, '首页h1', 'site_h1', '一鱼留言评论'),
(6, '首页h2', 'site_h2', '一个用willphp框架开发的留言评论系统'),
(7, 'logo文字', 'site_logo', 'willphp'),
(8, '留言说明', 'gbook_about', '文明上网，理性发言！填写QQ将自动获取QQ头像！');
DROP TABLE IF EXISTS `wp_voteip`;
CREATE TABLE IF NOT EXISTS `wp_voteip` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ip` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ctime` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='点赞表';
COMMIT;