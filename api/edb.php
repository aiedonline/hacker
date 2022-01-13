<?php

// EDB em API
//api/edb.php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

require_once __DIR__  . '/json.php';

class Database {
	
	public static function LayoutGet($entity, $name, $user, $domain=null){
		if($domain == null){
		   $domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_get", array( "domain" => substr($domain, strrpos($domain, "/")), "entity" => $entity, "name" => $name), false, null);
    }
	
	public static function LayoutRemoveField($entity, $name, $index, $user, $domain=null){
		if($domain == null){
		   $domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_remove_field", array( "index" => $index,   "domain" => substr($domain, strrpos($domain, "/")), "entity" => $entity, "name" => $name), false, null);
    }
	
	
	
	
	public static function Config(){
		try {
			return json_decode(file_get_contents(dirname(dirname(__FILE__)). "/data/config.json"));
		}catch (Exception $e) {
            error_log($e->getMessage(), 0);
            return null;
        }
	}
	
	//$domain, $action, $data, $cache, $user ,$group, $version=null
	public static function Tables($domain, $user = null){
		return Database::ExecuteV2($domain, "tables",  array( "domain" => $domain), false, $user, "reverse", "3");
	}
	
	public static function Fields($domain, $name, $user = null){
		return Database::ExecuteV2($domain, "fields",  array( "domain" => $domain, "name" => $name), false, $user, "reverse", "3");
	}
	
	public static function Install($domain, $server_ip, $server_db, $server_user, $server_password, $server_port, $admin_cpf, $admin_user, $admin_password, $user = null){
		return Database::ExecuteV2($domain, "install",  array( "domain" => $domain, "server_ip" => $server_ip, "server_db" => $server_db,
															 "server_user" => $server_user, "server_password" => $server_password, "server_port" => $server_port,
															 "admin_cpf" => $admin_cpf, "admin_user" => $admin_user, "admin_password" => $admin_password), false, $user, "edb", "3");
	}
	
	public static function DiagramRemove($domain, $diagram, $user = null){
		return Database::ExecuteV2($domain, "remove",  array( "domain" => $domain, "diagram" => $diagram), false, $user, "diagram", "3");
	}
	public static function DiagramLoad($domain, $diagram, $user = null){
		return Database::ExecuteV2($domain, "load",  array( "domain" => $domain, "diagram" => $diagram), false, $user, "diagram", "3");
	}
	public static function DiagramSave($domain, $diagram, $user = null){
		return Database::ExecuteV2($domain, "save",  array( "domain" => $domain, "diagram" => $diagram), false, $user, "diagram", "3");
	}
	public static function DiagramList($domain, $user = null){
		return Database::ExecuteV2($domain, "list",  array( "domain" => $domain), false, $user, "diagram", "3");
	}
	
	public static function ExecuteV2($domain, $action, $data, $cache, $user ,$group, $version=null ){
        try{
			
            $CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");
            $postData = array(
                'token' => $CONFIG->edbkey,
                'domain' => $domain,
                'action' => $action,
                'envelop' => $data,
                'cache' => $cache,
                'user' => $user,
				'group' => $group,
				'version' => $version
            );
		

            // Create the context for the request
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($postData)
                )
            ));

            // Send the request
            $response = file_get_contents($CONFIG->edb . "execute.php", false, $context);
            error_log($response, 0);
            // Check for errors
            if($response === false){
                die('Error');
            }
			
			$buffer_js = json_decode($response, true);
			return $buffer_js;
        }catch (Exception $e) {
			error_log($e->getTraceAsString(), 0);
            throw $e;
        }
    }
	
	
	
	
	public static function FkAdd( $entity, $table, $fk, $domain = null, $user = null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "fk_add",  array( "domain" => $domain, "entity" => $entity, "table" => $table, "fk" => $fk), false, $user);
	}
	
	public static function LayoutRemove($entity, $name, $user, $domain=null){
		if($entity == null || $name == null){
			throw new Exception('Dados estão faltando, entrada: ' . func_get_args() );
		}
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_remove", array( "domain" => $domain, "entity" => $entity, "name" => $name), false, null);
    }
	
	
	// ------------------- trabalhando ------------------------------------------
			//  $retorno = Database::PageV2($data['inner'], $data['name'], $data['page'], $data['records'], session_load('token'));
	public static function PageV($inner, $name, $page, $records, $user=null, $domain=null){
		$data_send = [];
		if($domain == null) {
			$domain = Database::Config()->domain;
		}
		if(session_exist('user_cookie')) {
			 $user = json_decode(session_load('user_cookie'), true)['_id'];
		}
        array_push($data_send, array( "inner" => $inner, "page" => $page, "records" => $records, "name" => $name  ));
        $buffer = Database::Execute($domain, "page", $data_send, false, $user);

        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }
	

	
	public static function Status($domain){
		if($domain == null){
			throw new Exception('Dados estão faltando, entrada: ' . func_get_args() );
		}
		return Database::Execute($domain, "status", array(), false, $user);
	}
	
	public static function Limits($domain){
		if($domain == null){
			throw new Exception('Dados estão faltando, entrada: ' . func_get_args() );
		}
		return Database::Execute($domain, "limits", array(), false, $user);
	}
	/**
	*
	*/
	public static function LayoutAdd($entity, $name, $user, $domain=null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_add", array( "domain" => $domain, "entity" => $entity, "name" => $name), false, null);
    }
	

	
	public static function LayoutEdit($entity, $name, $layout, $user, $domain=null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_edit", array( "domain" => $domain, "entity" => $entity, "name" => $name,"layout" => $layout), false, null);
    }
	/**
	*
	*/
	public static function LayoutList($entity, $user, $domain=null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "layout_list", array( "domain" => $domain, "entity" => $entity), false, null);
    }

	
	/**
	*
	*/
	public  static function Entity($domain, $entity, $user=null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "entity", array( "domain" => $domain, "entity" => $entity), false, null);
	}
	
	
	
	
	
	public static function unique($user){
		return Database::Execute(Database::Config()->domain, "unique", array( "domain" => $domain), false, null);
    }
	
	
	
	public static function AddField($domain, $entity, $name, $type, $user){
		return Database::Execute($domain, "field_add", array( "domain" => $domain, "entity" => $entity,  "name" => $name, "type" => $type ), false, null);
    }
	
	public static function Execute($domain, $action, $data, $cache, $user ){
        try{
			
            $CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");
            $postData = array(
                'token' => $CONFIG->edbkey,
                'domain' => $domain,
                'action' => $action,
                'envelop' => $data,
                'cache' => $cache,
                'user' => $user
            );
		

            // Create the context for the request
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($postData)
                )
            ));

            // Send the request
            $response = file_get_contents($CONFIG->edb . "execute.php", false, $context);
            // Check for errors
            error_log($response, 0);
            if($response === false){
                die('Error');
            }
			
			$buffer_js = json_decode($response, true);
			return $buffer_js;
        }catch (Exception $e) {
			error_log($e->getTraceAsString(), 0);
            throw $e;
        }
    }
	
	
	
	public static function Services($domain){
		return Database::Execute($domain, "services", array( "domain" => $entity  ), false, null);
	}

	public static function Service($domain, $service){
		return Database::Execute($domain, "service", array( "domain" => $entity, "service" =>  $service  ), false, null);
	}
	public static function ServiceSet($domain, $service){
		return Database::Execute($domain, "serviceset", array( "domain" => $entity, "service" =>  $service  ), false, null);
	}
	
	public static function ServiceAdd($domain, $service){
		return Database::Execute($domain, "servicesadd", array( "domain" => $entity, "service" =>  $service  ), false, null);
	}
	
	public static function SetService($domain, $service){
		return Database::Execute($domain, "service_update", array( "domain" => $entity, "service" =>  $service  ), false, null);
	}
	
	//----------------------------------------

	
	public static function  SetDescrRepository($domain, $description){
		return Database::Execute($domain, "repostiorydescription", array("description" => $description), false, $user);
	}
	
	public static function  Persist($domain, $user){
		return Database::Execute($domain, "persist", [], false, $user);
	}
	
	public static function Databases($domain=null){
		if($domain == null) {
			$domain = "/local/edb";
		}
		return Database::Execute($domain, "databases", [], false, $user);
	}
	
	
	public static function Repository($domain){
		return Database::Execute($domain, "repository", array(), false, $user);
	}	
		
	public static function Entitys($entity, $domain=null){
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		return Database::Execute($domain, "entitys", array("entity" => $entity), false, $user);
	}

	public static function  RemoveEntity($domain, $name){
		return Database::Execute($domain, "removeentity", array("name" => $name), false, $user);
	}
	
	public static function  AddEntity($domain, $name){
		return Database::Execute($domain, "addentity", array("name" => $name), false, $user);
	}
	
	public static function RmField($domain, $entity, $name, $user){
        return Database::Administrator($domain, "field_rm", array("entity" => $entity,  "name" => $name), $user);
    }
	

	
	public static function Clear( $name){
		$data_send = [];
		array_push($data_send, array("name" => $name  ));
		return Database::Execute(Database::Config()->domain, "clear", $data_send, false, null);
    }

	
	
    public static function Data($entity, $keys, $values, $cache=false, $user=null, $domain=null){
		if($entity == null){
			throw new Exception('Dados estão faltando, entrada: ' . func_get_args() );
		}
		try {
			if($domain == null){
				$domain = Database::Config()->domain;
			}

			$data_send = [];
			$query = array();
			for($i = 0; $i < count($keys); $i++){
				$query[$keys[$i]] = $values[$i];
			}

			array_push($data_send, array( "entity" => $entity, "data" =>  $query  ));

			$buffer = Database::Execute($domain, "read", $data_send, $cache, $user);
			if($buffer != null){
				return $buffer['rows'];
			} else {
				return null;
			}
		}catch (Exception $e) {
            error_log($e->getMessage(), 0);
            return null;
        }
    }

    public static function Monitoring($collection, $user){
        return Database::Execute(Database::Config()->domain, "monitoring", $collection, false, $user);
    }

    public static function Procedure($entity, $keys, $values, $cache=false, $user=null){
        //if($user == null){
            //error_log('Falta usuário para leitura da entidade: ' . $entity, 0);
        //}
        $data_send = [];
        $query = array();
        for($i = 0; $i < count($keys); $i++){
            $query[$keys[$i]] = $values[$i];
        }

        array_push($data_send, array( "procedure" => $entity, "data" =>  $query  ));
        $buffer = Database::Execute(Database::Config()->domain, "procedure", $data_send, $cache, $user);

        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }
	
	
	public static function List_data($domain, $entity, $keys, $values, $limit=99999, $order=[ array("field" => "_id", "order" => "asc") ], $where=[], $count=false, $user=null){

		
        $data_send = [];
        $query = array();
        for($i = 0; $i < count($keys); $i++){
            $query[$keys[$i]] = $values[$i];
        }
		
		if(count($keys) > 0 && count($where) == 0){
			 for($i = 0; $i < count($keys); $i++){
				array_push($where, "=");
			 }
		}

        array_push($data_send, array( "entity" => $entity, "data" =>  $query, "order" => $order, "limit" => $limit, "where" => $where, "count" => $count  ));
		
		if($domain == null) {
			$domain = Database::Config()->domain;
		}
		
		
        $buffer = Database::Execute($domain, "list", $data_send, false, $user);

        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }

    

    public static function Delete($entity, $keys, $values, $hash, $cache=false, $user=null, $domain=null){
        
        $data_send = [];
        $query = array();
        for($i = 0; $i < count($keys); $i++){
            $query[$keys[$i]] = $values[$i];
        }
        
		if($domain == null){
			$domain = Database::Config()->domain;
		}
        array_push($data_send, array( "entity" => $entity, "data" =>  $query, "hash" => $hash  ));
        $buffer = Database::Execute($domain, "delete", $data_send, $cache, $user);
        
        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }



    public static function Page($entity, $keys, $values, $name, $page, $records, $user=null, $order=[ array("field" => "_id", "order" => "asc") ]){
        //if($user == null){
        //    error_log('Falta usuário para leitura da entidade: ' . $entity, 0);
        //}
		if(session_exist('user_cookie')) {
			 $user =  json_decode(session_load('user_cookie'), true)['_id'];
		}
		
        $data_send = [];
        $query = array();
        for($i = 0; $i < count($keys); $i++){
            $query[$keys[$i]] = $values[$i];
        }

        array_push($data_send, array( "entity" => $entity, "data" =>  $query, "page" => $page, "records" => $records, "name" => $name, "order" => $order  ));
        $buffer = Database::Execute(Database::Config()->domain, "page", $data_send, false, $user);

        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }

	
	
	public static function Administrator($domain, $action, $data, $user ){
        try{
			
            $CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");

            $postData = array(
                'token' => $CONFIG->edbkey,
                'domain' => $domain,
                'action' => $action,
                'envelop' => $data,
                'user' => $user
            );

            // Create the context for the request
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($postData)
                )
            ));

            // Send the request
            
            $response = file_get_contents($CONFIG->edb . "dictionary/execute.php", false, $context);
            // Check for errors
            error_log($response, 0);
            if($response === false){

                die('Error');
            }

            try{ // try para tratar só a conversao do json, alguns serviços podem retornar um texto para o genexus
                $buffer_js = json_decode($response, true);
                return $buffer_js;
            }catch (\Exception $e) {
                error_log($response, 0);
                error_log($e->getMessage(), 0);
                return $response;
            }

        }catch (\Exception $e) {
            error_log($e->getMessage(), 0);
            return $e;
        }
    }
	/**
	*
	*/
	public static function Write($entity, $keys, $values, $hash, $cache=false, $user=null, $domain=null){ 
		
		if($domain == null){
			$domain = Database::Config()->domain;
		}
		
        $data_send = []; // array de execuçoes
		for($j = 0; $j < count($entity); $j++) {
			$query = array();
			for($i = 0; $i < count($keys[$j]); $i++){
				$query[$keys[$j][$i]] = $values[$j][$i];
			}
			$buffer = array( "entity" => $entity[$j], "data" =>  $query, "hash" => "");
			//if($j < count($hash)) {
			//	$buffer['hash'] = $hash[$j] ;
			//}
			//else {
			//	$buffer['hash'] = "" ;
			//}
			array_push($data_send, $buffer);
		}
        if($user == null) {
            if(session_exist('user_cookie')) {
                $user = json_decode(session_load('user_cookie'), true)['_id'];
            }
        }

		$buffer = Database::Execute($domain, "write", $data_send, $cache, $user);
        error_log(json_encode($buffer), 0);
        if($buffer != null){
            return $buffer['rows'];
        } else {
            return null;
        }
    }


	public static function ServiceExec($domain, $service, $version, $values, $cache, $user ){
        try{
            $CONFIG = \fs\Json::FromFile(   dirname(__DIR__, 1) . "/data/config.json");

            $postData = array(
                'token' => $CONFIG->edbkey,
                'domain' => $domain,
				"engine" => "service",
				'action' => "serviceexec",
                'service' => $service,
				'version' => $version,
                'envelop' => array("values" => $values, 'service' => $service, 'version' => $version),
                'cache' => $cache,
                'user' => $user
            );
            // Create the context for the request
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($postData)
                )
            ));

            // Send the request
            
            $response = file_get_contents($CONFIG->edb . "execute.php", false, $context);
            // Check for errors
            error_log($response, 0);
            if($response === false){
                error_log($response, 0);
                die('Error');
            }

            try{ // try para tratar só a conversao do json, alguns serviços podem retornar um texto para o genexus
                $buffer_js = json_decode($response, true);
                //if($buffer_js['status'] == 1){
                //    return $buffer_js['data'];
                //}else{
                return $buffer_js;
                //}
            }catch (\Exception $e) {
                error_log($response, 0);
                error_log($e->getMessage(), 0);
                return $response;
            }

        }catch (\Exception $e) {
            error_log($e->getMessage(), 0);
            return $e;
        }
    }
	
	
	
    
}


?>