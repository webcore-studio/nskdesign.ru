{strip}
  <form action="{mod_url modname='content' type='admin' func='subdoc_delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">

    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th>{#deletingpage#}</th>
        </tr>
        <tr align="center">
          <td class="head">{#deletepage#}&nbsp;"{$displayname|escape}"&nbsp;?</td>
        </tr>
        <tr align="center">
          <td class="even"><input type="submit" value="{#delete#}" name="submit"></td>
        </tr>

    </table>

  </form>
{/strip}