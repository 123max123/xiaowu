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
<div class="container1">
	<h4 class="blog-post-meta1 mtn"><?php echo $site_an; ?></h4>
	<div class="gbook-form mtn" id="gookform">
  <form class="form-horizontal submit-ajax" method="post" action="<?php echo U('gbook/add'); ?>">
  <input type="hidden" name="token" value="<?php echo get_token(); ?>"/>
  <input type="hidden" name="bid" value="<?php echo $bid; ?>"/> 
  	 <?php if (!session('user_id')) {?> 	
	 <div class="form-group">
	    <label for="name" class="col-sm-2 control-label">昵称</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="name" id="name" placeholder="请输入昵称"/>
	    </div>
	  </div>	 
	 <div class="form-group">
	    <label for="qq" class="col-sm-2 control-label">QQ</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="qq" id="qq" placeholder="请输入QQ号码"/>
	    </div>
	  </div>
	  <?php }?>	  
	  <div class="form-group">
	    <label for="pcontent" class="col-sm-2 control-label">评论</label>
	    <div class="col-sm-10">
	    	<textarea class="form-control" name="pcontent" id="pcontent" rows="3" placeholder="请输入评论内容"></textarea>
	    </div>
	  </div>
	 <div class="form-group">
	    <label for="captcha" class="col-sm-2 control-label">验证</label>
	    <div class="col-sm-5">		   
		    <input type="text" name="captcha" id="captcha" class="form-control" placeholder="请输入验证码"/>	    	
	    </div>
	    <div class="col-sm-5" style="margin-top:3px;">
	    	<img src="<?php echo __URL__; ?>/api/captcha" align="middle" style="cursor:pointer;" onclick="this.src='<?php echo __URL__; ?>/api/captcha?'+Math.random();"/>	    	
	    </div>	    
	  </div>	  
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary">提交评论</button>
	    </div>
	  </div>   
  </form>
</div>
	
<div class="blog-post-meta mtn cl" style="margin-bottom:5px;">
	<span class="z">共有评论 <?php echo $ptotal; ?> 条</span>
	<span class="y">	
    <?php if (session('cplevel')==3) {?>
		<a href="<?php echo U('admin/index'); ?>">管理</a> | 
	<?php }?>			
	<?php if (!session('user_id')) {?>
		<a href="<?php echo U('login/login'); ?>">登录</a>
	<?php }else {?>	
		<a href="<?php echo U('profile/index'); ?>"><?php echo session('nick_name'); ?></a> | <a href="<?php echo U('profile/updprofile'); ?>">资料</a> | <a href="<?php echo U('profile/updpwd'); ?>">改密</a> | <a href="javascript:actionLink('<?php echo U('login/logout'); ?>','退出')">退出</a>
	<?php }?>		
	</span>
</div>

<?php foreach((array)$list as $k => $vo) { ?>
<?php $k = $k+1+$pz; ?>
	<div class="media">
		<div class="media-left media-top">
			<?php if (empty($vo['qq'])) {?>
			<img src="<?php echo __PUBLIC__; ?>/img/head.png" class="media-object"/>
			<?php }else {?>
			<img src="http://q1.qlogo.cn/g?b=qq&nk=<?php echo $vo['qq']; ?>&s=100&t=<?php echo time(); ?>" class="media-object"/>
			<?php }?>
		</div>	
		<div class="media-body">
			<div class="media-heading"><span class="user">网友：<?php if (($vo['uid']>0)) {?><a href="<?php echo U('profile/info',['uid'=>$vo['uid']]); ?>"><?php echo $vo['name']; ?></a><?php }else {?><?php echo $vo['name']; ?><?php }?></span> <span class="time">时间：<?php echo format_date($vo['ctime']); ?></span>
				
				<span class="pull-right"><?php if ($vo['istop']==1) {?><span class="red">[置顶]</span><?php }?> #<?php echo $k; ?></span>
			</div>
			<div class="media-content">
				<?php echo $vo['pcontent']; ?>			
				<?php if (!empty(trim($vo['rcontent']))) {?>
					<p class="re">&nbsp;&nbsp;<strong class="blue"><a href="<?php echo U('profile/info',['uid'=>$vo['ruid']]); ?>"><?php echo $vo['rname']; ?></a> 回复</strong>：<span><?php echo $vo['rcontent']; ?></span></p>
				<?php }?>
				<?php if (session('cplevel')>=2) {?>
				<div id="rebox-id<?php echo $vo['id']; ?>" style="display:none;">
					<form method="post" class="form-inline submit-ajax" action="<?php echo U('gbook/replay'); ?>">						
						<input type="hidden" name="gid" value="<?php echo $vo['id']; ?>"/>
			            <div class="form-group">
		                   	<input type="text" name="rcontent" class="form-control" placeholder="请输入回复内容..." value="<?php echo $vo['rcontent']; ?>"/>
		               	</div>
		               	<button class="btn btn-primary">回复</button>			
					</form>				
				</div>
				<?php }?>			
			</div>
			<div class="media-footer">
				<span class="pull-left address">
				<?php echo format_ip($vo['ip']); ?>
				<?php if (session('cplevel')>=2) {?><a href="javascript:;" onclick="javascript:$('#rebox-id<?php echo $vo['id']; ?>').toggle();" title="回复评论">回复</a> | <a href="javascript:actionLink('<?php echo U('gbook/del',['id'=>$vo['id']]); ?>','删除',2);">删除</a> | <a href="javascript:actionLink('<?php echo U('gbook/status_top',['gid'=>$vo['id']]); ?>','置顶',2);">置顶</a><?php }?>
				</span> 
				<span class="pull-right"><a class="good" href="javascript:ajaxLink('<?php echo U('gbook/good',['gid'=>$vo['id']]); ?>',2);">赞一下（<?php echo $vo['good']; ?>）</a></span>							
			</div>	
		</div>
	</div>
<?php } if (empty($list)) { ?>
	<div class="blog-post-meta">暂无评论！</div>
<?php }?>
<?php echo $phtml; ?>	
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