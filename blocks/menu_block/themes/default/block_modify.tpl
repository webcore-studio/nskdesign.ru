{debug}
{strip}

  <tr>
    <td width="20%" align="left" class="head">Поведение меню :</td>
    <td width="80%" align="left" class="even">
      <select name=menu_type>
        {html_options options=$menutypes_list selected=$menu_type}
      </select>
    </td>
  </tr>

      <tr>
        <td class="head">Шаблон</td>
        <td class="even">
          <select name="template">
            {html_options options=$templates_list selected=$template}
          </select>
        </td>
      </tr>

  <tr>
    <td width="20%" align="left" class="head">Начинать с элемента :</td>
    <td width="80%" align="left" class="even">

      {* Формируем масив для вункции html_options *}
      {array name='flat_itemslist'}

      {foreach item=item from=$items_list}
        {array_append name='flat_itemslist' key=$item.id value=$item.displayname|escape|tree:$item.level}
      {/foreach}

      <select name="parent_id">
        {html_options options=$flat_itemslist selected=$parent_id}
      </select>

    </td>
  </tr>

{/strip}
