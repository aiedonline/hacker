<?php
require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";


// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

#_id, ip, geo, domain_id, shodan, script_version, _user, report, fraud_score, host,
# is_crawler, mobile, proxy, vpn, tor, active_vpn, active_tor, recent_abuse, ,

function qualquercoisa_to_int($qualquercoisa){
    if($qualquercoisa == null || $qualquercoisa == ""){
        return 0;
    }
    if(is_string($qualquercoisa)){
        $qualquercoisa = boolval($qualquercoisa);
    }
    if($qualquercoisa){
        return 1;
    }
    return 0;
}
//{"success":true,"message":"Success","fraud_score":70,"country_code":"BR",
//"region":"Sao Paulo","city":"Guarulhos","ISP":"NET Virtua","ASN":28573,
// "organization":"NET Virtua","is_crawler":false,"timezone":"America\/Sao_Paulo",
// "mobile":false,"host":"b3d7834c.virtua.com.br","proxy":true,"vpn":false,"tor":false,
// "active_vpn":false,"active_tor":false,"recent_abuse":false,"bot_status":false,
// "connection_type":"Premium required.","abuse_velocity":"Premium required.",
// "zip_code":"N\/A","latitude":-23.45000076,"longitude":-46.52999878,"request_id":"1VPCtI9Wc3"}

//error_log(json_encode($post_data), 0);
$retorno = Database::Write(["ip"], [["_id", "geo", "fraud_score", "host", "is_crawler", "mobile", "proxy", "vpn",
                                        "tor", "active_vpn", "active_tor", "recent_abuse" ]], 
            [[$post_data["_id"] , $post_data["country_code"] . " " . $post_data["region"] . " " . $post_data["city"] . " " . $post_data["ISP"], 
             $post_data["fraud_score"], 
             $post_data["host"],
             qualquercoisa_to_int($post_data["is_crawler"]),
             qualquercoisa_to_int($post_data["mobile"]),
             qualquercoisa_to_int($post_data["proxy"]),
             qualquercoisa_to_int($post_data["vpn"]),
             qualquercoisa_to_int($post_data["tor"]),
             qualquercoisa_to_int($post_data["active_vpn"]),
             qualquercoisa_to_int($post_data["active_tor"]),
             qualquercoisa_to_int($post_data["recent_abuse"])  ]], "", 
            $cache=false, $user=$post_data["user"]);

//error_log( json_encode($retorno), 0);

echo json_encode($retorno);

?>


