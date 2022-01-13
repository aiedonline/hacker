<?php
/*
 * Neste script encontra-se a classe USER que utilizam o barramento para buscar dados de pessoas. Também permite que administradores
 * possam realizar o impersonate
 *
 * Autor: wellington (ponto) aied (arroba) gmail (ponto) com
 */
//namespace user;

//Tabela: user: _id, password, cpf,



require_once __DIR__  . '/user.php';
require_once __DIR__  . '/edb.php';

class User
{
	// Invocado quando se faz o Impersonate de pessoas para entrar como outra pessoa.
	static public function Impersonate($link){
	
	}
	
	static public function ImpersonateLink($cpf){

	}
	
	static public function ValideUser($username, $password){
		try {
			// 1 - Primeiro roda-se o comando waut000101 que autentica a pessoa no Barramento;
			// 2 - Depois roda o comando waut000501 que obtem dados da pessoa autenticada.

			// $retorno será uma token usada para validar uma sessão
			// $retornoDados é uma variavel com resposta do banco de dados
			$token = md5(uniqid(rand(), true));

			//Todo: 1 - session in cookie
			session_save("session_cookie", $token );
			// Buscando agora dados da pessoa

			//Data($entity, $keys, $values, $cache=false, $user=null, $domain=null){
			$retornoDados = Database::Data("user", ["cpf"], [$username]);
			//Usuário: [{"entity":"user","data":{"_id":"04839105626","password":"aaaaa","cpf":"04839105626","_inserttime":null,"_updatetime":null},"cache":null,"hash":null,"font":"database"}]

			if($retornoDados[0]['font'] != null  && $retornoDados[0]['data']['password'] == $password ){ //
				// limpa os dados com somente o que quero.
				$limpo =  array ('cpf'=>$retornoDados[0]['data']['cpf'], 'nome'=> $retornoDados[0]['data']['cpf'],'email'=> '' , '_id' => $retornoDados[0]['data']['_id'], "grupos" => []);
				// agora local
				// List_data($entity, $keys, $values, $limit, $order=[ array("field" => "_id", "order" => "asc") ], $where, $count, $user=null){
				$local = Database::List_data(null, "admin", ["cpf"], [$username])[0]; //, 9999, [ array("field" => "_id", "order" => "asc") ], ["="], false
				if(count($local['data']) > 0) {
					array_push($limpo['grupos'], "edb.administrador");
				}


				//Todo: 1 - session in cookie
				session_save("user_cookie", json_encode($limpo)); // <<<<<<<<<<<<<<<<<<<<


				return $limpo;
			}
			else{
				throw new Exception('Teve dificuldades para logar, olha a mensagem: ' . $retorno['data']);
			}
		}catch(Exception $e){
			error_log('Error: ' . $e->getMessage(), 0);
			throw $e;
		}
    }

	// Carrega o perfil da pessoa logada, é usado somente aqui dentro deste arquivo quando a pessoa LOGA ou
	//   quando a ela faz IMPERSONATE.
    static public function Grupos($session){
      
    	if($retorno['status'] == 1){
			return [];
		}else{
			throw new Exception("Erro ao obter perfil do usuário");
		}
    }

}

?>

