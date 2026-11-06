{strip}
{if $curuser_sec_level>=50}
<div>
	{include file='modules/content/themes/default/include/folder_view_adminBtn.tpl'}
	{assign var = 'folder_view_adminBtn_inserted' value = true}
</div>
{/if}

{* Содержание *}
{if $content}
{$content}


{/if}



{* Список разделов *}
{if $sub_folders_list}
<div>
{foreach item=folder from=$sub_folders_list name=folders}
 
<div>

{* Формируем масив переменные для урла *}
{array name='url_vars'}
{array_append name='url_vars' key='id' value=$folder.id}

{* Если раздел не активен ставим хрестик *}
{if $curuser_sec_level>=70}
{if $folder.active!=true}
<img src="/files/shared/images/system/notactive.gif" width="16" height="16" alt="Не активирован" border=0 style="padding-right:10px;">
{/if}
{/if}

<h3 style="margin-bottom:5px; padding-bottom:0;">
{if $folder.name}<a href="/{$folder.name}">{else}<a href="/{$folder.id}">{/if}
{$folder.displayname|escape}</a> ({$folder.subdoc_counter})</h3>
{if $curuser_sec_level>=70}
<span>
{* добавляем ссылки для передвижки весов*}
{if $folder.weight_moveup_url}
<a href="{$folder.weight_moveup_url}"><img border="0" src="/files/shared/images/system/up.gif"></a>
{/if}

{if $folder.weight_movedown_url}
<a href="{$folder.weight_movedown_url}"><img border="0" src="/files/shared/images/system/down.gif"></a>
{/if}

{* добавляем ссылки для редактирования и удаления*}
<a href="{mod_url type='admin' modname='content' func='folder_modify' vars=$url_vars}">
<img src="/files/shared/images/system/edit.gif" width="16" height="16" alt="{#editfolder#}" border=0>
</a>

<a href="{mod_url type='admin' modname='content' func='folder_copy_dg' vars=$url_vars}">
<img src="/files/shared/images/system/copy.gif" width="16" height="16" alt="{#copyfolder#}" border=0>
</a>

<a href="{mod_url type='admin' modname='content' func='folder_confirm_delete' vars=$url_vars}">
<img src="/files/shared/images/system/del.gif" width="16" height="16" alt="{#deletefolder#}" border=0>
</a>
</span>
<br>
{/if}

{if $folder.description}            
{$folder.description|strip_tags|nl2br}
{/if}
<br><br>
</div>

{/foreach}
</div>
{/if}


{* Список документов *}
{if $sub_docs_list}



{* Инициализируем счетчик доукментов *}
{math equation="doc_perpage * (page_current-1)" doc_perpage=$doc_perpage page_current=$page_current assign='start_doc_num'}
{counter start=$start_doc_num print=false}

<div>
{foreach item=doc from=$sub_docs_list name=docs}
<div >

{* Формируем масив переменные для урла *}
{array name='url_vars'}
{array_append name='url_vars' key='id' value=$doc.id}
{array_append name='url_vars' key='fid' value=$id}

{if $curuser_sec_level>=70}
{if $doc.active!=true}
<img src="/files/shared/images/system/notactive.gif" width="16" height="16" alt="Не активирован" border=0 style="padding-right:10px;">
{/if}
{/if}
<h3  style="padding-bottom:5px;margin-bottom:0;">
<a href="{mod_url modname='content' type='user' func='url_counter' vars=$url_vars}">
{$doc.displayname|escape}</a>


{if $curuser_sec_level>=70}
{* добовляем ссылки для передвижки весов*}
{if $doc.weight_moveup_url}
<a href="{$doc.weight_moveup_url}"><img border="0" src="/files/shared/images/system/up.gif"></a>
{/if}

{if $doc.weight_movedown_url}
<a href="{$doc.weight_movedown_url}"><img border="0" src="/files/shared/images/system/down.gif"></a>
{/if}

{* добавляем ссылки для редактирования и удаления*}

<a href="{mod_url type='admin' modname='content' func='doc_modify' vars=$url_vars}" title="{#editdoc#}">
<img src="/files/shared/images/system/edit.gif" width="16" height="16" alt="{#editdoc#}" border=0>
</a>
<a href="{mod_url type='admin' modname='content' func='doc_links_dg' vars=$url_vars}" title="{#managelink#}">
<img src="/files/shared/images/system/links.png" width="16" height="16" alt="{#managelink#}" border=0>
</a>
<a href="{mod_url type='admin' modname='content' func='doc_copy_dg' vars=$url_vars}" title="{#copydoc#}">
<img src="/files/shared/images/system/copy.gif" width="16" height="16" alt="{#copydoc#}" border=0>
</a>

<a href="{mod_url type='admin' modname='content' func='doc_confirm_delete' vars=$url_vars}" title="{#deletedoc#}">
<img src="/files/shared/images/system/del.gif" width="16" height="16" alt="{#deletedoc#}" border=0>
</a>

{/if}
</h3>
(<a href="{mod_url modname='content' type='user' func='url_counter' vars=$url_vars}">{$doc.url}</a>)

{if $doc.logo}
<div><IMG src="{$doc.logo}" border=0 alt="{$doc.displayname|escape}"></div>
{/if}

{if $doc.description}
<br>
{$doc.description|strip_tags|nl2br}
{/if}
<br><br>
</div>
{/foreach}
</div>
{/if}



<div style="margin:50px 0 0 30px;">
{* Навигационная панель *}
  {if $page_total>1}
    {include file='modules/content/themes/default/folder_view_NPanel_short.tpl'}
  {/if}
{* END Навигационная панель *}


{* URL добавление документа/раздела *}
  {if $curuser_sec_level>=40}
  <div>
  
  {* Формируем масив переменные для урла *}
  {array name='url_vars'}
  {array_append name='url_vars' key='fid' value=$id}
  <a href="{mod_url type='admin' modname='content' func='doc_new' vars=$url_vars}">{#adddoc#}</a>
  &nbsp;|&nbsp;
  {array name='url_vars'}
  {array_append name='url_vars' key='id' value=$id}
  <a href="{mod_url type='admin' modname='content' func='folder_new' vars=$url_vars}">{#addfolder#}</a>
  </div>
  {/if}
</div>

{/strip}
