{strip}
<br>
 {if $num_users>0}
	Пользователей: {$num_users}<br>
  {/if} 
 {if $num_guests>0}
	Гостей: {$num_guests}<br>
  {/if}

<br>
 
  {if $user_vars}
  Вы зашли как 
    {* Если неуказан дисплей наме выводим наме *}
    {if $user_vars.user_displayname}
      <b>{$user_vars.user_displayname|escape}</b>.
    {else}
      <b>{$user_vars.user_name|escape}</b>.
{/if}

<br>
<br><a href="/index.php?module=SYS_users&func=logout">Выход</a>
  {/if}
<br>
{/strip}
