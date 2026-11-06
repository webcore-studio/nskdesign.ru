{strip}
  {* Для всех да нет *}
  {array name='yes_no'}
  {array_append name='yes_no' key='1' value=#yes#}
  {array_append name='yes_no' key='0' value=#no#}
    <form method="POST" name="config_form" action="{mod_url modname='content' type='admin' func='config_update'}">
      <table class="outer" cellSpacing="1" cellPadding="4">
        <colgroup>
          <col width="35%">
          <col width="65%">
        </colgroup>

        <thead>
        <tr>
          <th colSpan="2">{#content_config#}</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
          <td colspan="2" align="center" class="foot">
            <input type="submit" value="{#save#}" name="button">
          </td>
        </tr>
        </tfoot>

        <tr>
          <td class="head">{#enable_confirm#}</td>
          <td class="even">
            <select name="enable_confirm">
            {*дополнительная опция*}
            {if $enable_confirm eq 0}
                <option value="0" selected="selected">Нет</option>	
                <option value="1">Да</option>	
            {else}
                <option value="0">Нет</option>	
                <option value="1" selected="selected">Да</option>
            {/if}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">Email для отправки заявок</td>
          <td class="even">
            <input type="text" value="{$email_demand}" name="email_demand" size="40"/>
          </td>
        </tr>

        </tbody>

      </table>
    </form>
{/strip}
