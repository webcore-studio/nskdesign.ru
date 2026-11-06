{strip}
{* Для всех да нет *}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}
<form class="form-horizontal" name="userform" action="{mod_url type='admin' modname='SYS_users' func='create'}" method="post">
	
	{include file='modules/SYS_users/themes/default/include/user_data.tpl'}
		
	<div class="control-group">
		<label class="control-label" for="user_pass">Пароль&nbsp;<b>*</b></label>
		<div class="controls"><input type="password" maxLength="20" size="30" value name="user_pass" value="{$user_pass|escape}"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_gid">Группа</label>
		<div class="controls">
			<select name = "user_gid">
            {foreach item=group from=$groups_list}
              {if $group.id eq $default_group}
                <option selected value="{$group.id}">{$group.group_displayname|escape}</option>
              {else}
                <option value="{$group.id}">{$group.group_displayname|escape}</option>
              {/if}
            {/foreach}
          </select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_active">Активирован</label>
		<div class="controls">
			{html_radios name="user_active" options=$yes_no checked=1 separator=" "}
		</div>
	</div>
	
	<div class="control-group">	
          <div class="controls"><input class="btn btn-primary" type="submit" value="Добавить" name="submit"></div>
	</div>
</form>
{/strip}
