{*Template Name:列表页模板*}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        <div class="mask"></div>
        <div class="wrap">
            {if $zbp->Config('tpure')->PostSITEMAPON=='1'}
            <div class="sitemap">{$lang['tpure']['sitemap']}<a href="{$host}">{$lang['tpure']['index']}</a>
{if $type=='category'}
{php}
$html='';
function tpure_navcate($id){
   global $html;
   $cate = new Category;
   $cate->LoadInfoByID($id);
   $html = ' > <a href="' .$cate->Url.'" title="查看' .$cate->Name. '中的全部文章">' .$cate->Name. '</a> '.$html;
   if(($cate->ParentID)>0){tpure_navcate($cate->ParentID);}
}
tpure_navcate($category->ID);
global $html;
echo $html;
{/php}
{else}
> {$title}
{/if}
            </div>
            {/if}
            <div class="content">
                <div class="block">
                    {foreach $articles as $article}
                        {if $article.IsTop}
                        {template:post-istop}
                        {else}
                        {template:post-multi}
                        {/if}
                    {/foreach}
                </div>
                {if $pagebar && $pagebar.PageAll > 1}
                <div class="pagebar">
                    {template:pagebar}
                </div>
                {/if}
            </div>
            <div class="sidebar">
                {template:sidebar2}
            </div>
        </div>
    </div>
    {template:footer}