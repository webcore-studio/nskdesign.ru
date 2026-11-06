{$text}
{if $curuser_sec_level>=70}
<p>Ссылка: <a href="{$url}">{$url}</a>
<p>Тип ошибки: {$excType}
<p>Сообщение об ошибке: {$message}
{/if}
