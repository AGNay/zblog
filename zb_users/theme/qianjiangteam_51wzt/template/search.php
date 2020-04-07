{template:header}

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
        {template:sidebar2}
    </div>
    
</div>

{template:footer}