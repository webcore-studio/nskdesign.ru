{strip}
{if $message}
	<div class="alert alert-error">{$message}</div>
{/if}
{if !$user_info}
<form method="POST" action="{mod_url type='user' modname='SYS_users' func='login'}" style="max-width: 600px;">

	<input type="hidden" name="http_referer" value="{$http_referer}">

	<table class="outer" cellSpacing="1" cellPadding="4" width="500">
    <colgroup>
      <col width="50%">
      <col width="50%">
    </colgroup>

 
    <tfoot>
      <tr>
        <td class="foot" width="100%" colspan="2" align="center">
          <input type="submit" value="Войти" name="login" class="btn">
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td class="head">Имя пользователя</td>
        <td class="even">
          <input type="text" name="user_name" size="40">
        </td>
      </tr>

      <tr>
        <td class="head">Пароль</td>
        <td class="even">
          <input type="password" name="user_pass" size="40">
        </td>
      </tr>
	  
	  <tr>
        <td class="head"></td>
        <td class="even" style="text-align:right">
           <a href="/lostpassscreen">Забыли пароль?</a>
        </td>
      </tr>

      <tr>
        <td class="head">Запомнить меня</td>
        <td class="even">
          <input type="checkbox" name="user_rememberme" value="1">
        </td>
      </tr>
	  
	  
    </tbody>

  </table>
</form>

{else}
	<div>
		Вы авторизовались как&nbsp; 
		<b>{if $user_info.user_displayname}{$user_info.user_displayname} ({$user_info.user_name})
		{else}{$user_info.user_name}
		{/if}</b>
		<br><a href="/user/profile_modify/">Профиль</a><br>
		<a href="/logout">Выход</a>
	</div>
{/if}
{/strip}