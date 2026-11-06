{strip}

<tr>
    <td align="left" class="head">Html код:</td>
    <td align="left" class="even">
		<div id="htmlblock">
			{if $wysiwyg}
				{texteditor name="content" id="c" text=$content}
			{else}
				<textarea name="content" rows="20" cols="78" id="c">{$content|escape}</textarea>
			{/if}
		</div>
    </td>
</tr>
<tr>
    <td align="left" class="head">Включить вайсвиг</td>
    <td align="left" class="even">
		<select name="wysiwyg" onChange="window.location='/index.php?module=SYS_blocks&type=admin&func=modify&id={$id}&wysiwyg='+this.value" id="wysiwyg">
			<option value="1" {if ($wysiwyg == 1)}selected{/if}>да</option>
			<option value="0" {if $wysiwyg == 0 or !$wysiwyg}selected{/if}>нет</option>
		</select>
	</td>
</tr> 
{/strip}