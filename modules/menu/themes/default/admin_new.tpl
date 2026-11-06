{strip}

{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

<form name="item_new_form" action="{mod_url type='admin' modname='menu' func='create'}" method="post">
  <input type="hidden" name="parent_id" value="{$parent_id}">
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="25%">
      <col width="75%">
    </colgroup>
    
    <thead>
      <tr>
        <th colSpan="2">Добавление элемента</th>
      </tr>
    </thead>
    
    <tfoot>
      <tr>
        <td class="foot" colspan="2" align="center">
          <input class="btn formButton" type="submit" value="Добавить" name="submit">
        </td>
      </tr>
    </tfoot>
    
    <tbody>
      <tr>
        <td class="head">Добавить в</td>
        <td class="even">
          {* Формируем плоский масив для вункции html_options *}
          {array name='flat_itemslist'}
          {array_append name='flat_itemslist' key='0' value='Корень'}

          {foreach item=item from=$items_list}
            {array_append name='flat_itemslist' key=$item.id value=$item.displayname|tree:$item.level}
          {/foreach}

          <select name=parent_id>
            {html_options options=$flat_itemslist selected=$parent_id}
          </select>

        </td>
      </tr>

      <tr>
        <td class="head">Активна</td>
        <td class="even">
          {html_radios name="active" options=$yes_no checked=1 separator=" "}
        </td>
      </tr>

      <tr>
        <td class="head">Имя</td>
        <td class="even">
          <input type="text" size="70" name="displayname" value="{$displayname}">
        </td>
      </tr>
      
      <tr>
        <td class="head">Маска, где развернуты вложенные элементы</td>
        <td class="even">
          <input type="text" size="70" name="item_pattern" value="{$item_pattern}">
        </td>
      </tr>

      <tr>
        <td class="head">Описание</td>
        <td class="even">
          <textarea name="description" rows="5" cols="70">{$description}</textarea>
        </td>
      </tr>

      <tr>
        <th>Тип : </th>
        <th>Параметры : </th>
      </tr>

      <tr>
        <td class="head">
          <input type="radio" value="1" checked name="item_type"> Разделитель
        </td>
        <td class="even">&nbsp</td>
      </tr>

      <tr>
        <td class="head">
          <input type="radio" value="2" name="item_type"> Заголовок
        </td>
        <td class="even">&nbsp</td>
      </tr>

      <tr>
        <td class="head">
          <input type="radio" value="3" name="item_type"> Url
        </td>
        <td class="even">
          <input type="text" size="70" name="content3" value="{$content}">
        </td>
      </tr>

      <tr>
        <td class="head">
          <input type="radio" value="4" name="item_type"> Html-код
        </td>
        <td class="even">
          <textarea name="content4" rows="5" cols="70">{$content}</textarea>
        </td>
      </tr>


      <tr>
        <td class="head">
         Маска
        </td>
        <td class="even">
          <input type="text" size="70" name="pattern" value="">
        </td>
      </tr>
    </tbody>
    
  </table>
</form>

{/strip}