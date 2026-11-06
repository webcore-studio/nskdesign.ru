function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
    }
	
	
function formCheck()
{

var elem=$(".required");
var flag=false;
elem.each(function() {
	if ($(this).val()=='' && $(this).is(':visible'))
		{
			flag=true;
			$(this).addClass("vanadium-invalid");
		}
})

var mails=$(".email");
var flag1=false;
mails.each(function() {
	if ($(this).val()!='' && !isValidEmailAddress($(this).val()) && $(this).is(':visible'))
		{
			flag1=true;
			$(this).addClass("online_field  vanadium-invalid");
		}
})



var checks=$(".checkrequired");
var flag2=false;
checks.each(function() {
	if (!$(this).is(":checked") && $(this).is(':visible'))
		{
			flag2=true;
		}
})

if (flag || flag1 || flag2) 
{	
	if (flag || flag1) {
		$("#error_msg").html("Проверьте правильность заполнения полей! ").show();
	}
	if (flag2) {
		$("#error_msg").append("Необходимо принять условия договора! ").show();
	}
	$("html, body").stop().animate({scrollLeft:0,scrollTop:$("#error_msg").offset().top}, 400);
	return false;
}
	else return true;
	return false;

}