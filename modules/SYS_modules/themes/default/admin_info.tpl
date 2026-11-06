{strip}
<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
  <colgroup>
    <col width="35%">
    <col width="65%">
  </colgroup>

  <tfoot>
  <tr>
    <td align="center" colspan="2" class="foot">
      <a href="{mod_url modname='SYS_modules' type='admin' func='main'}">Список модулей</a>
    </td>
  </tr>
  </tfoot>

  <tbody>
  <tr>
    <th align="center" colspan="2">Информация о модуле</th>
  </tr>

  <tr>
    <td align="left" class="head">Системное имя:</td>
    <td align="right" class="even">{$mod_name|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Описание:</td>
    <td align="right" class="even">{$mod_description|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Отображаемое имя:</td>
    <td align="right" class="even">{$mod_displayname|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Директория модуля:</td>
    <td align="right" class="even">modules/{$mod_name|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">ID модуля в системе:</td>
    <td align="right" class="even">{$id}</td>
  </tr>
  <tr>
    <td align="left" class="head">Версия:</td>
    <td align="right" class="even">{$mod_version|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Автор(ы):</td>
    <td align="right" class="even">{$mod_author|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Контактная информация:</td>
    <td align="right" class="even">{$mod_contact|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Лицензия:</td>
    <td align="right" class="even">{$mod_license|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Changelog:</td>
    <td align="right" class="even">{$mod_changelog|escape}</td>
  </tr>
  <tr>
    <td align="left" class="head">Зависимости:</td>
    <td align="right" class="even">{$mod_dependency|escape}</td>
  </tr>
  </tbody>

</table>
{/strip}