{strip}
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="3%">
      <col width="97%">
    </colgroup>

    <thead>
      <tr>
        <th colspan="4">Предварительный просмотр</th>
      </tr>
    </thead>


    <tbody>
      <tr id="comment_id_preview">
        <td width="120" rowspan="3" height="80" align="left" valign="top" class="even">
	        {* аватара *}
          {if $user_id>0}
            <b><a href="/user/{$user_id_name|escape}/">{$user_id_name}</a></b>
              <br>
            	{if $user_id_rank}
            		{$user_id_rank|escape}
            	{else}
            		Новичок
           		{/if}
            <br><br>

            <a href="/user/{$user_id_name|escape}/">
            	{if $user_id_avatar_w>0}
            		<img src="/images/user/avatar/{$user_id_avatar}" width="{$user_id_avatar_w}" height="{$user_id_avatar_h}" alt="Аватар" border="0">
            	{else}
               <img src="/files/shared/images/avatars/noavatar.png" width="100" height="100" alt="Аватар" border="0">
             {/if}
            </a>

            <br><br>
            <b>Зарегистрирован:</b> {$user_id_regdate|date_format:"%d.%m.%Y"}
          {elseif $user_displayname}
            {$user_displayname|truncate:16}
            <br><br>
						<img src="/files/shared/images/avatars/noavatar.png" width="100" height="100" alt="Аватар" border="0">
          {else}
            Гость
            <br><br>
						<img src="/files/shared/images/avatars/noavatar.png" width="100" height="100" alt="Аватар" border="0">
          {/if}
        </td>

        <td width="100%" height="19" class="head" align="left">
          {* Название поста *}
            {if $comm_displayname}
              {$comm_displayname|truncate:82}
            {else}
              {$comm_name|truncate:82}
            {/if}
        </td>
      </tr>

      <tr>
        <td width="100%" height="100" colspan="2" class="even" align="left" valign="top">
          {$comm_content|escape|wordwrap|autosmile|bbcode}
        </td>
      </tr>
    </tbody>
  </table>
{/strip}