{strip}
<div id="mod_comment_new"></div>
<div id="mod_comment_list">

    {foreach item=comment from=$comment_list}
        <div id="comment_id_{$comment.id}" style="padding-bottom:10px;">
			{if $curuser_sec_level>=50}
                &nbsp;&nbsp;
                {array name='url_vars'}
                {array_append name='url_vars' key='id' value=$comment.id}
                <a href="" onclick="comment_modify({$comment.id});return false;"><i class="icon-edit"></i></a>&nbsp;
            {/if}
			{if $curuser_sec_level>=60}
				<a href="" onclick="comment_delete({$comment.id});return false;"><i class="icon-remove"></i></a>
            {/if}
			<br>
			{if $comment.user_id}
				<a href="/user/{$comment.user_id_name}" title="Профиль пользователя">
					<b>{if $comment.user_id_displayname}{$comment.user_id_displayname}{else}{$comment.user_id_name}{/if}</b>
				</a>
			{else}
				<b>{if $comment.user_displayname}{$comment.user_displayname|truncate:50}{else}Гость{/if}</b>
			{/if}
			{if $comment.comm_module=='content'}
				<div style="color:#237DD4"><small>К новости <a href="/{$comment.folder_info.name}/{$comment.doc_info.name}.html" style="color:#237DD4">{$comment.doc_info.displayname}</a></small></div>
			{elseif $comment.comm_module=='polls'}
				<div style="color:#237DD4"><small>К опросу <a href="/polls/result/{$comment.comm_key_b}" style="color:#237DD4">{$comment.poll_info.poll_displayname}</a></small></div>
			{elseif $comment.comm_module=='shop'}
				<div style="color:#237DD4"><small>К продукту <a href="/shop/{$comment.comm_key_a}/{$comment.comm_key_b}.html" style="color:#237DD4">{$comment.product_info.product_displayname}</a></small></div>

			{/if}
			<div>{$comment.comm_content|escape}</div>
			<div class="date">{$comment.comm_addtime|date_format:" %d.%m.%Y %H:%M"}</div>
		</div>

      {/foreach}
</div>
<div class="clearfix"></div>
 
{if $curuser_sec_level>=70}
	<div style="text-align:center;"><a class="btn" href="{mod_url type='admin' modname='comment' func='config_modify'}">Конфигурация</a></div>
{/if}
{/strip}