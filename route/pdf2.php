<?php
require_once dirname(__DIR__) . '/api/edb.php';

//http://localhost/secanalysis/route/pdf2.php?id=cc40fff9-b1e6-e9b2-e108-4c01178a573f&token=d5c8cb88-2980-4fe2-a6a6-74d931546d48

function limparBody($entrada_buffer){
	// resumo, introdução completa
	preg_match("/<body.*\/body>/s", $entrada_buffer, $matches);
	if(count($matches) == 0){
        
		return $entrada_buffer;
	} else {
		return $matches[0];
	}
}


function add_note($index, $domain_id ){
    $texto = "";
    try{
        
        $dados = Database::List_data("/local/hacker", "note", ["entity_id"], [$domain_id],
        $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
        if(count($dados) > 0){
            $texto .= "<h3>". strval($index) . ".3. Notas</h3>";
        }
        for($k = 0; $k < count($dados); $k){
            if($dados[$k]['note'] == "") continue;
            $texto .= limparBody($dados[$k]['note']) . "</br>";
            
        }
    } catch (Exception $e) {
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return $texto;
}



function array_to_texto($a){
    $texto = "<table border='1'>";
    for($ji = 0; $ji < count($a); $ji++){
        $texto = $texto . "<tr>";
        for($jj = 0; $jj < count($a[$ji]); $jj++){
            $texto = $texto . "<td>" . $a[$ji][$jj] . "</td>";
        }
        $texto = $texto . "</tr>";
    }
    $texto = $texto . "</table>";
    return $texto;
}

function add_evidence($cve_occurrence_id ){
    $texto = "";
    $dados = Database::List_data("/local/hacker", "evidence", ["cve_occurrence_id"], [$cve_occurrence_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['title']) ) {
            $texto .= $dados[$i]['title'] . "</br>" . limparBody($dados[$i]['evidence']) ;
        }
    }
    return $texto;
}

function add_cve($indice, $occurrence_id){
    $texto = "";
    $dados = Database::List_data("/local/hacker", "cve_occurrence", ["occurrence_id"], [$occurrence_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    if(count($dados) > 0){
        $texto .= "<br/><br/><h3>" . strval($indice) . ".2. Common Vulnerabilities and Exposures</h3>"
        . '<span style="text-align:justify;">' . "O Common Vulnerabilities and Exposures é um banco de dados que registra vulnerabilidades e exposições relacionadas a segurança da informação conhecidas publicamente. Abaixo temos uma lista de Vulnerabilidades listadas para execução de testes de intrusão</span>.<br/>";

    }
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['cve_id']) ) {
            $cve = Database::Data("cve", ["_id"], [$dados[$i]['cve_id']], $cache=false)[0]['data'];
            $texto .= "<b> " . $cve['codigo'] . " - " . $cve['description'] . "</b>";
            $texto .= $cve['full_description'];
            if($cve['score'] != "") {
                $tabela = [
                    ["Pontuação CVSS", $cve['score']],
                    ["Impacto de Confidencialidade", $cve['confidentitality']],
                    ["Impacto de integridade", $cve['integrity']],
                    ["Impacto de disponibilidade", $cve['availability']],
                    ["Complexidade de acesso", $cve['access_complexity']],
                    ["Autenticação", $cve['autentication']],
                    ["Acesso obtido", $cve['gained_access']],
                    ["Tipo (s) de vulnerabilidade", $cve['vulnerability_type']]
                ];
                $texto .= array_to_texto($tabela);
            }
            $texto .= add_evidence($dados[$i]["_id"]);
        }
    }
    return $texto;
}

function add_domain_ip_port($ip_id){
    $texto = "";
    $dados = Database::List_data("/local/hacker", "ip_port", ["ip_id"], [$ip_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['port']) ) {
            $texto .= $dados[$i]['port'] . " - " . $dados[$i]['protocol'] ;
        }
    }
    return $texto;
}

function add_domain_ips($indice, $domain_id){
    $dados = Database::List_data("/local/hacker", "ip", ["domain_id"], [$domain_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $texto = "";
    if(count($dados) > 0){
        $texto .= "<h3>" . strval($indice) . ".1. IPs e Portas</h3>";
    }
    for($i = 0; $i < count($dados); $i++){
        $j = [];
        if( isset($dados[$i]['ip']) ) {
            $texto .= "<h5>". $dados[$i]['ip'] . " - " . $dados[$i]['geo'] ."</h5>";
            $texto .= add_domain_ip_port($dados[$i]['_id']);
        }
    }
    return $texto;
}

function add_domains($project_id){
    $domains = Database::List_data("/local/hacker", "domain", ["project_id"], [$project_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $texto = "";
    $indice = 1;
    for($i = 0; $i < count($domains); $i++){
        $texto .= "<h2>" . strval($indice) . ". Domínio: " . $domains[$i]['domain'] . "</h2>" ;
        $texto .= $domains[$i]['about'];
        $texto .= add_domain_ips($indice, $domains[$i]['_id']);
        $texto .= add_cve($indice, $domains[$i]['_id']);
        $indice = $indice + 1;
    }
    return $texto;
}



$project_id = 'cc40fff9-b1e6-e9b2-e108-4c01178a573f';//$_GET["id"]
$project_id = $_GET["id"];

// dados do projeto
$project = Database::Data("project", ["_id"], [$project_id], $cache=false)[0]['data'];
if($project['token'] != $_GET["token"]){
    die;
}
$domains =   Database::List_data("/local/hacker", "domain", ["project_id"], [$project_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
$ambientes = Database::List_data("/local/hacker", "ambiente", ["project_id"], [$project_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];


$texto = "<html><body><h1>Penetration Test Report</h1></br><h3>". $project['client_name'] ."</h3></br></br></br></br></br>Por ". $project['development_by'] ."";
$texto .=  "<h1>1. CONFIDENCIALIDADE</h1>Em nenhuma hipótese a ". $project['development_by'] ." será responsável por qualquer pessoa por danos especiais, incidentes, colaterais ou consequentes decorrentes do uso dessas informações. ";
$texto .= "Este documento contém informações confidenciais e de propriedade da ". $project['development_by'] ." e " . $project['client_name'] . ". Extremo cuidado deve ser tomado antes de distribuir cópias deste documento ou o conteúdo extraído deste documento. A Segurança ". $project['development_by'] ." está autorizando nosso ponto de contato na " . $project['client_name'] . " para: visualizar e divulgar este documento conforme achar adequado, de acordo com a política e procedimentos de tratamento de dados da " . $project['client_name'] . ". ";
$texto .= "Este documento deve ser marcado como “CONFIDENCIAL” e, portanto, sugerimos que este documento seja divulgado com base na \"necessidade de saber\". ";
$texto .= "Abordar questões relacionadas ao uso adequado e legítimo deste documento para: ";
$texto .= $project['client_name'] . "</br>" . limparBody( $project['client_address']);

$texto .= "<h1>2. ISENÇÕES DE RESPONSABILIDADE</h1>";
$texto .= "As informações apresentadas neste documento são fornecidas no estado em que se encontram e sem garantia. As avaliações de vulnerabilidade são uma análise \"pontual\" e, como tal, é possível que algo no ambiente possa ter mudado desde que os testes refletidos neste relatório foram executados. Além disso, é possível que novas vulnerabilidades tenham sido descobertas desde a execução dos testes. Por esse motivo, este relatório deve ser considerado um guia, não uma representação 100% do risco que ameaça seus sistemas, redes e aplicativos. ";


// ---------------------------------------------- PROPÓSITOS -------------------------------------------------
$texto .= "<h1>3. PROPÓSITOS</h1>";
$subindex = 1;
$texto .= $project['client_name'] . " solicitou que a ". $project['development_by'] ." realizasse um exame de segurança detalhado de: ";
if(count($domains) > 0) {
    $texto .= "<h2>3.". strval($subindex) .". Análise dos seguintes domínios públicos:</h2><ul>";
    for($i = 0; $i < count($domains); $i++){
        $texto .= "<li>" . $domains[$i]['domain'] . "</li>";
    }
    $texto .= "</ul>";
    $subindex = $subindex + 1;
}

if(count($ambientes) > 0) {
    $texto .= "<h2>3.". strval($subindex) .". Análise dos seguintes ambientes (LAN):</h2><ul>";
    for($i = 0; $i < count($ambientes); $i++){
        $lans_ambiente = Database::List_data("/local/hacker", "lan", ["ambiente_id"], [$ambientes[$i]['_id'] ], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
        for($j = 0;$j < count($lans_ambiente ); $j++){
            $texto .= "<li><b>" . $ambientes[$i]['name'] . "</b> - ". $lans_ambiente[$j]['address'] ."/". $lans_ambiente[$j]['mask'] ."</li>";
        }
    }
    $texto .= "</ul>";
    $subindex = $subindex + 1;
}

//$texto .= "Estes ambientes baseados na web estavam em produção no momento do teste e tivemos acesso a um sistema de teste/preparação. ";
//$texto .= "Este esforço de teste ocorreu em novembro de 2021. Algumas descobertas preliminares foram fornecidas, e este relatório está sendo apresentado para mostrar a totalidade dos  resultados de nossos esforços de teste e para fazer recomendações quando apropriado. ";

$texto .= "<h2>3.". strval($subindex) .". Escopo e tecnologias</h2>";
$texto .= limparBody($project['scope']);
// AGORA TENHO QUE PEGAR QUAIS PROGRAMAS DEVERÃO RODAR....
$project_nmap   = Database::Data("project_nmap_arguments",   ["_id"], [$project_id], $cache=false)[0]['data'];
$project_nmap_domain   = Database::Data("project_nmap_domain_arguments",   ["_id"], [$project_id], $cache=false)[0]['data'];
$project_shodan = Database::Data("project_shodan_arguments", ["_id"], [$project_id], $cache=false)[0]['data'];
$project_whois = Database::Data("project_whois_arguments", ["_id"], [$project_id], $cache=false)[0]['data'];

$texto .= "Seguintes tecnologias serão executadas neste escopo:<ul>";
if($project_nmap['enable'] == "1"){
    $texto .= "<li> Execução do comando nmap contra endereço de rede local definido como \"ambiente\", com o seguinte argumento: " . $project_nmap['arguments'] . ";</li>";
}
if($project_nmap_domain['enable'] == "1"){
    $texto .= "<li> Execução do comando nmap contra IPs localizados nos \"domínios\", com o seguinte argumento: " . $project_nmap_domain['arguments'] . ";</li>";
}
if($project_shodan['enable'] == "1"){
    $texto .= "<li> Consulta shodan.io sobre IPs localizados nos \"domínios\";</li>";
}
if($project_whois['enable'] == "1"){
    $texto .= "<li> Consulta WHOIs dos \"domínios\";</li>";
}
$texto .= "</ul>";
$project_nmap = null;
$project_nmap_domain = null;
$project_shodan = null;
$project_whois = null;
// -------------------------------------------------------------------


$index = 4;
$subindex = 1;
$texto .= "<h1>". strval($index) .". AMBIENTES</h1>";
if(count($ambientes) > 0) {
    for($i = 0; $i < count($ambientes); $i++){
        $lans_ambiente = Database::List_data("/local/hacker", "lan", ["ambiente_id"], [ $ambientes[$i]['_id'] ], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
        
        for($j = 0;$j < count($lans_ambiente ); $j++){
            $texto .= "<h2>". strval($index) . ".". strval($subindex) .". LAN: " . $lans_ambiente[$j]['address'] ."/". $lans_ambiente[$j]['mask'] ."</h2>";
            $texto .= $ambientes[$i]["scope"];
            $texto .= $lans_ambiente[$j]['descricao'] ;
            
            $subindex = $subindex + 1;
            
            
            $hosts = Database::List_data("/local/hacker", "lan_host", ["lan_id"], [ $lans_ambiente[$j]['_id'] ], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
            
            for($k = 0; $k < count($hosts); $k++){
                //_id, ip, os, _user, lan_id, name, nmap, ,
                $js = json_decode($hosts[$k]['nmap'], true);
                
                //
                $texto .= "<b>". $hosts[$k]['ip']  . " - " . $hosts[$k]['name'];   
                if($js["addresses"]["mac"])    {         
                    $texto .= "  -  (" . $js["addresses"]["mac"] . ")" ;
                }
                $texto .=  "</b></br>";

                //lan_host_port:	_id, service, port, _user, lan_host_id, nmap, ,

                $ports = Database::List_data("/local/hacker", "lan_host_port", ["lan_host_id"], [ $hosts[$k]['_id'] ], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
                
                $texto .= "<ul>";
                for($l = 0; $l < count($ports); $l++){
                    //$nmap_port = json_decode( $ports[$l]["nmap"], true);
                    $nmap_port = explode(",", $ports[$l]["nmap"]);
                    $texto .= "<li><b>". $ports[$l]["port"] . " - " . $ports[$l]["service"] . "</b><br/> <span> Dados: ";
                    for($m = 0; $m < count($nmap_port); $m++){
                        $nmap_port_field = explode(":", $nmap_port[$m]);
                        if( trim($nmap_port_field[1]) != ""){
                            $texto .= "<b>" . $nmap_port_field[0] . "</b>: " . $nmap_port_field[1]  . ", ";
                        }
                        //for($n = 0; $n < count($nmap_port))
                    }
                    $texto .= "</span></li>";
                }
                $texto .= "</ul>";

            }
        
        }
    }
    
}

// -------------------------------------------------------------------
$index = 5;
$subindex = 1;
$texto .= "<h1>". strval($index) .". DOMÍNIOS</h1>";
if(count($domains) > 0) {
    //domain	_id, domain, project_id, about, shodan, script_version, whois, consideracoes, _user, report, ,
    for($i = 0; $i < count($domains); $i++){
        $texto .= "<h2>". strval($index) . ".". strval($subindex) .". Domínio: " . $domains[$i]['domain'] . "</h2>";

    }

}






//$texto = limparBody($texto);
//$texto .= add_domains($project['_id']);
//$texto = str_replace($texto, '<body>');
//$texto = str_replace($texto, '</html>');
//$texto = str_replace($texto, '</body>');

//$myfile = fopen(dirname(__DIR__)  . "/tmp/texto.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $texto);
//fclose($myfile);

print($texto);








