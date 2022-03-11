<?php


require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php"; // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< BLOQUEIO DE ip AQUI...........

// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$retorno = Database::List_data("/local/hacker", "tecnology", [], [], 99999, [ array("field" => "_id", "order" => "asc") ]);
#$retorno = Database::List_data("/local/hacker", "tecnology", ["domain_id"], [$post_data["domain_id"]], 99999, [ array("field" => "_id", "order" => "asc") ]);


echo json_encode($retorno);
?>



