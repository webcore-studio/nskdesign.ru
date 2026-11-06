<p>Здравствуйте,&nbsp;
  {if $user_displayname}
    {$user_displayname|escape}
  {else}
    {$user_name|escape}
  {/if}.
<br><br>
Вы получили это письмо потому, что вы (либо кто-то, выдающий себя за вас) попросили выслать новый пароль к вашей учётной записи на сайте "{$url}". Если вы не просили выслать пароль, то не обращайте внимания на это письмо, если же подобные письма будут продолжать приходить, обратитесь к администратору сайта.<br>
<br>
Прежде чем использовать новый пароль, вы должны его активировать. Для этого перейдите по ссылке:<br>
<br>
{array name='url_vars'}
{array_append name='url_vars' key='user_id' value=$id}
{array_append name='url_vars' key='user_actkey' value=$user_actkey}
<a href="{$url}{mod_url type='user' modname='SYS_users' func='pass_act' vars=$url_vars}">{$url}{mod_url type='user' modname='SYS_users' func='pass_act' vars=$url_vars}</a><br>
<br>
В случае успешной активизации вы сможете входить в систему, используя следующий пароль:<br>
<br>
Пароль: {$user_newpasswd}<br>
<br>
Вы сможете сменить этот пароль на странице редактирования профиля. Если у вас возникнут какие-то трудности, обратитесь к администратору сайта.<br>
</p>

{include file='modules/SYS_users/themes/default/mail_footer.tpl}