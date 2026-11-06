{strip}
<h2>Поиск</h2>
<form class="form-inline" action="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars_s}" method="post" >
	<p>Имя пользователя или e-mail: <input type="text" name="filters[user_email]" value="{$filters.user_email}">&nbsp;&nbsp;&nbsp;
	ID пользователя: <input type="text" name="filters[id]" value="{$filters.id}" class="span1">&nbsp;&nbsp;&nbsp;
	
	{foreach from=$groups_list item=group}
		<label class="checkbox">
			<input type="checkbox" name="user_gid[]" value="{$group.id}" {if $group.checked}checked{/if}>&nbsp;{$group.group_displayname}&nbsp;&nbsp;
		</label>
	{/foreach}
	
	<input type="submit" value="Найти" class="btn">
</form>
{/strip}