<?php
// FUNCOES
// Funçòes genéricas

// Pegando o IP real
$ip = null;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

function console($texto){
	 print_r('<script>console.log("PHP: ' .  $texto  . ' ");</script>');
}
function error($texto){
	 print_r('<script>console.error("PHP: ' .  $texto  . ' ");</script>');
}

function debug($variable, $texto=null){
	
	print_r('<script>console.error("DEBUG: var=' . $variable  . ' ");</script>');
}

//TODO: Isso tem que vir do arquivo de configuraçao.....
$UUID_JS = "";
$importados = [];

$LOCAL_SITE = dirname(dirname(__FILE__) . "/") ;
$CONFIG = json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"));
// carregando agora informações da página baseado no template
$TEMPLATE = array("page" => array("logout" => array("exit_url" => "")) );
if( file_exists(dirname(dirname(__FILE__)). "/data/template.json")  == true ) {
	$TEMPLATE= json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/template.json"), true);
}

if(array_key_exists("uuid", $CONFIG) == true){
	$UUID_JS = $CONFIG->uuid;
} else {
	$UUID_JS =  md5(uniqid(""));
}

$URL_CLOUD = $CONFIG->jscloud . "project/";
$SITE = $CONFIG->ui->sigla;
$inicio_path = strpos($_SERVER["REQUEST_URI"], "/" . $SITE . "/") + strlen($SITE) + 2;
$fim_path    = strpos($_SERVER["REQUEST_URI"], ".php") - strlen($SITE) - 2;
$path = substr($_SERVER["REQUEST_URI"], $inicio_path, $fim_path );

function importar_js($path){
    global $SITE;
	global $importados;
	console($path);
	if(in_array($path, $importados)){
		return;
	}
	array_push($importados, $path);
	
    $filename =  $_SERVER['DOCUMENT_ROOT'] . "/" . $SITE . "/"  . $path . ".js";
    if(file_exists($filename)) {
        echo('<script src="/' . $SITE . "/" . $path . '.js?id=' . hash_file('md5', $filename) . '"></script>');
    }
}

function importar_jscloud($project, $path, $version){
	global $URL_CLOUD;
	global $importados;
	global $UUID_JS;
	
	if(in_array($path . $version, $importados)){
		return;
	}
	array_push($importados, $path . $version);
	
	 echo('<script src="' .  $URL_CLOUD . "/" . $project . "/" . $path . ".cp/" . $version . '.js?id='. $UUID_JS .'"></script>');
}

function importar_css($path){
    global $SITE;
    $filename =  $_SERVER['DOCUMENT_ROOT'] . "/" . $SITE . "/" . $path . ".css";
    if(file_exists($filename)) {
        echo('<link href="/' . $SITE . "/" . $path . '.css?id=' . hash_file('md5', $filename) . '" rel="stylesheet" type="text/css" />');
    }
}


function http_response($url)
{
	try{
		return file_get_contents_res($url, false);
	}catch (\Exception $e) {
        error_log($e->getMessage(), 0);
        return null;
    }
} 

// tive que sobrepor isso par evitar probemas de SSL e TLS
function file_get_contents_res($url){
	global $UUID_JS;

	if( !file_exists(dirname( __dir__ ) . "/tmp/") ){
		mkdir(dirname( __dir__ ) . "/tmp/", 0700);
	}

	$file_cache_name = dirname( __dir__ ) . "/tmp/" . md5($UUID_JS . $url);
	if( file_exists( $file_cache_name  ) ){
		return file_get_contents($file_cache_name);
	}
	$context = stream_context_create(array(
		'socket' => ['bindto' => '0:0']
	));

	if(strpos($url, "http") === false){
		$url = dirname( __dir__, 2 ) . $url;
	}
	$response = file_get_contents(  $url, false, $context);
	if($response === false){
		
		die('Error');
	} else {
		file_put_contents($file_cache_name, $response);
		return $response;
	}
	

}

function importacoes($streamfile) {
	global $importados;
	
	preg_match_all('/\/\/require\s+[\w|\/]+\s+\w+\s+\w+/', $streamfile, $matches);
	for ($i = 0; $i < count($matches[0]); $i++) {
		$funcao = explode(" ",  $matches[0][$i])[1];
		$projeto = explode(" ", $matches[0][$i])[2];
		$vercao = explode(" ",  $matches[0][$i])[3];

		$url =  $GLOBALS['URL_CLOUD'] . $projeto ."/" . $funcao . ".cp/" . $vercao . ".js";
		$o_arquivo = http_response($url);
		if($o_arquivo != null) {
			importacoes($o_arquivo);
		}
		
		if(! in_array($funcao . $vercao, $importados)){
			array_push($importados, $funcao . $vercao);		
			print_r("<script src='" . $url . "?id=". $GLOBALS["UUID_JS"] ."' ></script>\n");
		}
	}
}

?>