{foreach from=$fields_values item=field}
{if $field.values}
<p>{$field.fld_displayname}: 
{foreach  from=$field.values item=v name=values}
{$v.fld_field}{if !$smarty.foreach.values.last}, {/if}
{/foreach}
</p>
{/if}
{/foreach}