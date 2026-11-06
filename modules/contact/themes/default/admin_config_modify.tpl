{strip}

{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

{array name='sort_by_list'}
{array_append name='sort_by_list' key='addtime' value='Дате добавления'}
{array_append name='sort_by_list' key='modtime' value='Дате редактирования'}

<form method="POST" action="{mod_url modname='contact' type='admin' func='config_update'}" name="update_config_form">
  <table class="outer" cellSpacing="1" cellPadding="4" width="80%" align="center">
    <colgroup>
      <col width="40%">
      <col width="60%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="2">Настройка модуля комментариев</th>
      </tr>
    </thead>


    <tbody>
      <tr>
        <td class="head">Отправлять сообщения на почту</td>
        <td class="even">{html_radios name="email_notify_enable" options=$yes_no checked=$email_notify_enable separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Использовать капчу</td>
        <td class="even">{html_radios name="captcha_enabled" options=$yes_no checked=$captcha_enabled separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Текст выводимый до добавления комментария</td>
        <td class="even">{texteditor name="before_send_text" text=$before_send_text height=300}</td>
      </tr>
      <tr>
        <td class="head">Текст выводимый после добавления комментария</td>
        <td class="even">{texteditor name="after_send_text" text=$after_send_text height=300}</td>
      </tr>
      <tr>
	      <td class="head">Получатели</td>
		  <td>
			<table>
				<tr>
					<td class="head" align="center">Название группы</td>
					<td class="head" align="center">Email, через запятую</td>
					<td class="head" align="center">По умолчанию</td>
				  </tr>
				{foreach item=rec from=$rec_list name=recs}
				<tr>
				  <td class="even" align="center"><input type="text" maxlength="25" size="40" name="rec_name[]" value="{$rec.rec_name}"></td>
				  <td class="even" align="center"><input type="text" maxlength="50" size="60" name="rec_emails[]" value="{$rec.rec_emails}"></td>
				  <td class="even" align="center"><input type="radio" name="rec_default" value="{$smarty.foreach.recs.iteration}" {if $rec_default==$smarty.foreach.recs.iteration}checked{/if}></td>
				</tr>
				{/foreach}
				<tr>
					{assign var='def' value=$smarty.foreach.recs.iteration+1}
					<td class="even" align="center"><input type="text" axlength="25" size="40" name="rec_name[]" value=""></td>
					<td class="even" align="center"><input type="text" maxlength="50" size="60" name="rec_emails[]" value=""></td>
					<td class="even" align="center"><input type="radio" name="rec_default" value="{$def}"></td>
				</tr>
				<tr>
					{assign var='def' value=$smarty.foreach.recs.iteration+2}
					<td class="even" align="center"><input  type="text" maxlength="25" size="40" name="rec_name[]" value=""></td>
					<td class="even" align="center"><input  type="text" maxlength="50" size="60" name="rec_emails[]" value=""></td>
					<td class="even" align="center"><input type="radio" name="rec_default" value="{$def}"></td>
				</tr>
			</table>
		</td>
	</tr>
		
    <tfoot>
      <tr>
        <td colspan="2" align="center" class="even"><input class="btn" type="submit" value="Сохранить" name="button"></td>
      </tr>
    </tfoot>

  </table>

</form>
{/strip}