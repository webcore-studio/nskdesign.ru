function setfields (usertype) {

	if (usertype=='f' || !usertype)
	{
		$("#bank_details").hide();
		$("#bank_details input").removeClass("required");
		
		$("#legal_address input").removeClass("required");
		$("#user_passport").parent().parent("tr").show();
		$("#user_passport").addClass("required");
		$("input[name=user_legalname]").parent().parent("tr").hide();
		$("input[name=user_legalname]").removeClass("required");
		$("input[name=user_fax]").parent().parent("tr").hide();
		$("label#for_user_displayname").html('ФИО');
		//$("span#bank_for_ip").hide();
		
	}
	
	if (usertype=='u')
	{
		$("#user_passport").parent().parent("tr").hide();
		$("#user_passport").removeClass("required");
		$("#bank_details").show();
		$("#bank_details input").addClass("required");
		$("#legal_address").show();
		$("#legal_address input").addClass("required");
		$("input[name*=district]").removeClass("required");
		$("input[name*=office]").removeClass("required");
		$("input[name=user_legalname]").parent().parent("tr").show();
		$("input[name=user_legalname]").addClass("required");
		$("input[name=user_fax]").parent().parent("tr").show();
		$("label#for_user_displayname").html('Контактное лицо');
		//$("span#bank_for_ip").show();
	}
	
	if (usertype=='ip')
	{
		$("#user_passport").parent().parent("tr").show();
		$("#user_passport").addClass("required");
		$("#bank_details").show();
		//$("#bank_details input").removeClass("required");
		$("#bank_details input").addClass("required");
		//$("span#bank_for_ip").show();
		$("#legal_address").show();
		$("#legal_address input").addClass("required");
		$("input[name*=district]").removeClass("required");
		$("input[name*=office]").removeClass("required");
		$("input[name=user_fax]").parent().parent("tr").show();
		$("input[name=user_legalname]").parent().parent("tr").hide();
		$("input[name=user_legalname]").addClass("required");
		$("label#for_user_displayname").html('ФИО');
		
	}
}

$(document).ready(function() {
	setfields();
	$("input[name=user_type]").click(function() {
	setfields($(this).val());

	
	})
})