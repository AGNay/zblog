{* Template Name:首页及列表页 *}

{template:header}

{template:adv}

<div class="container maindiv js-main-div">
    {template:pageinfo}

    <div class="container qjt-post01 qjt-padding-right">
        {foreach $articles as $article}
            {template:post-multi}
        {/foreach}
    </div>

    <div class="container qjt-paginate01 qjt-padding-right">
        {template:pagebar}
    </div>

    

    <div class="container qjt-sidebar01 hidden-sm hidden-xs js-sider-bar">
        {if $type=='index' && $page=='1'}
            {template:sidebar}
        {else}
            {template:sidebar2}
        {/if}
    </div>
    

</div>

{template:footer}