{strip}
  <form name="image_modify_form" action="{mod_url modname='content' type='admin' func='image_update'}" method="post">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="doc_id" value="{$doc_id}">
  <input type="hidden" name="http_referer" value="{$http_referer}">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
        <tr>
          <th colSpan="2">Редактирование фотографии</th>
        </tr>

        <tr>
          <td class="head">Название</td>
          <td class="even"><input maxLength="255" size="78" name="image_displayname" value="{$image_displayname}"></td>
        </tr>

        <tr>
          <td class="head">Описание</td>
          <td class="even"><textarea name="image_description" rows="5" cols="78">{$image_description}</textarea></td>
        </tr>

        <tr>
          <td class="head">Файл</td>
          <td class="even"><input name="image_file" size="60" type="file"></td>
        </tr>

        <tr>
          <td class="foot" colspan="2" align="center">
            <input type="submit" value="Редактировать" name="submit">
          </td>
        </tr>

    </table>
  </form>
{/strip}