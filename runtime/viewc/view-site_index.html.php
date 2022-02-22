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
	<h4 class="blog-post-title1 mtn">网站配置 | <a href="javascript:actionLink('<?php echo U('site/confsave'); ?>','生成配置文件');">生成配置</a> | <a href="javascript:actionLink('<?php echo U('api/clear'); ?>','清除缓存');">清除缓存</a>
	文件位置：<code>app/config/site.php</code> 调用方式：<code>C('键名')</code>
	</h4>

	<div class="row mtn">	
		<div class="col-xs-2">名称</div>
		<div class="col-xs-2">键名</div>
		<div class="col-xs-6">键值</div>
		<div class="col-xs-2">操作</div>
	</div>
				
    <?php foreach((array)$conf as $vo) { ?>	
	    <form id="myform<?php echo $vo['id']; ?>" method="post" class="submit-ajax" action="<?php echo U('site/index'); ?>">
	    <input type="hidden" name="id" value="<?php echo $vo['id']; ?>"/>		
		<div class="row">
		    <div class="col-lg-2 mtn">
		      <input type="text" name="cname" value="<?php echo $vo['cname']; ?>" class="form-control" placeholder="输入名称"/>
		    </div>
		    <div class="col-lg-2 mtn">
		      <input type="text" name="ckey" value="<?php echo $vo['ckey']; ?>" class="form-control" placeholder="输入键名"/>
		    </div>
		    <div class="col-lg-6 mtn">
		      <input type="text" name="cvalue" value="<?php echo $vo['cvalue']; ?>" class="form-control" placeholder="请输入值"/>
		    </div>
		    <div class="col-lg-2 mtn">
		      <input type="submit" class="btn btn-primary" value="修改" />
		      <a href="javascript:actionLink('<?php echo U('site/del_id',['id'=>$vo['id']]); ?>','删除',2);" class="btn btn-danger">删除</a>
		    </div>				    
		</div>
	    </form>	
		<?php }?>	
		<form id="myformadd" method="post" class="submit-ajax" action="<?php echo U('site/index'); ?>">		
		<div class="row">
		    <div class="col-lg-2 mtn">
		      <input type="text" name="cname" value="" class="form-control" placeholder="输入名称"/>
		    </div>
		    <div class="col-lg-2 mtn">
		      <input type="text" name="ckey" value="" class="form-control" placeholder="输入键名"/>
		    </div>
		    <div class="col-lg-6 mtn">
		      <input type="text" name="cvalue" value="" class="form-control" placeholder="请输入值"/>
		    </div>
		    <div class="col-lg-2 mtn">
		      <input type="submit" class="btn btn-success" value="添加" />
		    </div>				    
		</div>
	    </form>		

		<div class="blog-post-meta1 mtn">注意：键值最多250个字符。修改或添加后要先生成配置文件，再清除缓存才能生效！</div>	

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