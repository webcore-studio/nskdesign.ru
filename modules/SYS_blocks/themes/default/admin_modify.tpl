{strip}
<div id="block_modify">
<form action="{mod_url type='admin' modname='SYS_blocks' func='update'}" method="post" id="form_block_modify" name="form_block_modify">
<input type="hidden" name="id" value="{$id}">

<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
  <colgroup>
    <col width="35%">
    <col width="65%">
  </colgroup>

  <thead>
  <tr>
    <th width="100%" align="center" colspan="2">Редактирование блока "{$block_name}"</th>
  </tr>
  </thead>

  <tfoot>
  <tr>
    <td width="100%" align="center" class="foot" colspan="2">
      <input type="submit" value="Сохранить" name="submit">
    </th>
  </tr>
  </tfoot>

  <tbody>
  <tr>
    <td width="20%" align="left" class="head">Название :</td>
    <td width="80%" align="left" class="even">{$block_name}</td>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">ID:</td>
    <td width="80%" align="left" class="even">{$id}</td>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">Отображаемое название:</td>
    <td width="80%" align="left" class="even">
      <input type="text" name="block_displayname" size="50" value="{$block_displayname|escape}">
    </td>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">Комментарий</td>
    <td width="80%" align="left" class="even">
      <input type="text" name="block_comment" size="50" value="{$block_comment|escape}">
    </td>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">Маска :</td>
    <td width="80%" align="left" class="even">
      <input type="text" name="block_pattern" size="50" value="{$block_pattern|escape}">
    </td>
  </tr>
   <tr>
    <td width="20%" align="left" class="head">Шаблон :</td>
    <td width="80%" align="left" class="even">
      <input type="text" name="block_tpl" size="50" value="{$block_tpl|escape}">
    </td>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">Положение :</td>
    <td width="80%" align="left" class="even">
    <select name=block_position>
      {html_options options=$positions selected=$block_position}</td>
    </select>
  </tr>
  <tr>
    <td width="20%" align="left" class="head">Язык :</td>
    <td width="80%" align="left" class="even">
      <select name=block_lang>
        {html_options options=$langlist selected=$block_lang}
      </select>
    </td>
  </tr>
        {array name='yes_no'}
        {array_append name='yes_no' key='1' value="да"}
        {array_append name='yes_no' key='0' value="нет"}
  <tr>
    <td width="20%" align="left" class="head">Активен :</td>
    <td width="80%" align="left" class="even">{html_radios name="block_active" options=$yes_no checked=$block_active separator=" "}</td>
  </tr>
  {$blockModifyInput}
  </tbody>

</table>



</form>
</div>
{/strip}