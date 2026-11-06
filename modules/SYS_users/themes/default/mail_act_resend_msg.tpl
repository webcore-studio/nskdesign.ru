
<p>Здравствуйте,&nbsp;
  {if $user_displayname}
    {$user_displayname|escape}
  {else}
    {$user_name|escape}
  {/if}.
<br>
<br>
<b>Вы подали заявку на регистрацию на сайте {$url}, однако не активировали свою учетную запись. . </b>
<br>
Чтобы стать зарегистрированным пользователем сайта, пожалуйста, подтвердите Ваш запрос, нажав на ссылку, указанную ниже:<br>
<br>
{array name='url_vars'}
{array_append name='url_vars' key='user_id' value=$id}
{array_append name='url_vars' key='user_actkey' value=$user_actkey}
<a href="{mod_getvar modname='SYS_config' name='site_url'}/index.php?module=SYS_users&func=reg_act&user_id={$id}&user_actkey={$user_actkey}">{mod_getvar modname='SYS_config' name='site_url'}/index.php?module=SYS_users&func=reg_act&user_id={$id}&user_actkey={$user_actkey}</a><br>

<br>
Если ссылка  неактивна, скопируйте ее, пожалуйста, в адресную строку браузера и нажмите Enter.

<br><br>
Если вы не подтвердите свою регистрацию, ваш аккаунт будет удален.

{include file='modules/SYS_users/themes/default/mail_footer.tpl}