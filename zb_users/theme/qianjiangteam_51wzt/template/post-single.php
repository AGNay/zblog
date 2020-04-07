<!-- 文章内容 post-single -->
<div class="container qjt-post-single01 qjt-padding-right">
    <div class="row col-xs-12">
        <div class="article">
            <div class="current-position">
                您的位置<span>></span>首页-{$type}
                {if $article.Category.Parent}
                {if $article.Category.Parent.Parent}
                <span>></span>{$article.Category.Parent.Parent.Name}
                {/if}
                <span>></span>{$article.Category.Parent.Name}
                {/if}
                <span>></span>{$article.Category.Name}
            </div>
            <h4 class="title">{$article.Title}</h4>
            <div class="single-info">
                <span class="hidden-sm hidden-xs">作者：{$article.Author.Name}</span>
                <span>发布：{$article.Time('Y-m-d')}</span>
                <span class="hidden-xs"><i class="glyphicon glyphicon-eye-open"></i>{$article.ViewNums}阅读</span>
                <span class="hidden-xs"><a href="#comment"><i class=" glyphicon glyphicon-edit"> </i>{$article.CommNums}评论</a></span>
                <div class="share hidden-xs">
                    分享到<span class="weibo"></span><span class="weixin"></span><span class="qq"></span><span class="qzone"></span>
                </div>
            </div>
            <div class="brief-introduct">
                {if strlen($article.Intro)>0}
                    {php}$intro= trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),85)).'...';{/php}
                {else}
                    {php}$intro= trim(SubStrUTF8(TransferHTML($article->Content,'[nohtml]'),85)).'...';{/php}
                {/if}
                {$intro}
            </div>
            <div class="post-content">
                {$article.Content}
            </div>
            
            <div class="single-credit">
                本文为{$article.Author.Name}原创文章，转载请保留版权和出处:{$article.Url}
            </div>
            
            <!-- <div class="thumbs-up">
                <a href="javascript:void(0);"><span class="glyphicon glyphicon-thumbs-up"></span>0赞</a>
            </div> -->

            <div class="single-tags">
                <span>标签：</span>
                {foreach $article.Tags as $tag}<a href="{$tag.Url}" target="_blank" title="{$tag.Name}">{$tag.Name}</a>{/foreach}

                <div class="share hidden-xs">
                    分享到<span class="weibo"></span><span class="weixin"></span><span class="qq"></span><span class="qzone"></span>
                </div>
            </div>

        </div>

        {template:post-related}

        {template:comments}

        {template:commentpost}
    </div>
</div>