$(document).ready(function() {
	
	$('input,textarea').focus(function(){
		$(this).data('placeholder',$(this).attr('placeholder'))
		$(this).attr('placeholder','');
	})
	$('input,textarea').blur(function(){
		$(this).attr('placeholder',$(this).data('placeholder'));
	});

	$(window).scroll(function() { 
		var hh = getClientHeight();
		var hs = getClientSTop();
		var elem =  document.getElementById("header");
		var fl = getOffset(elem);
		if (hs*1 + hh*1 > fl["top"]*1) {
			$("#back_to_top").show();}
			
		if (hs*1  <= fl["top"]*1) { 
			$("#back_to_top").hide();
		}
			//return false;

	})
	
	$("#back_to_top>a").click(function() {
		$("html, body").stop().animate({scrollLeft:0,scrollTop:$("#header").offset().top}, 400);

		return false;
	})
	
	
	$("a[rel^='prettyPhoto']").prettyPhoto({
			allow_resize: false,
			overlay_gallery: false,
			social_tools:'',
			deeplinking:false
		});
	$('a.lightbox').prettyPhoto({
			allow_resize: false,
			overlay_gallery: false,
			social_tools:'',
			deeplinking:false
		});
	$('a[rel*=lightbox]').prettyPhoto({
			allow_resize: false,
			overlay_gallery: false,
			social_tools:'',
			deeplinking:false
		});
		
	$("input#mod_search_string").focusin(function() {
		$(this).removeAttr("placeholder");
	})
	
		$("input#mod_search_string").focusout(function() {
		$(this).attr("placeholder", "Поиск");
	})


	$("#cabinet>a").click(function() {
		if ($('#cabinet_list').is(":visible")) 
		{
			$("#cabinet_list").hide();
		}
		else
		{
			$("#cabinet_list").stop(true,true);
			$("#cabinet_list").show().delay('2000').queue(function() { $("#cabinet_list").hide();});
		}
			return false;
	})

	$("#cabinet_list").hover(
		function() {$("#cabinet_list").stop(true,true);},
		function() {$("#cabinet_list").hide()}
	);
	
	$("#cabinet").hover(
		function() {$("#cabinet_list").stop(true,true);},
		function() {}
	);

	
	$("#auth>a").click(function() {
		if ($('#ulogin').is(":visible")) 
		{
			$("#ulogin").hide();
		}
		else
		{
			$("#ulogin").show();
		}
			return false;
	})


	
	$("#ulogin .close").click(function() {
		$("#ulogin").hide();

	})

	
		
		
})


function getOffset(elem) 
{
    if (elem.getBoundingClientRect) {
        // "правильный" вариант
        return getOffsetRect(elem)
    } else {
        // пусть работает хоть как-то
        return getOffsetSum(elem)
    }
}

function getOffsetSum(elem) 
{
    var top=0, left=0
    while(elem) {
        top = top + parseInt(elem.offsetTop)
        left = left + parseInt(elem.offsetLeft)
        elem = elem.offsetParent
    }

    return {top: top, left: left}
}

function getOffsetRect(elem) 
{
    // (1)
    var box = elem.getBoundingClientRect()

    // (2)
    var body = document.body
    var docElem = document.documentElement

    // (3)
    var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop
    var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft

    // (4)
    var clientTop = docElem.clientTop || body.clientTop || 0
    var clientLeft = docElem.clientLeft || body.clientLeft || 0

    // (5)
    var top  = box.top +  scrollTop - clientTop
    var left = box.left + scrollLeft - clientLeft

    return { top: Math.round(top), left: Math.round(left) }
}

function getClientHeight() 
{
  //return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
 return document.documentElement.clientHeight;
}

//смешение по высоте
function getClientSTop()
{
return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop)
}





