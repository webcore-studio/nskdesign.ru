{strip}
&nbsp;
{array name='url_vars'}
{array_append name='url_vars' key='id' value=$doc.id}
{if $folder.subdocs}
	{array_append name='url_vars' key='fid' value=$folder.id}
{else}
	{array_append name='url_vars' key='fid' value=$id}
{/if}

{if $doc.weight_moveup_url}
<a href="{$doc.weight_moveup_url}"><i class="icon-arrow-up"></i></a>&nbsp;
{/if}

{if $doc.weight_movedown_url}
<a href="{$doc.weight_movedown_url}"><i class="icon-arrow-down"></i></a>&nbsp;
{/if}

{* добавляем ссылки для редактирования и удаления*}

<a href="{mod_url type='admin' modname='content' func='doc_modify' vars=$url_vars}" title="{#editdoc#}"><i class="icon-edit"></i></a>&nbsp;
<a href="{mod_url type='admin' modname='content' func='doc_links_dg' vars=$url_vars}" title="{#managelink#}"><i class="icon-tasks"></i></a>&nbsp;

{if $curuser_sec_level>=60}
<a href="{mod_url type='admin' modname='content' func='doc_confirm_delete' vars=$url_vars}" title="{#deletedoc#}"><i class="icon-remove"></i></a>&nbsp;
{/if}

{/strip}