<?php
//ini_set('memory_limit', '-1');
set_time_limit(0);
//ini_set('memory_limit', '2048M');
//require_once dirname(__DIR__) . '/api/tcpdf/tcpdf_include.php';
require_once dirname(__DIR__) . '/api/tcpdf/tcpdf.php';


$author = "cyberframework.online";
$title = "Penetration Test Report";
$subject = "Penetration Test Report";

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Wellington Pinto de Oliveira');
$pdf->SetTitle('Projeto');
$pdf->SetSubject('Projeto');
$pdf->SetKeywords('Projeto');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('times', '', 11, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();



require_once dirname(__DIR__) . '/api/edb.php';

function add_note($index, $domain_id, $pdf ){
    $html = "";
    try{
        $dados = Database::List_data("/local/hacker", "note", ["entity_id"], [$domain_id],$limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
        
        //if(count($dados) > 0){
        //    $html .= "<h3>". strval($index) . ".3. Notas</h3>";
        //}
        //for($k = 0; $k < count($dados); $k){
        //    if($dados[$k]['note'] == "") continue;
        //    $html .= $dados[$k]['note'] . "</br>";
        //    
        //}
    } catch (Exception $e) {
        echo 'Exceção capturada: ',  $e->getMessage(), "\n";
    }
    return $html;
}



function array_to_html($a){
    $html = "<table border='1'>";
    for($ji = 0; $ji < count($a); $ji++){
        $html = $html . "<tr>";
        for($jj = 0; $jj < count($a[$ji]); $jj++){
            $html = $html . "<td>" . $a[$ji][$jj] . "</td>";
        }
        $html = $html . "</tr>";
    }
    $html = $html . "</table>";
    return $html;
}

function add_evidence($cve_occurrence_id, $pdf ){
    $html = "";
    $dados = Database::List_data("/local/hacker", "evidence", ["cve_occurrence_id"], [$cve_occurrence_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['title']) ) {
            $html .= $dados[$i]['title'] . "</br>" . $dados[$i]['evidence'] ;
            //$pdf->writeHTML($html, true, 0, true, true);
        }
    }
    return $html;
}

function add_cve($indice, $occurrence_id, $pdf){
    $html = "";
    $dados = Database::List_data("/local/hacker", "cve_occurrence", ["occurrence_id"], [$occurrence_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    if(count($dados) > 0){
        //$pdf->writeHTML("<br/><br/><h3>" . strval($indice) . ".2. Common Vulnerabilities and Exposures</h3>"
        //. '<span style="text-align:justify;">' . "O Common Vulnerabilities and Exposures é um banco de dados que registra vulnerabilidades e exposições relacionadas a segurança da informação conhecidas publicamente. Abaixo temos uma lista de Vulnerabilidades listadas para execução de testes de intrusão</span>.<br/>", true, 0, true, true);
        $html .= "<br/><br/><h3>" . strval($indice) . ".2. Common Vulnerabilities and Exposures</h3>"
        . '<span style="text-align:justify;">' . "O Common Vulnerabilities and Exposures é um banco de dados que registra vulnerabilidades e exposições relacionadas a segurança da informação conhecidas publicamente. Abaixo temos uma lista de Vulnerabilidades listadas para execução de testes de intrusão</span>.<br/>";

    }
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['cve_id']) ) {
            $cve = Database::Data("cve", ["_id"], [$dados[$i]['cve_id']], $cache=false)[0]['data'];
            $html .= "<b> " . $cve['codigo'] . " - " . $cve['description'] . "</b><br/>";
            $html .= $cve['full_description'];
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
                $html .= "<br/>" . array_to_html($tabela);
            }
            //if($dados[$i]['impacto'] != "") {
            //    array_push($j, array("type" => "paragraph", "text" => "<b>Impacto</b>","newpage" => false, "br"=> 0));
            //    array_push($j, array("type" => "paragraph", "text" => $dados[$i]["impacto"],"newpage" => false, "br"=> 0));
            //}
            $html .= add_evidence($dados[$i]["_id"], $pdf);
            //$pdf->writeHTML($html, true, 0, true, true);
        }
    }
    return $html;
}

function add_domain_ip_port($ip_id, $pdf){
    $html = "";
    $dados = Database::List_data("/local/hacker", "ip_port", ["ip_id"], [$ip_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    for($i = 0; $i < count($dados); $i++){
        if( isset($dados[$i]['port']) ) {
            $html .= $dados[$i]['port'] . " - " . $dados[$i]['protocol'] ;
            //$pdf->writeHTML($html, true, 0, true, true);
        }
    }
    return $html;
}

function add_domain_ips($indice, $domain_id, $pdf){
    $dados = Database::List_data("/local/hacker", "ip", ["domain_id"], [$domain_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $html = "";
    if(count($dados) > 0){
        //$pdf->writeHTML("<h3>" . strval($indice) . ".1. IPs e Portas</h3>", true, 0, true, true);
        $html .= "<h3>" . strval($indice) . ".1. IPs e Portas</h3>";
    }
    for($i = 0; $i < count($dados); $i++){
        $j = [];
        if( isset($dados[$i]['ip']) ) {
            $html .= "<h5>". $dados[$i]['ip'] . " - " . $dados[$i]['geo'] ."</h5>";
            //$pdf->writeHTML($html, true, 0, true, true);
            $html .= add_domain_ip_port($dados[$i]['_id'], $pdf);
        }
    }
    return $html;
}

function add_domains($project_id, $pdf){
    $domains = Database::List_data("/local/hacker", "domain", ["project_id"], [$project_id], $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[])[0]['data'];
    $html = "";
    $indice = 1;
    for($i = 0; $i < count($domains); $i++){
        $html .= "<h2>" . strval($indice) . ". Domínio: " . $domains[$i]['domain'] . "</h2>" ;
        $html .= $domains[$i]['about'];
        //$pdf->AddPage();
        //$pdf->writeHTML($html, true, 0, true, true);
        $html .= add_domain_ips($indice, $domains[$i]['_id'], $pdf);
        $html .= add_cve($indice, $domains[$i]['_id'], $pdf);
        $html .= add_note($indice, $domains[$i]['_id'], $pdf );
        $indice = $indice + 1;
        //add_evidence($domains[$i]['_id'], $pdf);
    }
    return $html;
    //return $j;
}


$project = Database::Data("project", ["_id"], [$_GET["id"]], $cache=false)[0]['data'];
$html = "<h1>PENETRATION TEST REPORT</h1><br/><br/><p></p><p></p><h3>". $project['client_name'] ."</h3><br/><br/><br/><br/><br/>Por cyberframework.online";
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->AddPage();

$html = "";
$html .=  "<h1>CONFIDENCIALIDADE</h1>Em nenhuma hipótese a CyberFramework.online será responsável por qualquer pessoa por danos especiais, incidentes, colaterais ou consequentes decorrentes do uso dessas informações.";
$html .= "Este documento contém informações confidenciais e de propriedade da CyberFramework.online e " . $project['client_name'] . ". Extremo cuidado deve ser tomado antes de distribuir cópias deste documento ou o conteúdo extraído deste documento. A Segurança Cyberframework.online está autorizando nosso ponto de contato na " . $project['client_name'] . " para: visualizar e divulgar este documento conforme achar adequado, de acordo com a política e procedimentos de tratamento de dados da " . $project['client_name'] . ".";
$html .= "Este documento deve ser marcado como “CONFIDENCIAL” e, portanto, sugerimos que este documento seja divulgado com base na “necessidade de saber";
$html .= "Abordar questões relacionadas ao uso adequado e legítimo deste documento para:";
$html .= $project['client_name'] . "</br>" . $project['client_address'];

//$pdf->writeHTML($html, true, 0, true, true);
//$pdf->AddPage();

$html .= "<h1>ISENÇÕES DE RESPONSABILIDADE</h1>";
$html .= "As informações apresentadas neste documento são fornecidas no estado em que se encontram e sem garantia. As avaliações de vulnerabilidade são uma análise “pontual” e, como tal, é possível que algo no ambiente possa ter mudado desde que os testes refletidos neste relatório foram executados. Além disso, é possível que novas vulnerabilidades tenham sido descobertas desde a execução dos testes. Por esse motivo, este relatório deve ser considerado um guia, não uma representação 100% do risco que ameaça seus sistemas, redes e aplicativos.";



$html .= "<h1>PROPÓSITOS</h1>";
$html .= $project['client_name'] . " solicitou que a Cyberframework.online realizasse um exame de segurança detalhado de seu Ambiente público, domínios venki.com.br, heflo.com, app.hreflo.com e academy.heflo.com.";
$html .= "Estes ambientes baseados na web estavam em produção no momento do teste e tivemos acesso a um sistema de teste/preparação.";
$html .= "Este esforço de teste ocorreu em novembro de 2021. Algumas descobertas preliminares foram fornecidas, e este relatório está sendo apresentado para mostrar a totalidade dos  resultados de nossos esforços de teste e para fazer recomendações quando apropriado.";



$html .= "<h1>ESCOPO</h1>";
$html .= $project['scope'];

$html .= add_domains($project['_id'], $pdf);
$html .= "";

$html = preg_replace('/<body>/', "", $html);
$html = preg_replace('/<html>/', "", $html);
$html = preg_replace('/<\/body>/', "", $html);
$html = preg_replace('/<\/html>/', "", $html);
$html = preg_replace('/<head>/', "", $html);
$html = preg_replace('/<\/head>/', "", $html);
$html = preg_replace('/<title>/', "",          $html);
$html = preg_replace('/<\/title>/', "",        $html);

$myfile = fopen(dirname(__DIR__)  . "/tmp/html.txt", "w") or die("Unable to open file!");
fwrite($myfile, $html);
fclose($myfile);

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->Output('/tmp/example_002.pdf', 'I');






