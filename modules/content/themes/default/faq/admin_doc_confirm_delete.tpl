{strip}

  <form action="{mod_url modname='content' type='admin' func='doc_delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">
  <input name="fid" type="hidden" value="{$fid}">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">

        <tr>
          <th>{#deletefaq#}</th>
        </tr>
        <tr align="center">
          <td class="head">{#deleteconfirmfaq#}&nbsp;"{$displayname|escape}" ?</td>
        </tr>
        <tr align="center">
          <td class="even"><input type="submit" value="{#delete#}" name="submit"></td>
        </tr>

    </table>

  </form>

{/strip}