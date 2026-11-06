{strip}

{if $curuser_sec_level>=40}
<div>
	{include file='modules/content/themes/default/include/folder_view_adminBtn.tpl'}
	{assign var = 'folder_view_adminBtn_inserted' value = true}
</div>
{/if}

{if $content}
	<div class="clearfix item">{$content}</div>
{/if}


{if $sub_docs_list}
	<div id="doc_list">{include file='modules/content/themes/default/news/doc_list.tpl'}</div>
	{if $ajax_paging}
		<div id="loading_more_docs" style="color:#fff;">2</div>
		<div id="loading" style="display:none;"><img src="/files/shared/images/system/loading.gif"></div>
	{/if}
{/if}



{if $sub_folders_list}
<div>
{foreach item=folder from=$sub_folders_list name=folders}
 
<div class="item">

<h4>

{* Если раздел не активен ставим хрестик *}
{if $curuser_sec_level >= 50 && $folder.active != true}<i class="icon-eye-close" title="{#no_active#}"></i>&nbsp;{/if}
<a href="{mod_short_url modname='content' type='user' name=$folder.name id=$folder.id}">{$folder.displayname|escape}</a></h4>

{if $curuser_sec_level>=50}
<div>
{include file='modules/content/themes/default/include/folder_modify_adminBtn.tpl'}
</div>
{/if}

{if $folder.description}            
{$folder.description|strip_tags|nl2br}
{/if}
</div>

{/foreach}
</div>
{/if}

{if $page_total>1 && !$ajax_paging}
    {include file='modules/content/themes/default/include/folder_view_NPanel_short.tpl'}
{/if}


{if $curuser_sec_level>=40}
	{include file='modules/content/themes/default/include/folder_view_adminBtn_bot.tpl'}
{/if}


{if $ajax_paging}
{literal}
	<script type="text/javascript">
		function show_more() 
		{
	
			var page = $("#loading_more_docs").html();
			if (!page) {
				return false;
			}
			var page_total = {/literal}{$page_total}{literal};
			if (page*1 > page_total*1) {
				return false;
			}
			$("#loading_more_docs").html("");
			$("#loading").show();
			var fid = {/literal}{$id}{literal};
			data_arr = {
				'fid' : fid,
				'page' : page
			};

			$.ajax({
				type: "POST",
				url: "/index.php?module=content&type=ajax&func=doc_list",
				data:  data_arr,
				dataType : "html", 
				success: function (html) {
					$("#doc_list").append(html);
					$("#loading_more_docs").html(page*1 + 1);
					$("#loading").hide();
					
				}
			});
			
		}
		$(window).scroll(function(){ 
			var hh = getClientHeight();
			var hs = getClientSTop();
			var elem = document.getElementById("loading_more_docs");
			var fl = getOffset(elem);
			if (hs*1 + hh*1 > fl["top"]*1) {
				show_more();
				return false;
			}
		})
	</script>
	{/literal}
{/if}
{/strip}
