<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";

$ip = null;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$retorno = Database::Write(["lan_host_port"], [["_id", "lan_host_id", "port", "service", "nmap"]], 
            [[$post_data["lan_host_id"] . $post_data["port"], $post_data["lan_host_id"],  $post_data["port"], $post_data["service"], $post_data["nmap"]]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);
?>

