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
	<div class="row">
		<div class="col-xs-12">	
			<h4 class="blog-post-meta1">修改资料<span class="pull-right"><a href="javascript:history.back(-1);">返回</a></span></h4>
			<div class="blog-post-content">
				<form class="form-horizontal submit-ajax" role="form" action="<?php echo __URL__; ?>/profile/updprofile" method="post">
				  <input type="hidden" name="token" value="<?php echo get_token(); ?>" />		
				  <input type="hidden" name="id" value="<?php echo $vo['id']; ?>" />	
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
				      <input type="text" name="qq" class="form-control" style="width:auto;" placeholder="请输入QQ号" value="<?php echo $vo['qq']; ?>" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="col-sm-2 control-label">邮箱</label>
				    <div class="col-sm-10">
				      <input type="text" name="email" class="form-control" style="width:auto;" placeholder="请输入邮箱" value="<?php echo $vo['email']; ?>" />
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
		</div>    
	</div><!--/row-->  	
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