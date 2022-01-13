<?php
//route/edb.php

require_once dirname(__DIR__, 1)  . '/api/edb.php';
require_once dirname(__DIR__, 1)  . '/api/user.php';

$data = json_decode(file_get_contents('php://input'), true);

// ========== TRABALHANDO ==================

// FkAdd( $entity, $table, $fk, $domain = null)
function service_exec($domain, $service, $version, $values) {
	$retorno = ServiceExec($domain, $service, $version, $values, "", session_load('token') );
	echo(json_encode($retorno));
}

function fk_add($data){
    $retorno = Database::FkAdd($data['entity'], $data['table'], $data['fk'], $data['domain']);
    echo(json_encode($retorno));
}

function tables($data){
    $retorno = Database::Tables($data['domain']);
    echo(json_encode($retorno));
}

function table_fields($data){
    $retorno = Database::Fields($data['domain'], $data['name']);
    echo(json_encode($retorno));
}
	
	
function diagram_load($data){
    $retorno = Database::DiagramLoad($data['domain'], $data['diagram']);
    echo(json_encode($retorno));
}

function diagram_remove($data){
    $retorno = Database::DiagramRemove($data['domain'], $data['diagram']);
    echo(json_encode($retorno));
}

function diagram_save($data){
    $retorno = Database::DiagramSave($data['domain'], $data['diagram']);
    echo(json_encode($retorno));
}

function diagram_list($data){
    $retorno = Database::DiagramList($data['domain']);
    echo(json_encode($retorno));
}

function removeentity($data){
	$retorno = Database::RemoveEntity($data['domain'], $data['name']  )     ;
    echo(json_encode($retorno));
}


function inner($data){
	$retorno = Database::PageV($data['inner'], $data['name'], $data['page'], $data['records'], session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}

function layoutremove($data){
    $retorno = Database::LayoutRemove($data['entity'], $data['name'], session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}


// layout

function limits($data){
	$retorno = Database::Limits($data['domain']);
    echo(json_encode($retorno));
}

function status($data){
	$retorno = Database::Status($data['domain']);
    echo(json_encode($retorno));
}

function layoutlist($data){
    $retorno = Database::LayoutList($data['entity'], session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}

function layoutadd($data){
    $retorno = Database::LayoutAdd($data['entity'], $data['name'], session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}



function layoutedit($data){
    $retorno = Database::LayoutEdit($data['entity'], $data['name'], $data['layout'], session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}

function layoutremovefield($data){
    $retorno = Database::LayoutRemoveField($data['entity'], $data['name'], $data["index"], session_load('token'), null);
    echo(json_encode($retorno));
}
	
function layoutget($data){
    $retorno = Database::LayoutGet($data['entity'], $data['name'], session_load('token'), null);
    echo(json_encode($retorno));
}


// administracao
function delete($data){
    $retorno = Database::Delete($data['entity'], $data['keys'], $data['values'], $data['hash'], false, session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}

function write($data){
	//$entity, $keys, $values, $hash, $cache=false, $user=null, $domain=null
    $retorno = Database::Write($data['entity'], $data['keys'], $data['values'], $data['hash'], false, session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}

function read($data){
    $retorno = Database::Data($data['entity'], $data['keys'], $data['values'], false, session_load('token'), $data['domain']);
    echo(json_encode($retorno));
}


function unique($data){
	$retorno = Database::Unique(session_load('token'));
    echo(json_encode($retorno));
}

function addfield($data){
	//$domain, $name, $type, $user
	$retorno = Database::AddField($data['domain'], $data['entity'],  $data['name'], $data['type'], session_load('token'));
    echo(json_encode($retorno));
}

function entity($data){
	$retorno = Database::Entity($data['domain'], $data['entity'], session_load('token'));
    echo(json_encode($retorno));
}


function serviceexec($data){
    $retorno = Database::ServiceExec($data['domain'], $data['service'], $data['version'], $data['values'], false, session_load('token'));
    echo(json_encode($retorno));
}


function services($data){
	$retorno = Database::Services($data['domain']);
    echo(json_encode($retorno));
}

function service($data){
	$retorno = Database::Service($data['domain'], $data['service']);
    echo(json_encode($retorno));
}
function serviceset($data){
	$retorno = Database::ServiceSet($data['domain'], $data['service']);
    echo(json_encode($retorno));
}

function servicesadd($data){
	$retorno = Database::ServiceAdd($data['domain'], $data['service']);
    echo(json_encode($retorno));
}

// --------------- ADMINISTRAÇAO --------------

function setdescriptionrepository($data){
	$retorno = Database::SetDescrRepository($data['domain'], $data['description']);
    echo(json_encode($retorno));
}

// retorna dados d o repostirio
function databases($data){
	$domain = null;
	if(array_key_exists('domain', $data)){
		$domain = $data['domain'];
	}
	$retorno = Database::Databases($domain);
    echo(json_encode($retorno));
}

function repository($data){
	$retorno = Database::Repository($data['domain']);
    echo(json_encode($retorno));
}

// todas as entidades
function entitys($data){
	$retorno = Database::Entitys($data['entity'], $data['domain']);
    echo(json_encode($retorno));
}

// busca uma entidade
function addentity($data){
	$retorno = Database::AddEntity($data['domain'], $data['name']  )     ;
    echo(json_encode($retorno));
}

function persist($data){
	$retorno = Database::Persist($data['domain'], session_load('token'));
    echo(json_encode($retorno));
}



// -------------------
function list_data($data){
    $order = [ array("field" => "_id", "order" => "asc") ];
	$limit = 999999;
	$where = [];
	$count = false;
	
    if(array_key_exists("order", $data) &&  $data['order'] != null){
        $order = $data['order'];
    }
	
	if(array_key_exists("limit", $data) &&  $data['limit'] != null){
        $limit = $data['limit'];
    }
	
	if(array_key_exists("count", $data) &&  $data['count'] != null){
        $count = $data['count'];
    }
	
	if(array_key_exists("where", $data) &&  $data['where'] != null){
        $where = $data['where'];
    } else {
		for($i = 0; $i < count($data['keys']); $i++){
			array_push($where, "=");
		}
	}
	
    $retorno = Database::List_data($data['domain'], $data['entity'], $data['keys'], $data['values'], $limit, $order, $where, $count,  session_load('token'));
    echo(json_encode($retorno));
}





function clear($data){
	$retorno = Database::Clear( $data['name']);
    echo(json_encode($retorno));
}




// dados


//

function procedure($data){
    $retorno = Database::Procedure($data['procedure'], $data['keys'], $data['values'], false, session_load('token'));
    echo(json_encode($retorno));
}






function page($data){
    $retorno = Database::Page($data['entity'], $data['keys'], $data['values'], $data['name'], $data['page'], $data['records'], session_load('token'), $data['order']);
    echo(json_encode($retorno));
}




function monitoring($data){
    echo(json_encode(Database::Monitoring($data, session_load('token'))));
}

require_once __DIR__  . '/events.php';
