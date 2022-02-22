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
	<h4 class="blog-post-title1 mtn">修改页面</h4>				
	<form class="form-horizontal submit-ajax" role="form" action="<?php echo U('danye/edit'); ?>" method="post" onsubmit="javascript:editorgetcontent();">
	  <input type="hidden" name="token" value="<?php echo get_token(); ?>" />
	  <input type="hidden" name="id" value="<?php echo $vo['id']; ?>" />	
	  <div class="form-group">
	    <label class="col-sm-2 control-label">名称</label>
	    <div class="col-sm-10">
	       <input type="text" name="pname" class="form-control" placeholder="请输入页面名称" value="<?php echo $vo['pname']; ?>" />
	    </div>
	  </div>		  	
	  <div class="form-group">
	    <label class="col-sm-2 control-label">标识</label>
	    <div class="col-sm-10">
	       <input type="text" name="pageid" class="form-control" placeholder="请输入页面标识" value="<?php echo $vo['pageid']; ?>" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">标题</label>
	    <div class="col-sm-10">
	      <input type="text" name="ptitle" class="form-control" placeholder="请输入页面标题" value="<?php echo $vo['ptitle']; ?>" />
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-sm-2 control-label">内容</label>
	    <div class="col-sm-10">
	      <textarea id="editor" name="content" style="display: none;"><?php echo $vo['content']; ?></textarea>
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
<script src="<?php echo __PUBLIC__; ?>/js/hdeditor/HandyEditor.min.js" type="text/javascript"></script>
<script type="text/javascript">
var he = HE.getEditor('editor',{
	  autoHeight : true,
	  uploadPhoto : true,
	  uploadPhotoHandler : '<?php echo __URL__; ?>/api/editorupload',
	  uploadPhotoSize : 1048576, //1M 
	  uploadPhotoType : 'gif,png,jpg,jpeg',
	  uploadPhotoSizeError : '不能上传大于1MB的图片',
	  uploadPhotoTypeError : '只能上传gif,png,jpg,jpeg格式的图片',
	  skin : 'myeditor',
	  item : ['html','|','bold','underline','paragraph','color','backColor','|','link','unlink','|','textBlock','code','|','image','expression','|','orderedList','unorderedList','|','undo','redo','selectAll','removeFormat','trash']
	});
function editorgetcontent(){	
	$('#editor').val(he.getHtml());
}
</script>
</body>
</html>