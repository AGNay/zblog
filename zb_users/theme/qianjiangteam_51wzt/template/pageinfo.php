    <div class="container qjt-pageinfo01 qjt-padding-right">
        <div class="row col-xs-12">
            {if $type=='index' && $page=='1'} {* 首页 *}
                <h4>最新文章</h4>
            {elseif $type=='category'} {* 分类页面 *}
                <h4>{$category.Name}</h4>
            {elseif $type=='tag'} {* tag *}
                <h4>{$tag.Name}</h4>
            {else}
                <h4>{$title}</h4>
            {/if}
        </div>
    </div>
