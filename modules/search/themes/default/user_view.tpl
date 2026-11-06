{strip}
<div class="item">
	{include file='modules/search/themes/default/include/search_form.tpl'}
</div>

<table class="outer table" cellSpacing="1" cellPadding="4" width="100%">

	<thead>
		<tr><th colspan="2">Результаты поиска</th></tr>
	</thead>

	<tbody>
		{math equation="doc_perpage * (page_current-1)" doc_perpage=$result_perpage page_current=$page_current assign='start_doc_num'}
		{counter start=$start_doc_num print=false}

		{foreach item=result from=$search_result}
   
			<tr>
				<td class="span1">{counter}</td>
				<td>
					{if $result.parents_list}
						{foreach from = $result.parents_list item = parent key=k}
							<div class="item10" style="overflow:hidden">
								{if $result.thumb_url}
									<a href="{$result.url[$k]}" target=_blank ><img src="{$result.thumb_url}" class="pull-left" style="margin-right:10px"></a>
								{/if}
								<a href="{$result.url[$k]}" target=_blank>{$result.displayname}</a>
								<div class="grey"><small>в разделе {browsein date=$parent href_last=true}</small></div>
							</div>
							
						{/foreach}
					{else}
						<a href="{$result.url.0}" target=_blank>{$result.displayname}</a>
					{/if}
				</td>
			</tr>

	{foreachelse}
		<tr>
			<td colspan=2 align="center">
				Поиск не дал результатов.
			</td>
      </tr>
 
	{/foreach}
  </tbody>

  </table>

{if $page_total>1}
	{include file='modules/search/themes/default/include/search_view_NPanel_short.tpl'}
{/if}

{/strip}