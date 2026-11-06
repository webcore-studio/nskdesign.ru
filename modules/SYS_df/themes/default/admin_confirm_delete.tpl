{strip}
  <form action="{mod_url modname='SYS_df' type='admin' func='delete'}" method="post">
    <input name="table_name" type="hidden" value="{$table_name}">
    <input name="table_field" type="hidden" value="{$table_field}">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <thead>
        <tr>
          <th>Удаление поля</th>
        </tr>
      </thead>

      <tfoot>
        <tr align="center">
          <td class="foot" align="center">
            <input type="submit" value="Удалить" name="submit">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr align="center">
          <td class="even">Удалить поле {$table_name|escape}::{$table_field|escape} ?</td>
        </tr>
      </tbody>


    </table>

  </form>
{/strip}