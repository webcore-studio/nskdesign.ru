{strip}
<form action="{mod_url type='admin' modname='SYS_themes' func='weight'}" method="post">
<table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="50%">
      <col width="40%">
      <col width="5%">
      <col width="5%">
    </colgroup>

  <thead>
    <tr align="middle">
      <th colspan="4">Просмотр шаблонов компонента</th>
    </tr>

    <tr align="middle">
      <th class="head">Маска/Имя файла</th>
      <th class="head">Описание</th>
      <th class="head">Позиция</th>
      <th class="head">Действия</th>
    </tr>
  </thead>

   <tfoot>
    <tr>
      <td colspan="2" class="foot">
        {array name='url_vars'}
        {array_append name='url_vars' key='tpl_compname' value=$tpl_compname}
        <a class="btn" href="{mod_url type='admin' modname='SYS_themes' func='template_new' vars=$url_vars}">Добавить</a>
      </td>
	  <td colspan="2" class="foot" style="text-align:right">
        {array name='url_vars'}
        {array_append name='url_vars' key='tpl_compname' value=$tpl_compname}
        <input type="submit" class="btn" value="Сохранить сортировку">
      </td>
    </tr>
  </tfoot>

  <tbody>
  {foreach item=template from=$tpls_list}
  {cycle name="tplslist" values="even,odd" assign="class" print=false}
    <tr align="middle">
      <td align="left" class="{$class}">{$template.tpl_pattern}<br>{$template.tpl_name}</td>
      <td align="left" class="{$class}">{$template.tpl_description}</td>
      <td align="center" nowrap class="{$class}">
        {* Передвижка весов *}
        <input type="hidden" name="ids[]" value="{$template.id}">
		<input type="text" size="2" value="{$template.weight}" name="weights[]" class="span1">
      </td>

      <td class="{$class}">
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$template.id}
        <a href="{mod_url type='admin' modname='SYS_themes' func='template_modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
        <a href="{mod_url type='admin' modname='SYS_themes' func='template_confirm_delete' vars=$url_vars}" title="Удалить"><i class="icon-remove"></i></a>
      </td>
    </tr>
  {/foreach}
  </tbody>

</table>
</form>
{/strip}