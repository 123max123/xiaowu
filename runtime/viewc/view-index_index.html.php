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
    
    .iframebox {
        position: absolute;
        top: 51px;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container1">
        <!-- 导航头部 -->
        <div class="navbar-header ">
            <a href="<?php echo __URL__; ?>" class="navbar-brand" title="<?php echo C('site_logo'); ?>"><img src="<?php echo __PUBLIC__; ?>/img/logo.png" height="25" /></a>
            <button class="navbar-toggle collapsed" data-toggle='collapse' data-target='.collapse'>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                
            </button>
        </div>
        <!-- 导航链接 -->
        <div class="collapse navbar-collapse">
            <!-- 默认的导航 -->
            <ul class="nav navbar-nav">
                <li><a href="<?php echo U('gbook/index',['bid'=>0]); ?>" target="gbookiframe" ">留言</a></li>
                <li><a href="<?php echo U( 'gbook/index',[ 'bid'=>1]); ?>" target="gbookiframe">评论</a>
                </li>
                <li><a href="<?php echo U('index/about'); ?>" target="gbookiframe">关于</a></li>
                <?php if (session('cplevel')==3) {?>
                <li><a href="<?php echo U('admin/index'); ?>" target="gbookiframe">管理</a></li>
                <?php }?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!session('user_id')) {?>
                <li><a href="<?php echo U('login/login'); ?>" target="gbookiframe">登录</a></li>
                <?php }else {?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle='dropdown'>
                	<?php echo session('user_name'); ?>
                	<span class="caret"></span>	
                    <ul class="dropdown-menu">
                		<li><a href="<?php echo U('profile/index'); ?>" target="gbookiframe">个人中心</a></li>
                <li><a href="<?php echo U('profile/updprofile'); ?>" target="gbookiframe">修改资料</a></li>
                <li><a href="<?php echo U('profile/updpwd'); ?>" target="gbookiframe">修改密码</a></li>
                <li><a href="javascript:actionLink('<?php echo U('login/logout'); ?>','退出')">退出登录</a></li>
            </ul>
            </li>
            <?php }?>


            </ul>
        </div>

    </div>
</nav>
<div class="cl" style="height:51px;"></div>

<div class="container1 myiframe">
    <div class="iframebox">
        <iframe src="<?php echo U('gbook/index',['bid'=>0]); ?>" name="gbookiframe" width="100%" height="100%" frameborder="0" border="0" marginwidth="0" scrolling="auto"></iframe>
    </div>
</div>
<script src="<?php echo __PUBLIC__; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/layer_mobile/layer.js"></script>
<script src="<?php echo __PUBLIC__; ?>/js/willphp.js"></script>
<script>
    $('.iframebox').height($(window).height() - 56);
</script>
</body>

</html>