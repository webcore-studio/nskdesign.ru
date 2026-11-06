{strip}

{array name='url_vars'}
{array_append name='url_vars' key='plugin' value=$plugin}
{array_append name='url_vars' key='search_string' value=$search_string|escape}
{if $vendor_id}
	{array_append name='url_vars' key='search_param[vendor_id]' value=$vendor_id}
{/if}
{if $price_from}
	{array_append name='url_vars' key='search_param[price_to]' value=$price_from}
{/if}
{if $price_to}
	{array_append name='url_vars' key='search_param[price_to]' value=$price_to}
{/if}
{if $cat_id}
	{array_append name='url_vars' key='search_param[cat_id]' value=$cat_id}
{/if}

<div class="pagination">
	<ul>


	{if $page_current > 1}
		{math equation="page-1" page=$page_current assign="page_prev"}
		{array_append name='url_vars' key='page_current' value=$page_prev}
		<li>
			<a href="{mod_url modname='search' type='user' func='view' vars=$url_vars}"><span>Предыдущая</span></a>
		</li>
	{/if}

	{section name=navigation loop=$page_total}

		{if $smarty.section.navigation.iteration!=$page_current}
			<li>
		{else}
			<li class="active">
		{/if}
			{array_append name='url_vars' key='page_current' value=$smarty.section.navigation.iteration}
			<a href="{mod_url modname='search' type='user' func='view' vars=$url_vars}"><span>{$smarty.section.navigation.iteration}</span></a>
		</li>

	{/section}

	{if $page!=$page_total}
		{math equation="page+1" page=$page_current assign="page_next"}
		{array_append name='url_vars' key='page_current' value=$page_next}
		<li>
			<a href="{mod_url modname='search' type='user' func='view' vars=$url_vars}"><span>Следующая</span></a>
		</li>
	{/if}
	</ul>
</div>
{/strip}
