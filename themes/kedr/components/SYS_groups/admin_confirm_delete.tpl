{strip}
<form name="groupform" action="{mod_url type='admin' modname='SYS_groups' func='delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <thead>
        <tr>
          <th>Удаление группы</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <td class="foot" align="center">
            <input class="btn btn-primary" type="submit" value="Удалить" name="group_submit">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr vAlign="top" align="center">
          <td class="even">Удалить группу {$group_displayname|escape} ({$group_description|escape}) ?</td>
        </tr>
      </tbody>

    </table>

</form>

{/strip}