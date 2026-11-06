{usergetvars assign=uservars}
<nav class="cms-admin-bar" aria-label="Управление сайтом">
  <a class="cms-admin-brand" href="/index.php?module=SYS_modules&amp;type=admin">CMS</a>
  <span>{$uservars.user_name|escape}</span>
  {foreach item=item from=$items_list}<a href="{$item.content}">{$item.displayname|escape}</a>{/foreach}
</nav>
