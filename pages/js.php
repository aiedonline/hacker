<?php
	try{
	importar_jscloud("layout", "SetContext", "v1");
	$fileContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/" . $SITE . "/"  . '/' . $path . ".js");

	importacoes($fileContent);

	preg_match_all('/\/\/import\s+\w+/', $fileContent, $matches);
	for ($i = 0; $i < count($matches[0]); $i++) {
		$arquivo = explode(" ",  $matches[0][$i])[1];
		//
		if(file_exists($LOCAL_SITE . "pages/". $arquivo .".js")) {
			importacoes(file_get_contents($LOCAL_SITE . "pages/". $arquivo .".js"));
			importar_js("pages/" . $arquivo);
		}
	}
	
	if(isset($depend)){
		for($i = 0; $i < count($depend); $i++){
			importar_js($depend[$i]);
		}
	}
	importar_js($path);
	importar_js("js/funcoes");

	if(file_exists($LOCAL_SITE . "/". $path .".css")) {
		print_r("    <link rel='stylesheet' href='/" . $SITE . "/". $path .".css?id=". $UUID_JS ."'></link>");
	}
} catch (Exception $e) {
    error_log($e->getMessage(), 0);
}
?>
<script language="JavaScript">
	SetContext( 'raiz' );
	$( document ).ready(function() {
		main();
	});	
</script>
