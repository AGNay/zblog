{* Template Name:文章/单页 *}
{template:header}
{if $article.Type==ZC_POST_TYPE_ARTICLE}
<section id="one" class="wrapper">
  <div class="inner alt">
  <div class="breadcrumbs"> {if $type=='article'}<a href="{$host}">首页</a>&gt; <a href="{$article.Category.Url}">{$article.Category.Name}</a>&gt; 正文 {elseif $type=='category'}<a href="{$host}">首页</a>&gt; <a href="{$category.Url}">{$category.Name}</a> {elseif $type=='index'} {else}<a href="{$host}">首页</a>&gt; {$title}{/if} </div>
    <section>
      <h2>{$article.Title}</h2>
    <section class="Content"> {$article.Content} </section>
    {if !$article.IsLock}
    {template:comments}
    {/if} 
      </div>
    </section>
  </div>
</section>
{else}
<section id="one" class="wrapper">
  <div class="inner alt">
  <div class="breadcrumbs"> {if $type=='article'}<a href="{$host}">首页</a>&gt; <a href="{$article.Category.Url}">{$article.Category.Name}</a>&gt; 正文 {elseif $type=='category'}<a href="{$host}">首页</a>&gt; <a href="{$category.Url}">{$category.Name}</a> {elseif $type=='index'} {else}<a href="{$host}">首页</a>&gt; {$title}{/if} </div>
    <section>
      <h2>{$article.Title}</h2>
    <section class="Content"> {$article.Content} </section>
    {if !$article.IsLock}
    {template:comments}
    {/if} 
      </div>
    </section>
  </div>
</section>
{/if}
{template:footer}