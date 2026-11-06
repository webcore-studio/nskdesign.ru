<ul>

{foreach item=item from=$items_list}

    {array name='url_vars'}
    {array_append name='url_vars' key='id' value=$item.id}    

{if $item.item_type==2}
<li><b>{$item.displayname|escape|tree:$item.level:'&nbsp;&nbsp;&nbsp;'}</b></li>

{elseif $item.item_type==3}
{if $item.selected}
<li><b>{$item.displayname|escape|tree:$item.level:'&nbsp;&nbsp;&nbsp;'}</b></li>
{else}
<li><a href="{$item.content}">{$item.displayname|escape|tree:$item.level:'&nbsp;&nbsp;&nbsp;'}</a></li>
{/if}
{elseif $item.item_type==4}
{$item.content}
 {/if}
{/foreach}
</ul>