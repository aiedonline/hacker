//require EnviarJsonPost layout v1
//require eraseCookie cookie v1

function Logout(){
	EnviarJsonPost(PREFIX + "route/user.php", {"action" : "logout"}, function(saida, status, erro){
		window.location.href = PREFIX + "pages/logout.php";
	});

}