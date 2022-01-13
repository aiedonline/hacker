<?php



require_once dirname(__DIR__) . '/api/edb.php';

function limparBody($entrada_buffer){
	// resumo, introdução completa
	preg_match("/<body.*\/body>/s", $entrada_buffer, $matches);
	if(count($matches) == 0){
        error_log($entrada_buffer, 0);
		return $entrada_buffer;
	} else {
		return $matches[0];
	}
}


function add_note($index, $domain_id, $pdf ){
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

function add_evidence($cve_occurrence_id, $pdf ){
    $texto = "";
    $dados = Database::List_data("/local/hacker", "evidence", ["cve_occurrence_id"], [$cve_occurrence_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['title']) ) {
            $texto .= $dados[$i]['title'] . "</br>" . limparBody($dados[$i]['evidence']) ;
        }
    }
    return $texto;
}

function add_cve($indice, $occurrence_id, $pdf){
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
            $texto .= add_evidence($dados[$i]["_id"], $pdf);
        }
    }
    return $texto;
}

function add_domain_ip_port($ip_id, $pdf){
    $texto = "";
    $dados = Database::List_data("/local/hacker", "ip_port", ["ip_id"], [$ip_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['port']) ) {
            $texto .= $dados[$i]['port'] . " - " . $dados[$i]['protocol'] ;
        }
    }
    return $texto;
}

function add_domain_ips($indice, $domain_id, $pdf){
    $dados = Database::List_data("/local/hacker", "ip", ["domain_id"], [$domain_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $texto = "";
    if(count($dados) > 0){
        $texto .= "<h3>" . strval($indice) . ".1. IPs e Portas</h3>";
    }
    for($i = 0; $i < count($dados); $i++){
        $j = [];
        if( isset($dados[$i]['ip']) ) {
            $texto .= "<h5>". $dados[$i]['ip'] . " - " . $dados[$i]['geo'] ."</h5>";
            $texto .= add_domain_ip_port($dados[$i]['_id'], $pdf);
        }
    }
    return $texto;
}

function add_domains($project_id, $pdf){
    $domains = Database::List_data("/local/hacker", "domain", ["project_id"], [$project_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $texto = "";
    $indice = 1;
    for($i = 0; $i < count($domains); $i++){
        $texto .= "<h2>" . strval($indice) . ". Domínio: " . $domains[$i]['domain'] . "</h2>" ;
        $texto .= $domains[$i]['about'];
        $texto .= add_domain_ips($indice, $domains[$i]['_id'], $pdf);
        $texto .= add_cve($indice, $domains[$i]['_id'], $pdf);
        $indice = $indice + 1;
    }
    return $texto;
}


$project = Database::Data("project", ["_id"], [$_GET["id"]], $cache=false)[0]['data'];
$texto = "<html><body><h1>Penetration Test Report</h1></br><h3>". $project['client_name'] ."</h3></br></br></br></br></br>Por cyberframework.online";

$texto .=  "<h1>Confidencialidade</h1>Em nenhuma hipótese a CyberFramework.online será responsável por qualquer pessoa por danos especiais, incidentes, colaterais ou consequentes decorrentes do uso dessas informações.";
$texto .= "Este documento contém informações confidenciais e de propriedade da CyberFramework.online e " . $project['client_name'] . ". Extremo cuidado deve ser tomado antes de distribuir cópias deste documento ou o conteúdo extraído deste documento. A Segurança Cyberframework.online está autorizando nosso ponto de contato na " . $project['client_name'] . " para: visualizar e divulgar este documento conforme achar adequado, de acordo com a política e procedimentos de tratamento de dados da " . $project['client_name'] . ".";
$texto .= "Este documento deve ser marcado como “CONFIDENCIAL” e, portanto, sugerimos que este documento seja divulgado com base na “necessidade de saber";
$texto .= "Abordar questões relacionadas ao uso adequado e legítimo deste documento para:";
$texto .= $project['client_name'] . "</br>" . limparBody( $project['client_address']);

$texto .= "<h1>ISENÇÕES DE RESPONSABILIDADE</h1>";
$texto .= "As informações apresentadas neste documento são fornecidas no estado em que se encontram e sem garantia. As avaliações de vulnerabilidade são uma análise “pontual” e, como tal, é possível que algo no ambiente possa ter mudado desde que os testes refletidos neste relatório foram executados. Além disso, é possível que novas vulnerabilidades tenham sido descobertas desde a execução dos testes. Por esse motivo, este relatório deve ser considerado um guia, não uma representação 100% do risco que ameaça seus sistemas, redes e aplicativos.";
$texto .= "<h1>PROPÓSITOS</h1>";
$texto .= $project['client_name'] . " solicitou que a Cyberframework.online realizasse um exame de segurança detalhado de seu Ambiente público, domínios venki.com.br, heflo.com, app.hreflo.com e academy.heflo.com.";
$texto .= "Estes ambientes baseados na web estavam em produção no momento do teste e tivemos acesso a um sistema de teste/preparação.";
$texto .= "Este esforço de teste ocorreu em novembro de 2021. Algumas descobertas preliminares foram fornecidas, e este relatório está sendo apresentado para mostrar a totalidade dos  resultados de nossos esforços de teste e para fazer recomendações quando apropriado.";

$texto .= "<h1>ESCOPO</h1>";
$texto .= limparBody($project['scope']);


$texto = limparBody($texto);
//$texto .= add_domains($project['_id'], $pdf);
//$texto = str_replace($texto, '<body>');
//$texto = str_replace($texto, '</html>');
//$texto = str_replace($texto, '</body>');

$myfile = fopen(dirname(__DIR__)  . "/tmp/texto.txt", "w") or die("Unable to open file!");
fwrite($myfile, $texto);
fclose($myfile);

print($texto);








