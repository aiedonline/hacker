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


//$project =   Database::Data("project", ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];
//if($project['token'] != $post_data["token"]){
//    die;
//}
//$ambientes = Database::List_data("/local/hacker", "ambiente", ["project_id"], [$post_data["project_id"]], 99999, [ array("field" => "_id", "order" => "asc") ])[0]['data'];
//for($i = 0; $i < count($ambientes); $i++ ){
//    $lans = Database::List_data("/local/hacker", "lan", ["ambiente_id"], [$ambientes[$i]["_id"]], 99999, [ array("field" => "_id", "order" => "asc") ])[0]['data'];
//    $retorno["nmap"]["lans"] = array_merge($retorno["nmap"]["lans"], $lans);
//}


$retorno = Database::Write(["lan_host"], [["_id", "lan_id", "ip", "os", "name", "nmap"]], 
            [[$post_data["lan_id"] . $post_data["ip"], $post_data["lan_id"],  $post_data["ip"], $post_data["os"], $post_data["name"], $post_data["nmap"]]], "", 
            $cache=false, $user=$post_data["user"]);

error_log("Antes de enviar: " . json_encode($retorno), 0);
echo json_encode($retorno);



?>



