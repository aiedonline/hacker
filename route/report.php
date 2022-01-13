<?php

require_once dirname(__DIR__) . '/api/rtf/lib/PHPRtfLite.php';
require_once dirname(__DIR__) . '/api/edb.php';


$project = Database::Data("project", ["_id"], [$_GET["id"]], $cache=false)[0]['data'];
$html = "<h1>Penetration Test Report</h1></br><h3>". $project['client_name'] ."</h3></br></br></br></br></br>Por cyberframework.online";
$html .=  "<h1>Confidencialidade</h1>Em nenhuma hipótese a CyberFramework.online será responsável por qualquer pessoa por danos especiais, incidentes, colaterais ou consequentes decorrentes do uso dessas informações.";
$html .= "Este documento contém informações confidenciais e de propriedade da CyberFramework.online e " . $project['client_name'] . ". Extremo cuidado deve ser tomado antes de distribuir cópias deste documento ou o conteúdo extraído deste documento. A Segurança Cyberframework.online está autorizando nosso ponto de contato na " . $project['client_name'] . " para: visualizar e divulgar este documento conforme achar adequado, de acordo com a política e procedimentos de tratamento de dados da " . $project['client_name'] . ".";
$html .= "Este documento deve ser marcado como “CONFIDENCIAL” e, portanto, sugerimos que este documento seja divulgado com base na “necessidade de saber";
$html .= "Abordar questões relacionadas ao uso adequado e legítimo deste documento para:";
$html .= $project['client_name'] . "</br>" . $project['client_address'];

$html .= "<h1>ISENÇÕES DE RESPONSABILIDADE</h1>";
$html .= "As informações apresentadas neste documento são fornecidas no estado em que se encontram e sem garantia. As avaliações de vulnerabilidade são uma análise “pontual” e, como tal, é possível que algo no ambiente possa ter mudado desde que os testes refletidos neste relatório foram executados. Além disso, é possível que novas vulnerabilidades tenham sido descobertas desde a execução dos testes. Por esse motivo, este relatório deve ser considerado um guia, não uma representação 100% do risco que ameaça seus sistemas, redes e aplicativos.";

$html .= "<h1>PROPÓSITOS</h1>";
$html .= $project['client_name'] . " solicitou que a Cyberframework.online realizasse um exame de segurança detalhado de seu Ambiente público, domínios venki.com.br, heflo.com, app.hreflo.com e academy.heflo.com.";
$html .= "Estes ambientes baseados na web estavam em produção no momento do teste e tivemos acesso a um sistema de teste/preparação.";
$html .= "Este esforço de teste ocorreu em novembro de 2021. Algumas descobertas preliminares foram fornecidas, e este relatório está sendo apresentado para mostrar a totalidade dos  resultados de nossos esforços de teste e para fazer recomendações quando apropriado.";

$html .= "<h1>ESCOPO</h1>";
$html .= $project['scope'];

#$html .= add_domains($project['_id'], $pdf);


// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

$rtf = new PHPRtfLite();
$sect = $rtf->addSection();
$sect->writeText($html, new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));

// save rtf document
$rtf->save(dirname(__DIR__)  . '/tmp/report.rtf');
