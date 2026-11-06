{strip}
{* Вид у админа *}

{if $curuser_sec_level>=50}
<form action="{mod_url type='admin' modname='shop' func='item_weight'}" method="post">
<input type="hidden" value="cat" name="item_type">
<table class="table outer">

	<colgroup>
		<col width="15%">
		<col width="50%">
		<col width="15%">
		<col width="15%">
	</colgroup>

	<thead>
		<tr>
			<td class="head" align="center">Id</td>
			<td class="head" align="center">{#folders#}</td>
			<td class="head" align="center">{#weight#}</td>
			<td class="head" align="center">{#action#}</td>
		</tr>
	</thead>

	<tbody>
		{foreach item=cat from=$cats_list}
			{cycle name="fols" values="even,odd" assign="class" print=false}
			{array name='url_vars'}
			{array_append name='url_vars' key='id' value=$cat.id}
			
			<tr>
				<td class="{$class}">{$cat.id}</td>

				<td class="{$class}" nowrap>
					{if $cat.cat_active!=true}
						<i class="icon-eye-close" title="{#no_active#}"></i>&nbsp;
					{/if}
					<a href="{mod_short_url modname='shop' type='user' name=$cat.cat_name id=$cat.id}">{$cat.cat_displayname|escape|tree:$cat.level}</a>&nbsp;({$cat.cat_subproduct_counter})
				</td>

				<td class="{$class}" align="center" nowrap>
					<input type="hidden" name="ids[]" value="{$cat.id}">
					<input type="text" size="3" value="{$cat.weight}" name="weights[]" class="input-mini">
				</td>

				<td class="{$class}" align="center" nowrap>
					<a href="{mod_url type='admin' modname='shop' func='cat_modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
					<a href="{mod_url type='admin' modname='shop' func='cat_new' vars=$url_vars}" title="{#add#}"><i class="icon-folder-close"></i></a>&nbsp;
					<a href="{mod_url type='admin' modname='shop' func='cat_confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
				</td>

			</tr>
		{/foreach}
		{if $cats_list}
		<tr>
			<td class="head" align="center" colspan=2></td>
			<td class="head" align="center" colspan=2><input type="submit" value="Сохранить сортировку" class="btn" >	<br></td>
		</tr>
		<tr>
			<td class="head" align="center" colspan=4>Общее количество продуктов: {$product_count_all}<br>Активных продуктов: {$product_count_active}</td>
		</tr>
		{/if}
	</tbody>

</table>
<div class="item" align="center">
	<span>
		<a class="btn" href="{mod_url modname='edu' type='admin' func='cat_new'}">{#addfolder#}</a>&nbsp;
		<a class="btn" href="{mod_url modname='edu' type='admin' func='main'}">{#admin#}</a> &nbsp;
		
	</span>
</div>
</form>

{else}

	<ul>
		{foreach item=cat from=$cats_list}
			{if $cat.level==0}
				<li><a href="{mod_short_url modname='shop' type='user' name=$cat.cat_name id=$cat.id}">{$cat.cat_displayname|escape}</a></li>
			{/if}
		{/foreach}
	</ul>
{/if}

{/strip}
