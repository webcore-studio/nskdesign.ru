{foreach from=$field_list item=fld key=k}
{if $fld.fld_type=='radio'}
	<tr>
	<td>{$fld.fld_displayname}</td>
	<td>
	{foreach from=$fld.param item=p}
	<p><input type="radio" value="{$p.id}" name="fld[{$fld.id}][]" {if $p.fld_value==$p.id} checked {/if}>&nbsp;{$p.fld_field}</p>
	{/foreach}
	</td>
	</tr>
{else}
	<tr>
	<td>{$fld.fld_displayname}</td>
	<td>
	{foreach from=$fld.param item=p}
	<p><input type="checkbox" value="{$p.id}" name="fld[{$fld.id}][]" {if $p.fld_value} checked {/if}>{$p.fld_field}</p>
	{/foreach}
	</td>
	</tr>
{/if}
{/foreach}