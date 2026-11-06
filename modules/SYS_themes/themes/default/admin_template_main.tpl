{strip}
  <table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="30%">
      <col width="30%">
      <col width="20%">
      <col width="20%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="5">Шаблоны компонентов</th>
      </tr>

      <tr align="middle">
        <td class="head">Отображаемое имя</td>
        <td class="head">Имя компонента</td>
        <td class="head">Версия</td>
        <td class="head">Действие</td>
      </tr>
    </thead>

    <tbody>
      {foreach item = mod from=$modules_list}
      {cycle name="complist" values="even,odd" assign="class" print=false}
      <tr align="middle">
        <td class="{$class}" align="left">{$mod.mod_displayname}</td>
        <td class="{$class}" align="left">{$mod.mod_name}</td>
        <td class="{$class}">{$mod.mod_version}</td>
        <td class="{$class}">
           {* В зависимости от состаяния модуля выводим действия *}
            {array name='url_vars'}
            {array_append name='url_vars' key='tpl_compname' value=$mod.mod_name}
              <a href="{mod_url type='admin' modname='SYS_themes' func='template_view' vars=$url_vars}">Шаблоны</a>
        </td>
      </tr>
      {/foreach}

      {foreach item = block from=$blocks_list}
      {cycle name="complist" values="even,odd" assign="class" print=false}
      <tr align="middle">
        <td class="{$class}" align="left">{$block.block_displayname}</td>
        <td class="{$class}" align="left">{$block.block_name}</td>
        <td class="{$class}">-</td>
        <td class="{$class}">
           {* В зависимости от состаяния модуля выводим действия *}
            {array name='url_vars'}
            {array_append name='url_vars' key='tpl_compname' value=$block.block_name}
              <a href="{mod_url type='admin' modname='SYS_themes' func='template_view' vars=$url_vars}">Шаблоны</a>
        </td>
      </tr>
      {/foreach}
    </tbody>

  </table>
{/strip}