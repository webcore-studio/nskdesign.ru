<div>
	{* Формируем масив переменные для урла *}
	{array name='url_vars'}
	{array_append name='url_vars' key='fid' value=$id}
	<a class="btn" href="{mod_url type='admin' modname='content' func='doc_new' vars=$url_vars}">{#adddoc#}</a>
	
	&nbsp;&nbsp;
  
	{array name='url_vars'}
	{array_append name='url_vars' key='id' value=$id}
	<a class="btn" href="{mod_url type='admin' modname='content' func='folder_new' vars=$url_vars}">{#addfolder#}</a>
</div>