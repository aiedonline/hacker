<?php
require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";


// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$retorno = Database::Write(["lan_host"], [["_id", "lan_id", "ip", "os", "name", "nmap"]], 
            [[$post_data["lan_id"] . $post_data["ip"], $post_data["lan_id"],  $post_data["ip"], $post_data["os"], $post_data["name"], $post_data["nmap"]]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);

?>



