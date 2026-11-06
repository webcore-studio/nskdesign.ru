{strip}
<form name="userform" action="{mod_url type='admin' modname='SYS_users' func='delete'}" method="post">
<input name="id" type="hidden" value="{$id}">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">

    <thead>
      <tr>
        <th>Удаление пользователя</th>
      </tr>
    </thead>

    <tfoot>
      <tr align="center">
        <td class="foot" align="center">
          <input type="submit" value="Удалить" name="usersubmit">
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr align="center">
        <td class="even">Удалить пользователя <b>"{$user_name|escape} ({$user_displayname|escape})"</b> ?</td>
      </tr>
    </tbody>

  </table>
</form>
{/strip}