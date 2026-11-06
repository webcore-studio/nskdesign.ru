{*debug*}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="{mod_getvar modname='SYS_config' name='meta_robots'}">
{if $module_meta_keywords}
    <meta name="keywords" content="{$module_meta_keywords}">
{else}
    <meta name="keywords" content="{mod_getvar|escape modname='SYS_config' name='meta_keywords'}">
{/if}
{if $module_meta_description}
    <meta name="description" content="{$module_meta_description}">
{else}
    <meta name="description" content="{mod_getvar|escape modname='SYS_config' name='meta_description'}">
{/if}
<meta name="rating" content="{mod_getvar modname='SYS_config' name='meta_rating'}">
<meta name="author" content="{mod_getvar|escape modname='SYS_config' name='meta_author'}">
<meta name="copyright" content="{mod_getvar|escape modname='SYS_config' name='meta_copyright'}">

{if $module_meta_title}
    <title>{$module_meta_title}</title>
{elseif $module_browsein}
    <title>
        {mod_getvar|escape modname='SYS_config' name='site_name'}
        {foreach item=item from=$module_browsein}
            &nbsp;/&nbsp;{$item.displayname|escape}
        {/foreach}
    </title>
{else}
    <title>{mod_getvar|escape modname='SYS_config' name='site_name'}</title>
{/if}

{config_css}

<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet"/>

<link rel="stylesheet" href="/themes/{$user_theme}/style/icon-bootstrap.css" type="text/css" media="all">
<link rel="stylesheet" href="/themes/{$user_theme}/style/kedr-design.css?{$smarty.now}" type="text/css" media="all">

<link rel="icon" href="/favicon.ico">
</head>

<body class="kedr-theme">
{mod_getvar modname='SYS_config' name='site_url' assign="site_url"}

<!-- Header (TopNavBar) -->
<header class="kedr-header">
<div class="kedr-header-inner">
<!-- Brand -->
<a class="kedr-brand" href="{$site_url}">
    <img src="/files/shared/images/logo.png" style="width: 150px;/* display: none; */">
</a>

<!-- Mobile Menu Toggle (Sandwich) -->
<button class="kedr-mobile-menu-toggle kedr-hidden-desktop" aria-label="Меню">
    <span class="material-symbols-outlined">menu</span>
</button>

<!-- Navigation Links -->
 {$blocks.exact.2.block_content}



<!-- Desktop Phone + Social Icons -->
<div class="kedr-header-actions kedr-hidden-mobile">
  <a href="tel:+79130703080" class="kedr-phone">
    <span class="material-symbols-outlined kedr-phone-icon">call</span>
    <span>8-913-070-30-80</span>
  </a>
  <span class="kedr-divider"></span>
  
  <!-- Telegram -->
  <a href="https://t.me/ostrovdetstvar" target="_blank" rel="noopener" title="Telegram" class="kedr-social-link">
    <svg class="kedr-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
  </a>
  
  <!-- VKontakte -->
  <a href="https://vk.ru/kedrostrovdetsva" target="_blank" rel="noopener" title="ВКонтакте" class="kedr-social-link">
    <svg class="kedr-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.391 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.525-2.05-1.727-1.033-1-1.49-1.135-1.744-1.135-.356 0-.458.102-.458.593v1.575c0 .424-.135.678-1.253.678-1.846 0-3.896-1.118-5.335-3.202C4.624 10.857 4 8.756 4 8.316c0-.254.102-.491.593-.491h1.744c.44 0 .61.203.78.678.847 2.456 2.27 4.607 2.86 4.607.22 0 .322-.102.322-.66V9.721c-.068-1.186-.695-1.287-.695-1.71 0-.203.17-.407.44-.407h2.744c.372 0 .508.203.508.643v3.473c0 .372.17.508.271.508.22 0 .407-.136.813-.542 1.254-1.406 2.151-3.574 2.151-3.574.119-.254.322-.491.763-.491h1.744c.525 0 .644.27.525.643-.22 1.017-2.354 4.031-2.354 4.031-.186.305-.254.44 0 .78.186.254.796.779 1.203 1.253.745.847 1.32 1.558 1.473 2.05.17.49-.085.744-.576.744z"/></svg>
  </a>
  

  <!-- Search -->
  <button title="Поиск" class="kedr-search-btn">
    <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
  </button>
</div>

</div>
</header>

<!-- Main Content Canvas -->
<main class="kedr-main">

{if $object=='MainPage'}
<!-- Hero Section -->
<section class="kedr-container">
<div class="kedr-hero">
<div class="kedr-hero-content">
<h1 class="kedr-hero-title">
    ДОЛ «Кедр» — летний лагерь в Горной Шории
</h1>
<p class="kedr-hero-description">
    Безопасный отдых на природе. Камерный формат на 80 детей, комфортные номера с удобствами внутри и насыщенная программа для незабываемого лета.
</p>
<div class="kedr-hero-buttons">
<button class="kedr-btn kedr-btn-primary">
    Забронировать путевку
</button>
<button class="kedr-btn kedr-btn-secondary">
    Узнать подробнее
</button>
</div>
</div>
<div class="kedr-hero-image">
<img alt="Camp activity" src="/files/shared/images/Image14_12_35.png"/>
</div>
</div>
</section>

<!-- Trust Markers -->
<section class="kedr-container">
<div class="kedr-trust-markers">
<div class="kedr-trust-item">
<span class="material-symbols-outlined kedr-trust-icon">hotel_class</span>
<span class="kedr-trust-label">Удобства в номере</span>
</div>
<div class="kedr-trust-item">
<span class="material-symbols-outlined kedr-trust-icon">security</span>
<span class="kedr-trust-label">Охрана 24/7</span>
</div>
<div class="kedr-trust-item">
<span class="material-symbols-outlined kedr-trust-icon">restaurant</span>
<span class="kedr-trust-label">5-разовое питание</span>
</div>
<div class="kedr-trust-item">
<span class="material-symbols-outlined kedr-trust-icon">eco</span>
<span class="kedr-trust-label">Экологически чистый район</span>
</div>
<div class="kedr-trust-item kedr-col-span-2">
<span class="material-symbols-outlined kedr-trust-icon">groups</span>
<span class="kedr-trust-label">Камерный лагерь: 80 детей</span>
</div>
</div>
</section>

<!-- Summer Shifts 2026 -->
<section class="w-full bg-surface-container-low py-12">
<div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop">
<h2 class="font-display text-headline-lg-mobile md:text-headline-lg text-text-primary mb-8 text-center">Летние смены 2026</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
<!-- Shift 1 (Nearest) -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-border-subtle flex flex-col gap-4 hover:border-primary/50 transition-colors relative overflow-hidden">
<div class="absolute top-0 right-0 bg-primary-fixed-dim text-on-primary-fixed-variant px-3 py-1 rounded-bl-lg font-body text-label-sm text-[11px]">БЛИЖАЙШАЯ</div>
<div class="bg-secondary-fixed text-primary px-3 py-1 rounded-full font-body text-label-sm self-start">20.06 – 03.07</div>
<h3 class="font-display text-headline-md text-text-primary">Приключенческая</h3>
<p class="font-body text-body-md text-text-secondary mt-auto">Для активных исследователей и любителей природы.</p>
</div>
<!-- Shift 2 -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-border-subtle flex flex-col gap-4 hover:border-primary/50 transition-colors">
<div class="bg-surface-container text-text-secondary px-3 py-1 rounded-full font-body text-label-sm self-start">05.07 – 18.07</div>
<h3 class="font-display text-headline-md text-text-primary">Творческая</h3>
<p class="font-body text-body-md text-text-secondary mt-auto">Раскрываем таланты в искусстве, театре и музыке.</p>
</div>
<!-- Shift 3 -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-border-subtle flex flex-col gap-4 hover:border-primary/50 transition-colors">
<div class="bg-surface-container text-text-secondary px-3 py-1 rounded-full font-body text-label-sm self-start">20.07 – 02.08</div>
<h3 class="font-display text-headline-md text-text-primary">Лидерская</h3>
<p class="font-body text-body-md text-text-secondary mt-auto">Развиваем навыки коммуникации и работы в команде.</p>
</div>
<!-- Shift 4 -->
<div class="bg-surface-container-lowest rounded-xl p-6 border border-border-subtle flex flex-col gap-4 hover:border-primary/50 transition-colors">
<div class="bg-surface-container text-text-secondary px-3 py-1 rounded-full font-body text-label-sm self-start">04.08 – 17.08</div>
<h3 class="font-display text-headline-md text-text-primary">Экономическая</h3>
<p class="font-body text-body-md text-text-secondary mt-auto">Основы финансовой грамотности в игровом формате.</p>
</div>
</div>
</div>
</section>

<!-- Infrastructure Preview -->
<section class="kedr-container">
<h2 class="kedr-section-title">Инфраструктура</h2>
<div class="kedr-infrastructure-grid">
<!-- Rooms -->
<div class="kedr-infrastructure-item">
<img alt="Rooms" src="/files/shared/images/Image_gagieqgagieqgagi.png"/>
<div class="kedr-infrastructure-overlay">
<h3 class="kedr-infrastructure-title">Номера</h3>
<p class="kedr-infrastructure-description">Комфортное размещение с удобствами в каждой комнате.</p>
</div>
</div>

<!-- Food -->
<div class="kedr-infrastructure-item">
<img alt="Food" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDD3HiemUOX98n859iCvP144uU5ANjeGp9JBKLosKNyxKI5VwKiNrOILR8-3cO2u5lDnGn8LxAaW-4suFolXXCnFH_BNYKiaNjAsS56OM5ar0hJCoutGlpjp8y2wLjsvnvY822VnM6FZICie7ZLkjdPTZ7KrGl0a3oWCcfL2UHiKqASl5HXwn3ixQDGHi1f-yT1nVPEk_zz4vqFYXc-VdKVmKD7QwnWiqcyFvrMaZZPQYQU-Akv1Ya8XmDnjWTj_Ob1AlOo6l3MklY"/>
<div class="kedr-infrastructure-overlay">
<h3 class="kedr-infrastructure-title">Питание</h3>
<p class="kedr-infrastructure-description">Сбалансированное 5-разовое меню из свежих продуктов.</p>
</div>
</div>

<!-- Activities -->
<div class="kedr-infrastructure-item">
<img alt="Activities" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8xJPfp13RApmuvPIzj5KWRisW7_vHKnrLPs3tM03sTaupslMGCPIdcWqN4B06HwCI6wmuH2qQbWGdvUFPG1W3iV6xxmP4_iM-x33wxUdILKeCheuTND4YZ6VGWd6g49egWzLTjj5gDNG86D0rUnCsIYDEo84PSeVtXDCxoeANw08cKtkQ7wLlDBTx20I5gDpHpDvdo5TB6-Kt_w8WvkUcm0hxVgxg6illpOKaUoxSbKH-LrO4BybCeWX66c8NWMyNcfPy6hJG690"/>
<div class="kedr-infrastructure-overlay">
<h3 class="kedr-infrastructure-title">Активности</h3>
<p class="kedr-infrastructure-description">Лазертаг, скалодром, творческие мастерские и спорт.</p>
</div>
</div>
</div>
</section>

<!-- Booking Form -->
<section class="kedr-booking-section" style="display: none;">
<div class="kedr-booking-form">
<h2 class="kedr-form-title">Оставить заявку</h2>
<form class="kedr-form" method="post" action="">
<div class="kedr-form-group">
<label class="kedr-form-label">Ваше имя</label>
<input class="kedr-form-input" type="text" placeholder="Иван Иванов" name="name" required/>
</div>
<div class="kedr-form-group">
<label class="kedr-form-label">Телефон</label>
<input class="kedr-form-input" type="tel" placeholder="+7 (999) 000-00-00" name="phone" required/>
</div>
<div class="kedr-form-group">
<label class="kedr-form-label">Возраст ребенка</label>
<input class="kedr-form-input" type="number" min="7" max="16" placeholder="Например: 10" name="age" required/>
</div>
<div class="kedr-form-checkbox-group">
  <label class="kedr-form-checkbox-label">
    <input type="checkbox" id="kedr-policy-agree" class="kedr-form-checkbox" required />
    <span>Я соглашаюсь с <a href="/privacy-policy" target="_blank" class="kedr-form-policy-link">политикой обработки персональных данных</a></span>
  </label>
</div>
<button class="kedr-form-submit" type="submit" disabled>
    Отправить заявку
</button>
</form>
</div>
</section>

<!-- Blocks Top -->
{if $blocks.top}
<div class="kedr-container">
    {foreach item=block from=$blocks.top}
    <div class="kedr-block kedr-block-top">{$block.block_content}</div>
    {/foreach}
</div>
{/if}

<!-- Blocks Center -->
{if $blocks.center}
<div class="kedr-container">
    {foreach item=block from=$blocks.center}
    <div class="kedr-block kedr-block-center">
        {if $block.block_displayname}<h2 class="kedr-block-title">{$block.block_displayname}</h2>{/if}
        {$block.block_content}
    </div>
    {/foreach}
</div>
{/if}

<!-- Blocks Bottom -->
{if $blocks.bottom}
<div class="kedr-container">
    {foreach item=block from=$blocks.bottom}
    <div class="kedr-block kedr-block-bottom">
        {if $block.block_displayname}<h2 class="kedr-block-title">{$block.block_displayname}</h2>{/if}
        {$block.block_content}
    </div>
    {/foreach}
</div>
{/if}

{else}
<!-- Blocks Top -->
{if $blocks.top}
<div class="kedr-container">
    {foreach item=block from=$blocks.top}
    <div class="kedr-block kedr-block-top">{$block.block_content}</div>
    {/foreach}
</div>
{/if}

<!-- Breadcrumbs -->
{if $module_browsein}
<div class="kedr-container">
    <div class="browsein">
        <div class="browsein">{browsein date=$module_browsein show_last=false href_last=true}</div>
        <h1>{browsein date=$module_browsein lastname_only = true}</h1>
    </div>
</div>
{/if}

<!-- Blocks Center -->
{if $blocks.center}
<div class="kedr-container">
    {foreach item=block from=$blocks.center}
    <div class="kedr-block kedr-block-center">
        {if $block.block_displayname}<h2 class="kedr-block-title">{$block.block_displayname}</h2>{/if}
        {$block.block_content}
    </div>
    {/foreach}
</div>
{/if}

<!-- Module Content -->
{if $module_content}
<div class="kedr-container">
    <div class="kedr-module-content">{$module_content}</div>
</div>
{/if}

<!-- Blocks Bottom -->
{if $blocks.bottom}
<div class="kedr-container">
    {foreach item=block from=$blocks.bottom}
    <div class="kedr-block kedr-block-bottom">
        {if $block.block_displayname}<h2 class="kedr-block-title">{$block.block_displayname}</h2>{/if}
        {$block.block_content}
    </div>
    {/foreach}
</div>
{/if}
{/if}

</main>

<!-- Footer -->
<footer class="kedr-footer">
<div class="kedr-footer-inner">
<!-- Brand -->
<div class="kedr-footer-brand">
<span class="kedr-footer-brand-name">Кедр</span>
<p class="kedr-footer-copyright">
    © {$smarty.now|date_format:"%Y"} ДОЛ «Кедр». Все права защищены.
</p>
<a class="kedr-footer-policy" href="/privacy-policy">Политика обработки персональных данных</a>
</div>

<!-- Links Group 1 -->
<div class="kedr-footer-links">
<a class="kedr-footer-link" href="/about">О лагере</a>
<a class="kedr-footer-link" href="/smeny-i-putevki">Смены и путёвки</a>

<a class="kedr-footer-link" href="/programma">Программа</a>
<a class="kedr-footer-link" href="/programma">Документы</a>
</div>

<!-- Links Group 2 (Contacts & Social) -->
<div class="kedr-footer-links">
<a class="kedr-footer-link" href="/contacts">Контакты</a>
<div class="kedr-footer-socials">
  <!-- Telegram -->
  <a href="https://t.me/ostrovdetstvar" target="_blank" rel="noopener" title="Telegram" class="kedr-social-link">
    <svg class="kedr-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
  </a>
  <!-- VKontakte -->
  <a href="https://vk.ru/kedrostrovdetsva" target="_blank" rel="noopener" title="ВКонтакте" class="kedr-social-link">
    <svg class="kedr-social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.391 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.525-2.05-1.727-1.033-1-1.49-1.135-1.744-1.135-.356 0-.458.102-.458.593v1.575c0 .424-.135.678-1.253.678-1.846 0-3.896-1.118-5.335-3.202C4.624 10.857 4 8.756 4 8.316c0-.254.102-.491.593-.491h1.744c.44 0 .61.203.78.678.847 2.456 2.27 4.607 2.86 4.607.22 0 .322-.102.322-.66V9.721c-.068-1.186-.695-1.287-.695-1.71 0-.203.17-.407.44-.407h2.744c.372 0 .508.203.508.643v3.473c0 .372.17.508.271.508.22 0 .407-.136.813-.542 1.254-1.406 2.151-3.574 2.151-3.574.119-.254.322-.491.763-.491h1.744c.525 0 .644.27.525.643-.22 1.017-2.354 4.031-2.354 4.031-.186.305-.254.44 0 .78.186.254.796.779 1.203 1.253.745.847 1.32 1.558 1.473 2.05.17.49-.085.744-.576.744z"/></svg>
  </a>
</div>

</div>

<!-- Developer Credit -->
<div class="kedr-footer-developer">
<a class="kedr-developer-link" href="https://nskdesign.ru" target="_blank" rel="noopener">
    Создание сайтов NSK Design
</a>
</div>
</div>
</footer>

<!-- Back to Top Button -->
<button id="kedr-back-to-top" class="kedr-back-to-top" aria-label="Наверх">
  <span class="material-symbols-outlined">arrow_upward</span>
</button>


<div class="col-sm-4" id="cookie-banner" style="display: none; position: fixed; bottom: 20px; /*! left: 20px; */ right: 0px; background: #333; color: #fff; padding: 15px 25px; border-radius: 8px; font-family: sans-serif; font-size: 14px; z-index: 9999; box-shadow: 0 4px 15px rgba(0,0,0,0.3); align-items: center; justify-content: space-between; flex-wrap: wrap;margin: 20px;">
    <span style="margin-right: 15px; margin-bottom: 10px;">
        Мы используем файлы cookie для вашего удобства. Оставаясь на сайте, вы соглашаетесь с нашей 
        <a href="/privacy-policy" style="text-decoration: underline;">Политикой конфиденциальности</a>.
    </span>
    <button id="cookie-accept" style="border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">ОК</button>
</div>
{literal}
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function() {
        var cookieName = "cookie_consent_accepted";
        var banner = document.getElementById('cookie-banner');
        var acceptBtn = document.getElementById('cookie-accept');

        if (!banner) return;

        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        if (!getCookie(cookieName)) {
            banner.style.display = 'flex';
        }

        if (acceptBtn) {
            acceptBtn.onclick = function() {
                var date = new Date();
                date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));

                document.cookie = cookieName + "=true; expires=" + date.toUTCString() + "; path=/; SameSite=Lax";

                banner.style.display = 'none';
            };
        }
    })();
});
</script>

<script>
(function() {
    function initMenu() {
        var menuToggle = document.querySelector('.kedr-mobile-menu-toggle');
        var mobileNav = document.querySelector('.kedr-mobile-nav');
        
        if (menuToggle && mobileNav) {
            menuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                mobileNav.classList.toggle('kedr-mobile-nav-open');
            });
            
            // Close menu if clicked outside
            document.addEventListener('click', function(e) {
                if (!mobileNav.contains(e.target) && !menuToggle.contains(e.target)) {
                    mobileNav.classList.remove('kedr-mobile-nav-open');
                }
            });
        }
    }

    function initBackToTop() {
        var backToTopBtn = document.getElementById('kedr-back-to-top');
        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > window.innerHeight) {
                    backToTopBtn.classList.add('visible');
                } else {
                    backToTopBtn.classList.remove('visible');
                }
            });

            backToTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    function initBookingForm() {
        var checkbox = document.getElementById('kedr-policy-agree');
        var submitBtn = document.querySelector('.kedr-form-submit');
        if (checkbox && submitBtn) {
            // Set initial state
            submitBtn.disabled = !checkbox.checked;
            
            checkbox.addEventListener('change', function() {
                submitBtn.disabled = !checkbox.checked;
            });
        }
    }

    function initAll() {
        initMenu();
        initBackToTop();
        initBookingForm();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=109567359', 'ym');

    ym(109567359, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/109567359" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->



{/literal}
</body>
</html>
