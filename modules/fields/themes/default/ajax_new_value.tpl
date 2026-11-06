<form method=POST action="{mod_url type='ajax' modname='fields' func='field_value_create'}" method="post">
<input type="hidden" name="fld_type" id="fld_type" value="{$fld_type}">
{if $fld_type=='text'}
<p>Значение по умолчанию:
<input type="text" name="fld_default" id="fld_default" value="{$fld_default}">
{else if $fld_type=='file'}
{else}
Варианты выбора:
{foreach from=$fld_values item=fld}
<p><input type="text" value="{$fld}"> <input type="radio" id="fld_default" name="" value="{$fld}"> по умолчанию
{/foreach}
<p><input type="text" value="">
<p><input type="text" value="">
{/if}
</form>