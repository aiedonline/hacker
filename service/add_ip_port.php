<?php



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


$retorno = Database::Write(["ip_port"], [["_id",         "port",                  "evidence",       "ip_id",                "protocol"]], 
            [[$post_data["ip_id"] . strval($post_data["port"]), $post_data["port"],  $post_data["evidence"],  $post_data["ip_id"], $post_data["protocol"]]], "", 
            $cache=false, $user=$post_data["user"]);
error_log(json_encode($retorno), 0);
echo json_encode($retorno);

// _id, port, evidence, ip_id, , date_create, protocol, _user, ,
//{'ip_id': 'aaaa', 'port': 53, 'evidence': '{"state": "open", "reason": "syn-ack", "name": "tcpwrapped", "product": "", "version": "", "extrainfo": "", "conf": "8", "cpe": ""}', 'font': 'nmap', 'protocol': 'tcp', 'project_id': 'aaa', 'token': 'aaaa', 'user': 'aaa'} 

?>
