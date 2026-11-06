{debug}
{strip}

  <table class="table" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="20%">
      <col width="75%">
      <col width="5%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="3">Список групп</th>
      </tr>
      <tr align="middle">
        <td class="head">Группа</td>
		<td class="head">Год</td>
        <td class="head">Описание</td>
        <td class="head">Действия</td>
      </tr>
    </thead>

    {* Список груп в системе *}
    <tbody>
      {foreach item=group from=$groups_list}
      {cycle name="grplist" values="even,odd" assign="class" print=false}
        <tr>
          <td class="{$class}">
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='user_gid' value=$group.id}
            <a href="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars}">{$group.group_displayname|escape}</a>
          <td class="{$class}">{$group.group_year}</td>
		  <td class="{$class}">{$group.group_description|escape}</td>
          <td class="{$class}" align="center">
            {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$group.id}
            <a href="{mod_url type='admin' modname='SYS_groups' func='modify' vars=$url_vars}" title="{#edit#}"><i class="icon-edit"></i></a>&nbsp;
            <a href="{mod_url type='admin' modname='SYS_groups' func='confirm_delete' vars=$url_vars}" title="{#delete#}"><i class="icon-remove"></i></a>
          </td>
        </tr>
      {/foreach}
    </tbody>
  </table>

  <br>

  <form action="{mod_url type='admin' modname='SYS_groups' func='create'}" method="post">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="20%">
        <col width="80%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Создание новой группы</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <td class="foot" colspan="2" align="center">
            <input type="submit" value="Создать" name="group_submit" class="btn btn-primary">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr vAlign="top" align="left">
          <td class="head">Название группы</td>
          <td class="even">
            <input  type="text" id="group_displayname" maxLength="60" size="70" name="group_displayname">
          </td>
        </tr>
		
		<tr vAlign="top" align="left">
          <td class="head">Год начала обучения</td>
          <td class="even">
            <input  type="text" id="group_year" maxLength="60" size="70" name="group_year">
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Описание</td>
          <td class="even"><textarea name="group_description" rows="5" cols="70"></textarea></td>
        </tr>
      </tbody>

    </table>

  </form>

{/strip}