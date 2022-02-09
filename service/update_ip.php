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


$retorno = Database::Write(["ip"], [["_id", "ip", "geo",                 "shodan",             "script_version"]], 
            [[$post_data["_id"], $post_data["host"],  $post_data["geo"], $post_data["shodan"],   "1"]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);

//_id, ip, geo, domain_id, shodan, script_version, _user, ,
//{"_id" : data["_id"], "host" :  data["host"] , "geo" : geo, "shodan" : shodan, "project_id" : data['project_id'], "token" : data["token"], "user" : data["user"] };
//{'ip_id': 'aaaa', 'host': '8.8.8.8', 'geo': 'United States CA Mountain View', 'shodan': {'city': 'Mountain View', 'region_code': 'CA', 'os': None, 'tags': [], 'ip': 134744072, 'isp': 'Google LLC', 'area_code': None, 'longitude': -122.0775, 'last_update': '2021-11-26T01:23:58.951594', 'ports': [53], 'latitude': 37.4056, 'hostnames': ['dns.google'], 'postal_code': None, 'country_code': 'US', 'country_name': 'United States', 'domains': ['dns.google'], 'org': 'Google LLC', 'data': [{'asn': 'AS15169', 'hash': -553166942, 'timestamp': '2021-11-26T01:23:58.951594', 'isp': 'Google LLC', 'transport': 'udp', 'data': '\nRecursion: enabled', 'port': 53, 'hostnames': ['dns.google'], 'location': {'city': 'Mountain View', 'region_code': 'CA', 'area_code': None, 'longitude': -122.0775, 'latitude': 37.4056, 'postal_code': None, 'country_code': 'US', 'country_name': 'United States'}, 'dns': {'resolver_hostname': None, 'recursive': True, 'resolver_id': None, 'software': None}, 'ip': 134744072, 'domains': ['dns.google'], 'org': 'Google LLC', 'os': None, '_shodan': {'crawler': 'cdd92e2d835a37d2798fa6c7105171f4d214012f', 'options': {}, 'id': '2a829883-0c1c-421b-a777-f8b321f7e76f', 'module': 'dns-udp', 'ptr': True}, 'opts': {'raw': '34ef818200010000000000000776657273696f6e0462696e640000100003'}, 'ip_str': '8.8.8.8'}], 'asn': 'AS15169', 'ip_str': '8.8.8.8'}, 'project_id': 'aaa', 'token': 'aaaa', 'user': 'aaa'}


?>