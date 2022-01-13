function Pessoa(cpf, callback){
	EnviarJsonPost(PREFIX + "route/proxy.php", {"service" : "wsurh001501", "parameters" : [cpf]}, function(saida, status, erro){
		callback(saida, status, erro);	
	}, {"cookie" : {"name" : cpf}});
}