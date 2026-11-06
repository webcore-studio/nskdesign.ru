function open_brunch_c (id,flag)
{
var elems = $(".elemc"+id);
elems.each(function() {
	if (flag==1)
	{
		if ($(this).is(":hidden")) {$(this).show();}
		else  
		{
			$(this).hide();
			var id_n=$(this).attr('id');
			open_brunch_c(id_n,0);
		}
	}
	else
	{
		$(this).hide();
		var id_n=$(this).attr('id');
		open_brunch_c(id_n,0);
} 
 })
return true;
}

function open_all()
{
var items=$(".elemc0");
items.each(function(){open_brunch($(this).attr("id"),1);})
}

