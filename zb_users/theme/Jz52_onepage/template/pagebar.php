{* Template Name:分页 *}
{if $pagebar}
{foreach $pagebar.buttons as $k=>$v}
{if $pagebar.PageNow==$k}
<li><a class="button active">{$k}</a>
{elseif $k=='‹‹' and $pagebar.PageNow!=$pagebar.PageFirst}
{elseif $k=='‹‹' and $pagebar.PageNow==$pagebar.PageFirst}
{elseif $k=='››' and $pagebar.PageNow==$pagebar.PageLast}
{elseif $k=='››' and $pagebar.PageNow!=$pagebar.PageLast}
{elseif $k=='‹'}
<li><a href="{$v}" class="button" >上一页</a></li>
{elseif $k=='›'}
<li><a href="{$v}" class="button" >下一页</a></li>
{else}
<li><a class="button hide" href="{$v}">{$k}</a></li>{/if}
{/foreach}
{/if} 