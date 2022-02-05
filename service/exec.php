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

// _id, , project_id, stdout, stderr, status_code, date_execution, status, , date_start, date_end, ,
$keys = []; $values = [];
$agora = (new \DateTime());
if( $post_data["action"] == "start"){
    // $agora->format(DateTime::ISO8601),
    $keys = ["_id", "project_id", "date_start", "status", "ip", "date_execution"];
    $values = [ 
        $post_data["_id"], 
        $post_data["project_id"], 
        $agora->format('Y-m-d H:i:s'),
        0,
        $ip, $agora->format('Y-m-d H:i:s')
    ];
} else{
    $keys = ["_id", "project_id", "stdout", "stderr", "status_code", "status", "date_execution", "date_end"];
    $values = [ 
        $post_data["_id"], 
        $post_data["project_id"], 
        $post_data["stdout"], 
        $post_data["stderr"], 
        $post_data["status_code"], 
        1, $agora->format('Y-m-d H:i:s'), $agora->format('Y-m-d H:i:s')
    ];
}

$retorno = Database::Write(["execution"], [$keys], [$values], "",  $cache=false, $user=$post_data["user"]);
echo json_encode($retorno);
?>



