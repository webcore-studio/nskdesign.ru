{strip}
<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="20%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
      <col width="10%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="9">Поля таблицы</th>
      </tr>

      <tr>
        <td class="head" align="center">Поле</td>
        <td class="head" align="center">Тип</td>
        <td class="head" align="center">Ноль</td>
        <td class="head" align="center">По умолч.</td>
        <td class="head" align="center">Auto inc.</td>
        <td class="head" align="center">Перв.</td>
        <td class="head" align="center">Инд.</td>
        <td class="head" align="center">Уник.</td>
        <td class="head" align="center">Действия</td>
      </tr>
    </thead>

    <tfoot>
      <tr>
        {* Формируем масив переменные для урла *}
        {array name='url_vars'}
        {array_append name='url_vars' key='table_name' value=$table_name}
        <td width="100%" align="center" colspan="9" class="foot">
          <a href="{mod_url type='admin' modname='SYS_df' func='new' vars=$url_vars}">Добавить поле</a>
        </td>
      </tr>
    </tfoot>

    <tbody>
      {foreach item=field from=$table_fields}
        {cycle name="tf" values="even,odd" assign="class" print=false}
        <tr align="middle">
          <td class="{$class}" align="left">{$field.table_field}</td>

          <td class="{$class}" align="left" nowrap>{$field.table_type}&nbsp;
            {if $field.table_size}
             ({$field.table_size})
            {/if}
          </td>

          <td class="{$class}">
            {if $field.table_null}
              Да
            {else}
              Нет
            {/if}
          </td>

          <td class="{$class}">{$field.table_default}</td>

          <td class="{$class}">
            {if $field.table_increment}
              Да
            {else}
              Нет
            {/if}
          </td>

          <td class="{$class}">
            {if $field.table_primary_key}
              Да
            {else}
              Нет
            {/if}
          </td>

          <td class="{$class}">
            {if $field.table_index}
              Да
            {else}
              Нет
            {/if}
          </td>

          <td class="{$class}">
            {if $field.table_unique}
              Да
            {else}
              Нет
            {/if}
          </td>

          <td class="{$class}">
            {* список действий над модулем *}
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$field.id}
            {if $field.table_canmodify}
              <a href="{mod_url type='admin' modname='SYS_df' func='modify' vars=$url_vars}"><img border="0" src="/files/shared/images/system/edit.gif" alt="Редактировать"></a>&nbsp;
            {/if}
            {if $field.table_candelete}
              <a href="{mod_url type='admin' modname='SYS_df' func='confirm_delete' vars=$url_vars}"><img border="0" src="/files/shared/images/system/del.gif" alt="Удалить"></a>
            {/if}
          </td>

        </tr>
      {/foreach}

    </tbody>

  </table>
{/strip}