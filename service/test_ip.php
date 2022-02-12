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

function validar_ip($ip, $user){
    if ($ip != "127.0.0.1"){
        $ips = Database::List_data("/local/hacker", "access_ip", [], [], 99999, [ array("field" => "_id", "order" => "asc") ]);
        for($i = 0; $i < count($ips); $i++){
            if($ips[$i]["ip"] == $ip){
                return;
            }
        }
        error_log("IP que não pode acessar: " . $ip, 0);
        die;
    }
}

?>