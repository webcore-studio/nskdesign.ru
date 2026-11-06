  {foreach item=item from=$items_list}
    {* Формируем масив переменные для урла *}
    {array name='url_vars'}
    {array_append name='url_vars' key='id' value=$item.id}    

    {* в зависимости от типа отображаем эллимент *}

    {if $item.item_type==1}            {* 1 = Разделитель *}

    {elseif $item.item_type==2}        {* 2 = Заголовок *}

      <b>{$item.displayname|escape|tree:$item.level:'&nbsp;<img src="/files/shared/images/system/right.gif" width="8"  border=0 alt="">':'':'&nbsp;&nbsp;'}</b>

    {elseif $item.item_type==3}        {* 3 = Url *}
      {if $item.selected}
        {* Текуший элемент *}
        <b>{$item.displayname|escape|tree:$item.level:'&nbsp;<img src="/files/shared/images/system/right.gif" width="8"  border=0 alt="">':'':'&nbsp;&nbsp;'}</b>
      {elseif ($block_position=='l' or $block_position=='l')}
        <a href="{$item.content}">{$item.displayname|escape|tree:$item.level:'&nbsp;<img src="/files/shared/images/system/right.gif" width="8"  border=0 alt="">':'':'&nbsp;&nbsp;'}</a>
      {/if}

    {elseif $item.item_type==4}        {* 4 = Html *}

      {$item.content}
  {/if}

  <br>

  {/foreach}