{strip}
{foreach item=doc from=$docs_list}
	<div class="item">
		{if $doc_orderby=='pub_datetime'}
			<div class="date">{$doc.pub_datetime|date_format:"%e %B"} 
			{if (($doc.pub_datetime|date_format:"%Y")!=($smarty.now|date_format:"%Y"))}&nbsp;{$doc.pub_datetime|date_format:"%Y"}{/if}</div>
		{/if}
			
		<a href="/{$doc.folder_name}/{$doc.name}.html">{$doc.displayname|escape}</a>
			
        {if $doc.thumb_url}
            <div class="pull-left page-logo">
				<a href="/{$doc.folder_name}/{$doc.name}.html"><img  src="{$doc.thumb_url}" alt=""></a>
			</div>
        {/if}
			
		<div class="doc_text">
			{if $doc.description}
				{$doc.description|nl2br|strip_tags|truncate:200}
			{else}
				{$doc.content|nl2br|strip_tags|truncate:200}
			{/if}
		</div>
		<div class="clearfix"></div>
	</div>
{/foreach}
{/strip}