{strip}
Пользователем добавлен новый документ в раздел "{$folder_displayname}"<br><br>

Заголовок: <i>{$displayname}</i><br>
Содержание: {$content}<br><br>
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

{if $description}
{$description}<br>
{/if}

{if $misc_varchar_a && $misc_varchar_a!=1}
Телефон: {$misc_varchar_a}<br>
{/if}

{if $url}
Приложенный файл: {$url}<br>
{/if}


{/strip}
