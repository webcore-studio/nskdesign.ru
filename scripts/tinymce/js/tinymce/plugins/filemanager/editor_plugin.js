(function() {

	tinymce.create('tinymce.plugins.FileManager', 
	{

		init : function(editor, url)
		{

			function filemanager_onMessage(event){
				if(editor.settings.external_filemanager_path.toLowerCase().indexOf(event.origin.toLowerCase()) === 0){
					if(event.data.sender === 'filemanager'){
						tinymce.activeEditor.execCommand('mceInsertContent', false, event.data.html); 
						tinymce.activeEditor.windowManager.close( tinymce.activeEditor.windowManager.params.mce_window_id );

						// Remove event listener for a message from Filemanager
						if(window.removeEventListener){
							window.removeEventListener('message', filemanager_onMessage, false);
						} else {
							window.detachEvent('onmessage', filemanager_onMessage);
						}
					}
				}
			}

			function filemanager_onMessage(event){
				if(editor.settings.external_filemanager_path.toLowerCase().indexOf(event.origin.toLowerCase()) === 0){
					if(event.data.sender === 'filemanager'){
						tinymce.activeEditor.windowManager.params.setUrl(event.data.url);
						tinymce.activeEditor.windowManager.close(tinymce.activeEditor.windowManager.params.mce_window_id );

						// Remove event listener for a message from Filemanager
						if(window.removeEventListener){
							window.removeEventListener('message', filemanager_onMessage, false);
						} else {
							window.detachEvent('onmessage', filemanager_onMessage);
						}
					}
				}
			}

			// File manager callback
			function openmanager() 
			{
				editor.focus(true);
				var title="FileManager";
				if (typeof editor.settings.filemanager_title !== "undefined" && editor.settings.filemanager_title) {
					title=editor.settings.filemanager_title;
				}
				var akey="key";
				if (typeof editor.settings.filemanager_access_key !== "undefined" && editor.settings.filemanager_access_key) {
					akey=editor.settings.filemanager_access_key;
				}
				var sort_by="";
				if (typeof editor.settings.filemanager_sort_by !== "undefined" && editor.settings.filemanager_sort_by) {
					sort_by="&sort_by="+editor.settings.filemanager_sort_by;
				}
				var descending="false";
				if (typeof editor.settings.filemanager_descending !== "undefined" && editor.settings.filemanager_descending) {
					descending=editor.settings.filemanager_descending;
				}
				var fldr="";
				if (typeof editor.settings.filemanager_subfolder !== "undefined" && editor.settings.filemanager_subfolder) {
					fldr="&fldr="+editor.settings.filemanager_subfolder;
				}
				var crossdomain="";
				if (typeof editor.settings.filemanager_crossdomain !== "undefined" && editor.settings.filemanager_crossdomain) {
					crossdomain="&crossdomain=1";

					// Add handler for a message from Filemanager
					if(window.addEventListener){
						window.addEventListener('message', filemanager_onMessage, false);
					} else {
						window.attachEvent('onmessage', filemanager_onMessage);
					}
				}

				win = editor.windowManager.open({
					title: title,
					file: editor.settings.external_filemanager_path+'/index.php?module=fm&type=ajax&func=tinymce',
					width: 860,
					height: 570,
					inline: 1,
					resizable: true,
					maximizable: true
				});
			}

			editor.settings.file_browser_callback = filemanager;
			
			function filemanager (id, value, type, win) {
				// DEFAULT AS FILE
				urltype=2;
				if (type=='image') { urltype=1; }
				if (type=='media') { urltype=3; }
				var title="FileManager";
				if (typeof editor.settings.filemanager_title !== "undefined" && editor.settings.filemanager_title) {
					title=editor.settings.filemanager_title;
				}
				var akey="key";
				if (typeof editor.settings.filemanager_access_key !== "undefined" && editor.settings.filemanager_access_key) {
					akey=editor.settings.filemanager_access_key;
				}
				var sort_by="";
				if (typeof editor.settings.filemanager_sort_by !== "undefined" && editor.settings.filemanager_sort_by) {
					sort_by="&sort_by="+editor.settings.filemanager_sort_by;
				}
				var descending="false";
				if (typeof editor.settings.filemanager_descending !== "undefined" && editor.settings.filemanager_descending) {
					descending=editor.settings.filemanager_descending;
				}
				var fldr="";
				if (typeof editor.settings.filemanager_subfolder !== "undefined" && editor.settings.filemanager_subfolder) {
					fldr="&fldr="+editor.settings.filemanager_subfolder;
				}
				var crossdomain="";
				if (typeof editor.settings.filemanager_crossdomain !== "undefined" && editor.settings.filemanager_crossdomain) {
					crossdomain="&crossdomain=1";

					// Add handler for a message from Filemanager
					if(window.addEventListener){
						window.addEventListener('message', filemanager_onMessage, false);
					} else {
						window.attachEvent('onmessage', filemanager_onMessage);
					}
				}

				tinymce.activeEditor.windowManager.open({
					title: title,
					file: editor.settings.external_filemanager_path+'/index.php?module=fm&type=ajax&func=tinymce',
					width: 860,  
					height: 570,
					resizable: true,
					maximizable: true,
					inline: 1
					}, {
					setUrl: function (url) {
						var fieldElm = win.document.getElementById(id);
						fieldElm.value = editor.convertURL(url);
						if ("fireEvent" in fieldElm) {
							fieldElm.fireEvent("onchange")
						} else {
							var evt = document.createEvent("HTMLEvents");
							evt.initEvent("change", false, true);
							fieldElm.dispatchEvent(evt);
						}
					}
				});
			};

			// Register buttons
			editor.addButton('filemanager', 
			{
				title : 'Browse files',
				image : url + '/img/insertfile.gif',
				shortcut: 'Ctrl+E',
                onclick: openmanager
			});
		}
		
	});

	// Register plugin
	tinymce.PluginManager.add('filemanager', tinymce.plugins.FileManager);
})();
