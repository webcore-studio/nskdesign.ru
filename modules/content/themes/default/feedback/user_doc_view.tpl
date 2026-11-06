{array name='url_vars'}
{array_append name='url_vars' key='id' value=$id}
{array_append name='url_vars' key='fid' value=$fid}
			
<table class="outer table" cellSpacing="1" cellPadding="4"  border=0>

<thead>
<tr>
<th colspan=2>Просмотр сообщения обратной связи</th>
</tr>
</thead>
<tbody>
<tr vAlign="top" align="left">
<td>Название</td>
<td>{$displayname}	
	{if $curuser_sec_level>=60}
				&nbsp;
				<span>
					<a href="{mod_url type='admin' modname='content' func='doc_confirm_delete' vars=$url_vars}" title="{#deletedoc#}">
						<img src="/files/shared/images/system/del.gif" width="16" height="16" alt="{#deletedoc#}" border=0>
					</a>
				</span>
			{/if}
</td>
</tr>

<tr>
<td>Сообщение</td>
<td>{$content}</td>
</tr> 

<tr>
<td>Отправитель</td>
<td>{$author}</td>
</tr>

<tr>
<td>E-mail</td>
<td>{$author_email}</td>
</tr>

<tr>
<td>Контактный телефон</td>
<td>{$misc_varchar_a}</td>
</tr>

{if $url}
<tr>
<td>Прикрепленный файл</td>
<td>{$url}</td>
</tr>
{/if}
</tbody>
</table>
