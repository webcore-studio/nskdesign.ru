{strip}
<br>
{* Навигационная панель *}
<center>Страницы:&nbsp;

  {if $page_current>1}{* Стрелочка *}
    {math equation="page_current-1" page_current=$page_current assign="page_prev"}
    <a href="/{$name}/{$page_prev}">&lt;</a>&nbsp;&nbsp;
  {/if}

  {section name=navigation loop=$page_total}

    {if $smarty.section.navigation.iteration!=$page_current}{* Вывод номера документа *}
      <a href="/{$name}/{$smarty.section.navigation.iteration}">{$smarty.section.navigation.iteration}</a>
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