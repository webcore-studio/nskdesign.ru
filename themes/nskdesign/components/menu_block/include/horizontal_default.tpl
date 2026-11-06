{*debug*}
<div class="navbar-inner" style="margin-top: 6px;">

	<ul>
	{foreach item=item from=$items_list name=menu_items}
		
		{* Выбранные и разделители и другие пункты становятся *}
		{if $item.selected} 
			<li class="active {if $item.inner}dropdown-submenu{/if} horizontal">
				<a href="{$item.content}">{$item.displayname|escape}</a>
			{else}
				<li class="{if $item.inner}dropdown-submenu{/if} horizontal"><a href="{$item.content}">{$item.displayname|escape}</a>
			{/if}
			{if $item.inner}
				<ul class="dropdown-menu nav-stacked">
					{foreach item=in from=$item.inner}
						<li><a href="{$in.content}">{$in.displayname|escape}</a></li>
					{/foreach}
				</ul>
			{/if}
			</li>
		{/foreach}
	</ul>

</div>


