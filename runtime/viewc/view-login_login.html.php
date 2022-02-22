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
			<h4 class="blog-post-meta1">用户登录<span class="pull-right"><a href="<?php echo __URL__; ?>/book/<?php echo session('gbook_bid'); ?>">返回</a></span></h4>					
			<div class="blog-post-content">		
				<form class="form-horizontal submit-ajax" role="form" action="<?php echo __URL__; ?>/login/login" method="post">
				  <input type="hidden" name="token" value="<?php echo get_token(); ?>" />		
				  <div class="form-group">
				    <label class="col-sm-2 control-label">用户名</label>
				    <div class="col-sm-10">
				      <input type="text" name="username" class="form-control" style="width:auto;" placeholder="请输入用户名" value="<?php echo $from; ?>" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="col-sm-2 control-label">密码</label>
				    <div class="col-sm-10">
				      <input type="password" name="userpwd" class="form-control" style="width:auto;" placeholder="请输入密码" value="" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="col-sm-2 control-label">验证码</label>
				    <div class="col-sm-10">
				      <input name="captcha" type="text" class="form-control" style="width:auto;display: inline-block;" placeholder="请输入验证码" />
				      <img src="<?php echo __URL__; ?>/api/captcha" style="height:30px;cursor:pointer;" align="top" onclick="this.src='<?php echo __URL__; ?>/api/captcha?'+Math.random();" />								
				    </div>
				  </div>				  		  
				  <div class="form-group">
				    <label class="col-sm-2 control-label">&nbsp;</label>
				    <div class="col-sm-10">
				      	<input type="submit" value="登录" class="btn btn-primary" />		
				      	<a href="<?php echo U('login/reg'); ?>">注册</a>	      	
				    </div>
				  </div>
				</form>	
			
			</div>
		</div>    
	</div><!--/row--> 
</div> 		
<div class="container1">	
	<footer>
		<p><span class="z">&copy;<?php echo date('Y'); ?> <a href="http://www.113344.com" target="__blank">一鱼留言本v1.2</a> Time: <?php echo __RUNTIME__; ?> s</span> <span class="y">Powered by <a href="http://www.113344.com" target="__blank" class="blue"><?php echo __WPHP__; ?></a></span></p>
	</footer>	
</div>
<script src="<?php echo __PUBLIC__; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/layer_mobile/layer.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/willphp.js"></script>	
</body>
</html>