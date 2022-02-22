<!doctype html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?php echo C('site_title'); ?></title>  
    <meta name="keywords" content="<?php echo C('site_kw'); ?>"/>
	<meta name="description" content="<?php echo C('site_desc'); ?>"/>    
    <link rel="stylesheet" href="<?php echo __PUBLIC__; ?>/js/bootstrap/css/bootstrap.min.css"/>  	
    <link rel="stylesheet" href="<?php echo __PUBLIC__; ?>/css/style.css?v=<?php echo time(); ?>"/>  	
  </head>
<body>
<div class="container mtn">
	<div class="blog-post-meta1">
	<a href="<?php echo U('admin/index'); ?>">管理中心</a> | <a href="<?php echo U('member/index'); ?>">用户管理</a> | <a href="<?php echo U('site/index'); ?>">网站配置</a> | <a href="<?php echo U('danye/index'); ?>">单页管理</a> | <a href="javascript:actionLink('<?php echo U('api/clear'); ?>','清除缓存');">清除缓存</a> 
	<span class="pull-right"><a href="<?php echo __URL__; ?>/book/<?php echo session('gbook_bid'); ?>">首页</a></span>
</div>
	<h4 class="blog-post-title1 mtn">管理中心 | <a href="javascript:actionLink('<?php echo U('admin/cleardb'); ?>','清空数据');">清空数据</a></h4>
	<table class="table table-striped">	
	  <tbody>
	  <?php foreach((array)$info as $k => $v) { ?>
	    <tr>
	      <td><?php echo $k; ?></td>
	      <td><?php echo $v; ?></td>			     
	    </tr>
	  <?php }?>	
	  </tbody>
	</table>	
	
</div>
<div class="container1">	
	<footer>
		
	</footer>	
</div>
<script src="<?php echo __PUBLIC__; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/layer_mobile/layer.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/willphp.js"></script>
</body>
</html>