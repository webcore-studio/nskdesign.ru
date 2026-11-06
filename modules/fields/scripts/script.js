$(document).ready(function () {

	$("a.field_value").click(function() {
		var fld_value = $(this).html();
		data_arr={
			'module':'fields',
			'type':'ajax',
			'func':'filter',
			'fld_value':fld_value,		}
		
		$.ajax({
			type: "POST",
			url: "/index.php",
			data:  data_arr,
			dataType : "html", 
			success: function (html) {
				
				$('embed, object, select').css({ 'visibility' : 'hidden' });
				$('body').append('<div id="jquery-overlay"></div>');
			// Get page sizes
			var arrPageSizes = ___getPageSize();

			$('#jquery-overlay').css({
				backgroundColor:	'#000',
				opacity:			0.4,
				width:				arrPageSizes[0],
				height:				arrPageSizes[1]
			}).fadeIn();	


			$(window).resize(function() {
				// Get page sizes
				var arrPageSizes = ___getPageSize();
				// Style overlay and show it
				$('#jquery-overlay').css({
					width:		arrPageSizes[0],
					height:		arrPageSizes[1]
				});
				// Get page scroll
			});
				$("body").append("<div id='fld_filter' class='infobox'><a class='close' href='#'>x</a></div>");
				$("#fld_filter").append(html);
				return false;
				
			},
			error: function () {
            alert ('Ошибка!'); return false;
			}
	
	
		})
		return false;
	})
	
	$("#.infobox .close").live("click",function() {
		$(".infobox").fadeOut(function() { $(".infobox").remove(); });
		$('embed, object, select').css({ 'visibility' : 'visible' });
		$('#jquery-overlay').fadeOut(function() { $('#jquery-overlay').remove(); });
		return false;
})

})




		function ___getPageSize() {
			var xScroll, yScroll;
			if (window.innerHeight && window.scrollMaxY) {	
				xScroll = window.innerWidth + window.scrollMaxX;
				yScroll = window.innerHeight + window.scrollMaxY;
			} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
			} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
			}
			var windowWidth, windowHeight;
			if (self.innerHeight) {	// all except Explorer
				if(document.documentElement.clientWidth){
					windowWidth = document.documentElement.clientWidth; 
				} else {
					windowWidth = self.innerWidth;
				}
				windowHeight = self.innerHeight;
			} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			} else if (document.body) { // other Explorers
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}	
			// for small pages with total height less then height of the viewport
			if(yScroll < windowHeight){
				pageHeight = windowHeight;
			} else { 
				pageHeight = yScroll;
			}
			// for small pages with total width less then width of the viewport
			if(xScroll < windowWidth){	
				pageWidth = xScroll;		
			} else {
				pageWidth = windowWidth;
			}
			arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight);
			return arrayPageSize;
		};

	