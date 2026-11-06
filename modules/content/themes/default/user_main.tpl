{strip}

{* Вид у админа (или приближенного к админу :)) ) *}
{if $curuser_sec_level >= 50}
<form action="{mod_url type='admin' modname='content' func='folder_weight'}" method="post">
<table class="outer table" cellSpacing="1" cellPadding="4">
	<colgroup>
		<col width="10%">
		<col width="60%">S
		<col width="10%">
		<col width="10%">
	</colgroup>
	<thead>
		<tr>
			<th class="head" align="center">Id</th>
			<th class="head" align="center">Имя раздела</th>
			<th class="head" align="center">Посл.</th>
			<th class="head" align="center">Действия</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td align="center" colspan="5" >
				<input class="btn" type="submit" value="Сохранить сортировку">&nbsp;
				<a class="btn" href="{mod_url type='admin' modname='content' func='folder_new'}">Добавить раздел</a>&nbsp;
				<a class="btn" href="{mod_url type='admin' modname='content' func='config_modify'}">Настройки</a>
			</td>
		</tr>
	</tfoot>

	<tbody>
		{foreach item=folder from=$folders_list}
			{array name='url_vars'}
			{array_append name='url_vars' key='id' value=$folder.id}
			<tr>
				<td class="{$class}">
					{$folder.id}
				</td>
				<td>
					{if $folder.active!=true}
						<i class="icon-eye-close" title="{#no_active#}"></i>&nbsp;
					{/if}
					<a href="{mod_short_url modname='content' type='user' name=$folder.name id=$folder.id}">{$folder.displayname|escape|tree:$folder.level}</a>
				</td>
				<td class="{$class}" align="center">
					<input type="hidden" name="ids[]" value="{$folder.id}">
					<input type="text" class="span1" size="2" value="{$folder.weight}" name="weights[]">
				</td>

				<td class="{$class}" align="center">
					<a href="{mod_url type='admin' modname='content' func='folder_modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
					<a href="{mod_url type='admin' modname='content' func='folder_new' vars=$url_vars}" title="Добавить подраздел"><i class="icon-folder-close"></i></a>&nbsp;
					<a href="{mod_url type='admin' modname='content' func='folder_confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
				</td>

			</tr>
		{/foreach}
	</tbody>

</table>
</form>

{else}

{* рядовой чайник *}
<table class="table">

	<tbody>
		{foreach item=folder from=$folders_list}
			<tr>
				<td><a href="{mod_short_url modname='content' type='user' name=$folder.name id=$folder.id}">{$folder.displayname|escape|tree:$folder.level}</a></td>
			</tr>
		{/foreach}
	</tbody>

</table>

{/if}
{/strip}