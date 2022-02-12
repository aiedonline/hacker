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

function validar_ip($ip){
    if ($ip != "127.0.0.1"){
        $ips = Database::Data("access_ip", [], [], $cache=false)[0]['data'];
        for($i = 0; $i < count($ips); $i++){
            if($ips[$i]["ip"] == $ip){
                return;
            }
        }
        error_log("IP que não pode acessar: " . $ip, 0);
        die;
    }
}

validar_ip($ip);



?>