{strip}
{* Сортировать по возрастанию ? *}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

{* Сортировать по возрастанию ? *}
{array name='asc_yes_no'}
{array_append name='asc_yes_no' key='1' value='По возрастанию&nbsp;'}
{array_append name='asc_yes_no' key='0' value='По убыванию&nbsp;'}

  <form action="{mod_url modname='search' type='admin' func='updateconfig'}" method="post">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Опции поиска</th>
        </tr>
      </thead>

      <tfoot>
        <tr align="left">
          <td  colspan="2" align="center" class="foot">
            <input class="formButton" type="submit" value="Сохранить" name="button">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr>
          <td align="left" class="head">Минимальная длина ключевого слова</td>
          <td class="even">
            <input maxLength="5" size="5" value="{$search_string_min}" name="search_string_min">
          </td>
        </tr>

        <tr align="left">
          <td align="left" class="head">Использовать "живой поиск"</td>
          <td class="even">
            {html_radios name="use_livesearch" options=$yes_no checked=$use_livesearch separator=" "}
          </td>
        </tr>

        <tr>
          <td align="left" class="head">Варианты выбора "результатов на страницу"</td>
          <td class="even">
            <input maxLength="255" size="50" value="{$result_perpage_list}" name="result_perpage_list">
          </td>
        </tr>

        <tr>
          <td align="left" class="head">Результатов на страницу (по умолчанию)</td>
          <td class="even">
            <input maxLength="5" size="5" value="{$result_perpage}" name="result_perpage">
          </td>
        </tr>

        <tr align="left">
          <td align="left" class="head">Сортировать по</td>
          <td class="even">
            <select name="result_orderby">
              {html_options options=$sort_orders_list selected=$result_orderby}
            </select>
            &nbsp;&nbsp;
            {html_radios name="result_order_asc" options=$asc_yes_no checked=$result_order_asc separator=" "}
          </td>
        </tr>

      </tbody>

    </table>
  </form>
{/strip}