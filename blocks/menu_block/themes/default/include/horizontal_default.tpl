<div class="horizontal1">
<ul>
{foreach item=item from=$items_list name=menu_items}
{* Формируем масив переменные для урла *}
{array name='url_vars'}
{array_append name='url_vars' key='id' value=$item.id}   
{if  $smarty.foreach.menu_items.last}
<li class="no_padding_border">
{else}
<li>
{/if}
{if $item.selected}
<span style="text-decoration:underline;">{$item.displayname|escape}</span>
{else}
<a href="{$item.content}">{$item.displayname|escape}</a>
{/if}
</li>
{/foreach}
</ul>
</div>