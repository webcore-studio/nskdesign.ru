
{assign var="ver" value="2.0"}
{*assign var="ver" value="2.1.48"*}

		<!-- jQuery and jQuery UI (REQUIRED) -->
		
		<link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/fm/scripts/elfinder-{$ver}/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/fm/scripts/elfinder-{$ver}/css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="/modules/fm/scripts/elfinder-{$ver}/js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="/modules/fm/scripts/elfinder-{$ver}/js/i18n/elfinder.ru.js"></script>

		{literal}
		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : '/index.php?module=fm&type=ajax&func=connector',  // connector URL (REQUIRED)
					lang: 'ru',             // language (OPTIONAL)
				}).elfinder('instance');
			});
		</script>
		{/literal}
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div class="item">
			<div id="elfinder"></div>
		</div>
		<a href="/index.php?module=fm&type=admin&func=modifyconfig" class="btn">Настройки</a>
		
