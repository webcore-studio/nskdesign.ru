{strip}
<form action="{mod_url modname='menu' type='admin' func='delete'}" method="post">
  <input name="id" type="hidden" value="{$id}">
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
  
    <thead>
      <tr>
        <th>Удаление элемента</th>
      </tr>
    </thead>

    <tfoot>    
      <tr align="center">
        <td class="foot" colspan="2" align="center"><input type="submit" value="Удалить" name="submit"></td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td class="even" align="center">Удалить элемент "{$displayname|escape}" ? </td>
      </tr>

      {if $content}
        <tr>
          <td class="even" colspan="2" align="center">{$content|escape}</td>
        </tr>
      {/if}
    </tbody>

  </table>
</form>
{/strip}
