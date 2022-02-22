<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
{if $status==1}
<meta http-equiv="refresh" content="5;url={$url}"/> 
{/if}
<title>提示</title>
<style type="text/css">
<!--
.boxinfo {max-width:730px;margin:60px auto;}
.alert {font-size:14px;padding:8px 35px 8px 14px;color:#c09853;text-shadow:0 1px 0 rgba(255,255,255,0.5);background-color:#fcf8e3;border:1px solid #fbeed5;}
.alert .close {float:right;position:relative;top:-2px;right:-21px;line-height:20px;color:#999;font-size:13px;padding:0 6px;text-decoration:none;}
.alert .close:hover {color:#F00;text-decoration:none;}
.alert-error0 {color:#b94a48;background-color:#f2dede;border-color:#eed3d7;}
.alert-error1 {color:#468847;background-color:#dff0d8;border-color:#d6e9c6;}
p,p a {color:#333333;font-size:14px;}
-->
</style>
</head>
<body>
<div class="boxinfo">
	<div class="alert alert-error{$status}">
		<a href="{$url}" class="close">x</a>
		<strong>跳转提示：</strong> {$info}
	</div>		
	<p class="meta"><a href="{$url}" class="more">返回</a></p> 
	<p class="copyright">&copy; 2020-{:date('Y')} <a href="http://www.113344.com" target="_blank">__WPHP__</a></p>
</div>
</body>
</html>