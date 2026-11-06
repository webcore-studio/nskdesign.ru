
function comment_create_new ()
{
	$("#loading-layer-text").html("Публикация отзыва");
	$("#loading-layer").show();
	 var data= $("#comment_new_form").serialize();
	  $.ajax({
				type: "POST",
				url: "/index.php?module=comment&type=ajax&func=create",
				data: data,
				success: function (html) {
					$("#loading-layer").hide();
					$("#comment_new_wrap").slideUp();
					$("#comm_content").val("");
					$("#mod_comment_list").html(html);
				}
			})
	 return false;
}