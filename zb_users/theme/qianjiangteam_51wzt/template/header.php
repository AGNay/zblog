<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
    {template:meta}
	<link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/style/style.css" type="text/css">
	<link rel="shortcut icon" href="{$host}zb_users/theme/{$theme}/needfile/images/favicon.ico">
    <link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/needfile/css/bs.min.css" type="text/css">
    <link rel="stylesheet" href="{$host}zb_users/theme/{$theme}/needfile/css/qianjingteamindex.css" type="text/css">
	<script src="{$host}zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="{$host}zb_system/script/zblogphp.js" type="text/javascript"></script>
	<script src="{$host}zb_system/script/c_html_js_add.php" type="text/javascript"></script>
	<!--[if lt IE 9]><div class="fuck-ie"><p class="tips">*您的IE浏览器版本过低，为获得更好的体验请使用Chrome、Firefox或其他现代浏览器!</p></div><![endif]-->
</head>
<body>
	<div class="container-fluid qjt-nav01">
		<nav class="navbar navbar-default">
			<div class="container">
			  <!-- mobile -->
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-1" aria-expanded="false">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{$host}">
					<img src="{$host}zb_users/theme/{$theme}/needfile/images/front-logo.png" alt="logo-首页" title="首页"/>
				</a>
			  </div>
		  
			  <div class="collapse navbar-collapse" id="bs-navbar-1">
				<form class="navbar-form navbar-right" method="post" action="{$host}zb_system/cmd.php?act=search">
					<div class="form-group">
					  <input type="text" name="q" class="form-control" placeholder="搜索点啥吧...">
					</div>
					<button type="submit" class="btn btn-default">搜索</button>
				</form>
				{php} $cateList = qianjiangteam_51wzt_GetCateList();{/php}
				<ul class="nav navbar-nav navbar-right hidden-sm">
					<li>
						<a href="{$host}">首页</a>
					</li>
					{foreach $cateList as $list}
						{if empty($list['sub_cate'])}
							<li>
								<a href="{$list['cate_url']}">{$list['cate_name']}</a>
							</li>
						{else}
							<li class="dropdown">
								<a href="{$list['cate_url']}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$list['cate_name']}<span class="caret"></span></a>
								<ul class="dropdown-menu">
									{foreach $list['sub_cate'] as $sublist}
									<li><a href="{$sublist['cate_url']}">{$sublist['cate_name']}</a></li>
									{/foreach}
								</ul>
							</li>
						{/if}      
    				{/foreach}
				</ul>
			  </div>
			</div>
		  </nav>
	</div>
