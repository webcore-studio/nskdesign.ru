{strip}
{usergetvars assign=uservars}
<div class="mod_contact_sendf" id="mod_contact_sendf"></div>

<form class="form-horizontal contact_new_form" name="contact_new_form" id="contact_new_form">
  <div>{$before_send_text}</div>
	<div class="control-group">
		<label class="control-label" for="author">Ваше имя</label>
		<div class="controls">
			<input type="text" size="70" name="user_displayname" value="{$uservars.user_displayname}" class="required">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_email">E-mail</label>
		<div class="controls">
			<input type="text" size="70" name="user_email" value="{$uservars.user_email}"  class="email required">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_phone">Телефон</label>
		<div class="controls">
			<input type="text" size="70" name="user_phone" value="{$uservars.user_phone}"  class="required">
		</div>
	</div>
	
	{if $rec_list}
	<div class="control-group">
		<label class="control-label" for="rec">Получатель</label>
		<div class="controls">
			<select size="1" name="rec">
		        {foreach key=id item=rec from=$rec_list}
					<option value="{$id}">{$rec.rec_name}</option>
	         	{/foreach}
			</select>
		</div>
	</div>
	{/if}

	
	<div class="control-group">
		<label class="control-label" for="contact_displayname">Тема сообщения</label>
		<div class="controls">
			<input type="text" size="70" name="contact_displayname" value="{$contact_displayname}">
		</div>
	</div>
	
		
	<div class="control-group">
		<label class="control-label" for="contact_content">Сообщение</label>
		<div class="controls">
			 <textarea name="contact_content" rows="10" cols="70"  class="required"></textarea>
		</div>
	</div>
	
	{if $curuser_id<1 && $captcha_enabled}
	<div class="control-group">
			<label class="control-label" for="captcha_string">Код подтверждения *</label>
			<div class="controls">
				<input type="text" name="captcha_string" id="captcha_string" class="input-mini required" value="">&nbsp;
				<span class="add-on"  id="content_captcha_div" style="width:134px">
					<img src="{mod_url modname='contact' type='ajax' func='captcha_view'}" alt="Код безопасности" border="0" id="captcha_img">
				</span>&nbsp;
				<span class="add-on"><a href="#" onclick="contact_captcha_update(); return false;">Обновить код</a></span>
			</div>
		</div>
	{/if}
	
	<div class="control-group">
		<div class="controls">
			<input class="btn submit formButton" type="submit" value="Отправить" name="submit" onclick="contact_create('create');return false;">
		</div>
	</div>
 </form>

 	{if $curuser_sec_level>=70}
		<div class="center">
			<a class="btn" href="{mod_url modname='contact' type='admin' func='main'}">Последние сообщения</a>&nbsp;
			<a class="btn" href="{mod_url type='admin' modname='contact' func='config_modify'}">Конфигурация</a>
		
		</div>
	{/if}
{/strip}