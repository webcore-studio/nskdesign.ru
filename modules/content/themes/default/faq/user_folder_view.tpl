{strip}
{if $curuser_sec_level>=50}
<div>
	{include file='modules/content/themes/default/include/folder_view_adminBtn.tpl'}
	{assign var = 'folder_view_adminBtn_inserted' value = true}
</div>
{/if}

<script type="text/javascript" src="/scripts/vanadium/vanadium.js"></script>
<link rel="StyleSheet" href="/scripts/vanadium/vanadium.css" type="text/css">

{* Содержание *}
{if $content}
	{$content}
{/if}



{* Список разделов *}
{if $sub_folders_list}

	<div>
		{foreach item=folder from=$sub_folders_list name=folders}
 
			<div class="item">
				{* Если раздел не активен ставим хрестик *}
				{if $curuser_sec_level>=50 && $folder.active!=true}
					<img src="/files/shared/images/system/notactive.gif" width="16" height="16" alt="Не активирован" title="Не активирован" border=0 style="padding-right:10px;">
				{/if}

				<h3 style="padding-bottom:0; margin-bottom:5px;">
					<a href="/{$folder.name}">{$folder.displayname|escape}</a>
					{if $curuser_sec_level>=50}
						<span>
							{include file='modules/content/themes/default/include/folder_modify_adminBtn.tpl'}
						</span>
					{/if}
				</h3>

				{if $folder.description}            
					<div>{$folder.description|strip_tags|nl2br|truncate:70}</div>
				{/if}
				
				{if $folder.subdocs}            
					<div>
						{foreach item=doc from=$folder.subdocs name=docs}
							<div class="item">

								<p>
									{if $curuser_sec_level>=50 && $doc.active!=true}
										<img src="/files/shared/images/system/notactive.gif" width="16" height="16" alt="Не активирован" title="Не активирован" border=0 style="padding-right:10px;">
									{/if}
									<b><a href="#" class="green dotted" onclick="$('#answer{$doc.id}').slideToggle('fast');return false;">{$doc.displayname|escape}</a></b>


									{if $curuser_sec_level>=50}
										{include file='modules/content/themes/default/include/doc_modify_adminBtn.tpl'}
									{/if}
								</p>

								<div id="answer{$doc.id}" style="display:none;">
									{$doc.description}
								</div>
							</div>
						{/foreach}
					</div>
				{/if}
				

			</div>

		{/foreach}
	</div>
	
{/if}

{assign var="folder" value=""}
{* Список документов *}
{if $sub_docs_list}
	{* Инициализируем счетчик доукментов *}
	{math equation="doc_perpage * (page_current-1)" doc_perpage=$doc_perpage page_current=$page_current assign='start_doc_num'}
	{counter start=$start_doc_num print=false}

	<div>
		{foreach item=doc from=$sub_docs_list name=docs}
			<div class="item">

				<p>
					{if $curuser_sec_level>=50 && $doc.active!=true}
						<img src="/files/shared/images/system/notactive.gif" width="16" height="16" alt="Не активирован" title="Не активирован" border=0 style="padding-right:10px;">
					{/if}
					<b><a href="#" class="dotted" onclick="$('#answer{$doc.id}').slideToggle('fast');return false;">{$doc.displayname|escape}</a></b>


					{if $curuser_sec_level>=50}
						{include file='modules/content/themes/default/include/doc_modify_adminBtn.tpl'}
					{/if}
				</p>

				<div id="answer{$doc.id}" style="display:none;">
					{$doc.description}
				</div>
			</div>
		{/foreach}
	</div>
{/if}



<h3 style="padding:10px 0">Задать вопрос</h3>
{if $error_msg}
	<div class="alert alert-error">{$error_msg}</div>
{/if}

<script>document.write('<f'+'o'+'r'+'m n'+'a'+'m'+'e="doc_new_form" m'+'e'+'t'+'h'+'o'+'d="p'+'o'+'s'+'t" a'+'c'+'t'+'i'+'o'+'n'+'="/index.php?module=content&amp;type=admin&amp;func=doc_create" e'+'n'+'c'+'t'+'y'+'p'+'e="multipart/form-data" o'+'n'+'s'+'u'+'b'+'m'+'i'+'t="return formCheck(this);">');</script>
	<input type="hidden" name="fid" value="{$id}">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
		<tr vAlign="top" align="left">
			<td class="head">Вопрос *</td>
			<td class="even"><textarea cols=50 rows=7 name="displayname" class=":required :only_on_submit"></textarea>
			<br><small>Максимальная длина вопроса - 255 символов</small></td>
        </tr>

        <tr vAlign="top" align="left">
			<td class="head">{#author#} *</td>
			<td class="even"><input type="text" maxLength="60" size="50" name="author" value="{$uservars.user_displayname}" class=":required :only_on_submit"></td>
        </tr>

        <tr vAlign="top" align="left">
			<td class="head">{#author_email#} *</td>
			<td class="even"><input type="text" maxLength="60" size="50" name="author_email" value="{$uservars.user_email}" class=":email :required :only_on_submit"></td>
        </tr>
		
		{if $need_captcha &&  $curuser_sec_level<70}
			<tr>
				<td class="head">Введите код подтверждения *</td><td>
					{literal}
					<script language="JavaScript" type="text/javascript">
						
						function content_captcha_update()
						{
							var rndval = new Date().getTime();
							document.getElementById('content_captcha_div').innerHTML = '<img src="index.php?module=content&type=user&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0" width="134" height="39">';
							return false;
						 };
					</script>
					{/literal}
          
					<div style="text-indent: 0px;" id="content_captcha_div" style="height:39px">
						<img src="{mod_url modname='content' type='user' func='captcha_view'}" alt="Код безопасности" border="0" width="134" height="39">
					</div>
					<a href="#" onclick="content_captcha_update(); return false;">Обновить код</a>
					<br>
					<input size="16" name="captcha_string" value="" type="text" style="width:60px" class=":required :only_on_submit">
				
       	
				</td>
			</tr>
		{/if}


        <tr vAlign="top" align="center">
			<td class="even" colspan="2"><input class="btn submit" type="submit" value="Отправить" name="submit"></td>
        </tr>

    </table>
<script>document.write('</f'+'o'+'r'+'m>');</script>

<div style="margin:50px 0 0 30px;">
	{if $page_total>1}
		{include file='modules/content/themes/default/folder_view_NPanel_short.tpl'}
	{/if}
</div>



{if $curuser_sec_level>=40}
	{include file='modules/content/themes/default/include/folder_view_adminBtn_bot.tpl'}
{/if}


{/strip}
