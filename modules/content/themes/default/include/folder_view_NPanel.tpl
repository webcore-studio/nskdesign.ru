{strip}
<br>
{* Навигационная панель *}
<center>Страницы:&nbsp;

  {if $page_current>1}{* Стрелочка *}
    {array name='url_vars'}
    {math equation="page_current-1" page_current=$page_current assign="page_prev"}
    {array_append name='url_vars' key='page' value=$page_prev}
    {array_append name='url_vars' key='id' value=$id}
    {array_append name='url_vars' key='subdoc_orderby' value=$subdoc_orderby}
    {array_append name='url_vars' key='subdoc_order_asc' value=$subdoc_order_asc}

    <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">&lt;</a>&nbsp;::&nbsp;
  {/if}

  {section name=navigation loop=$page_total}

    {if $smarty.section.navigation.iteration!=$page_current}{* Вывод номера документа *}
      {array name='url_vars'}
      {array_append name='url_vars' key='page' value=$smarty.section.navigation.iteration}
      {array_append name='url_vars' key='id' value=$id}
      {array_append name='url_vars' key='subdoc_orderby' value=$subdoc_orderby}
      {array_append name='url_vars' key='subdoc_order_asc' value=$subdoc_order_asc}

      <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{$smarty.section.navigation.iteration}</a>
    {else}
      <b>{$smarty.section.navigation.iteration}</b>
    {/if}

    {if $smarty.section.navigation.iteration!=$page_total}{* Разделитель *}
      &nbsp;::&nbsp;
    {/if}

  {/section}

  {if $page_current!=$page_total}{* Стрелочка *}
    {array name='url_vars'}
    {math equation="page_current+1" page_current=$page_current assign="page_next"}
    {array_append name='url_vars' key='page' value=$page_next}
    {array_append name='url_vars' key='id' value=$id}
    {array_append name='url_vars' key='subdoc_orderby' value=$subdoc_orderby}
    {array_append name='url_vars' key='subdoc_order_asc' value=$subdoc_order_asc}

    &nbsp;::&nbsp;<a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">></a>
  {/if}

</center>

{* END Навигационная панель *}

{/strip}