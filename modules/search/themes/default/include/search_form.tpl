{* Сортировать по возрастанию ? *}
{array name='asc_yes_no'}
{array_append name='asc_yes_no' key='1' value='Возрастанию&nbsp;'}
{array_append name='asc_yes_no' key='0' value='Убыванию&nbsp;'}

<form name="search" action="index.php" method="get" class="form-search">
	<input type="hidden" name="module" value="search">
        <input type="hidden" name="func" value="view">
	<input name="result_orderby" value="score" type="hidden">
	<input name="result_order_asc" value="0" type="hidden">

{if $use_livesearch}
 <input class="span7" type="text" id="mod_search_string" autocomplete="off" onkeyup="search_onkeyup(event);" onkeydown="search_onkeydown(event);" onblur="search_hide();" maxlength="100" size="40" name="search_string"  value="{$search_string|escape}">&nbsp;<input type="submit" class="btn" value="Поиск">
	<div id="mod_search_list" class="LiveTextResult span7"></div>	
{else}
<input class="span7" type="text" id="mod_search_string" autocomplete="on" maxLength="100" size="20" name="search_string" style="width: 100%" value="{$search_string|escape}">
&nbsp;<input type="submit" class="btn" value="Поиск">
{/if}


<table> 
      {if !$use_livesearch}
        <tr>
          <td class="even" align="center" valign="middle" nowrap>
            Результатов на страницу&nbsp;
            <select name = "result_perpage">
              {html_options options=$result_perpage_list selected=$result_perpage}
            </select>
          </td>

          <td class="even" align="center">
            Сортировать по&nbsp;
            <select name = "result_orderby">
              {html_options options=$sort_orders_list selected=$result_orderby}
            </select>
            &nbsp;&nbsp;
            {html_radios name="result_order_asc" options=$asc_yes_no checked=$result_order_asc separator=" "}
          </td>
        </tr>
      {else}
        <tr>
          <td class="even" align="center" valign="middle"></td>
          <td class="even" align="center"></td>
        </tr>
      {/if}

    </tbody>

  </table>
</form>
