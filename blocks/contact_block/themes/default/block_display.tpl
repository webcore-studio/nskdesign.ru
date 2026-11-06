{strip}
{usergetvars assign=uservars}
<div id="mod_contact_sendf" class="mod_contact_sendf" ></div>

	<form class="form-horizontal contact_new_form" name="contact_new_form" id="contact_new_form">
		{$before_send_text|wordwrap:38|nl2br}
		
		<div class="control-group">
			<label class="control-label" for="user_displayname">Ваше имя *</label>
			<div class="controls">
				<input type="text" name="user_displayname" id="user_displayname" class="required" value="{$uservars.user_displayname}" maxLength="120">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="user_email">E-mail *</label>
			<div class="controls">
				<input type="text" name="user_email" id="user_email"  class="required" value="{$uservars.user_email}" maxLength="60">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="user_phone">Телефон</label>
			<div class="controls">
				<input type="text" size="70" name="user_phone" value="{$uservars.user_phone}" >
			</div>
		</div>
		
		{if $show_to}
			<div class="control-group">
				<label class="control-label" for="user_email">Получатель</label>
				<div class="controls">
					<select name="rec">
						{foreach key=id item=rec from=$rec_list}
							<option value="{$id}">{$rec.rec_name}</option>
						{/foreach}
					 </select>
				</div>
			</div>
		{/if}
		
		{if $show_theme}
			<div class="control-group">
				<label class="control-label" for="contact_displayname">Тема сообщения *</label>
				<div class="controls">
					<input type="text" name="contact_displayname" id="contact_displayname" class="required" value="" maxLength="120">
				</div>
			</div>
		{else}
			<input type="hidden" name="contact_displayname" id="contact_displayname" value="Сообщение с сайта">
		{/if}
		
		<div class="control-group">
			<label class="control-label" for="contact_content">Сообщение *</label>
			<div class="controls">
				<textarea name="contact_content" id="contact_content" value="" rows="8" cols="70" class="required"></textarea>
			</div>
		</div>
	

        {if $curuser_id < 1 && $captcha_enabled}
			<div class="control-group">
				<label class="control-label" for="captcha_string">Код подтверждения *</label>
				<div class="controls">
					<input type="text" name="captcha_string" id="captcha_string" class="input-mini required">&nbsp;
					<span class="add-on"  id="content_captcha_div" style="width:134px">
						<img src="{mod_url modname='contact' type='ajax' func='captcha_view'}" alt="Код безопасности" border="0" width="134" height="39">
					</span>&nbsp;
					<span class="add-on"><a href="#" onclick="contact_captcha_update(); return false;">Обновить код</a></span>
				</div>
			</div>
		{/if}
		
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn submit" onclick="contact_create('create');return false;">Отправить</button>
			</div>
		</div>
	</form>

{/strip}