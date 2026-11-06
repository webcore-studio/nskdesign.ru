{strip}

      <form action="{mod_url type='admin' modname='content' func='doc_copy'}" method="post">
        <input type="hidden" name="http_referer" value="{$http_referer}">
        <input type="hidden" name="id" value="{$id}">
        <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
          <tr>
            <th colspan="2">
              Копирование вопроса/ответа "{$displayname|escape}"
            </th>
          </tr>
          <tr>
            <td colspan="2" class="head">
              В раздел(ы):
            </td>
          </tr>
          {counter start=0 print=false}
          {foreach item=folder from=$folders_list}
            <tr>
              <td colspan="2" class="even">
                {$folder.displayname|escape|tree:$folder.level:'+--&nbsp;<img src="files/shared/images/system/folder.gif" border=0>&nbsp;'}
                &nbsp;&nbsp;&nbsp;<input type="checkbox" value="{$folder.id}" name="mv2folder[{counter}]">
              </td>
            </tr>
          {/foreach}

          <tr>
            <td align="right" width="50%" nowrap class="even">
              <input type="submit" value="Скопировать">
            </td>
      </FORM>
            <form action="{$http_referer}" method="post">
              <td align="left" width="50%" nowrap class="even">
              <input type="submit" value="Отмена"></td>
            </FORM>
          </tr>

        </table>

{/strip}