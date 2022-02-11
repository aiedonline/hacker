<?php


require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";


// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

$project =      Database::Data("project", ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];

if($project['token'] != $post_data["token"]){
    die;
}
$retorno = array("project" => $project, "nmap" => array("arguments" => null, "lans" => []), 
                    "nmap_domain" => array("arguments" => null, "ips" => []), "shodan" => null, 
                    "whois" => array("arguments" => null, "domains" => []));

$project_nmap   = Database::Data("project_nmap_arguments",   ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];
$project_nmap_domain   = Database::Data("project_nmap_domain_arguments",   ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];
$project_shodan = Database::Data("project_shodan_arguments", ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];
$project_whois = Database::Data("project_whois_arguments", ["_id"], [$post_data["project_id"]], $cache=false)[0]['data'];

$retorno["nmap_domain"]['arguments'] = $project_nmap_domain;
$retorno["nmap"]['arguments'] = $project_nmap;
$retorno["whois"]['arguments'] = $project_whois;

$retorno["shodan"] = $project_shodan;
$retorno["shodan"]["hosts"] = [];

$domains = Database::List_data("/local/hacker", "domain", ["project_id"], [$post_data["project_id"]], 99999, [ array("field" => "_id", "order" => "asc") ]);
if($domains[0]['font'] != null) {
    for($i = 0; $i < count($domains[0]['data']); $i++) {    
        if(array_key_exists("_id", $domains[0]['data'][$i])){
            array_push($retorno["whois"]['domains'], array("domain" => $domains[0]['data'][$i]["domain"], "domain_id" => $domains[0]['data'][$i]["_id"]));
            $ips = Database::List_data("/local/hacker", "ip", ["domain_id"], [$domains[0]['data'][$i]["_id"]], 99999, [ array("field" => "_id", "order" => "asc") ]);
            if(count($ips) > 0){
                $ips = $ips[0]['data'];
            }
            for($j = 0; $j < count($ips); $j++){
                array_push($retorno["shodan"]["hosts"], $ips[$j]);
                array_push($retorno["nmap_domain"]['ips'], array("_id" => $ips[$j]["_id"],  "ip" => $ips[$j]["ip"]));
            
            }
            //$retorno["shodan"]["hosts"] = array_merge($retorno["shodan"]["hosts"], $ips);
        }
    }
}

$ambientes = Database::List_data("/local/hacker", "ambiente", ["project_id"], [$post_data["project_id"]], 99999, [ array("field" => "_id", "order" => "asc") ]);
if($ambientes[0]['font'] != null) {
    for($i = 0; $i < count($ambientes[0]['data']); $i++) {    
        if(array_key_exists("_id", $ambientes[0]['data'][$i])){
            $lans = Database::List_data("/local/hacker", "lan", ["ambiente_id"], [$ambientes[0]['data'][$i]["_id"]], 99999, 
                                            [ array("field" => "_id", "order" => "asc") ]);
            
                                            if(count($lans) > 0){
                $lans = $lans[0]['data'];
            }
            $retorno["nmap"]["lans"] = array_merge($retorno["nmap"]["lans"], $lans);
        }
    }
}

echo json_encode($retorno);
?>



