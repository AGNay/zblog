
<div class="comment-lists hidden-xs">
    <h4 class="comment-lists-title">评论列表</h4>
    <div class="comments">
        <label id="AjaxCommentBegin"></label>

        {if $article.CommNums>0}
            <!--评论输出-->
            {foreach $comments as $key => $comment}
                {template:comment}
            {/foreach}

        {/if}
        <label id="AjaxCommentEnd"></label>
    </div>
</div>
