{php} $listrelated = qianjiangteam_51wzt_relatedPost($article){/php}
<div class="related">
    <h4 class="related-title">相关文章</h4>
    <ul class="related-list">
        {if $listrelated}
            {foreach $listrelated as $list}
            <li>
                <img class="img-responsive" src="{$list['img_url']}" alt="{$list['title']}" />
                <a href="{$list['url']}">{$list['title']}</a>
            </li> 
            {/foreach}
        {/if}
    </ul>
</div>