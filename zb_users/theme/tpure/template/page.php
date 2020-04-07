{*Template Name:通栏文章模板无侧栏*}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        <div class="mask"></div>
        <div class="wrap">
            {if $zbp->Config('tpure')->PostSITEMAPON=='1'}
            <div class="sitemap">{$lang['tpure']['sitemap']}<a href="{$host}">{$lang['tpure']['index']}</a> > 
                {if $type=='article'}{if is_object($article.Category) && $article.Category.ParentID}<a href="{$article.Category.Parent.Url}">{$article.Category.Parent.Name}</a> >{/if} <a href="{$article.Category.Url}">{$article.Category.Name}</a> > {/if}{$lang['tpure']['text']}
            </div>
            {/if}
            {if $article.Type==ZC_POST_TYPE_ARTICLE}
                {template:post-widesingle}
            {else}
                {template:post-widepage}
            {/if}
        </div>
    </div>
    {template:footer}