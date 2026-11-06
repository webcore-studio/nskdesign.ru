{debug}
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="ru">
	<meta http-equiv="Content-Style-Type" content="text/css">
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

	<link rel="stylesheet" href="/themes/{$user_theme}/style/common.css" type="text/css" media="all">
	<link rel="stylesheet" href="/themes/{$user_theme}/style/style.css" type="text/css" media="all">
	<link rel="stylesheet" href="/themes/{$user_theme}/style/bootstrap.css" type="text/css" media="all">

	<script type="text/javascript" src="/scripts/java_scripts/20091120.js"></script>
	<script type="text/javascript" src="/modules/contact/js/contact.js"></script>

	<script src="/scripts/java_scripts/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/scripts/java_scripts/jquery.liTranslit.js"></script>


	<link rel="stylesheet" href="/scripts/prettyPhoto_3.1.5/css/prettyPhoto.css" type="text/css" media="screen"
		charset="utf-8" />
	<script src="/scripts/prettyPhoto_3.1.5/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" src="/scripts/java_scripts/bootstrap.js"></script>
	<script type="text/javascript" src="/scripts/java_scripts/functions.js"></script>


	<script type="text/javascript" src="/scripts/vanadium/vanadium.js"></script>
	<link rel="StyleSheet" href="/scripts/vanadium/vanadium.css" type="text/css">


</head>

{if $blocks.left && $blocks.right}
{assign var="center_class" value="span6"}
{elseif $blocks.left || $blocks.right}
{assign var="center_class" value="span9"}
{else}
{assign var="center_class" value="span12"}
{/if}

<body>
	<div id="back_to_top" style="position: fixed; bottom: 10px; right: 20px; display: none;">
		<a href="#">наверх</a>
	</div>
	{include file="themes/$user_theme/pages/ajax.tpl"}
	{usergetvars assign=uservars}
	{$blocks.exact.2.block_content}
	<div id="wrap">
		<div class="container" style="position:relative;">

			<header>
				{mod_getvar modname='SYS_config' name='site_logo' assign="site_logo"}
				{mod_getvar modname='SYS_config' name='site_url' assign="site_url"}
				{if !$site_url}
				{assign var="site_url" value="/"}
				{/if}
				{if $site_logo}
				<a href="{$site_url}"><img src="{$site_logo}" style="margin:20px 0;"></a>
				{else}
				<h1><a href="{$site_url}">{mod_getvar|escape modname='SYS_config' name='site_name'}</a></h1>
				{/if}
			</header>


			<div class="row">
				<div class="span12">
					{foreach item=block from=$blocks.top}
					<div class="block">{$block.block_content}</div>
					{/foreach}
				</div>
			</div>

			<div class="row">
				{if $blocks.left}
				<div class="span3">
					{foreach item=block from=$blocks.left}
					<div class="item block">
						<h2><span>{$block.block_displayname}</span></h2>{$block.block_content}
					</div>
					{/foreach}
				</div>
				{/if}

				<div class="{$center_class}">

					{if $blocks.center}
					{foreach item=block from=$blocks.center}
					<div class="item block block_center">
						{if $block.block_displayname}<h2><span>{$block.block_displayname}</span></h2>{/if}
						{$block.block_content}
					</div>
					{/foreach}
					{/if}


					{if $module_browsein}
					<div class="browsein">
						<div class="browsein">{browsein date=$module_browsein show_last=false href_last=true}</div>
						<h1>{browsein date=$module_browsein lastname_only = true}</h1>
					</div>
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
				<div class="span3">
					{foreach item=block from=$blocks.right}
					<div class="item block">
						<h2><span>{$block.block_displayname}</span></h2>{$block.block_content}
					</div>
					{/foreach}
				</div>
				{/if}
			</div>
		</div>

		<div id="push"></div>
	</div>



	<div id="footer">
		<div class="container">
			<div>
				<footer>
					<p class="pull-left">&#169; {if
						$smarty.now|date_format:"%Y"!=2016}2016-{$smarty.now|date_format:"%Y"}{else}{$smarty.now|date_format:"%Y"}{/if}
						{mod_getvar|escape modname='SYS_config' name='site_name'}</p>
					<p class="pull-right"><a href="http://webcore.ru/" target="_blank">Создание сайта</a> &mdash;
						Webcore</p>
				</footer>
			</div>
		</div>
	</div>


</body>

</html>