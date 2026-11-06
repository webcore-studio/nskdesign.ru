
<p>Здравствуйте,&nbsp;
  {if $user_displayname}
    {$user_displayname|escape}
  {else}
    {$user_name|escape}
  {/if}.
<br><br>
Ваш e-mail адрес (<a href="mailto:{$user_email|escape}">{$user_email|escape}</a>) был использован при регистрации учетной записи на сайте {$url}.<br>
<br>
Чтобы стать зарегистрированным пользователем сайта, пожалуйста, подтвердите Ваш запрос, нажав на ссылку, указанную ниже:<br>
<br>
{array name='url_vars'}
{array_append name='url_vars' key='user_id' value=$id}
{array_append name='url_vars' key='user_actkey' value=$user_actkey}
<a href="{$url}{mod_url type='user' modname='SYS_users' func='reg_act' vars=$url_vars}">{$url}{mod_url type='user' modname='SYS_users' func='reg_act' vars=$url_vars}</a><br>
<br>
Если ссылка  неактивна, скопируйте ее, пожалуйста, в адресную строку браузера.

{include file='modules/SYS_users/themes/default/mail_footer.tpl}
