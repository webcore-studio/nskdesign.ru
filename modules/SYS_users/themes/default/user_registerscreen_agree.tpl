{if $user_info}
	<div>
		Вы авторизовались как&nbsp; 
		<b>{if $user_info.user_displayname}{$user_info.user_displayname} ({$user_info.user_name})
		{else}{$user_info.user_name}
		{/if}</b>
		<p><a href="/user/profile_modify/">Профиль</a></p>
		<p><a href="/logout">Выход</a></p>
	</div>
{else}
{array name='sex_list'}
{array_append name='sex_list' key='1' value=#man#}
{array_append name='sex_list' key='2' value=#woman#}
{strip}
<form name="userform" action="/register" method="post">
  <table class="outer" cellSpacing="1" cellPadding="4" width="500">
    <colgroup>
      <col width="30%">
      <col width="70%">
    </colgroup>

    <tfoot>
      <tr>
        <td class="text-center" colspan="2" align="center">
				<input type="hidden" name="user_ref" value="{$ref}">
				<input type="submit" value="Зарегистрироваться" name="submit" class="btn submit">
        </td>
      </tr>
    </tfoot>

    <tbody>
	{*<tr>
        <td class="head">Логин *</td>
        <td class="even"><input type="text" maxLength="60" size="40" name="user_name" value="{$user_name|escape}" class="span4 :required :only_on_submit"></td>
      </tr>*}
	  <tr>
        <td class="head" nowrap>E-mail *</td>
        <td class="even"><input type="text" maxLength="60" size="40" name="user_email" value="{$user_email|escape}" class="span4 :email :required :only_on_submit"></td>
      </tr>
	  
	 <tr>
        <td class="head">Пароль*</td>
        <td class="even"><input type="password" maxLength="40" size="40" name="user_pass" type="password" value="{$user_pass|escape}" class="span4 :required :only_on_submit"></td>
      </tr>
	  <tr>
        <td class="head">Имя</td>
        <td class="even"><input type="text" maxLength="128" size="40" name="user_displayname" value="{$user_displayname|escape}" class="span4"></td>
      </tr>
	  
 
     
      <tr>
        <td class="head">Телефон</td>
        <td class="even"><input type="text" maxLength="60" size="40" name="user_phone" value="{$user_phone|escape}" class="span4"></td>
      </tr>

      <tr>
        <td class="head">Город</td>
        <td class="even"><input type="text" maxLength="60" size="40" name="user_city" value="{$user_city|escape}" class="span4"></td>
      </tr>

      <tr>
        <td class="head">Адрес</td>
        <td class="even"><input type="text" maxLength="128" size="40" name="user_from" value="{$user_from}" class="span4"></td>
      </tr>




      <tr>
        <td class="head" colspan="2">Введите символы, указанные на картинке</td>
		</tr>
		
		<tr>
			<td class="even" align="center">
				<script language="JavaScript" type="text/javascript">
					{literal}
						function SYS_users_captcha_update()   {
							var rndval = new Date().getTime();
							document.getElementById('SYS_users_captcha_div').innerHTML = '<img src="index.php?module=SYS_users&type=user&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0" width="134" height="39">';
							return false;
						}
					{/literal}
				</script>
				<div style="text-indent: 0px;" id="SYS_users_captcha_div">
					<img src="{mod_url modname='SYS_users' type='user' func='captcha_view'}" alt="Обновить символы" border="0" width="134" height="39">
				</div>
				<a href="#" onclick="SYS_users_captcha_update(); return false;">Обновить символы</a>
			</td>
			<td class="even" align="center">
				<input size="16" name="captcha_string" value="" type="text" class="span2 :required :only_on_submit">
			</td>
			
      </tr>
   </tbody>

</table>

</form>
<div class="text-center"><small><b>*</b> Поля, отмеченные звездочкой, обязательны к заполнению</small></div>

{/strip}
{/if}
