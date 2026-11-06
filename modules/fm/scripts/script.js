var $ = jQuery;
$(document).ready(function() {
    var dropZone = $('#dropZone'),
		maxFileSize = 1000000; // максимальный размер файла - 1 мб.
		
	if (typeof(window.FileReader) == 'undefined') {
		dropZone.text('Не поддерживается браузером!');
		dropZone.addClass('error');
	}
	
	dropZone[0].ondragover = function() {
		dropZone.removeClass('drop');
		dropZone.addClass('hover');
		return false;
	};
	dropZone[0].ondragleave = function() {
		dropZone.removeClass('hover');
		return false;
	};
	
	dropZone[0].ondrop = function(event) {
		
		event.preventDefault();
		dropZone.removeClass('hover');
		dropZone.addClass('drop');
		
		var modname = $("#modname").val();
		var item_id = $("#item_id").val();
		
		var files = event.dataTransfer.files;
		
		var formData = new FormData(),
		xhr = new XMLHttpRequest(),
		x;

		for(x = 0; x < files.length; x++) {
			formData.append('file[]', files[x]);
		}

		xhr.onload = function() {
			//var data = JSON.parse(this.responseText);
			//displayUploads(data);
		};
		xhr.upload.addEventListener('progress', uploadProgress, false);
		xhr.onreadystatechange = stateChange;
		xhr.open('POST', '/index.php?module=fm&type=ajax&func=upload');
		formData.append("modname", modname);
		formData.append("item_id", item_id);
		xhr.send(formData);
   
		
	};
	
	function uploadProgress(event) {
		var percent = parseInt(event.loaded / event.total * 100);
		dropZone.text('Загрузка: ' + percent + '%');
	}
	
	function stateChange(event) {
		if (event.target.readyState == 4) {
			if (event.target.status == 200) {
				if (event.target.responseText!="0") {
					dropZone.text('Загрузка успешно завершена!');
					$("#upload_result").append(event.target.responseText);
				}
				else {
					dropZone.text('Произошла ошибка!');
					dropZone.removeClass("drop").addClass('error');
				}
			} else {
				dropZone.text('Произошла ошибка!');
				dropZone.removeClass("drop").addClass('error');
			}
		}
	}

});