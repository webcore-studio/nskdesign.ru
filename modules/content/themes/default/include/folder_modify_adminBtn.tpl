{strip}

{array name='url_vars'}
{array_append name='url_vars' key='id' value=$folder.id}
&nbsp;
{* добавляем ссылки для передвижки весов*}
{if $folder.weight_moveup_url}
<a href="{$folder.weight_moveup_url}"><i class="icon-arrow-up"></i></a>&nbsp;
{/if}

{if $folder.weight_movedown_url}
<a href="{$folder.weight_movedown_url}"><i class="icon-arrow-down"></i></a>&nbsp;
{/if}

{* добавляем ссылки для редактирования и удаления*}
<a href="{mod_url type='admin' modname='content' func='folder_modify' vars=$url_vars}" title="{#editfolder#}"><i class="icon-edit"></i></a>&nbsp;
{if $curuser_sec_level>=60}
<a href="{mod_url type='admin' modname='content' func='folder_confirm_delete' vars=$url_vars}" title="{#deletefolder#}"><i class="icon-remove"></i></a>&nbsp;
{/if}
{/strip}