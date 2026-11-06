{strip}
<form action="{mod_url modname='SYS_themes' type='admin' func='template_delete'}" method="post">
<input name="id" type="hidden" value="{$id}">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <thead>
      <tr>
        <th>Удаление шаблона</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td class="foot" align="center">
          <input type="submit" value="Удалить" name="submit">
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr align="center">
        <td class="even">Удалить шаблон "{$tpl_compname}" -> "{$tpl_name}" ?</td>
      </tr>
    </tbody>

  </table>

</form>
{/strip}