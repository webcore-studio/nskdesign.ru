{strip}
  <form action="{mod_url modname='SYS_config' type='admin' func='mailconfig_update'}" method="post">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Настройка почты</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <td colspan="2" align="center" class="foot">
            <input class="btn formButton" type="submit" value="Сохранить" name="button">
          </td>
        </tr>
      </tfoot>

      <tbody>
        <tr>
          <td class="head">Обратный адрес</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" name="mail_from" value="{$mail_from|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Имя отправителя</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" name="mail_fromname" value="{$mail_fromname|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Метод доставки почты</td>
          <td class="even">
            <select size="1" name="mail_method">
              {html_options options=$mail_method_list selected=$mail_method}
            </select>
          </td>
        </tr>
		
		<tr>
          <td class="head">Сохранять отправленные письма на сервере</td>
          <td class="even">
           {array name='yes_no'}
			{array_append name='yes_no' key='1' value=#yes#}
			{array_append name='yes_no' key='0' value=#no#}
			{html_radios name="mail_savelog" options=$yes_no checked=$mail_savelog separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">Путь к программе sendmail</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" value="{$mail_sendmailpath|escape}" name="mail_sendmailpath">
          </td>
        </tr>

        <tr>
          <td class="head">SMTP-сервер</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" value="{$mail_smtphost|escape}" name="mail_smtphost">
          </td>
        </tr>

        <tr>
          <td class="head">SMTP-пользователь</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" value="{$mail_smtpuser|escape}" name="mail_smtpuser">
          </td>
        </tr>

        <tr>
          <td class="head">SMTP-пароль</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" value="{$mail_smtppass|escape}" name="mail_smtppass">
          </td>
        </tr>
      </tbody>

    </table>
  </form>
{/strip}