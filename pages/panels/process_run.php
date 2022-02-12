<?php
    require_once dirname(__FILE__, 3) . "/api/json.php";
    require_once dirname(__FILE__, 3) . "/api/edb.php";

    $post_data = json_decode(file_get_contents('php://input'), true);
    $project =      Database::Data("project", ["_id"], [$post_data["id"]], $cache=false)[0]['data'];

    echo json_encode( array("command" => "sudo python3 ./app.py -s '127.0.0.1' -p '". $post_data["id"] ."' -t '" . $project['token'] . "' -u '". $post_data["user"] ."' -pt http -po 80" ) );
?>