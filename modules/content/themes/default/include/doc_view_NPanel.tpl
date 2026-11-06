  <div align="center"> 
      {if $doc_part_current>1}{* Стрелочка *}
        {array name='url_vars'}
        {math equation="doc_part_current-1" doc_part_current=$doc_part_current assign="part_prev"}
        {array_append name='url_vars' key='part' value=$part_prev}
        {array_append name='url_vars' key='id' value=$id}
        <a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">&lt;</a>&nbsp;&nbsp;
      {/if}

      {section name=navigation loop=$doc_part_total}
        {if $smarty.section.navigation.iteration!=$doc_part_current}{* Вывод номера части *}
          {array name='url_vars'}
          {array_append name='url_vars' key='part' value=$smarty.section.navigation.iteration}
          {array_append name='url_vars' key='id' value=$id}
          <a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">{$smarty.section.navigation.iteration}</a>&nbsp;
        {else}
          <b>{$smarty.section.navigation.iteration}</b>&nbsp;
        {/if}

        {if $smarty.section.navigation.iteration!=$doc_part_total}{* Разделитель *}
          &nbsp;&nbsp;
        {/if}

      {/section}

      {if $doc_part_current!=$doc_part_total}{* Стрелочка *}
        {array name='url_vars'}
        {math equation="doc_part_current+1" doc_part_current=$doc_part_current assign="part_next"}
        {array_append name='url_vars' key='part' value=$part_next}
        {array_append name='url_vars' key='id' value=$id}
        &nbsp;&nbsp;<a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">></a>
      {/if}

    </div>