{usergetvars assign=uservars}
{literal}
<div id="admin_menu" onMouseOver="$(this).css({'margin-top':'0'});" onMouseOut="$(this).css({'margin-top':'-25px'});">
{/literal}
	<div id="admin_menu_in">

	<ul class="unstyled">
		<li><b>{$uservars.user_name}</b></li>
		{foreach item=item from=$items_list name=menu_items}
			<li {if $item.selected}class="active"{/if}>
				<a href="{$item.content}">{$item.displayname|escape}</a>
			</li>
		{/foreach}
	</ul>
</div>
</div>
