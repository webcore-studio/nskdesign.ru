{usergetvars assign=uservars}

<h2>Обратная связь</h2>
{if $error_msg}
	<div class="alert alert-error">{$error_msg}</div>
{/if}
<script>document.write('<f'+'o'+'r'+'m n'+'a'+'m'+'e="doc_new_form" class="form-horizontal" m'+'e'+'t'+'h'+'o'+'d="p'+'o'+'s'+'t" a'+'c'+'t'+'i'+'o'+'n'+'="{mod_url type='admin' modname='content' func='doc_create'}" e'+'n'+'c'+'t'+'y'+'p'+'e="multipart/form-data">');</script>

{if $folder_dbdata.id}
	<input name="fid" value="{$folder_dbdata.id}" type="hidden">
{else}
	<input name="fid" value="{$id}" type="hidden">
{/if}
<input name="displayname" value="Сообщение с сайта {$smarty.now|date_format:'%d.%m.%Y'}" type="hidden">
<input name="active" value="0" type="hidden">


	<div class="control-group">
		<label class="control-label" for="author">Ваше имя *</label>
		<div class="controls">
			<input type="text" name="author" id="author" placeholder="Представьтесь, пожалуйста" class=":required :only_on_submit" value="{if $doc_data.author}{$doc_data.author}{else}{$uservars.user_displayname}{/if}" maxLength="120">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="author_email">E-mail *</label>
		<div class="controls">
			<input type="text" name="author_email" id="author_email" placeholder="Контактный e-mail" class=":email :required :only_on_submit" value="{if $doc_data.author_email}{$doc_data.author_email}{else}{$uservars.user_email}{/if}" maxLength="60">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="misc_varchar_a">Телефон</label>
		<div class="controls">
			<input type="text" name="misc_varchar_a" id="misc_varchar_a" placeholder="Контактный телефон" value="{if $doc_data.misc_varchar_a}{$doc_data.misc_varchar_a}{else}{$uservars.user_phone|escape}{/if}" maxLength="50">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="content">Сообщение *</label>
		<div class="controls">
			<textarea name="content" id="content" value="" rows="8" cols="70" class=":required :only_on_submit">{$doc_data.content}</textarea>
		</div>
	</div>
	
	{*<div class="control-group">
		<label class="control-label" for="orderfile">Прикрепить файл</label>
		<div class="controls">
			<input id="orderfile" name="orderfile" type="file">
		</div>
	</div>*}
	
	{if ($folder_dbdata.need_captcha || $need_captcha) &&  $curuser_sec_level<70}
		<script language="JavaScript" type="text/javascript">
			{literal}
				function content_captcha_update()
				{
					var rndval = new Date().getTime();
					document.getElementById('content_captcha_div').innerHTML = '<img src="index.php?module=content&type=user&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0" width="134" height="39">';
					return false;
				}
            {/literal}
          </script>
		<div class="control-group">
			<label class="control-label" for="captcha_string">Код подтверждения *</label>
			<div class="controls">
				<input type="text" name="captcha_string" id="captcha_string" class="input-mini :required :only_on_submit">&nbsp;
				<span class="add-on"  id="content_captcha_div" style="width:134px">
					<img src="{mod_url modname='content' type='user' func='captcha_view'}" alt="Код безопасности" border="0" width="134" height="39">
				</span>&nbsp;
				<span class="add-on"><a href="#" onclick="content_captcha_update(); return false;">Обновить код</a></span>
			</div>
		</div>
	{/if}
	
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn submit">Отправить</button>
		</div>
	</div>

<script>document.write('</f'+'o'+'r'+'m>');</script>

<br><br>
Поля, отмеченные *, обязательны для заполнения!
<br>
Использование html-тэгов запрещено!