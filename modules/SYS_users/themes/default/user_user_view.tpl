{strip}

<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
  <colgroup>
    <col width="30%">
    <col width="70%">
  </colgroup>

  <thead>
  <tr>
    <th colspan="2">Профиль пользователя "{$user_name|escape}"</th>
  </tr>
  </thead>

  <tbody>
  {if $user_displayname}
    <tr>
      <td class="head">Имя:</td>
      <td class="even">{$user_displayname|escape}</td>
    </tr>
  {/if}

  {if $user_femail}
    <tr>
      <td class="head">E-mail:</td>
      <td class="even">
      <a href="mailto:{$user_femail|escape}">{$user_femail|escape}</a>
      </td>
    </tr>
  {/if}

  {if $user_url}
    <tr>
      <td class="head">Сайт:</td>
      <td class="even">{$user_url|escape}</td>
    </tr>
  {/if}

  {if $user_icq}
    <tr>
      <td class="head">ICQ:</td>
      <td class="even">{$user_icq|escape}</td>
    </tr>
  {/if}

  {if $user_from}
    <tr>
      <td class="head">Откуда:</td>
      <td class="even">{$user_from|escape}</td>
    </tr>
  {/if}

  {if $user_interests}
    <tr>
      <td class="head">Интересы:</td>
      <td class="even">{$user_interests|escape}</td>
    </tr>
  {/if}

  {if $user_about}
    <tr>
      <td class="head">О себе:</td>
      <td class="even">{$user_about|escape}</td>
    </tr>
  {/if}
  </tbody>

</table>

{/strip}