{* добaвляем ссылки для редактирования, копирования и удаления*}
{if $curuser_sec_level>=50}
<div>
	{include file='modules/content/themes/default/include/doc_view_adminBtn.tpl'}
	{assign var = 'doc_view_adminBtn_inserted' value = true}
</div>
{/if}

{if $thumb_url}
	<div class="pull-left page-logo">
		<img src="{$thumb_url}" title="{$displayname|escape}">
	</div>
{/if}

<div>
{if $content}
{if ($subdocs_list)  or ($images_list)}
{if $subdocs_list}

        {capture name=subpages_tree}
          {foreach item=subdoc from=$subdocs_list}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$subdoc.id}
            {array_append name='url_vars' key='fid' value=$fid}
            <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">
              {$subdoc.displayname|escape|tree:$subdoc.level:'-&nbsp;&nbsp;':''}
            </a>
            <br>
          {/foreach}
        {/capture}

        {capture name=subpages_list}
          {foreach item=subdoc from=$subdocs_list}
            {if $subdoc.parent_id==$id} {* выводим только дочерниe разделы *}
              {array name='url_vars'}
              {array_append name='url_vars' key='id' value=$subdoc.id}
              {array_append name='url_vars' key='fid' value=$fid}
              <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">
                {$subdoc.displayname|escape}
              </a>
              <br>
            {/if}
          {/foreach}
        {/capture}
{/if}

{if ($images_list)}
        {capture name=foto_list}
          {foreach item=image from=$images_list}
	<div style="margin:10px;display:inline-block;">
	<a href="{$image.big.thumb_url}" rel="lightbox[]" title="{$image.image_displayname} {$image.image_description}"><img src="{$image.thumb_url}"  title="{$image.image_displayname} {$image.image_description}" alt="{$image.image_displayname}">
	</a>
	</div>
          {/foreach}
        {/capture}
{/if}

      {* Вставка списка и дерева страниц в документ *}
        {$content|replace:$subpages_tree_tag:$smarty.capture.subpages_tree|replace:$subpages_list_tag:$smarty.capture.subpages_list|replace:$images_list_tag:$smarty.capture.foto_list}
{else}
{$content}
{/if}
{else}
{$description|escape|nl2br}
{/if}

{if !$images_flag && $smarty.capture.foto_list}
		<div class="item">{$smarty.capture.foto_list}</div>
	{/if}
</div>

<div>
      {#pub_datetime#}: {$pub_datetime|date_format:"%d.%m.%Y"}&nbsp;&nbsp;
{if $author}
<br>
          {if $author_email}
          {#author#}: {mailto address=$author_email text=$author}&nbsp;&nbsp;
          {elseif $url}
          {#author#}: <a href="{$url}">{$author}</a>
          {else}
          {#author#}: {$author}
          {/if}

        {else}
          {if $author_email}
          {#author#}: {mailto address=$author_email}
          {/if}
        {/if}
</div>


<div class="clearfix"></div>

{* Навигационная панель *}
{if $doc_part_total>1}
  
      {include file='modules/content/themes/default/doc_view_NPanel_short.tpl'}    
{/if}
{* END Навигационная панель *}

