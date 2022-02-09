<?php
/*
 * Neste script encontra-se a classe Barramento onde é feita a conexão com o ESB do CPS.
 *
 * ATENÇÃO: Veja que a TokenID está fixa neste código bem como a URL, recomendo que no futuro
 * crie-se um arquivo de configuração para isso.
 *
 * Autor: wellington (ponto) aied (arroba) gmail (ponto) com
 */
class Barramento {
	
	public static function Servico($nome, $params){
		try{ 
			// The data to send to the API
			$postData = array(
				'tokenId' => '398fe1a7-b309-4a77-9ac6-0a6f1c46b59c',
				'service' => $nome,
				'parametersvalue' => $params,
				'parametertype' => [],
				'parametersio' => [],
				'outtype' => 'json'
			);

			// Create the context for the request
			$context = stream_context_create(array(
				'http' => array(
					'method' => 'POST',
					'header' => "Content-Type: application/json\r\n",
					'content' => json_encode($postData)
				)
			));

			// Send the request
			$response = file_get_contents('http://esbprov2.azurewebsites.net/services/bus', FALSE, $context);
			
			// Check for errors
			if($response === FALSE){
				die('Error');
			}
			
			try{ // try para tratar só a conversao do json, alguns serviços podem retornar um texto para o genexus
				
				$buffer_js = json_decode($response, TRUE);
				if($buffer_js['status'] == 1){
					return $buffer_js['data'];
				}else{
					return $buffer_js['data'];
				}
			}catch (Exception $e) {
				return $response; 	
			}
			
		}catch (Exception $e) {
			return $e;	
		}
	}
}


?>

