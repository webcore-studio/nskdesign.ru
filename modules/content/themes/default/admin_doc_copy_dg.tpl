{strip}
      <form action="{mod_url type='admin' modname='content' func='doc_copy'}" method="post">
        <input type="hidden" name="http_referer" value="{$http_referer}">
        <input type="hidden" name="id" value="{$id}">
        <input type="hidden" name="fid" value="{$fid}">
        <table class="outer" cellSpacing="1" cellPadding="4">

        <thead>
          <tr>
            <th colspan="2">
              {#copyingdoc#}&nbsp;"{$displayname|escape}"
            </th>
          </tr>
          <tr>
            <td colspan="2" class="head">
              {#tofolder#}:
            </td>
          </tr>
        </thead>




        <tbody>
          {counter start=0 print=false}
          {foreach item=folder from=$folders_list}
            <tr>
              <td colspan="2" class="even">
                {$folder.displayname|escape|tree:$folder.level:'+--&nbsp;<img src="/files/shared/images/system/folder.gif" border=0>&nbsp;'}
                &nbsp;&nbsp;&nbsp;<input type="checkbox" value="{$folder.id}" name="mv2folder[{counter}]">
              </td>
            </tr>
          {/foreach}
        </tbody>

        <tfoot>
          <tr>
            <td align="right" width="50%" nowrap class="even">
              <input type="submit" value="{#copy#}">
            </td>
            </FORM>
            <form action="{$http_referer}" method="post">
            <td align="left" width="50%" nowrap class="even">
                <input type="submit" value="{#cancel#}">
            </td>
            </FORM>
          </tr>
        </tfoot>

        </table>
{/strip}