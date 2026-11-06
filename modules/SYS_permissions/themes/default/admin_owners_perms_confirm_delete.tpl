{strip}
<form name="groupform" action="{mod_url modname='SYS_permissions' type='admin' func='owners_perms_delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <thead>
      <tr>
        <th>Удаление прав владельца</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td align="center" class="foot">
          <input class="btn" type="submit" value="Удалить" name="submit">
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td align="center" class="even">Удалить правило <b>"{$component}::{$pattern}"</b> ?</td>
      </tr>
    </tbody>
  </table>
</form>
{/strip}