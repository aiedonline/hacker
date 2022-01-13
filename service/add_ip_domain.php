<?php
require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";


// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$retorno = Database::Write(["ip"], [["_id", "ip", "domain_id", "script_version"]], 
            [[$post_data["domain_id"] . $post_data["ip"], $post_data["ip"],  $post_data["domain_id"], 1 ]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);

?>
