{* Template Name:评论模块 *}
{if $socialcomment}
{$socialcomment}
{else}
<!--评论框-->
{template:commentpost}
{if $article.CommNums>0}
<h2 class="mtb2">评论列表：</h2>
{/if}
<label id="AjaxCommentBegin"></label>
<!--评论输出-->
{foreach $comments as $key => $comment}
{template:comment}
{/foreach}
<!--评论翻页条输出-->
<div class="Jz52-pagebar">
{template:pagebar}
</div>
<label id="AjaxCommentEnd"></label>
{/if}