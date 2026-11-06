{strip}
<table class="outer" cellSpacing="1" cellPadding="4">

<thead>
  <tr>
    <th>
    {if $curuser_sec_level>=50}
      <table cellSpacing="0" cellPadding="0" border="0">
         <tr>
          <th>
            {$displayname|escape}
          </th>
          <th width="2%" nowrap>
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$id}

            <a href="{mod_url type='admin' modname='content' func='subdoc_modify' vars=$url_vars}">
              <img src="/files/shared/images/system/edit.gif" width="16" height="16" alt="{#editpage#}" border=0>
            </a>&nbsp;


            {if $curuser_sec_level>=60}
              <a href="{mod_url type='admin' modname='content' func='subdoc_confirm_delete' vars=$url_vars}">
                <img src="/files/shared/images/system/del.gif" width="16" height="16" alt="{#deletepage#}" border=0>
              </a>
            {/if}
          </th>
        </tr>
      </table>
    {else}
      {$displayname|escape}
    {/if}
    </th>
  </tr>
</thead>






<tfoot>
  <tr>
    <td align="right" class="foot">
      .:&nbsp;
      {#pub_datetime#}: {$pub_datetime|date_format:"%d.%m.%Y %H:%M"}&nbsp;::&nbsp;

        {* выводим имя автора или email или url *}
        {if $author}

          {if $author_email}
          {#author#}: {mailto address=$author_email text=$author}&nbsp;::&nbsp;
          {elseif $url}
          {#author#}: <a href="{$url}">{$author}</a>&nbsp;::&nbsp;
          {else}
          {#author#}: {$author}&nbsp;::&nbsp;
          {/if}

        {else}
          {if $author_email}
          {#author#}: {mailto address=$author_email}&nbsp;::&nbsp;
          {/if}
        {/if}

      {if $doc_subdoc_counter>0}
        {#subdoccount#}: {$doc_subdoc_counter}&nbsp;::&nbsp;
      {/if}
      {#reads#}: {$counter}&nbsp;:.
    </td>
  </tr>
</tfoot>







<tbody>
  <tr>
    <td>

    {* если есть вложенные документы реплаисим *}
    {if $subdocs_list}

      {capture name=subpages_tree}
        {foreach item=subdoc from=$subdocs_list}
          {array name='url_vars'}
          {array_append name='url_vars' key='id' value=$subdoc.id}
          <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">
            {$subdoc.displayname|escape|tree:$subdoc.level:'-&nbsp;&nbsp;':''}
          </a>
          <br>
        {/foreach}
      {/capture}

      {capture name=subpages_list}
        {foreach item=subdoc from=$subdocs_list}
          {if $subdoc.parent_id==$id} {* выводим только дочернии разделы *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$subdoc.id}
            <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">
              {$subdoc.displayname|escape}
            </a>
            <br>
          {/if}
        {/foreach}
      {/capture}


    {* Вставка списка и дерева страниц в документ *}
    {$content|replace:$subpages_tree_tag:$smarty.capture.subpages_tree|replace:$subpages_list_tag:$smarty.capture.subpages_list}
    {else}
    {$content}
    {/if}

    </td>
  </tr>



  <tr>
    <td align="center">
    {* Навигационная панель *}
    {if $doc_part_total>1}
    <center><b>{#part#}&nbsp;{$doc_part_current}&nbsp;из&nbsp;{$doc_part_total}.</b> <br>

  {if $doc_part_current>1}{* Стрелочка *}
    {array name='url_vars'}
    {math equation="doc_part_current-1" doc_part_current=$doc_part_current assign="part_prev"}
    {array_append name='url_vars' key='part' value=$part_prev}
    {array_append name='url_vars' key='id' value=$id}

    <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">&lt;</a>&nbsp;::&nbsp;
  {/if}
  {section name=navigation loop=$doc_part_total}

    {if $smarty.section.navigation.iteration!=$doc_part_current}{* Вывод номера части *}
      {array name='url_vars'}
      {array_append name='url_vars' key='part' value=$smarty.section.navigation.iteration}
      {array_append name='url_vars' key='id' value=$id}

      <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">{$smarty.section.navigation.iteration}</a>&nbsp;
    {else}
      <b>{$smarty.section.navigation.iteration}</b>&nbsp;
    {/if}

    {if $smarty.section.navigation.iteration!=$doc_part_total}{* Разделитель *}
      ::&nbsp;
    {/if}

  {/section}

  {if $doc_part_current!=$doc_part_total}{* Стрелочка *}
    {array name='url_vars'}
    {math equation="doc_part_current+1" doc_part_current=$doc_part_current assign="part_next"}
    {array_append name='url_vars' key='part' value=$part_next}
    {array_append name='url_vars' key='id' value=$id}

    ::&nbsp;<a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">></a>
  {/if}

</center>
{/if}
{* END Навигационная панель *}
    </td>
  </tr>
</tbody>


</table>



{* типа еще одна панел - вперед назад вверх вниз *}
<table border="0" cellspacing="0" cellpadding="0">
<tbody>
  <tr>
    <td width="33%" rowspan="2" align="left">
      {if $prevdoc_info}
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$prevdoc_info.id}
        {array_append name='url_vars' key='fid' value=$fid}
          {if $prevdoc_info.parent_id}
            <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">Назад</a><br>
          {else}
            <a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">Назад</a><br>
          {/if}
        {$prevdoc_info.displayname|escape}
      {/if}
    </td>

    <td width="33%" align="center">
      {array name='url_vars'}
      {array_append name='url_vars' key='id' value=$rootdoc_info.id}
      {array_append name='url_vars' key='fid' value=$fid}
      <a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">В документ</a>
    </td>

    <td width="34%" rowspan="2" align="right">
      {if $nextdoc_info}
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$nextdoc_info.id}
        {array_append name='url_vars' key='fid' value=$fid}
        <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">Вперед</a><br>
        {$nextdoc_info.displayname|escape}
      {/if}
    </td>

  </tr>
  <tr>
    <td width="33%" align="center">
      {array name='url_vars'}
      {array_append name='url_vars' key='id' value=$updoc_info.id}
      {array_append name='url_vars' key='fid' value=$fid}
        {if $updoc_info.parent_id}
          <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">Вверх</a>
        {else}
          <a href="{mod_url type='user' modname='content' func='doc_view' vars=$url_vars}">Вверх</a>
        {/if}

    </td>
  </tr>
</tbody>
</table>
{/strip}