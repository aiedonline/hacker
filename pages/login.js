


function Logar(){
	try{
		$("#btn_login").removeAttr('href');   
		
		var cpf = $("#cpf").val();
		var senha = calcMD5( $("#senha").val() );
		if(cpf.trim() == "" || senha.trim() == ""){
			alert("Tem que informar CPF/Senha");
			return;
		}
		
		//JSON.stringify({"user" : txt_cpf.val().replace(/[^\d]+/g,''), "password" : txt_password.val()} ),
		EnviarJsonPost(PREFIX +"route/user.php",
					   {"action" : "login",  "user" : cpf, "password" : senha},
			function(pessoa, status, erro){
				try{
					if(pessoa != undefined && pessoa.status == true){
						createCookie("user", JSON.stringify(pessoa.data), Number.MAX_SAFE_INTEGER);
						window.location.href = PREFIX + "pages/painel.php";
					}else{
						alert("Usuário/senha incorreto");	
					}
				} catch(e){
					alert('Erro: ' + e.stack);
				}

			});
		
	} catch(e){
					alert('Erro: ' + e.stack);
	}
	finally{ 
			$("#btn_login").attr('href', "JavaScript:Logar();");   
	}
}

