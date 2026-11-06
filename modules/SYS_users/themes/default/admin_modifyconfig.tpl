{strip}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}
<form method="POST" action="{mod_url modname='SYS_users' type='admin' func='updateconfig'}" name="userconfig_form">
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="40%">
      <col width="60%">
    </colgroup>

    <thead>
      <tr>
        <th colSpan="2">Настройка информации о пользователях</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td colspan="2" align="center" class="even"><input type="submit" value="Сохранить" name="button"></td>
      </tr>
    </tfoot>

    <tbody>
	 <tr>
        <td class="head">Пользователей на страницу в списке</td>
        <td class="even"><input maxLength="255" size="50" value="{$perpage}" name="perpage"></td>
      </tr>
	  <tr>
        <td class="head">Капча</td>
        <td class="even">{html_radios name="need_captcha" options=$yes_no checked=$need_captcha separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Разрешить регистрацию новых пользователей?</td>
        <td class="even">{html_radios name="allow_register" options=$yes_no checked=$allow_register separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Разрешить востановление паролей</td>
        <td class="even">{html_radios name="allow_lostpass" options=$yes_no checked=$allow_lostpass separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Минимальная требуемая длина пароля</td>
        <td class="even"><input maxLength="255" size="50" value="{$minpass}" name="minpass"></td>
      </tr>
      <tr>
        <td class="head">Требуемая минимальная длина имени пользователя</td>
        <td class="even"><input maxLength="255" size="50" value="{$minuname}" name="minuname"></td>
      </tr>
      <tr>
        <td class="head">Максимальная длина имени пользователя</td>
        <td class="even"><input maxLength="255" size="50" value="{$maxuname}" name="maxuname"></td>
      </tr>
      <tr>
        <td class="head">Разрешить пользователям менять e-mail адрес?</td>
        <td class="even">{html_radios name="allow_chgmail" options=$yes_no checked=$allow_chgmail separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Известить по e-mail при регистрации нового пользователя?</td>
        <td class="even">{html_radios name="new_user_notify" options=$yes_no checked=$new_user_notify separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Выберите группу, которой будет поступать уведомления при регистрации новых пользователей :</td>
        <td class="even">
          <select name=new_user_notify_group>
            {html_options options=$groups_list selected=$new_user_notify_group}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Выберите тип активации при регистрации пользователя</td>
        <td class="even">
          <select name=activation_type>
            {html_options options=$activation_types selected=$activation_type}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Выберите группу которая будет рассылать активацию :</td>
        <td class="even">
          <select name=activation_group>
            {html_options options=$groups_list selected=$activation_group}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Выберите уровень фильтрации при выборе имени пользователя</td>
        <td class="even">
          <select name=uname_test_level>
            {html_options options=$uname_test_levels selected=$uname_test_level}
          </select>
        </td>
      </tr>
      <tr>
        <td class="head">Разрешить пользователям загружать собственные аватары?</td>
        <td class="even">{html_radios name="avatar_allow_upload" options=$yes_no checked=$avatar_allow_upload separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Требуемое минимальное количество сообщений
        (Введите минимальное количество сообщений, после которого можно загружать свой аватар)</td>
        <td class="even"><input maxLength="255" size="50" value="{$avatar_minposts}" name="avatar_minposts"></td>
      </tr>
      <tr>
        <td class="head">Максимальная ширина аватара (точек)</td>
        <td class="even"><input maxLength="255" size="50" value="{$avatar_width}" name="avatar_width"></td>
      </tr>
      <tr>
        <td class="head">Максимальная высота аватара (точек)</td>
        <td class="even"><input maxLength="255" size="50" value="{$avatar_height}" name="avatar_height"></td>
      </tr>
      <tr>
        <td class="head">Максимальный размер файла аватара (байт)</td>
        <td class="even"><input maxLength="255" size="50" value="{$avatar_maxsize}" name="avatar_maxsize"></td>
      </tr>
      <tr>
        <td class="head">Разрешить пользователям удалять свою учетную запись?</td>
        <td class="even">{html_radios name="self_delete" options=$yes_no checked=$self_delete separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Введите имена, которые не могут быть использованы учетными записями&nbsp;
          пользователей, не чувствительно к регистру, разрешен regex.</td>
        <td class="even"><textarea name="bad_unames" rows="5" cols="50">{$bad_unames|escape}</textarea></td>
      </tr>
      <tr>
        <td class="head">Введите e-mail адреса, которые не могут быть использованы учетными&nbsp;
          записями пользователей, не чувствительно к регистру, разрешен regex.</td>
        <td class="even"><textarea name="bad_emails" rows="5" cols="50">{$bad_emails|escape}</textarea></td>
      </tr>
      <tr>
        <td class="head">Показывать условия регистрации?</td>
        <td class="even">{html_radios name="reg_dispdsclmr" options=$yes_no checked=$reg_dispdsclmr separator=" "}</td>
      </tr>
      <tr>
        <td class="head">Условия регистрации</td>
        <td class="even"><textarea name="reg_disclaimer" rows="10" cols="50">{$reg_disclaimer|escape}</textarea></td>
      </tr>
    </tbody>

  </table>

</form>
{/strip}