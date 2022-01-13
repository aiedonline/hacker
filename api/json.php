<?php
namespace fs;

class Json {
    static public function FromFile($path, $padrao = null){
        try {
			if(file_exists($path) == true) {
            	$json = file_get_contents($path);
            	return json_decode($json);
			} else {
				if($padrao != null){
					Json::WriteFile($path, $padrao);
					return $padrao;
				}
			}
        }catch(Exception $error){
            echo $error;
        }
        return null;
    }
	
	static public function WriteFile($path, $json){
		try{
			$fp = fopen($path, 'w');
			fwrite($fp, json_encode($json));
			fclose($fp);
			return true;
		}catch(Exception $error){
            echo $error;
        }
		return null;
	}
}

?>