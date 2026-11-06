{strip}
  <table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
		<col width="5%">
      <col width="20%">
      <col width="10%">
      <col width="30%">
      <col width="10%">
      <col width="10%">
      <col width="5%">
      <col width="10%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="8">Установленные блоки</th>
      </tr>

      <tr align="middle">
	  <td class="head">№ блока</td>
        <td class="head" nowrap>Отображаемое имя</td>
        <td class="head">Имя блока</td>
        <td class="head" nowrap>Маска</td>
        <td class="head">Положение</td>
		<td class="head">Шаблон</td>
        <td class="head">Порядок</td>
        <td class="head">Действия</td>
      </tr>
    </thead>

    <tbody>
    {foreach item=block from=$blocks_list_installed}
      {cycle name="instblc" values="even,odd" assign="class" print=false}

      <tr align="middle">
		<td class="{$class}" align="left">{$block.id}</td>
        <td class="{$class}" align="left">
			{if $block.block_displayname} {$block.block_displayname|escape}
			{else} - 
			{/if}
			{if $block.block_comment}
				<br><small>({$block.block_comment|escape})</small>
			{/if}
		</td>
        <td class="{$class}" align="left">{$block.block_name|escape}</td>
        <td class="{$class}" align="left" nowrap><div title="{$block.block_pattern|escape}">{$block.block_pattern|escape|truncate:40:'.....'}</div></td>
        <td class="{$class}" nowrap>{$block.block_position}</td>
		<td class="{$class}" align="center">
			{if $block.block_tpl}<img src="/files/shared/images/system/help.gif" alt="{$block.block_tpl|escape}" title="{$block.block_tpl|escape}">{/if}
		</td>

        {* Передвижка весов *}
        <td class="{$class}" align="center" nowrap>
          {if $block.weight_moveup_url}
            <a href="{$block.weight_moveup_url}"><i class="icon-arrow-up"></i></a>
          {/if}
          {if $block.weight_moveup_url && $block.weight_movedown_url}
            &nbsp;
          {/if}
          {if $block.weight_movedown_url}
            <a href="{$block.weight_movedown_url}"><i class="icon-arrow-down"></i></a>
          {/if}
        </td>

        <td class="{$class}" align="center" nowrap>
          {* Действия над блоком *}
	{if $block.block_name!="advert_block"}
          {array name='url_vars'}
          {array_append name='url_vars' key='id' value=$block.id}
          <a href="{mod_url type='admin' modname='SYS_blocks' func='info' vars=$url_vars}" title="Информация"><i class="icon-info-sign"></i></a>&nbsp;
          {if $block.block_active}
            <a href="{mod_url type='admin' modname='SYS_blocks' func='deactivate' vars=$url_vars}" title="Деактивировать"><i class="icon-stop"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_blocks' func='modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
          {else}
            <a href="{mod_url type='admin' modname='SYS_blocks' func='activate' vars=$url_vars}" title="Активировать"><i class="icon-play"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_blocks' func='modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
            <a onclick="return confirm ('Вы точно хотите удалить блок?')"  href="{mod_url type='admin' modname='SYS_blocks' func='delete' vars=$url_vars}" title="Удалить"><i class="icon-remove"></i></a>&nbsp;
          {/if}
	{/if}
        </td>
      </tr>
    {/foreach}
    </tbody>
  </table>

  <br>

  <table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="20%">
      <col width="10%">
      <col width="50%">
      <col width="10%">
      <col width="10%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="5">Доступные блоки</th>
      </tr>

      <tr align="middle">
        <td class="head" nowrap>Отображаемое имя</td>
        <td class="head">Имя блока</td>
        <td class="head">Описание</td>
        <td class="head">Версия</td>
        <td class="head">Действия</td>
      </tr>
    </thead>

    <tbody>
      {foreach item=block from=$blocks_list_all}
      {cycle name="allblc" values="even,odd" assign="class" print=false}
      <tr align="middle">
        <td class="{$class}" align="left">{$block.block_displayname|escape}</td>
        <td class="{$class}" align="left">{$block.block_name|escape}</td>
        <td class="{$class}" align="left">{$block.block_description|escape}</td>
        <td class="{$class}">{$block.block_version|escape}</td>
        <td class="{$class}">
          {* Действия над блоком *}
          {array name='url_vars'}
          {array_append name='url_vars' key='block_name' value=$block.block_name}
          <a href="{mod_url type='admin' modname='SYS_blocks' func='info' vars=$url_vars}" title="Информация"><i class="icon-info-sign"></i></a>&nbsp;
          <a href="{mod_url type='admin' modname='SYS_blocks' func='add' vars=$url_vars}" title="Добавить блок"><i class="icon-plus"></i></a>
        </td>
      </tr>
      {/foreach}
    </tbody>

  </table>
{/strip}