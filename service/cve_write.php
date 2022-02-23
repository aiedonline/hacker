<?php
require_once dirname(__DIR__) . "/api/json.php";
require_once dirname(__DIR__) . "/api/edb.php";
require_once dirname(__FILE__) . "/test_ip.php";

// add_tecnology_domain.php
// DADOS QUE VEM DO JSON POST + url
$part = explode("/", $_SERVER["REQUEST_URI"]);
$post_data = json_decode(file_get_contents('php://input'), true);

//{'description': 'Deserialization of untrusted data in the Hessian Component of Apache Cayenne 4.1 with older Java versions',
//     'assigned': 'security@apache.org', 'state': 'PUBLIC', 'full_description': "", 
//     'productor': 'Apache Cayenne: <= 4.1, ; ', 'work_around': 'ons of Apache Cayenne 4.2 have whitelisting enabled by default for the Hessian deserialization.  Later versions of Java also have LDAP mitigation in place. Users can either upgrade Java or Apache Cayenne to avoid the issue.\n\nLDAP mitigation is present starting in JDK 6u211, 7u201, 8u191, and 11.0.1 where com.sun.jndi.ldap.object.trustURLCodebase system property is set to false by default to prevent JNDI from loading remote code through LDAP.; ', 
//     'problemtype': '(eng) CWE-502 Deserialization of Untrusted Data; '}

$retorno = Database::Write(["cve"], [["_id", "codigo", "description", "assigned", "state", "full_description", "productor", "work_around", "problemtype"]], 
            [[ md5( $post_data["cve"]  ), $post_data["cve"], $post_data["description"]
            , $post_data["assigned"], $post_data["state"], $post_data["full_description"],
             $post_data["productor"], $post_data["work_around"], $post_data["problemtype"] ]], "", 
            $cache=false, $user=$post_data["user"]);

echo json_encode($retorno);

?>
