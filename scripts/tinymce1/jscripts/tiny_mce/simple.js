tinyMCE.init({
    mode : "specific_textareas",
    editor_selector : "mceEditor",
    language : "ru",

		theme : "advanced",
       plugins : "table,inlinepopups",
		 // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,|,table,removeformat,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

		 invalid_elements : "xml,style",
		 convert_urls : false,

        // Drop lists for link/image/media/template dialogs
   		file_browser_callback : "fileBrowserCallBack",
			cleanup_callback : "myCustomCleanup",

		paste_auto_cleanup_on_paste : true,
    		paste_convert_headers_to_strong : false,
    		paste_strip_class_attributes : "all",
    		paste_remove_spans : false,
    		paste_remove_styles : false		

       
});

	function fileBrowserCallBack(field_name, url, type, win) {
	var connector = "/scripts/tinymce/jscripts/tiny_mce/filemanager/browser.html?Connector=connectors/php/connector.php";
		var enableAutoTypeSelection = true;
		
		var cType;
		tinyfck_field = field_name;
		tinyfck = win;
		
		switch (type) {
			case "image":
				cType = "images";
				break;
			case "flash":
				cType = "flash";
				break;
			case "media":
				cType = "media";
				break;
			case "file":
				cType = "file";
				break;
			default: cType = "file";
				break;
		}
		
		if (enableAutoTypeSelection && cType) {
			connector += "&Type=" + cType;
		}
		
		window.open(connector, "tinyfck", "modal,width=600,height=400");
    }
	 
	 function myCustomCleanup(type, value) {
        switch (type) {
                case "get_from_editor":
                        break;
                case "insert_to_editor":
					 
								value = value.replace(/<!--[\s\S]*-->/g, "");
								value = value.replace(/&lt;!--[\s\S]*--&gt;/g, "");
                        // Do custom cleanup code here
                        break;
                case "submit_content":
                        break;
                case "get_from_editor_dom":
                        break;
                case "insert_to_editor_dom":
								value = value.replace(/<!--[\s\S]*-->/g, "");
								value = value.replace(/&lt;!--[\s\S]*--&gt;/g, "");
                        // Do custom cleanup code here
                        break;
                case "setup_content_dom":
                        break;
                case "submit_content_dom":
                        break;
        }

        return value;
}

