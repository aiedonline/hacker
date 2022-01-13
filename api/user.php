<?php
/*
 * Neste script encontra-se as funções que guardam no cliente a SESSION criptografada e dados de usuário.
 *
 * Autor: wellington (ponto) aied (arroba) gmail (ponto) com
 *
 * ATENÇÃO: Por incapacidade da TI em resolver o problema em que a session vem caindo e por incapacidade
 * em domínio da TI de seu próprio ambiente esta medida foi necessára e expõe dados, se um dia o sistema
 * sair da guarda da TI é recomendado o uso de sessão classica em memória.
 */

// chamado aonde queremos salvar uma informação sigilosa no browser do usuário.
function session_save($name, $value){
	$CONFIG = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"));
	$key = $CONFIG->keyhash;
	$enc = encrypt($value, $key);
	setcookie($name . "_" . $CONFIG->ui->sigla, $enc, time() + (86400 * 30), "/");
}

function session_load($name){
	$CONFIG = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"));
	$key = $CONFIG->keyhash;
	if(isset($_COOKIE[$name . "_" . $CONFIG->ui->sigla])) {
		return decrypt($_COOKIE[$name . "_" . $CONFIG->ui->sigla], $key);
	}
	return null;
}

function session_exist($name){
	$CONFIG = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"));
	if(isset($_COOKIE[$name . "_" . $CONFIG->ui->sigla])) {
		return true;
	}
	return null;
}


function encrypt($plaintext, $key){
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	return base64_encode( $iv.$hmac.$ciphertext_raw );
}

function decrypt($ciphertext, $key){
	$c = base64_decode($ciphertext);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	return $original_plaintext;
}

// Dado um perfil solicitado avalia se essa pessoa tem ou não este determinado perfil.
function posso($grupo_permitido){
	$user = json_decode(session_load('user_cookie'));
	for($i = 0;  $i < count($grupo_permitido); $i++){
		if($grupo_permitido[$i] == ""){
			continue;
		}
		for($j = 0; $j < count($user->groups); $j++){
			if(strrpos($user->groups[$j], $grupo_permitido[$i]) === 0){
				return true;
			}
		}
	}
	return false;
}

?>