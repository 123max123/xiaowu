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
	<h4 class="blog-post-title1 mtn">修改用户</h4>				
	<form class="form-horizontal submit-ajax" role="form" action="<?php echo U('member/edit'); ?>" method="post">
	  <input type="hidden" name="token" value="<?php echo get_token(); ?>" />
	  <input type="hidden" name="id" value="<?php echo $vo['id']; ?>" />
	  <div class="form-group">
	    <label class="col-sm-2 control-label">用户名</label>
	    <div class="col-sm-10">
	      <p class="form-control-static"><?php echo $vo['username']; ?></p>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">级别</label>
	    <div class="col-sm-10"> 			    
	    	<select name="level" class="form-control" style="width:auto;">
	    	<?php foreach((array)$ulevel as $k => $lv) { ?>	
	    		<option value="<?php echo $k; ?>" <?php if ($vo['level']==$k) {?> selected="selected"<?php }?>><?php echo $lv; ?></option>
	    	<?php }?>
			</select>
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">*昵称</label>
	    <div class="col-sm-10">
	      <input type="text" name="nickname" class="form-control" style="width:auto;" placeholder="请输入昵称" value="<?php echo $vo['nickname']; ?>" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">简介</label>
	    <div class="col-sm-10">
	      <input type="text" name="about" class="form-control" style="width:auto;" placeholder="请输入简介" value="<?php echo $vo['about']; ?>" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">QQ</label>
	    <div class="col-sm-10">
	      <input type="text" name="qq" class="form-control" style="width:auto;" placeholder="请输入QQ" value="<?php echo $vo['qq']; ?>" />
	    </div>
	  </div>	
	  <div class="form-group">
	    <label class="col-sm-2 control-label">邮箱</label>
	    <div class="col-sm-10">
	      <input type="text" name="email" class="form-control" style="width:auto;" placeholder="请输入邮箱" value="<?php echo $vo['email']; ?>" />
	    </div>
	  </div>			  			  		  			  
	  <div class="form-group">
	    <label class="col-sm-2 control-label">新密码</label>
	    <div class="col-sm-10">
	      <input type="password" name="userpwd" class="form-control" style="width:auto;" placeholder="不修改请留空" value="" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">确认密码</label>
	    <div class="col-sm-10">
	      <input type="password" name="repass" class="form-control" style="width:auto;" placeholder="不修改请留空" value="" />
	    </div>
	  </div> 
  		  
	  <div class="form-group">
	    <label class="col-sm-2 control-label">&nbsp;</label>
	    <div class="col-sm-10">
	      	<input type="submit" value="修改" class="btn btn-primary" />	
	    </div>
	  </div>
	</form>	

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