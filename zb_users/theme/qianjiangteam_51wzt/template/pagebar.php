<div class="row col-xs-12">
{if $pagebar}
{foreach $pagebar.buttons as $k=>$v}
    {if $pagebar.PageNow==$k}
        <a class="current" href="{$v}" title="第{$k}页">{$k}</a>
    {else}
        {if $k=='›'}
            {if $pagebar.PageAll>1}
                <a href="{$v}" title="下一页">下一页</a>
            {/if}
        {elseif $k=='‹'}
            {if $pagebar.PageAll>1}
                <a href="{$v}" title="上一页">上一页</a>
            {/if}
        {elseif $k=='››'}
            {if $pagebar.PageAll>1}
                <a href="{$v}" title="最后一页">{$k}</a>
            {/if}
        {elseif $k=='‹‹'}
            {if $pagebar.PageAll>1}
                <a href="{$v}" title="第一页">{$k}</a>
            {/if}
        {else}
            <a href="{$v}" title="第{$k}页">{$k}</a>
        {/if}
    {/if}
{/foreach}
{/if}
</div>


