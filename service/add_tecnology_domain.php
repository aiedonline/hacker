<?php
require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";

// add_tecnology_domain.php
// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$retorno = Database::Write(["tecnology"], [["_id", "name", "version", "productor", "evidence", "entity_id"]], 
            [[ md5( $post_data["domain_id"] . $post_data["name"] . $post_data["version"] ),
            $post_data["name"],  $post_data["version"], $post_data["productor"], $post_data["evidence"], $post_data["domain_id"] ]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);

?>
