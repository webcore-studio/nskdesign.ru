{strip}
{* Для всех да нет *}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

<form action="{mod_url modname='SYS_df' type='admin' func='create'}" method="post">
<input type="hidden" name="table_name" value="{$table_name}">

<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup align="center">
      <col width="20%">
      <col width="20%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="8">Добавление поля в таблицу</th>
      </tr>

    <tr>
      <td class="head" align="center">Поле</td>
      <td class="head" align="center">Тип</td>
      <td class="head" align="center">Длины</td>
      <td class="head" align="center">Ноль</td>
      <td class="head" align="center">По умолч.</td>
      <td class="head" align="center">Инд.</td>
      <td class="head" align="center">Уник.</td>
      <td class="head" align="center">---</td>
    </tr>
    </thead>

    <tfoot>
    <tr>
      <th colspan="8" align="center" class="foot">
        <input type="submit" value="Добавить" name="submit">
      </th>
    </tr>
    </tfoot>

    <tbody>
    <tr>

      <td class="even"><input maxLength="64" size="16" value="" name="table_field"></td>

      <td class="even">
        <select size="1" name="table_type">
          {html_options options = $table_types}
        </select>
      </td>

      <td class="even">
        <input size="8" name="table_size">
      </td>

      <td class="even">
        <select size="1" name="table_null ">
          {html_options options=$yes_no}
        </select>
      </td>

      <td class="even"><input size="12" name="table_default"></td>

      <td class="even"><input type="radio" value="table_index" name="key"></td>
      <td class="even"><input type="radio" value="table_unique" name="key"></td>
      <td class="even"><input type="radio" value="" checked name="key"></td>

    </tr>
  </tbody>

</table>

</form>
{/strip}