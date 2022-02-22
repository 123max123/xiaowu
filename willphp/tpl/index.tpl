<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>{:C('site_title')}</title>
<style type="text/css">
* {word-wrap:break-word;}
body,input,button,select,textarea {font:14px;color:#333;}
textarea {resize:none;}
body,ul,ol,li,dl,dd,p,h1,h2,h3,h4,h5,h6,form,fieldset,.pr,.pc {margin:0;padding:0;}
table {empty-cells:show;border-collapse:collapse;}
caption,th {text-align:left;font-weight:400;}
ul li,.xl li {list-style:none;}
h1,h2,h3,h4,h5,h6 {font-size:1em;}
em,cite,i {font-style:normal;}
a img {border:none;}
label {cursor:pointer;}
a {text-decoration:none;color:#337ab7;}
a:hover {color:#073893;}
.z {float:left;}
.y {float:right;}
.cl:after {content:".";display:block;height:0;clear:both;visibility:hidden;}
.cl {zoom:1;}
body {font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:1.5;color:#333;}
.header {border-bottom:1px solid #e5e5e5;margin-bottom:15px;padding:10px 0;}
.nav-pills > li {float:left;}
.nav > li {position:relative;display:block;}
.nav > li > a {position:relative;display:block;padding:10px 15px;background-color:#ddd;}
.nav-pills > li.active > a,.nav-pills > li.active > a:focus,.nav-pills > li.active > a:hover {color:#fff;background-color:#337ab7;}
.container {max-width:700px;margin:0 auto;}
h1 {font-size:50px;}
.title_line {font-size:20px;padding:10px 0;border-bottom:1px solid #e5e5e5;margin-bottom:15px;}
.jumbotron {background-color:#eee;padding:35px 0;text-align:center;}
.jumbotron p {margin-bottom:15px;font-size:21px;font-weight:200;}
.marketing {margin:20px 0;clear:both;padding:10px;}
.col-lg-6 {width:50%;float:left;padding:15px 0;}
.col-lg-6 h4 {padding:10px 0;}
.marketing p {margin:0 10px 0 0;}
p {margin:0 0 10px;}
.btn {display:inline-block;font-size:14px;text-align:center;vertical-align:middle;cursor:pointer;padding:6px 12px;border-radius:4px;}
a.btn:hover {color:#fff;}
.btn-success {color:#fff;background-color:#5cb85c;border-color:#4cae4c;}
.btn-primary {color:#fff;background-color:#337ab7;border-color:#2e6da4;}
.btn-warning {color:#fff;background-color:#f0ad4e;border-color:#eea236;}
.btn-danger {color:#fff;background-color:#d9534f;border-color:#d43f3a;}
.footer {margin-top:10px;padding-top:10px;padding-bottom:10px;color:#777;border-top:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;}
</style>
</head>
<body>	
<div class="container">
	<div class="header cl">
	 	<h3 class="text-muted z"><a href="__URL__"><img src="__PUBLIC__/img/willphp.png" title="{:C('site_h1')}" /></a></h3>
	    <ul class="nav nav-pills y">
	      <li class="active"><a href="{:U('index/index')}">首页</a></li>
	      <li><a href="{:U('abc/abc')}">示例</a></li>	     
	    </ul>
	</div>
	<div class="jumbotron">
		<h1>Σ( ° △ °|||)︴</h1>
	    
	</div>
	<div class="row marketing cl">
		<div class="col-lg-6">                    
			<h4>简 单</h4>
			<p>PHP初学，入门ThinkPHP，轻量级Web开发。</p>
			<h4>易 用</h4>
			<p>封装数据库操作，轻松进行数据增删改查。</p>
		</div>
		<div class="col-lg-6">
			<h4>快 速</h4>
			<p>小于100KB的核心代码，按需加载，数据缓存。</p>                    
			<h4>安 全</h4>
			<p>防止sql注入，表单令牌，自动过滤输入输出。</p>
		</div>
	</div>		
    <footer class="footer">
      <p>&copy; {:date('Y')} WillPHPv2 Processed in __RUNTIME__ s Powered by <a href="" target="_blank">_WPHP__</a></p>
    </footer>
</div>
</body>
</html>