{debug}
<table style="border:1px solid #777" cellpadding=3 cellspacing=0 rules=all border=1>
{foreach from=$fields_list item=fld}
<tr>
<td>{$fld.fld_displayname} </td>
<td>
<a href="/index.php?module=fields&type=admin&func=field_modify&id={$fld.id}"><img src="/files/shared/images/system/edit.gif" alt="Редактировать" title="Редактировать"></a>
<a href="#"><img src="/files/shared/images/system/del.gif" alt="Удалить" title="Удалить"></a>
</td>
</tr>
{foreachelse}
{/foreach}
</table>
<br><br>

{if !$fld_key_b}
<h2>Добавить новое поле</h2>
<form method=POST action="{mod_url type='admin' modname='fields' func='field_create'}" method="post">
<input type="hidden" name="fld_module" value="{$fld_module}">
<input type="hidden" name="fld_key_a" value="{$fld_key_a}">
<input type="hidden" name="fld_key_b" value="{$fld_key_b}">


<p>Название: <input type="text" name="fld_displayname" value="" id="fld_displayname">
<p>Тип: 
<select name="fld_type" id="fld_type">
<option value="text" selected>text</option>
<option value="radiobutton">radiobutton</option>
<option value="checkbox">checkbox</option>
<option value="select">select</option>
<option value="multiselect">multiselect</option>
<option value="file">file</option>
</select>
<p>Наследование: 
<select name="fld_inh" id="fld_inh">
<option value="2" selected>Элемент и его потомки</option>
<option value="0">Только элемент</option>
<option value="1">Только потомки</option>
<p><input type="submit" name="submit" value="Добавить поле" id="submit">
</form>
{/if}