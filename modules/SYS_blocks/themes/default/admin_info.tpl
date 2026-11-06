{strip}
<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
  <colgroup>
    <col width="35%">
    <col width="65%">
  </colgroup>

  </tfoot>
  <tr>
    <td align="center" class="foot" colspan="2">
      <a href="{mod_url modname='SYS_blocks' type='admin' func='main'}">Список блоков</a>
    </td>
  </tr>
  <tfoot>

  <tbody>
  <tr>
    <th align="center" colspan="2">Информация о блоке "{$block_name|escape}"</th>
  </tr>
  <tr>
    <td align="left" class="head">Название:</td>
    <td align="right" class="even">{$block_name|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Описание:</td>
    <td align="right" class="even">{$block_description|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Отображаемое название:</td>
    <td align="right" class="even">{$block_displayname|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Директория:</td>
    <td align="right" class="even">blocks/{$block_name|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">ID:</td>
    <td align="right" class="even">{$id}</td>
  </tr>
  <tr>
    <td align="left" class="head">Версия:</td>
    <td align="right" class="even">{$block_version|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Автор(ы):</td>
    <td align="right" class="even">{$block_author|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Контакт(ы):</td>
    <td align="right" class="even">{$block_contact|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Лицензия:</td>
    <td align="right" class="even">{$block_license|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Изменения:</td>
    <td align="right" class="even">{$block_changelog|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Зависимости:</td>
    <td align="right" class="even">{$block_dependency|escape}</td>
  </tr>
  </tbody>

</table>

{/strip}