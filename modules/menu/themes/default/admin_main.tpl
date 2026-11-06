{strip}
<table class="table outer" cellSpacing="1" cellPadding="4" width="100%">
  <colgroup>
    <col width="30%">
    <col width="60%">
    <col width="5%">
    <col width="5%">
  </colgroup>

  <thead>
    <tr>
      <th colspan="4">Меню сайта</th>
    </tr>
    <tr>
      <td class="head" align="center">Имя элемента</td>
      <td class="head" align="center">Url / Html-код</td>
      <td class="head" align="center">Посл.</td>
      <td class="head" align="center">Действия</td>
    </tr>
  </thead>

  <tfoot>
    <tr>
      <td width="100%" align="center" colspan="5" class="foot">
		<a class="btn" href="{mod_url type='admin' modname='menu' func='new'}">Добавить элемент</a>
      </td>
    </tr>
  </tfoot>

  <tbody>
    {foreach item=item from=$items_list}
    {cycle name="muls" values="even,odd" assign="class" print=false}
      <tr>
        <td class="{$class}">
          {* Формируем масив переменные для урла *}
          {array name='url_vars'}
          {array_append name='url_vars' key='id' value=$item.id}
          {if $item.item_type==3 and $item.content}
            <a href="{$item.content}">{$item.displayname|escape|tree:$item.level}</a>
          {else}
            <b>{$item.displayname|escape|tree:$item.level}</b>
          {/if}
        </td>

        <td class="{$class}">
          {$item.content|escape}
        </td>

        <td class="{$class}" align="center">
          {if $item.weight_moveup_url}
            <a href="{$item.weight_moveup_url}"><i class="icon-arrow-up"></i></a>
          {/if}
          {if $item.weight_moveup_url && $item.weight_movedown_url}
            &nbsp;
          {/if}
          {if $item.weight_movedown_url}
            <a href="{$item.weight_movedown_url}"><i class="icon-arrow-down"></i></a>
          {/if}
        </td>

        <td class="{$class}" align="center">
          <a href="{mod_url type='admin' modname='menu' func='modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
          <a href="{mod_url type='admin' modname='menu' func='new' vars=$url_vars}" title="Добавить подпункт"><i class="icon-plus"></i></a>&nbsp;
          <a href="{mod_url type='admin' modname='menu' func='confirm_delete' vars=$url_vars}" title="Удалить"><i class="icon-remove"></i></a>
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>

{/strip}