{literal}
<script>
$(document).ready(function(){
$("#select_name").click(function(){
$("#select_div").toggle("fast");
})
})
</script>
{/literal}
<form method=POST action="{mod_url type='admin' modname='fields' func='field_update'}" method="post" name="fld_modify_form">
<input type="hidden" name="fld_type" id="fld_type" value="{$fld_type}">
<input type="hidden" name="id_field" value="{$id}">
Название поля: <input type="text" value="{$fld_displayname}" name="fld_displayname" id="fld_displayname">
<h3 class="head_href" id="select_name" style="cursor:pointer; text-decoration:underline">Варианты выбора</h3>
{if $fld_type=='text'}
<div style="display:none;margin-bottom:20px" id="select_div">
<input type="submit" value="Сохранить" class="submit">
<table>
<tr>
<td><b>Название</b></td>
<td><b>Удалить</b></td>
</tr>
<tr><td colspan=2 style="border-bottom:1px solid #aaa"></td></tr>
{foreach from=$param_dbdata item=p}
<tr>
<td>{$p.fld_field}</td>
<td><input type="checkbox" value="{$p.id}" name="param_delete[]"></td>
</tr>
<tr><td colspan=2 style="border-bottom:1px solid #aaa"></td></tr>
{/foreach}
</table>
</div>
<h3>Добавить новый вариант</h3>
<table>
<tr>
<td>Название</td><td><input type="text" value="" name="fld_field"></td>
</tr>
</table>
{elseif $fld_type=='file'}
{elseif $fld_type=='radio'}
<div style="display:none;margin-bottom:20px" id="select_div">
<input type="submit" value="Сохранить" class="submit">
<table>
<tr>
<td><b>Изображение</b></td>
<td><b>Название</b></td>
<td><b>По умолчанию</b></td>
<td><b>Удалить</b></td>
</tr>
<tr><td colspan=4 style="border-bottom:1px solid #aaa"></td></tr>
{foreach from=$param_dbdata item=p}
<tr>
<td>{if $p.fld_image}<img src="{$p.fld_image}" style="max-width:50px; max-height:50px">{/if}</td>
<td>{$p.fld_field}</td>
<td>{$p.fld_default}</td>
<td><input type="checkbox" value="{$p.id}" name="param_delete[]"></td>
</tr>
<tr><td colspan=4 style="border-bottom:1px solid #aaa"></td></tr>
{/foreach}
</table>
</div>
<h3>Добавить новый вариант</h3>
<table>
<tr>
<td>Изображение</td><td><input type="text" value="" name="fld_image">{imagebrowse action="document.fld_modify_form.fld_image.value"}</td>
</tr>
<tr>
<td>Название</td><td><input type="text" value="" name="fld_field"></td>
</tr>
<tr>
<td>По умолчанию</td><td><input type="checkbox" name="fld_default" value="1"></td>
</tr>
</table>
{else}
{/if}
<input type="submit" value="Сохранить" >
</form>
