 {* основные данные *}
 
	{if $curuser_sec_level>=70}
		<div class="control-group">
			<label class="control-label" for="user_email">Логин&nbsp;<b>*</b></label>
			<div class="controls"><input type="text" maxLength="80" size="40" name="user_name" value="{$user_name|escape}" class="required"></div>
		</div>

	{/if}
 
	<div class="control-group">
		<label class="control-label" for="user_displayname">ФИО&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" size="60" name="user_displayname" value="{$user_displayname|escape}" class="required" ></div>
	</div>
	
	<div class="control-group" {if $user_type=='f' || !$user_type}style="display:none;"{/if}>
		<label class="control-label" for="user_legalname">Название организации&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="60" size="40" name="user_legalname" value="{$user_legalname|escape}" class="required"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_displayname">Телефон</label>
		<div class="controls"><input type="text" maxLength="11" size="40" name="user_phone" value="{$user_phone|escape}"></div>
	</div>
	
	
	
	<div class="control-group">
		<label class="control-label" for="user_email">E-mail&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="80" size="40" name="user_email" value="{$user_email|escape}" class="email required"></div>
	</div>

