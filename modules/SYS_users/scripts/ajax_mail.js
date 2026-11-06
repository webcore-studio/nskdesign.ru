function request() {	
		 $.ajax({       type: "POST",
                        url:  '/index.php?module=SYS_users&type=ajax&func=checkmail',
                        data: {'user_email' : $("input[name=user_email]").val()},
                        success: function(html) {
                                $("#RegistrResults").empty();
                                $("#RegistrResults").append(html); 
								},
                        error: function() {
							var pic = document.getElementById("RegistrResultsImg");
							pic.innerHTML="<img src='/modules/SYS_users/images/wrong.png'>";
                            $("#RegistrResults").empty();
                            $("#RegistrResults").append("Error!"); 
							}
                        });	
         }
function clean() {	
		 document.getElementById("SyntaxErrorMsg").style.display='none';
		 $("#RegistrResults").empty();
		 $("#RegistrResultsImg").empty();
		};

function call() {
		var mail = document.getElementById("email").value;
		if (mail === "") return false;  
		
		//картинка загрузки
        var pic = document.getElementById("RegistrResultsImg");
        pic.innerHTML="<img src='/modules/SYS_users/images/load.GIF'>";
		
		//задержка выполнения функции
		setTimeout('request(); $("#RegistrResultsImg").empty();',800);
		}	
				 
function getText(el) {
		document.getElementById('email').value = el.firstChild.data;
		clean();
		call();
		};
		
//проверяем только кириллицу и пробелы во время ввода		

function KirillicaInEmail(email) {
    var pattern = new RegExp(/[а-яА-ЯёЁ\s]+/);
	return pattern.test(email);
    }

$(document).ready(function () {
	
	$("#email").keyup(function() {
	clean();
	var email = $("#email").val();
		if (KirillicaInEmail(email)) { document.getElementById("SyntaxErrorMsg").style.display='inline-block'; }
		else { document.getElementById("SyntaxErrorMsg").style.display='none'; }
	})
})
		

		
