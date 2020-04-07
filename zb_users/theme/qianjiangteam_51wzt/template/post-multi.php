<div class="row col-xs-12">
    <div class="media">
        <div class="media-left">
            <a target="_blank" href="{$article.Url}" title="{$article.Title}">
                <img class="media-object" src="{php}qianjiangteam_51wzt_firstImg($article){/php}" alt="{$article.Title}" title="{$article.Title}">
            </a>
        </div>
        <div class="media-body">
            <a target="_blank" href="{$article.Url}" title="{$article.Title}">
                <h4 class="media-heading">
                    {if $article.IsTop}<span class="settop">[置顶]</span>{/if}{$article.Title}
                </h4>
            </a>
            <div class="hidden-xs subtext">	
                {if strlen($article.Intro)>0}
                    {php}$intro= trim(SubStrUTF8(TransferHTML(str_replace('&nbsp;','',$article->Intro),'[nohtml]'),85)).'...';{/php}
                {else}
                    {php}$intro= trim(SubStrUTF8(TransferHTML(str_replace('&nbsp;','',$article->Intro),'[nohtml]'),85)).'...';{/php}
                {/if}
                {$intro}
            </div>
            <div class="post-info">
                <a target="_blank" href="{$article.Category.Url}"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span><i>{$article.Category.Name}</i></a>
                <span>•</span>
                <span class="hidden-xs"><i>{$article.Time('Y-m-d')}</i></span>	
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><i>{$article.ViewNums}阅读</i>	 
                <span class="hidden-xs glyphicon glyphicon-edit" aria-hidden="true"></span><i class="hidden-xs">{$article.CommNums}评论</i>
            </div>
        </div>
    </div>
</div>