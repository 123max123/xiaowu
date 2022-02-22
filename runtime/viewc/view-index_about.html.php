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
<style type="text/css">
body {
	overflow: hidden;
}
.myiframe {
	position: relative;
}
.iframebox {
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;	
}
</style>
<div class="container mtn">
	<h4 class="blog-post-title1"><?php echo $vo['ptitle']; ?><span class="pull-right"><a href="<?php echo __URL__; ?>/book/<?php echo session('gbook_bid'); ?>">首页</a></span></h4>	
	<div class="blog-post-content">
		<?php echo $vo['content']; ?>			
	</div>
	<div class="blog-post-title1">评论iframe调用绑定ID2：<pre> &lt;iframe src="<?php echo $tg; ?>"&gt;&lt;/iframe&gt;</pre></div>
	<div class="myiframe">
		<div class="iframebox">
			<iframe src="<?php echo U('gbook/index',['bid'=>2]); ?>" name="gbookiframe" width="100%" height="100%" frameborder="0" border="0" marginwidth="0" scrolling="auto"></iframe>
		</div>
	</div>	
</div>
<div class="container1">	
	<footer>
		
	</footer>	
</div>
<script src="<?php echo __PUBLIC__; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/layer_mobile/layer.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/willphp.js"></script>
<script>
$('.myiframe, .iframebox').height($(window).height());
</script>
</body>
</html>