{strip}
  <form action="{mod_url modname='content' type='admin' func='image_delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">
  <input name="doc_id" type="hidden" value="{$doc_id}">
  <input name="http_referer" type="hidden" value="{$http_referer}">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <thead>
        <tr>
          <th>Удаление фотографии</th>
        </tr>
      </thead>

      <tfoot>
        <tr align="center">
          <td class="foot">
            <input type="submit" value="Удалить" name="submit">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr align="center">
          <td class="even">Удалить фотографию&nbsp;"{$image_displayname|escape}"&nbsp;?</td>
        </tr>
      </tbody>

    </table>

  </form>
{/strip}