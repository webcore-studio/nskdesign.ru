{strip}
	{math equation="doc_perpage * (page_current-1)" doc_perpage=$doc_perpage page_current=$page_current assign='start_doc_num'}
	{counter start=$start_doc_num print=false}
	<div>
		{foreach item=doc from=$sub_docs_list name=docs}
			<div class="item">
				{if $doc.thumb_url}
					<div class="pull-left" style="margin-right:20px;">
						<a href="{mod_short_url modname='content' type='user' second=$name name=$doc.name id=$doc.id postfix=true}"><img src="{$doc.thumb_url}" title="{$doc.displayname|escape}"></a>
					</div>
				{/if}
				{if $curuser_sec_level >= 50 && $doc.active != true}<i class="icon-eye-close" title="{#no_active#}"></i>&nbsp;{/if}
				<b><a href="{mod_short_url modname='content' type='user' second=$name name=$doc.name id=$doc.id postfix=true}">{$doc.displayname|escape}</a></b>
				{if $curuser_sec_level>=50}
					{include file='modules/content/themes/default/include/doc_modify_adminBtn.tpl'}
				{/if}

				<div>
					{if $doc.description}
						<div>{$doc.description}</div>
					{elseif $doc.content}
						<div>{$doc.content|strip_tags|truncate:300}</div>
					{/if}
					<small>{$doc.pub_datetime|date_format:"%d.%m.%Y"}</small>
				</div>
			</div>
		{/foreach}
	</div>
{/strip}
