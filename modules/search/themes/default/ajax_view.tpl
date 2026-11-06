{strip}
  {if $search_result|@count>0}
  	{counter start=0 print=false}
  	<table border="0" cellpadding="1" cellspacing="0" width="100%">
  		<tbody>
      {foreach item=result from=$search_result}
      	<tr onmouseover='search_hover(this);' onmousedown='search_clicked(this);' _value='{$result.displayname}'>
      		{if $result.live_description}
      			<td class="LiveTextOption" align="left">{$result.displayname|truncate:35:"...":true}</td>
      			<td class="LiveTextOption" align="right"><font color="#022D96">{$result.live_description}</font></td>
      		{else}
	      	  <td class="LiveTextOption" align="left" colspan="2">{$result.displayname|truncate:45:"...":true}</td>
	      	{/if}
      	</tr>
  		{/foreach}
  		</tbody>
		</table>
   	<script language="javascript" type="text/javascript">
  	 	search_show();
  	 	search_cache_set('{$search_string}');
    </script>
  {else}
   	<script language="javascript" type="text/javascript">
  	 	search_hide();
  	 	search_cache_set('{$search_string}');
    </script>
  {/if}
{/strip}