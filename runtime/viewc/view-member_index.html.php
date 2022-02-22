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
	<h4 class="blog-post-title1 mtn">用户管理 | <a href="<?php echo U('member/add'); ?>">添加用户</a></h4>

				
			<button type="button" class="btn btn-success" onclick="javascript:actionAll('启用','<?php echo __URL__; ?>/member/open_ids');">启用</button>
			<button type="button" class="btn btn-info" onclick="javascript:actionAll('停用','<?php echo __URL__; ?>/member/stop_ids');">停用</button>
			<button type="button" class="btn btn-danger" onclick="javascript:actionAll('删除','<?php echo __URL__; ?>/member/del_ids');">删除</button>					
			
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="50"><input type="checkbox" onclick="selectAll(this.checked)" /></th>
							<th>ID</th>
							<th>用户名</th>
							<th>昵称</th>
							<th>等级</th>		
	                      	<th>注册时间</th>                                 
	                        <th>状态</th>
	                        <th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach((array)$list as $vo) { ?>				
						<tr>
							<td><input type="checkbox" name="ids" value="<?php echo $vo['id']; ?>" /> </td>
							<td><?php echo $vo['id']; ?></td>
	                          	<td><a href="<?php echo __URL__; ?>/member/edit/id/<?php echo $vo['id']; ?>"><?php echo $vo['username']; ?></a></td>
	                          	<td><?php echo $vo['nickname']; ?></td>  
	                          	<td><?php echo trim($ulevel[$vo['level']]); ?></td>                         
	                          	<td><?php echo date('Y-m-d',$vo['ctime']); ?></td>                             
	                          	<td>
					            <?php if ($vo['status'] == 1) {?>	
					               <a href="javascript:actionLink('<?php echo U('member/status_id',['id'=>$vo['id']]); ?>','停用',2);" class="btn btn-xs btn-success">已启用</a>
					            <?php }else {?>
					               <a href="javascript:actionLink('<?php echo U('member/status_id',['id'=>$vo['id']]); ?>','启用',2);" class="btn btn-xs btn-info">已停用</a>
					            <?php }?>
	                          	</td>
	                          <td>
								<a href="<?php echo U('member/edit',['id'=>$vo['id']]); ?>" class="btn btn-xs btn-primary">编辑</a>
								<a href="javascript:actionLink('<?php echo U('member/del_id',['id'=>$vo['id']]); ?>','删除',2);" class="btn btn-xs btn-danger">删除</a>
	                          </td>
						</tr>
					<?php }?>
					</tbody>
				</table>
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