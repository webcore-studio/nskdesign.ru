{strip}
Пользователем добавлен новый вопрос в раздел "{$folder_displayname}"<br><br>

Вопрос: <b>{$displayname}</b><br>
Автор: 
{if $author}{$author}
{elseif $user_data.user_displayname}{$user_data.user_displayname} (логин на сайте - {$user_data.user_name})
{elseif $user_data.user_name}{$user_data.user_name} (зарегистрированный пользователь)
{else}Гость
{/if}
<br>
{if $author_email}
Обратный e-mail: {$author_email}<br>
{/if}

{/strip}
