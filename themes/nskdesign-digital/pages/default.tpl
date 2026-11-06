{if isset($module_object) && strpos($module_object, '::admin::') !== false}
  {include file="themes/nskdesign-digital/pages/admin.tpl"}
{else}
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{if !empty($module_meta_title)}{$module_meta_title|escape}{elseif !empty($module_page_title)}{$module_page_title|escape} — NSK Design{else}NSK Design — Digital-агентство и веб-разработка с 2003 года{/if}</title>
  <meta name="description" content="{if !empty($module_meta_description)}{$module_meta_description|escape}{else}Разработка сайтов, интернет-магазинов и веб-сервисов. Яндекс.Директ, SEO и техническая поддержка. NSK Design — работаем с 2003 года.{/if}">
  {if !empty($module_meta_keywords)}<meta name="keywords" content="{$module_meta_keywords|escape}">{/if}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Space+Grotesk:wght@500;600;700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="/themes/nskdesign-digital/img/favicon.svg">
  <link rel="stylesheet" href="/themes/nskdesign-digital/style/theme.css">
  <script src="/themes/nskdesign-digital/js/theme.js" defer></script>
  {if !empty($module_object) && $module_object != 'MainPage'}
    <script src="/scripts/java_scripts/jquery-1.9.1.min.js"></script>
    <script src="/scripts/java_scripts/20091120.js"></script>
    <script src="/modules/contact/js/contact.js"></script>
    <script src="/scripts/java_scripts/functions.js"></script>
  {/if}
</head>
<body>
<a class="skip-link" href="#main">Перейти к содержанию</a>
{include file="themes/nskdesign-digital/pages/admin-bar.tpl"}
{include file="themes/nskdesign-digital/pages/ajax.tpl"}
{include file="themes/nskdesign-digital/pages/header.tpl"}
<main id="main">
  {if !empty($module_object) && $module_object == 'MainPage'}
    {include file="themes/nskdesign-digital/pages/home.tpl"}
  {else}
    <section class="inner-page wrap">
      {if !empty($module_browsein)}<nav class="breadcrumbs" aria-label="Хлебные крошки">{browsein date=$module_browsein show_last=true href_last=false}</nav>{/if}
      <div class="content-layout">
        {if !empty($blocks.left)}<aside class="sidebar">{foreach item=block from=$blocks.left}<section class="cms-block"><h2>{$block.block_displayname|escape}</h2>{$block.block_content}</section>{/foreach}</aside>{/if}
        <div class="cms-content">
          {if !empty($blocks.top)}{foreach item=block from=$blocks.top}<div class="cms-block">{$block.block_content}</div>{/foreach}{/if}
          {if !empty($module_content)}{$module_content}{/if}
          {if !empty($blocks.center)}{foreach item=block from=$blocks.center}<section class="cms-block">{$block.block_content}</section>{/foreach}{/if}
          {if !empty($blocks.bottom)}{foreach item=block from=$blocks.bottom}<section class="cms-block">{$block.block_content}</section>{/foreach}{/if}
        </div>
        {if !empty($blocks.right)}<aside class="sidebar">{foreach item=block from=$blocks.right}<section class="cms-block"><h2>{$block.block_displayname|escape}</h2>{$block.block_content}</section>{/foreach}</aside>{/if}
      </div>
    </section>
  {/if}
</main>
{include file="themes/nskdesign-digital/pages/footer.tpl"}
</body>
</html>
{/if}
