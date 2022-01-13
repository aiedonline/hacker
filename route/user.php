<?php

require dirname(__DIR__, 1)  . '/api/utilitario.php';
require dirname(__DIR__, 1)  . '/api/json.php';
$CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");
require dirname(__DIR__, 1)  . '/api/' . $CONFIG->auth->file . '.php';

$data = json_decode(file_get_contents('php://input'), true);

function login($data){
    $retorno = User::ValideUser($data["user"], $data["password"]);
    echo Utilitario::SaidaPadrao(count($retorno) > 0, $retorno, [] );	
}

function logout($data){
	session_save("user_cookie", null);
	echo Utilitario::SaidaPadrao(true, true, [] );	
}
require_once __DIR__  . '/events.php';
