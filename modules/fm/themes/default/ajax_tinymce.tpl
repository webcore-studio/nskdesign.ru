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
		
		<script type="text/javascript" src="/scripts/tinymce/js/tinymce/tinymce.js"></script>
		<script type="text/javascript" src="/scripts/tinymce/js/tinymce/plugins/filemanager/editor_plugin.js"></script>
		
		{literal}

		
		<script type="text/javascript">
		  var FileBrowserDialogue = {
			init: function() {
			  // Here goes your code for setting your custom things onLoad.
			},
			mySubmit: function (URL) {
			  // pass selected file path to TinyMCE
			  parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

			  // force the TinyMCE dialog to refresh and fill in the image dimensions
			  var t = parent.tinymce.activeEditor.windowManager.windows[0];
			  t.find('#src').fire('change');

			  // close popup window
			  parent.tinymce.activeEditor.windowManager.close();
			}
		  }

		  $().ready(function() {
			var elf = $('#elfinder').elfinder({
			  // set your elFinder options here
			url : '/index.php?module=fm&type=ajax&func=connector',  // connector URL (REQUIRED)
			lang: 'ru',             // language (OPTIONAL)
			  getFileCallback: function(file) { // editor callback
				// file.url - commandsOptions.getfile.onlyURL = false (default)
				// file     - commandsOptions.getfile.onlyURL = true
				FileBrowserDialogue.mySubmit(file.url); // pass selected file path to TinyMCE 
			  },
			  commandsOptions : {
						getfile : {
							onlyURL  : false,
						}
					}
			}).elfinder('instance');      
		  });
		</script>
		{/literal}
		<div id="elfinder"></div>
