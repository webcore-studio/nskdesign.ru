Здравствуйте.<br><br>

Новый пользователь "{$user_name|escape}" ({$user_email|escape}) создал учетную запись на сайте "{$url}".<br><br>
Вы должны активировать её перейдя по этой ссылке:<br>

{array name='url_vars'}
{array_append name='url_vars' key='user_id' value=$id}
{array_append name='url_vars' key='user_actkey' value=$user_actkey}
<a href="{$url}{mod_url type='user' modname='SYS_users' func='reg_act' vars=$url_vars}">{mod_getvar modname='SYS_config' name='site_url'}{mod_url type='user' modname='SYS_users' func='reg_act' vars=$url_vars}</a><br><br>

{include file='modules/SYS_users/themes/default/mail_footer.tpl}