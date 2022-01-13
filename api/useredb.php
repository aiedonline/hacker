<?php
/*
 * Neste script encontra-se a classe USER que utilizam o barramento para buscar dados de pessoas. Também permite que administradores
 * possam realizar o impersonate
 *
 * Autor: wellington (ponto) aied (arroba) gmail (ponto) com
 */
//namespace user;

//Tabela: user: _id, password, cpf,
require_once __DIR__  . '/json.php';
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
			//Todo: 1 - session in cookie
			$usuario =  ExecuteOauth("/local/edb", "oauth", "login", "3", array("user" => $username, "password" => $password) );
			//{"status":true,"rows":{"status":true,"user":{"_id":"33dde1619d4403d9dda1aab9cea7f349394d60e5","user":"11111111111","email":"wellington.aied@gmail.com","session":"24bc984","key":"2af53d8b-39d2d3","groups":["aiedv2.administrador"]}}}
			
			if($usuario["rows"]["status"] == true){
				// Tem que validar se existe restrição deste site específico para grupos específicos
				$config = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"), true);
				
				session_save("session_cookie", $usuario["rows"]["user"]["session"] );
				session_save("key_cookie", $usuario["rows"]["user"]["key"] );
				
				$usuario["rows"]["user"]["key"] = null;
				$usuario["rows"]["user"]["session"] = null;
				
				session_save("user_cookie", json_encode($usuario["rows"]["user"])); 
				return $usuario["rows"]["user"];
			} else {
				
				throw new Exception('Teve dificuldades para logar, olha a mensagem: ' . $usuario["rows"]["message"]);
			}
			
		}catch(Exception $e){
			error_log('Error: ' . $e->getMessage(), 0);
			throw $e;
		}
    }
}


function ExecuteOauth($domain, $group, $action, $version, $postData ){
	try{

		$CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");
		$postData["domain"] = $domain;
		$postData["action"] = $action;
		$postData["group"] = $group;
		$postData["version"] = $version;
		// Create the context for the request
		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/json\r\n",
				'content' => json_encode($postData)
			)
		));

		$response = file_get_contents($CONFIG->edb . "oauth.php", false, $context);
		if($response === false){
			die('Error');
		}

		$buffer_js = json_decode($response, true);
		return $buffer_js;
	}catch (Exception $e) {
		error_log($e->getTraceAsString(), 0);
		throw $e;
	}
}

?>

