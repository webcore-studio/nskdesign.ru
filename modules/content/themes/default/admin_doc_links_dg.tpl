<script type="text/javascript" src="/modules/content/scripts/script.js"></script>
{strip}
      <form action="{mod_url type='admin' modname='content' func='doc_links'}" method="post">
        <input type="hidden" name="http_referer" value="{$http_referer}">
        <input type="hidden" name="id" value="{$id}">

        <table class="outer" cellSpacing="1" cellPadding="4">

        <thead>
          <tr>
            <th colspan="2">
              {#managelink#}&nbsp;"{$displayname|escape}"
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
		    {if isset($folder)}
						
            <tr class="level{$folder.level} elemc{$folder.parent_id}" id="{$folder.id}" {if $folder.level==0}style="display:block;"{else}style="display:none;"{/if} >
              <td colspan="2" class="even">
              <input type="hidden" name="folder_id" value="{$folder_id}" />
               <a href="#" onClick="open_brunch_c({$folder.id},1);return false;"> {$folder.displayname|escape|tree:$folder.level:'+--&nbsp;<img src="files/shared/images/system/folder.gif" border=0>&nbsp;'}</a>
                &nbsp;&nbsp;&nbsp;<input type="hidden" name="fid[{$folder.id}]" value="0"/><input type="checkbox" value="1"
                 {foreach item=temp from=$folders}
                    {if $temp == $folder.id}
                    checked="checked"
                    {/if}
                {/foreach} name="fid[{$folder.id}]">
              </td>
            </tr>
			{/if}
          {/foreach}
        </tbody>

        <tfoot>
          <tr>
            <td align="right" width="50%" nowrap class="even">
              <input  class="btn" type="submit" value="{#change_links#}">
            </td>
            <td align="left" width="50%" nowrap class="even">
            <form action="{$http_referer}" method="get">
                <input class="btn" type="button" value="{#cancel#}" onClick="window.location.href='{$http_referer}';">
            </form>
          </td>
          </tr>
        </tfoot>

        </table>
        </FORM>
		{if $open_brunch}
<script>
{foreach item=open from=$open_brunch name=opens}
open_brunch_c({$open.id},1);
{/foreach}
</script>
{/if}
{/strip}
