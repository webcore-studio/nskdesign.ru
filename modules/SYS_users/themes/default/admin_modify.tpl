<script type="text/javascript" src="/modules/SYS_users/scripts/userdata.js"></script>
{if $curuser_sec_level<70}<script type="text/javascript" src="/scripts/java_scripts/formvalid.js"></script>{/if}

{literal}
	<script type="text/javascript">
		$(document).ready(function() {
			setfields("{/literal}{$user_type}{literal}");
		})
	</script>
{/literal}

<div class="alert alert-error" id="error_msg" style="display:none"></div>

{strip}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

{array name='yes_no_desc'}
{array_append name='yes_no_desc' key='0' value='Да'}
{array_append name='yes_no_desc' key='1' value='Нет'}
<form class="form-horizontal" name="userform" action="{mod_url type='admin' modname='SYS_users' func='update'}" method="post" onsubmit="return formCheck(this);">
	<input name="id" type="hidden" value="{$id}">
		{if $curuser_sec_level >= 70}
			{include file='modules/SYS_users/themes/default/include/user_type.tpl'}
		{/if}

	{include file='modules/SYS_users/themes/default/include/user_data.tpl'}

	<div class="control-group">
		<label class="control-label" for="user_pass">Новый пароль</label>
		<div class="controls"><input type="password" maxLength="20" size="30" value name="user_pass" value=""></div>
	</div>
	
	{if $curuser_sec_level>=70}
     
	<div class="control-group">
		<label class="control-label" for="user_gid">Группа</label>
		<div class="controls">
			<select name="user_gid">
            {foreach item=group from=$groups_list}
              {if $group.id eq $user_gid}
                <option selected value="{$group.id}">{$group.group_displayname|escape}</option>
              {else}
                <option value="{$group.id}">{$group.group_displayname|escape}</option>
              {/if}
            {/foreach}
          </select>
		   <div id="sendinfo" style="display:none;">
				<input type="checkbox" name="send_info_mail" value=1>&nbsp;<small>Послать письмо с уведомлением об изменении группы</small>
		  </div>
		</div>
	</div>
	
  
	{if $user_state==0}
		<div class="control-group">
			<label class="control-label" for="user_active">Активен</label>
			<div class="controls">
				 {html_radios name="user_active" options=$yes_no checked=$user_active separator=" "}
			</div>
		</div>
    {else}
		<div class="control-group">
			<label class="control-label" for="user_active">Подтвержден</label>
			<div class="controls">
				<label><input type="radio" value="0" name="user_state">Да</label>&nbsp;<label><input type="radio" value="1" name="user_state" checked>Нет</label>
			</div>
		</div>
	{/if}
{else}
	<input type="hidden" value="1" name="user_active">
{/if}
 

	{include file='modules/SYS_users/themes/default/include/main_address.tpl'}
	{include file='modules/SYS_users/themes/default/include/legal_address.tpl'}
	{include file='modules/SYS_users/themes/default/include/bank_details.tpl'}
  
 
	


	<div class="control-group">	
        <div class="controls"> <input class="btn" type="submit" value="Сохранить" name="submit"></div>
	</div>
</form>
{/strip}
