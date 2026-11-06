		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/fm/scripts/elfinder-2.0/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/modules/fm/scripts/elfinder-2.0/css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="/modules/fm/scripts/elfinder-2.0/js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="/modules/fm/scripts/elfinder-2.0/js/i18n/elfinder.ru.js"></script>

		{literal}
		<style>
			.elfinder-cwd-file {
				cursor:pointer !important;
			}
		</style>
		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : '/index.php?module=fm&type=ajax&func=connector',  // connector URL (REQUIRED)
					lang: 'ru',             // language (OPTIONAL)
					onlyMimes: ["image"],
					handlers : {
						 select : function(event, elfinderInstance) {
							var selected = event.data.selected;

							if (selected.length) {
								if (elfinderInstance.file(selected[0]).mime != 'directory') {
									filepath = "/files/"+elfinderInstance.path(selected[0]);
									setImage(filepath);
									close();
									//$("input[name=site_logo]").val(filepath);
								}
							}
						}
					}
				}).elfinder('instance');
			});
		</script>
		{/literal}
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
