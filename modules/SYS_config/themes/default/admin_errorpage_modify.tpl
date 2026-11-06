{strip}
  <form action="{mod_url modname='SYS_config' type='admin' func='errorpage_update'}" method="post">
  
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      
      <tfoot>
        <tr>
          <td align="center" class="foot">
            <input class="formButton" type="submit" value="{#save#}" name="button">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr>
          <td class="head">{#page404#} &nbsp; 
          <a href="/index.php?module=SYS_config&type=user&func=errorpage_view&page=404" target = _blank> просмотр</a><br>
           {texteditor name="page404" text=$page404}
          </td>
        </tr>

         <tr>
          <td class="head">{#page_error#} &nbsp; 
          <a href="/index.php?module=SYS_config&type=user&func=errorpage_view&page=error" target = _blank> просмотр</a><br>
           {texteditor name="page_error" text=$page_error}
          </td>
        </tr>


      

      </tbody>

    </table>
  </form>
{/strip}
