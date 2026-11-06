function contact_create(action, idform)
{
	
	if (idform == '')
	{
			idform = "contact_new_form";
	}
	
	if ($("#pp_full_res").length>0) {
		var form = $("#pp_full_res #contact_new_form");
		$("#pp_full_res #contact_new_form .required").removeClass("vanadium-invalid");
		var required = $("#pp_full_res #contact_new_form .required");
	}
	else {
		
		var form = $('#'+idform);
		
		$('#'+idform + " .required").removeClass("vanadium-invalid");
		var required = $('#'+idform + " .required");
	}
	
	var inv_count = 0;
	required.each(function() {
		if ($(this).val()=="") {
			$(this).addClass("vanadium-invalid");
			inv_count = inv_count+1;
		}
	})
	
	
	//console.info(idform);
	
	//console.info(inv_count);
	
	//console.info(action);
	
	if (inv_count > 0)
		return false;

	//$("body").append('<div class="pp_overlay"></div>');
	//$(".pp_overlay").css("opacity", "0.5").height($(document).height()).width($(document).width()).fadeIn("fast");
	
	//console.info(action);
	
	//Публикация
	if (action=='create')   {
	
		var str = form.serialize();
		
		idmess = ".mod_contact_sendf."+idform;
		
		//console.info(idmess);
	
		$.ajax({
			type: "POST",
			url: "/index.php?module=contact&type=ajax&func=create",
			data:  str,
			dataType : "html", 
			success: function (html) {
				$(".pp_overlay").fadeOut("fast").remove();
				if ($("#pp_full_res").length>0) {
					$("#pp_full_res  #mod_contact_sendf").html(html);
				}
				else {
					$(idmess).html(html);
				}
				
				if (idform == 'contact_popup_form')
				{
					//$('body').removeClass('is-modal-open overflow');
					//$( ".modal-list" ).removeClass("show overlay");
					$( ".m_modal" ).removeClass("show overlay");
					$(".m_60668_done").addClass('show');
					
				}
				else{
					form.hide();
				}
				
				
				//$( ".modal-list.show.overlay" ).hide();
				
				
				$("*[name=contact_displayname],*[name=contact_content]").val("");
				$("html, body").stop().animate({scrollLeft:0,scrollTop:$(idmess).offset.top}, 400);
				
				
				
				return false;
			},
			error: function (html) {
				$(".pp_overlay").fadeOut("fast").remove();
				alert ('Ошибка отправки формы.');
				return false;
			}
		})
	
  }
	
  return false;
  
 }
 
 function contact_captcha_update()
 {
	var theBigDay = new Date();
	var src="/index.php?module=contact&type=ajax&func=captcha_view&rand="+theBigDay.getTime()
	$("#captcha_img").attr("src", src);
 }