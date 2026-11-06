{strip}

<form action="{mod_url type='admin' modname='SYS_groups' func='update'}" method="post">
  <input name="id" type="hidden" value="{$id}">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="20%">
      <col width="80%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="2">Редактирование группы</th>
      </tr>
    </thead>

    

    <tbody>
      <tr vAlign="top" align="left">
        <td class="head">Группа</td>
        <td class="even">
          <input type="text" maxLength="60" size="70" name="group_displayname" value="{$group_displayname|escape}">
        </td>
      </tr>
	  <tr vAlign="top" align="left">
        <td class="head">Год</td>
        <td class="even">
          <input type="text" maxLength="60" size="70" name="group_year" value="{$group_year}">
        </td>
      </tr>

      <tr vAlign="top" align="left">
        <td class="head">Описание</td>
        <td class="even"><textarea name="group_description" rows="5" cols="70">{$group_description|escape}</textarea></td>
      </tr>
    </tbody>

<tfoot>
      <tr>
        <td class="foot" colSpan="2" align="center"><input class="btn btn-primary" type="submit" value="Сохранить" name="group_submit"></td>
      </tr>
    </tfoot>

  </table>
</form>
{/strip}