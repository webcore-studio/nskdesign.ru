{*debug*}
{strip}
  <table class="table">


    <thead>
      <tr align="middle">
        <th colspan="3">Группы пользователей</th>
      </tr>
      <tr align="middle">
        <th>Группа</th>
		<th>Год</th>
        <th>Описание</th>
      </tr>
    <thead>


    <tbody>
      {foreach item=group from = $groups_list}
	  {cycle name="grplist" values="even,odd" assign="class" print=false}
	  {* Формируем масив переменные для урла *}
            {array name='url_vars'}
            {array_append name='url_vars' key='user_gid' value=$group.id}
        <tr>
          <td class="{$class}"><a href="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars}">{$group.group_displayname}</a></td>
		  <td class="{$class}">{$group.group_year}</td>
          <td class="{$class}">{$group.group_description}</td>
        </tr>
      {/foreach}
    </tbody>

  </table>
  
  <a class="btn btn-primary" href="{mod_url type='admin' modname='SYS_users' func='new'}">Добавить пользователя</a>&nbsp;
    <a class="btn" href="{mod_url type='admin' modname='SYS_users' func='modifyconfig'}">Конфигурация</a>&nbsp;
{/strip}