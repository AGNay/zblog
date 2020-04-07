{if $zbp->Config('qianjiangteam_51wzt')->qjt_seo_switch>0} 
{if $type=='article'}
<title>{$title}-{$name}</title>
{php}$aryTags=array();foreach($article->Tags as $key){$aryTags[]=$key->Name;}{/php}
{php}if(count($aryTags)>0){$keywords=implode(',',$aryTags);}else{$keywords=$zbp->name;}{/php}
<meta name="keywords" content="{if $article.Metas.keywords}{$article.Metas.keywords}{else}{$keywords}{/if}" />
{php}$description = preg_replace('/[\r\n\s]+/',' ',trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),150)));{/php}
<meta name="description" content="{if $article.Metas.description}{$article.Metas.description}{else}{$description}{/if}" />
{elseif $type=='page'}
<title>{if $article.Metas.arttitle}{$article.Metas.arttitle}{else}{$title}{/if}-{$name}</title>
<meta name="keywords" content="{if $article.Metas.keywords}{$article.Metas.keywords}{else}{$title}{/if}" />
{php}$description = preg_replace('/[\r\n\s]+/', ' ', trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),150)).'...');{/php}
<meta name="description" content="{if $article.Metas.description}{$article.Metas.description}{else}{$description}{/if}" />
<meta name="author" content="{$article.Author.StaticName}">
{elseif $type=='index'}
<title>{if $page=='1'}{$zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_title}{else}{$title}-{$name}{/if}</title>
<meta name="keywords" content="{$zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_keywords}">
<meta name="description" content="{$zbp->Config('qianjiangteam_51wzt')->qjt_seo_site_discribe}">
{elseif $type=='category'}
<title>{$category.Name}{if $page>'1'}-第{$pagebar.PageNow}页{/if}-{$name}</title>
<meta name="keywords" content="{$category.Name}">
<meta name="description" content="{$category.Intro}">
{elseif $type=='tag'}
<title>{$tag.Name}{if $page>'1'}-第{$pagebar.PageNow}页{/if}-{$name}</title>
<meta name="keywords" content="{$tag.Name}">
<meta name="description" content="{$tag.Intro}">
{else}
<title>{$title}-{$name}</title>
<meta name="keywords" content="">
<meta name="description" content="{$title},{$name}"> 
{/if}
{else}
<title>{$title}-{$name}</title>
<meta name="keywords" content="">
<meta name="description" content="{$title},{$name}"> 
{/if}