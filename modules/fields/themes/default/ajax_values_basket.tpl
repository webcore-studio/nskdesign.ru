<form action="/shop/add_to_basket" method=post>
<input type="hidden" name="id" value="{$fld_key_b}">
{foreach from=$fields_values item=field}
{if $field.values}
<p>{$field.fld_displayname}: 

{if $field.fld_type=="checkbox"}
{foreach from=$field.values item=v name=values}
<br>{$v.fld_field} <input type="checkbox" name="" value="1">
{/foreach}

{elseif $field.fld_type=="select"}
<select name="field{$field.id}">
{foreach from=$field.values item=v name=values}
<option value="{$v.fld_field}">{$v.fld_field} </option>
{/foreach}
</select>

{elseif $field.fld_type=="text"}
{foreach from=$field.values item=v name=values}
<br>{$v.fld_field} <input type="text" name="field{$field.id}[{$v.fld_field}]" value="1">
{/foreach}

{/if}

</p>
{else}
<input type="text" name="count" value="1">
{/if}
{/foreach}
<input type="submit" value="Добавить">
</form>