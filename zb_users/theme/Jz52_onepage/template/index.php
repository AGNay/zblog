{* Template Name:首页列表模板 *}
{template:header}
{if $type=='index'}
<section id="one" class="wrapper style2 special">
  <header class="major">
    <h2>{$zbp->Config('Jz52_onepage')->onet}</h2>
  </header>
  <ul class="icons major">
  {$zbp->Config('Jz52_onepage')->oneico}
  </ul>
</section>
<section id="two" class="wrapper">
  <div class="inner alt">
    <section class="spotlight">
      <div class="image"><img src="{$zbp->Config('Jz52_onepage')->two1img}" alt="{$zbp->Config('Jz52_onepage')->two1t}"></div>
      <div class="content">
        <h3><a href="{$zbp->Config('Jz52_onepage')->two1u}" title="{$zbp->Config('Jz52_onepage')->two1t}">{$zbp->Config('Jz52_onepage')->two1t}</a></h3>
        <p>{$zbp->Config('Jz52_onepage')->two1p}</p>
      </div>
    </section>
    <section class="spotlight">
      <div class="image"><img src="{$zbp->Config('Jz52_onepage')->two2img}" alt="{$zbp->Config('Jz52_onepage')->two2t}"></div>
      <div class="content">
        <h3><a href="{$zbp->Config('Jz52_onepage')->two2u}" title="{$zbp->Config('Jz52_onepage')->two2t}">{$zbp->Config('Jz52_onepage')->two2t}</a></h3>
        <p>{$zbp->Config('Jz52_onepage')->two2p}</p>
      </div>
    </section>
    <section class="spotlight">
      <div class="image"><img src="{$zbp->Config('Jz52_onepage')->two3img}" alt="{$zbp->Config('Jz52_onepage')->two3t}"></div>
      <div class="content">
        <h3><a href="{$zbp->Config('Jz52_onepage')->two3u}" title="{$zbp->Config('Jz52_onepage')->two3t}">{$zbp->Config('Jz52_onepage')->two3t}</a></h3>
        <p>{$zbp->Config('Jz52_onepage')->two3p}</p>
      </div>
    </section>
    <section class="special">
      <ul class="icons labeled">
	  {$zbp->Config('Jz52_onepage')->twoico}
      </ul>
    </section>
  </div>
</section>
<section id="three" class="wrapper style2 special">
  <header class="major">
    <h2>{$zbp->Config('Jz52_onepage')->threet}</h2>
    <p>{$zbp->Config('Jz52_onepage')->threep}</p>
  </header>
  <ul class="actions special">
    <li><a href="{$zbp->Config('Jz52_onepage')->headbutu}" class="button primary icon solid fa-download" target="_blank">{$zbp->Config('Jz52_onepage')->headbut}</a></li>
    <li><a href="{$zbp->Config('Jz52_onepage')->threebutu}" class="button">{$zbp->Config('Jz52_onepage')->threebut}</a></li>
  </ul>
</section>
{else}
<section id="one" class="wrapper">
  <div class="inner alt">
  
  <div class="breadcrumbs"> {if $type=='article'}<a href="{$host}">首页</a>&gt; <a href="{$article.Category.Url}">{$article.Category.Name}</a>&gt; 正文 {elseif $type=='category'}<a href="{$host}">首页</a>&gt; <a href="{$category.Url}">{$category.Name}</a> {elseif $type=='index'} {else}<a href="{$host}">首页</a>&gt; {$title}{/if} </div>
  
  {foreach $articles as $article}
      {if $article.IsTop}
<section class="spotlight">
      <div class="content">
        <h3><a href="{$article.Url}#one" title="{$article.Title}">{$article.Title}</a></h3>
        <p>{php}$description = preg_replace('/[\r\n\s)]|(\s|\&nbsp\;|　|\xc2\xa0)+/', ' ', trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),33)).'...');{/php}{$description}</p>
      </div>
    </section>
      {else}
	<section class="spotlight">
      <div class="content">
        <h3><a href="{$article.Url}#one" title="{$article.Title}">{$article.Title}</a></h3>
        <p>{php}$description = preg_replace('/[\r\n\s)]|(\s|\&nbsp\;|　|\xc2\xa0)+/', ' ', trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),33)).'...');{/php}{$description}</p>
      </div>
    </section>
      {/if}
{/foreach}
  
  <ul class="pages">
{template:pagelist}
</ul>
  </div>
</section>
{/if}
{template:footer}