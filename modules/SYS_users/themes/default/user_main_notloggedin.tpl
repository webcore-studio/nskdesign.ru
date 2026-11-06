{strip}
<table class="outer" cellSpacing="1" cellPadding="4" width="500" align="center">
  <thead>
    <tr>
      {if $allow_register}
        <th colspan="4">Регистрация/Вход</th>
      {else}
        <th colspan="4">Вход для пользователей</th>
      {/if}
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="odd">
        <ul>
          <li><a href="{mod_url type='user' modname='SYS_users' func='loginscreen'}">Вход</a>
          {if $allow_register}
            <li><a href="{mod_url type='user' modname='SYS_users' func='registerscreen'}">Регистрация</a>
          {/if}
          {if $allow_lostpass}
            <li><a href="{mod_url type='user' modname='SYS_users' func='lostpassscreen'}">Запросить потерянный пароль</a>
          {/if}
        </ul>
      </td>
    </tr>
  </tbody>

</table>
{/strip}