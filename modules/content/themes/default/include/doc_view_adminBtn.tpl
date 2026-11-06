{strip}
<div id="admin_panel">

	&nbsp;
	{array name='url_vars'}
	{array_append name='url_vars' key='id' value=$id}
	{array_append name='url_vars' key='fid' value=$fid}

    {if $curuser_sec_level>=50}
		<a href="{mod_url type='admin' modname='content' func='doc_modify' vars=$url_vars}" title="{#editdoc#}"><i class="icon-edit"></i></a>&nbsp;
		<a href="{mod_url type='admin' modname='content' func='doc_links_dg' vars=$url_vars}" title="{#managelink#}"><i class="icon-tasks"></i></a>&nbsp;
	{/if}

    {if $curuser_sec_level>=60}
		<a href="{mod_url type='admin' modname='content' func='doc_confirm_delete' vars=$url_vars}" title="{#deletedoc#}"><i class="icon-remove"></i></a>
    {/if}
</div>


{/strip}