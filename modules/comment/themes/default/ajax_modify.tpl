{strip}
<form name="bbcode_form" id="comment_modify_form">
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="25%">
      <col width="75%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="2">Редактирование отзыва</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td class="foot" colspan="2" align="center">
	          <input class="formButton" type="submit" value="Отмена" name="cancel" onclick="comment_update('cancel', '{$id}', '{$comm_module}', '{$comm_key_a}', '{$comm_key_b}');return false;">&nbsp;&nbsp;&nbsp;
           {* <input class="formButton" type="submit" value="Пред. просмотр" name="preview" onclick="comment_update('preview', '{$id}', '{$comm_module}', '{$comm_key_a}', '{$comm_key_b}');return false;">&nbsp;&nbsp;&nbsp;*}
            <input class="formButton" type="submit" value="Редактировать" name="submit" onclick="comment_update('update', '{$id}', '{$comm_module}', '{$comm_key_a}', '{$comm_key_b}');return false;">
        </td>
      </tr>
    </tfoot>

    <tbody>
    	{if $preview_html}
        <tr>
          <td class="even" colspan="2">
            {$preview_html}
          </td>
        </tr>
        <tr>
          <td width="100%" colspan="2" class="foot"></td>
        </tr>
    	{/if}

    	{if $curuser_id<1}
        <tr>
          <td class="head">Ваше Имя:</td>
          <td class="even">
            <input size="70" name="user_displayname" value="{$user_displayname|escape}">
          </td>
        </tr>
      {/if}

      <tr>
        <td class="head">Заголовок:</td>
        <td class="even">
          <input size="70" name="comm_displayname" value="{$comm_displayname|escape}">
        </td>
      </tr>

      <tr>
        <td class="head">Текст:</td>
        <td class="even">
         	{*bbeditor name='comm_content' rows='6' cols='70' width='580' text=$comm_content*}
		{texteditor name='comm_content'  width='580' text=$comm_content}
		
        </td>
      </tr>
    </tbody>

  </table>
</form>
{/strip}