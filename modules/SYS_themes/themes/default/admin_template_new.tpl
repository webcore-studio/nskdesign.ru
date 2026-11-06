{strip}
<form name="template_edit_form" action="{mod_url type='admin' modname='SYS_themes' func='template_create'}" method="post">
  <input type="hidden" name="tpl_compname" value="{$tpl_compname}">

  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="20%">
      <col width="80%">
    </colgroup>

      <thead>
        <tr>
          <th align="center" colspan="2">Добавление шаблона</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <td class="foot" align="center" colspan="2">
            <input class="btn" type="submit" value="Добавить" name="template_create">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr vAlign="top" align="left">
          <td class="head">Имя компонента</td>
          <td class="even">{$tpl_compname}</td>
        </tr>
        <tr vAlign="top" align="left">
          <td class="head">Имя файла</td>
          <td class="even"><input type="text" name="tpl_name" size="50" value="{$tpl_name}"></td>
        </tr>
        <tr vAlign="top" align="left">
          <td class="head">Маска</td>
          <td class="even"><input type="text" name="tpl_pattern" size="50" value="{$tpl_pattern}"></td>
        </tr>
        <tr vAlign="top" align="left">
          <td class="head">Описание</td>
          <td class="even"><textarea name="tpl_description" rows="5" cols="70">{$tpl_description}</textarea></td>
        </tr>
      </tbody>

    </table>
</form>
{/strip}