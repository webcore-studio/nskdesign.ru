{strip}

{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

{array name='sort_by_list'}
{array_append name='sort_by_list' key='addtime' value='Дате добавления'}
{array_append name='sort_by_list' key='modtime' value='Дате редактирования'}

<form method="POST" action="{mod_url modname='comment' type='admin' func='config_update'}" name="update_config_form">
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="40%">
      <col width="60%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="2">Настройка модуля комментариев</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td colspan="2" align="center" class="even"><input type="submit" value="Сохранить" name="button"></td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td class="head">Глобально разрешены комментарии?</td>
        <td class="even">{html_radios name="comment_enabled" options=$yes_no checked=$comment_enabled separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Использовать капчу</td>
        <td class="even">{html_radios name="captcha_enabled" options=$yes_no checked=$captcha_enabled separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Количество комментариев на страницу</td>
        <td class="even"><input maxLength="255" size="5" value="{$comment_perpage}" name="comment_perpage"></td>
      </tr>

      <tr>
        <td class="head">Минимальная длина комментария</td>
        <td class="even"><input maxLength="255" size="5" value="{$content_min_len}" name="content_min_len"> символов</td>
      </tr>
      <tr>
        <td class="head">Максимальная длина комментария</td>
        <td class="even"><input maxLength="255" size="5" value="{$content_max_len}" name="content_max_len"> символов</td>
      </tr>

      <tr>
        <td class="head">Сортировать комментарии по</td>
        <td class="even">
          <select name="sort_by">
            {html_options options=$sort_by_list selected=$sort_by}
          </select>
          &nbsp;возростанию {html_radios name="sort_asc" options=$yes_no checked=$sort_asc separator=" "}
        </td>
      </tr>

      <tr>
        <td class="head">Минимальный интервал между комментариями с одного Ip адресса</td>
        <td class="even"><input maxLength="255" size="5" value="{$flood_ip_time}" name="flood_ip_time"> секунд</td>
      </tr>

      <tr>
        <td class="head">Уведомлять по e-mail при добавлении комментария</td>
        <td class="even">{html_radios name="email_notify_enable" options=$yes_no checked=$email_notify_enable separator=" "}</td>
      </tr>

      <tr>
        <td class="head">Выберите группу, которой будет поступать уведомления:</td>
        <td class="even">
          <select name="email_notify_group">
            {html_options options=$groups_list selected=$email_notify_group}
          </select>
        </td>
      </tr>

        <tr>
          <td class="head">Использовать редактор</td>
          <td class="even">
            <select name="use_texteditor">
              {html_options options=$texteditorlist selected=$use_texteditor}
            </select>
          </td>
        </tr>

    </tbody>

  </table>

</form>
{/strip}