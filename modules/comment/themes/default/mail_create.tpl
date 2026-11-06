{strip}
Пользователем добавлен новый отзыв на товар http://kazanovashop.ru/shop/{$comm_key_a}/{$comm_key_b}.html<br><br>

Отзыв:<br> <i>{$comm_content}</i><br><br>
Автор: {$user_displayname}<br>
Обратный e-mail: {$author_email}<br>
{if $user_phone}
Телефон: {$user_phone}<br>
{/if}

{if $bonus_card}
Номер бонусной карты: {$bonus_card}<br>
{/if}

{if $no_bonus_card}
Пользователь хочет получить карту<br>
{/if}

{/strip}
