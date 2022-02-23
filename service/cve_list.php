<?php


require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php"; // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< BLOQUEIO DE ip AQUI...........

// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);


$retorno = Database::Data("cve", ["codigo"], [$post_data["cve"]], $cache=false)[0]['data'];

echo json_encode($retorno);
?>



