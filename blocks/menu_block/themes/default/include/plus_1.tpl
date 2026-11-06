<div class="header_menu">
<ul>

{foreach item=item from=$items_list name=menu_items}
{* Формируем масив переменные для урла *}
{array name='url_vars'}
{array_append name='url_vars' key='id' value=$item.id}   
<li>
{if $item.selected}
{$item.displayname|escape}
{else}
<a href="{$item.content}">{$item.displayname|escape}</a>
{/if}
</li>
{/foreach}
</ul>
</div>