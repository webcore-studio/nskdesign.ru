{strip}
  <table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="10%">
      <col width="10%">
      <col width="60%">
      <col width="10%">
      <col width="5%">
      <col width="5%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="6">Права групп</th>
      </tr>

      <tr align="middle">
        <th class="head">Группа</th>
        <th class="head">Компонент</th>
        <th class="head">Объект</th>
        <th class="head">Уровень прав</th>
        <th class="head">Посл.</th>
        <th class="head">Действия</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td width="100%" align="center" colspan="6" class="foot">
          <a class="btn" href="{mod_url type='admin' modname='SYS_permissions' func='groups_perms_new'}">Добавить</a>
        </td>
      </tr>
    </tfoot>

    <tbody>
      {foreach item=groups_perms from=$groups_perms_list}
      {cycle name="grprls" values="even,odd" assign="class" print=false}
        <tr>
          <td class="{$class}" align="left">{$groups_perms.gid}</td>
          <td class="{$class}" align="left">{$groups_perms.component}</td>
          <td class="{$class}" align="left">{$groups_perms.pattern}</td>
          <td class="{$class}" align="center">{$groups_perms.level}</td>
          <td class="{$class}" nowrap align="center">
            {if $groups_perms.weight_moveup_url}
              <a href="{$groups_perms.weight_moveup_url}"><i class="icon-arrow-up"></i></a>
            {/if}
            {if $groups_perms.weight_moveup_url && $groups_perms.weight_movedown_url}
              &nbsp;
            {/if}
            {if $groups_perms.weight_movedown_url}
              <a href="{$groups_perms.weight_movedown_url}"><i class="icon-arrow-down"></i></a>
            {/if}
          </td>
          <td class="{$class}" align="center">
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$groups_perms.id}
            <a href="{mod_url type='admin' modname='SYS_permissions' func='groups_perms_modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_permissions' func='groups_perms_confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
          </td>
        </tr>
      {/foreach}
    </tbody>
  </table>

<br>


  <table class="table outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="10%">
      <col width="10%">
      <col width="60%">
      <col width="10%">
      <col width="5%">
      <col width="5%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="6">Права пользователей</th>
      </tr>

      <tr align="middle">
        <th class="head">Пользователь</th>
        <th class="head">Компонент</th>
        <th class="head">Объект</th>
        <th class="head">Уровень прав</th>
        <th class="head">Посл.</th>
        <th class="head">Действия</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td width="100%" align="center" colspan="6" class="foot">
          <a class="btn" href="{mod_url type='admin' modname='SYS_permissions' func='users_perms_new'}">Добавить</a>
        </td>
      </tr>
    </tfoot>


    <tbody>
      {foreach item=users_perms from=$users_perms_list}
      {cycle name="usrprls" values="even,odd" assign="class" print=false}
        <tr align="middle">
          <td class="{$class}" align="left">{$users_perms.uid}</td>
          <td class="{$class}" align="left">{$users_perms.component}</td>
          <td class="{$class}" align="left">{$users_perms.pattern}</td>
          <td class="{$class}">{$users_perms.level}</td>
          <td class="{$class}" nowrap>
            {if $users_perms.weight_moveup_url}
              <a href="{$users_perms.weight_moveup_url}"><i class="icon-arrow-up"></i></a>
            {/if}
            {if $users_perms.weight_moveup_url && $users_perms.weight_movedown_url}
              &nbsp;
            {/if}
            {if $users_perms.weight_movedown_url}
              <a href="{$users_perms.weight_movedown_url}"><i class="icon-arrow-down"></i></a>
            {/if}
          </td>
          <td class="{$class}">
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$users_perms.id}
            <a href="{mod_url type='admin' modname='SYS_permissions' func='users_perms_modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_permissions' func='users_perms_confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
          </td>
        </tr>
      {/foreach}
    </tbody>

  </table>

<br>

  <table class="table outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="10%">
      <col width="10%">
      <col width="60%">
      <col width="10%">
      <col width="5%">
      <col width="5%">
    </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="6">Права владельцев</th>
      </tr>

      <tr align="middle">
        <th class="head">Группа</th>
        <th class="head">Компонент</th>
        <th class="head">Объект</th>
        <th class="head">Уровень прав</th>
        <th class="head">Посл.</th>
        <th class="head">Действия</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td width="100%" align="center" colspan="6" class="foot"><a class="btn" href="{mod_url type='admin' modname='SYS_permissions' func='owners_perms_new'}">Добавить</a></td>
      </tr>
    </tfoot>

    <tbody>
      {foreach item=owners_perms from=$owners_perms_list}
      {cycle name="owprls" values="even,odd" assign="class" print=false}
        <tr align="middle">
          <td class="{$class}" align="left">{$owners_perms.gid}</td>
          <td class="{$class}" align="left">{$owners_perms.component}</td>
          <td class="{$class}" align="left">{$owners_perms.pattern}</td>
          <td class="{$class}">{$owners_perms.level}</td>
          <td class="{$class}" nowrap>
            {if $owners_perms.weight_moveup_url}
              <a href="{$owners_perms.weight_moveup_url}"><i class="icon-arrow-up"></i></a>
            {/if}
            {if $owners_perms.weight_moveup_url && $owners_perms.weight_movedown_url}
              &nbsp;
            {/if}
            {if $owners_perms.weight_movedown_url}
              <a href="{$owners_perms.weight_movedown_url}"><i class="icon-arrow-down"></i></a>
            {/if}
          </td>
          <td class="{$class}">
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$owners_perms.id}
            <a href="{mod_url type='admin' modname='SYS_permissions' func='owners_perms_modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_permissions' func='owners_perms_confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
          </td>
        </tr>
      {/foreach}
    </tbody>

  </table>
{/strip}