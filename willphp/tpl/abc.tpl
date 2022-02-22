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
.mtm {margin-top:20px;}
.mtn {margin-top:10px;}
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
.title_line {font-size:20px;padding:10px 0 10px 10px;border-bottom:1px solid #e5e5e5;margin-bottom:15px;}
p {margin:10px;}
.footer {margin-top:10px;padding-top:10px;padding-bottom:10px;color:#777;border-top:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;}
code {color: #c7254e;background-color: #f9f2f4;border-radius: 4px;}
</style>
</head>
<body>	
<div class="container">
	<div class="header cl">
	 	<h3 class="text-muted z"><a href="__URL__"><img src="__PUBLIC__/img/willphp.png" title="{:C('site_h1')}" /></a></h3>
	    <ul class="nav nav-pills y">
	      <li><a href="{:U('index/index')}">首页</a></li>
	      <li class="active"><a href="{:U('abc/abc')}">示例</a></li>	     
	    </ul>
	</div>	
	<h4 class="title_line">入门</h4>
	<p>调试模式：在index.php文件中<code>define('APP_DEBUG', true); //true开启,false关闭</code></p>	
	<p>缓存目录：<code>runtime目录下</code>的所有目录和文件(可删除)</p>	
	<p>可写目录：请确认<code> runtime, public/upload</code> 目录可写</p>
	<p>生成应用：重新生成应用时，删除 <code>app和runtime目录下</code>的所有目录和文件</p>
	<h4 class="title_line">示例</h4>
	<p>路由访问：<a href="__URL__/demo">index.php/demo</a> 实际地址：  <a href="__URL__/abc/abc">index.php/abc/abc</a></p>
	<p>空操作器：<a href="{:U('xxx/xxx')}">空操作器</a></p>	
	<p>图片引用：<img src="__PUBLIC__/img/willphp.png"/></p>
	<p>缓存操作：<a href="{:U('api/clear')}">清除缓存</a> | <span style="color:green;">修改配置文件，清除缓存才能生效(关闭调试后)</span></p>
	<p>配置引用(缓存)：{:C('site_title')}</p>
	<p>验证码图片：<img src="__URL__/api/captcha" style="cursor:pointer;" onclick="this.src='__URL__/api/captcha?'+Math.random();"/></p>
	<p>表单令牌：{:get_token()}</p>
	<p>{var $uid='test'}引用变量uid：{$uid}</p>
	<p>处理变量uid：{$uid|intval} (使用intval函数)</p>
	<p>运行时间：__RUNTIME__ s</p>
	<p>获取当前IP：{:get_ip()}</p>
	<p>{var $ctime=time()}时间处理(int)：{$ctime} 格式化：{:date('Y-m-d H:i',$ctime)}</p>
	<p>插件调用：{:hook('demo:test', '123')}</p>	
    <footer class="footer">
      <p>&copy; {:date('Y')} WillPHPv2 Processed in __RUNTIME__ s Powered by <a href="/" target="_blank">PHP__</a></p>
    </footer>
</div>
</body>
</html>