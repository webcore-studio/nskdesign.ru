{strip}
  <table class="table outer" cellSpacing="1" cellPadding="4" width="100%">
  
    <thead>
      <tr>
        <th colSpan="5">Пользователи <small>(всего: {$users_count})</small></th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td width="100%" align="center" colspan="4" class="foot">
          <a class="btn btn-primary" href="{mod_url type='admin' modname='SYS_users' func='new'}">{#add#}</a>
        </td>
      </tr>
    </tfoot>
			{array name='url_vars_s'}
            {array_append name='url_vars_s' key='user_gid' value=$user_gid}
    <tbody>
<tr>
<td><b><a href="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars_s}&orderby=user_name{if $order_asc!=1 && $orderby=='user_name'}&order_asc=1{/if}">{#login#}</a></b></td>
<td><b><a href="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars_s}&orderby=user_displayname{if $order_asc!=1 && $orderby=='user_displayname'}&order_asc=1{/if}">{#name#}</a></b></td>
<td><b>
Оценки
</b></td>


<td><b>{#action#}</b></td>
</tr>
      {foreach item=user from=$users_list}
      {cycle name="grls" values="even,odd" assign="class" print=false}
        <tr>
          <td class="{$class}" >{$user.user_name|escape}</td>
          <td class="{$class}">{$user.user_displayname}</td>
		  
		  {array name='url_vars'}
          {array_append name='url_vars' key='id' value=$user.id}
		  
  	 <td class="{$class}">
	 <a href="{mod_url type='admin' modname='edu' func='score_add' vars=$url_vars}" title="Добавить оценки">Добавить</a>&nbsp;
	 <a href="{mod_url type='user' modname='edu' func='score_list' vars=$url_vars}" title="Просмотр оценок">Просмотр</a>&nbsp;
	 </td>
	 
	 
          <td class="{$class}" align="center">
            {* Формируем масив переменные для урла *}
            
{if $user.user_actkey && $user.user_active==0}
{array name='url_vars1'}
{array_append name='url_vars1' key='user_id' value=$user.id}
{array_append name='url_vars1' key='user_actkey' value=$user.user_actkey}
<a href="{mod_url type='user' modname='SYS_users' func='reg_act' vars=$url_vars1}" title="{#to_active#}">
<img border="0" src="/files/shared/images/system/activate.gif" alt="{#to_active#}" title="{#to_active#}"></a>&nbsp;

{*
{array name='url_vars2'}
{array_append name='url_vars2' key='user_id' value=$user.id}
<a href="{mod_url type='user' modname='SYS_users' type='admin' func='act_resend' vars=$url_vars2}" title="Отправить письмо-напоминание"><img border="0" src="/files/shared/images/system/mail.gif" alt="Отправить письмо-напоминание" title="Отправить письмо-напоминание"></a>&nbsp;
*}
{/if}



              <a href="{mod_url type='admin' modname='SYS_users' func='modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
              <a href="{mod_url type='admin' modname='SYS_users' func='confirm_delete' vars=$url_vars}"  title="Удалить"><i class="icon-remove"></i></a>
          </td>


        </tr>
      {/foreach}
    </tbody>

  </table>
  {if $page_total>1}
  <br>
  {include file='modules/SYS_users/themes/default/include/admin_view_NPanel_short.tpl'}
  {/if}
  <br>
  
  {if $users_count_na>0}
	{array name='url_vars'}
	{array_append name='url_vars' key='user_gid' value=$user_gid}
	<a style="color:#409F14" href="{mod_url type='admin' modname='SYS_users' func='act_resend' vars=$url_vars}">Разослать письма неактивированным пользователям группы <b>{$group_displayname}</b> ({$users_count_na})</a>
  {/if}
{/strip}
