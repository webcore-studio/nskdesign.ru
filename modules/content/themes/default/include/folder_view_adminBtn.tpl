{strip}

<div id="admin_panel">
	{array name='url_vars'}
	{array_append name='url_vars' key='id' value=$id}
	
	&nbsp;
    {if $curuser_sec_level>=40}
		<a href="{mod_url type='admin' modname='content' func='folder_new' vars=$url_vars}" title="{#addfolder#}"><i class="icon-folder-close"></i></a>&nbsp;

		{array name='url_vars1'}
		{array_append name='url_vars1' key='fid' value=$id}
		<a href="{mod_url type='admin' modname='content' func='doc_new' vars=$url_vars1}" title="{#adddoc#}" ><i class="icon-file"></i></a>&nbsp;
    {/if}

    {if $curuser_sec_level>=50}
		<a href="{mod_url type='admin' modname='content' func='folder_modify' vars=$url_vars}"  title="{#editfolder#}"><i class="icon-edit"></i></a>&nbsp;
    {/if}

    {if $curuser_sec_level>=60}
		<a href="{mod_url type='admin' modname='content' func='folder_confirm_delete' vars=$url_vars}" title="{#deletefolder#}"><i class="icon-remove"></i></a>&nbsp;
    {/if}

</div>
 

{/strip}