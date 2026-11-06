{strip}
<br>
{* Навигационная панель *}
<center>Страницы:&nbsp;

  {if $page_current>1}{* Стрелочка *}
    {math equation="page_current-1" page_current=$page_current assign="page_prev"}
    <a href="/{$name}/{$page_prev}">&lt;</a>&nbsp;&nbsp;
  {/if}

  {section name=navigation loop=$page_total}
  {array name='url_vars'}
  {array_append name='url_vars' key='user_gid' value=$user_gid}
  {array_append name='url_vars' key='orderby' value=$orderby}
  {array_append name='url_vars' key='order_asc' value=$order_asc}
  {array_append name='url_vars' key='page' value=$smarty.section.navigation.iteration}
  
    {if $smarty.section.navigation.iteration!=$page_current}{* Вывод номера документа *}
      <a href="{mod_url type='admin' modname='SYS_users' func='view' vars=$url_vars}">{$smarty.section.navigation.iteration}</a>
    {else}
      <b>{$smarty.section.navigation.iteration}</b>
    {/if}

    {if $smarty.section.navigation.iteration!=$page_total}{* Разделитель *}
      &nbsp;&nbsp;
    {/if}

  {/section}

  {if $page_current!=$page_total}{* Стрелочка *}
 
    {math equation="page_current+1" page_current=$page_current assign="page_next"}

    &nbsp;&nbsp;<a href="/{$name}/{$page_next}">></a>
  {/if}

</center>

{* END Навигационная панель *}

{/strip}