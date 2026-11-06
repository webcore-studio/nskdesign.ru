{strip}
<a name="comments"></a>
<script type="text/javascript" src="/scripts/java_scripts/bbcode.js"></script>
<h3>Добавить отзыв</h3>
  <form style="margin-top:20px;" name="bbcode_form" id="comment_new_form" onsubmit="comment_create('create', '{$comm_module}', '{$comm_key_a}', '{$comm_key_b}');{if $captcha_enabled && $curuser_id<1}comment_captcha_update();{/if}this.blur();return false;">

    <input type="hidden" name="comment_rating_ball" value="" id="comment_rating_ball">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
         <colgroup>
          <col width="35%">
          <col width="65%">
        </colgroup>

 

      <tfoot>
        <tr>
          <td class="foot" colspan="3" align="center">
		  <br>
	        <input class="formButton" type="submit" value="Добавить" name="submit">
			
          </td>
        </tr>
      </tfoot>

      <tbody>

      	{if $curuser_id<1}
          <tr>
            <td class="head">Ваше Имя:</td>
            <td class="comment_even">
              <input size="50" name="user_displayname" value="{$user_displayname}">
			  
            </td>
          </tr>
        {/if}
		<input size="1" type="hidden" name="comm_displayname" value=" " />
       

        <tr>
          <td class="head">Текст:</td>
          <td class="comment_even">
          	{*{bbeditor name='comm_content' rows='6' cols='50'text=$comm_content}*}

<textarea style="border:solid 1px #999999;" rows='6' cols='50' name="comm_content" id="comm_content" onClick="clear();"></textarea>
          </td>
        </tr>
		
		<tr>
		<td></td>
		<td>
		{if $smiles_enabled}
			{include file='modules/comment/themes/default/include/smiles.tpl'}
		{/if}

		</td>
		</tr>

      	{if $captcha_enabled && $curuser_id<1}
          <tr align="left">
            <td class="head" valign=top>Код подтверждения</td>
            <td class="comment_even" align="left" valign="middle">
{literal}
<script language="JavaScript" type="text/javascript">
 function comment_captcha_update()
              {
              var rndval = new Date().getTime();
              document.getElementById('comment_captcha_div').innerHTML = '<img src="/index.php?module=comment&type=ajax&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0">';
              return false;
              }
</script>
{/literal}
				<input style="border:solid 1px #999999; " size="10" name="captcha_string" value="">
				&nbsp;	&nbsp;	&nbsp;
				<span style="position:relative; top:10px;" id="comment_captcha_div">
	            	<img src="{mod_url modname='comment' type='ajax' func='captcha_view'}" alt="Код безопасности" border="0" />
				</span>
				<span>
					<img style="border:none; cursor:pointer; margin-left:10px;margin-top:25px;" src="/modules/comment/images/gtk-refresh.png" alt="Обновить код" onclick="comment_captcha_update(); return false;">
	           	</span>

			
		
            </td>
          </tr>
        {/if}
      </tbody>

    </table>
  </form>
{/strip}