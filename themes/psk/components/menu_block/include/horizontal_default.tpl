{strip}
<nav class="nav">
	{foreach item=item from=$items_list name=menu_items}
		<a class="{if $item.selected}is-active{/if}" href="{$item.content}">{$item.displayname|escape}</a>
	{/foreach}
</nav>
{/strip}
