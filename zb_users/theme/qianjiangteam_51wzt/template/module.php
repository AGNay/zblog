{if $module.Type=='div'}
    <div class="module-ul" id="{$module.HtmlID}">
        <h4 class="title">{$module.Name}</h4>
        <div>
            {$module.Content}
        </div>
    </div>
{/if}


{if $module.Type=='ul'}
    <div class="module-ul" id="{$module.HtmlID}">
        <h4 class="title">{$module.Name}</h4>
        <ul class="list-group">
            {$module.Content}
        </ul>
    </div>
{/if}

