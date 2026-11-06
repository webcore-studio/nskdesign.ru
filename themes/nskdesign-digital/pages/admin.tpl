<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Управление сайтом — NSK Design</title>
  <link rel="stylesheet" href="/themes/develope/style/bootstrap.css">
  <link rel="stylesheet" href="/themes/develope/style/common.css">
  <link rel="stylesheet" href="/themes/nskdesign-digital/style/admin.css">
  <script src="/scripts/java_scripts/jquery-1.9.1.min.js"></script>
  <script src="/scripts/java_scripts/20091120.js"></script>
  <script src="/scripts/java_scripts/jquery.liTranslit.js"></script>
  <script src="/scripts/java_scripts/bootstrap.js"></script>
  <script src="/scripts/java_scripts/functions.js"></script>
  <script src="/modules/contact/js/contact.js"></script>
  <link rel="stylesheet" href="/scripts/prettyPhoto_3.1.5/css/prettyPhoto.css">
  <script src="/scripts/prettyPhoto_3.1.5/js/jquery.prettyPhoto.js"></script>
  <link rel="stylesheet" href="/scripts/vanadium/vanadium.css">
  <script src="/scripts/vanadium/vanadium.js"></script>
  <script src="/themes/nskdesign-digital/js/theme.js" defer></script>
  {config_css}
</head>
<body>
{include file="themes/nskdesign-digital/pages/admin-bar.tpl"}
<header class="admin-heading"><a href="/">NSK DESIGN <small>Перейти на сайт ↗</small></a></header>
{include file="themes/nskdesign-digital/pages/ajax.tpl"}
<main class="container-fluid admin-workspace">
  {if !empty($module_browsein)}<div class="breadcrumb">{browsein date=$module_browsein show_last=true href_last=false}</div>{/if}
  {$module_content}
</main>
</body>
</html>
