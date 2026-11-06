{strip}
<form action="{mod_url modname='content' type='admin' func='doc_delete'}" method="post">
	<input name="id" type="hidden" value="{$id}"/>
	<input name="fid" type="hidden" value="{$fid}"/>
 
	<div class="alert alert_danger">
		{#deletedoc#}&nbsp;"{$displayname|escape}"&nbsp;?
	</div>
    <button class="btn" type="submit" name="submit">{#delete#}</button>&nbsp;
	<button class="btn" type="button" name="cancel" onClick="window.location.href='{$http_referer}';">Отмена</button>
</form>
{/strip}