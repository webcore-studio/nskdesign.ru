{*debug*}
<!doctype html>
<html class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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


        <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


<link rel="stylesheet" href="/themes/{$user_theme}/style/common.css" type="text/css" media="all">
<link rel="stylesheet" href="/themes/{$user_theme}/style/style.css" type="text/css" media="all">

<link rel="stylesheet" href="/themes/{$user_theme}/style/icon-bootstrap.css" type="text/css" media="all">

<link rel="stylesheet" href="/scripts/prettyPhoto_3.1.5/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />

<!--Logo -->
<link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">



<link rel="StyleSheet" href="/scripts/vanadium/vanadium.css" type="text/css">
<link rel="icon" href="/favicon.ico">		

<!-- Optional JavaScript -->
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script type="text/javascript" src="/scripts/java_scripts/20091120.js"></script>


<script type="text/javascript" src="/modules/edu/js/script.js"></script>

<script type="text/javascript" src="/modules/contact/js/contact.js"></script>



<script type="text/javascript" src="/scripts/java_scripts/jquery.liTranslit.js"></script>


<script type="text/javascript" src="/scripts/java_scripts/bootstrap.js"></script>



  </head>
{mod_getvar modname='SYS_config' name='site_logo' assign="site_logo"}
{mod_getvar modname='SYS_config' name='site_url' assign="site_url"} 

 <body class="d-flex flex-column h-100">
    <!-- Begin page content -->
	
	<div id="back_to_top" style="position: fixed; bottom: 10px; right: 20px; display: none;">
			<a href="#">наверх</a>
		</div>
	{include file="themes/$user_theme/pages/ajax.tpl"}
	{usergetvars assign=uservars}
	{$blocks.exact.2.block_content}
	
<header class="sticky-top bg-design">
    <div class="container" style="position:relative; padding-top: .5rem;">
		<div class="logofont"><a href="{$site_url}">{mod_getvar|escape modname='SYS_config' name='site_name'}</a></div>
		<div class="float-right">	{$blocks.exact.1.block_content}</div>
	</div>
</header>


  
<main role="main" class="flex-shrink-0">

<div class="MainHeader">
	<div class="container">

		<header class="row justify-content-lg-between">
	<div class="col-lg-7" style="display: flex;margin-top: 20px;">		

			
			{if !$site_url}
				{assign var="site_url" value="/"}
			{/if}
			{if $site_logo}
			<div style="padding-right: 20px;">
				<a href="{$site_url}"><img src="{$site_logo}" style="width:70px;"></a>
			</div>
				<div>{mod_getvar|escape modname='SYS_config' name='site_slogan'}</div>
			{else}
				
			{/if}
	</div>

{$blocks.exact.5.block_content}
{$blocks.exact.6.block_content}

	</header>
</div>
</div>	
	
<div class="container">
		<div class = "row">
			<div class="col-sm">
				{foreach item=block from=$blocks.top}
					<div class="block">{$block.block_content}</div>
				{/foreach}
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm">
				{if $module_browsein}
					<div class="browsein">
						<div class="browsein">{browsein date=$module_browsein show_last=false href_last=true}</div>
						<h1>{browsein date=$module_browsein lastname_only = true}</h1>
					</div>
				{/if}
			</div>
		</div>
		
		<div class = "row">
			{if $blocks.left}
				<div class="col-sm">
					{foreach item=block from=$blocks.left}
						<div class="item block"><h2><span>{$block.block_displayname}</span></h2>{$block.block_content}</div>
					{/foreach}
				</div>
			{/if}
			
			<div class="col-sm">

				{if $blocks.center}
					{foreach item=block from=$blocks.center}
						<div class="item block block_center">
							{if $block.block_displayname}<h2><span>{$block.block_displayname}</span></h2>{/if}
							{$block.block_content}
						</div>
					{/foreach}
				{/if}

		
				
				
				{if $module_content}
					<div class="content item">{$module_content}</div>
				{/if}
				
				<div>
					{foreach item=block from=$blocks.bottom}
						<div class="item block">
							<h2>{$block.block_displayname}</h2>
							{$block.block_content}
						</div>
					{/foreach}
				</div>
				
			</div>
			
			{if $blocks.right}
				<div class="col-sm">
					{foreach item=block from=$blocks.right}
						<div class="item block"><h2><span>{$block.block_displayname}</span></h2>{$block.block_content}</div>
					{/foreach}
				</div>
			{/if}
			
		</div>
	</div> 
	
</main>	
	<div id="push"></div>


<footer class="footer mt-auto py-3" style="background-color: #f5f5f5;">

  <div class="container">
  	
    <p class="pull-left">&#169; {if $smarty.now|date_format:"%Y"!=2019}2003 - 
	{$smarty.now|date_format:"%Y"}{else}{$smarty.now|date_format:"%Y"}{/if} {mod_getvar|escape modname='SYS_config' name='site_name'}
	</p>
	
  </div>
  
</footer>
{literal}
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(158547, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/158547" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
{/literal}
</body>
</html>
