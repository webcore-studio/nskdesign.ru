{strip}
<form method="POST" action="{mod_url modname='SYS_permissions' type='admin' func='groups_perms_update'}">
  <input type="hidden" value="{$id}" name="id">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="25%">
      <col width="75%">
    </colgroup>

    <thead>
      <tr>
        <th colspan="2">Редактирование прав группы</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td align="center" colSpan="2" class="foot">
          <input class="btn" type="submit" value="Сохранить">
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td class="head">Группа</td>
        <td class="even">
          <select size="1" name="gid">
            {html_options options=$groups_list selected=$gid}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Компонент</td>
        <td class="even">
          <select size="1" name="component">
            {html_options options=$components_list selected=$component}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Объект</td>
        <td class="even">
          <input type="text" maxLength="255" size="70" name="pattern" value="{$pattern}">
        </td>
      </tr>
      <tr>
        <td class="head">Уровень доступа</td>
        <td class="even">
          <select size="1" name="level">
            {html_options options=$levels_list selected=$level}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Описание</td>
        <td class="even">
          <textarea name="description" rows="5" cols="70">{$description}</textarea>
        </td>
      </tr>
    </tbody>

  </table>
</form>

{/strip}