  <div style="text-align:center"> 
      {if $doc_part_current>1}{* Стрелочка *}
        {math equation="doc_part_current-1" doc_part_current=$doc_part_current assign="part_prev"}
        <a href="/{$fname}/{$dname}.html?p={$part_prev}">&lt;</a>&nbsp;&nbsp;
      {/if}

      {section name=navigation loop=$doc_part_total}

        {if $smarty.section.navigation.iteration!=$doc_part_current}{* Вывод номера части *}
          <a href="/{$fname}/{$dname}.html?p={$smarty.section.navigation.iteration}">{$smarty.section.navigation.iteration}</a>&nbsp;
        {else}
          <b>{$smarty.section.navigation.iteration}</b>&nbsp;
        {/if}

        {if $smarty.section.navigation.iteration!=$doc_part_total}{* Разделитель *}
          &nbsp;&nbsp;
        {/if}

      {/section}

      {if $doc_part_current!=$doc_part_total}{* Стрелочка *}
        {math equation="doc_part_current+1" doc_part_current=$doc_part_current assign="part_next"}
        &nbsp;&nbsp;<a href="/{$fname}/{$dname}.html?p={$part_next}">></a>
      {/if}

    </div>