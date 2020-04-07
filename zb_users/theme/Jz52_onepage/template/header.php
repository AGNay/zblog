{* Template Name:公用头部模块 *}
<!doctype html>
<html lang="{$lang['lang_bcp47']}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>{$name}-{$title}</title>
<meta name="generator" content="{$zblogphp}">
<link href="{$host}zb_users/theme/{$theme}/style/style.css" rel="stylesheet" type="text/css">
<script src="{$host}zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="{$host}zb_system/script/zblogphp.js" type="text/javascript"></script>
<script src="{$host}zb_system/script/c_html_js_add.php"></script>
<script src="{$host}zb_users/theme/{$theme}/script/custom.js"></script>
{$header}
{if $type=='index'&&$page=='1'}
<link rel="alternate" type="application/rss+xml" href="{$feedurl}" title="{$name}">
{/if}
</head>
<body class="is-preload">
<header id="header">
  <div class="content">
    <h1><a href="{$host}">{$zbp->Config('Jz52_onepage')->headtite}</a></h1>
    <p>{$zbp->Config('Jz52_onepage')->headp}</p>
    <ul class="actions">
      <li><a href="{$zbp->Config('Jz52_onepage')->headbutu}" class="button primary icon solid fa-download"  target="_blank">{$zbp->Config('Jz52_onepage')->headbut}</a></li>
      <li><a href="#one" class="button icon solid fa-chevron-down scrolly">{$zbp->Config('Jz52_onepage')->headbut1}</a></li>
    </ul>
  </div>
  <div class="image phone">
    <div class="inner"><img src="{$zbp->Config('Jz52_onepage')->headimg}" alt=""></div>
  </div>
</header>