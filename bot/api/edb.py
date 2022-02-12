#!/usr/bin/env python
# -*- coding: utf-8 -*-
import requests, json, uuid, os, sys, traceback, hashlib;

from threading import Thread
from multiprocessing import Queue
from multiprocessing.pool import ThreadPool
from datetime import date
from uuid import *

PATH_CACHE = os.environ["ROOT"];

class Database:
	def __init__(self, port=80):
		self.port = 80
		self.sessionId = str(uuid.uuid4())

	def SendServer(self, domain, action, data):
		CONFIG = json.loads(open( PATH_CACHE + "/data/config.json", "r" ).read());
		try:
			edb = {"url" : CONFIG['edb']["url"] + "edb/", "port" : 80, "file" : "execute.php", "default" : "/local/botweb", "token" : CONFIG['edb']["key"]};
			envelope = {};
			envelope['sessionId'] = self.sessionId;
			envelope['trasactionId'] = str(uuid.uuid4());
			envelope['token'] = edb['token'];
			envelope['domain'] = domain ;
			envelope['action'] = action;
			envelope['envelop'] = data;
			envelope['user'] = data['user'];

			url = edb['url'] + edb['file'];
			
			buffer_connection = requests.post(url, json=envelope, verify=False);
			
			buffer = buffer_connection.json();
			if buffer.get('version') and buffer['version'] == 2:
				if buffer['status']:
					result = buffer['data'];
				else:
					result = None;
			else:
				result = buffer;
			return result;
		except Exception as e:
			traceback.print_exc();
			return None;
# ------------------------------------------ shodan -----------------------------------------
def domains(project_id):
    db = Database();
    try:
        retorno = db.SendServer("/local/hacker","list",[{"entity":"domain","data":{"project_id" : project_id}, "order" : [{"field" : "domain", "order" : "desc"}]}]);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        print("Sem conexao com edb.");

def host_list():
    db = Database();
    try:
        retorno = db.SendServer("/local/hacker","list",[{"entity":"ip","data":{}, "order" : [{"field" : "_id", "order" : "desc"}]}]);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        print("Sem conexao com edb.");

def host_read(ip):
    db = Database();
    try:
        retorno = db.SendServer("/local/hacker","read",[{"entity":"ip","data":{"ip" : ip}, "order" : [{"field" : "_id", "order" : "desc"}]}]);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        print("Sem conexao com edb.");

def host_save_shodan(ip, domain_id, shodan_output, script_version):
    return _host_save(ip, domain_id, {"geo" : shodan_output["country_name"] + " - " + shodan_output["city"] }, script_version);

def host_init_shodan(ip, domain_id, dados_do_ip, script_version):
    return _host_save(ip, domain_id, { "geo" : dados_do_ip["country_name"] + " - " + dados_do_ip["city"]}, script_version);

def shodan_save(domain_id, shodan, script_version):
    db = Database();
    try:
        if shodan == None or shodan == "" or len(shodan["matches"]) == 0:
            return;
        json_envelope = [{"entity":"domain","data": {"_id" : domain_id, "shodan" : json.dumps(shodan["matches"][0]) },  "order" : [{"field" : "_id", "order" : "desc"}]}];
        retorno = db.SendServer("/local/hacker","write", json_envelope);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        traceback.print_exc();
        print("Sem conexao com edb.");

def _host_save(ip, domain_id, dados, script_version):
    db = Database();
    try:
        host_carregado_banco = host_read(ip);
        json_envelope = {};
        if host_carregado_banco[0]['font'] == "database":
            # já existe
            json_envelope = [{"entity":"ip","data":
				{"_id" : host_carregado_banco[0]['data']['_id'] , "ip" : ip, "domain_id" : host_carregado_banco[0]['data']['domain_id'] }, 
				"order" : [{"field" : "_id", "order" : "desc"}]}];
        else:
            # não existe
            json_envelope = [{"entity":"ip","data":
				{"_id" : hashlib.md5((ip + domain_id).encode()).hexdigest() , "ip" : ip, "domain_id" : domain_id }, 
				"order" : [{"field" : "_id", "order" : "desc"}]}];
        for key, value in dados.items():
            json_envelope[0]['data'][key] = value;
        json_envelope[0]['data']["script_version"] = script_version;
        retorno = db.SendServer("/local/hacker","write", json_envelope);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        traceback.print_exc();
        print("Sem conexao com edb.");
#---------------------------------------------------WHOIS----------------------------------

def whois_save(domain_id, whois, script_version):
    db = Database();
    try:
        json_envelope = [{"entity":"domain","data": {"_id" : domain_id, "whois" : json.dumps(whois) },  "order" : [{"field" : "_id", "order" : "desc"}]}];
        #json_envelope[0]['data']["script_version"] = script_version;
        retorno = db.SendServer("/local/hacker","write", json_envelope);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        traceback.print_exc();
        print("Sem conexao com edb.");

# ------------------------------------------------- CVES ----------------------
def cve_read(cve):
    db = Database();
    try:
        retorno = db.SendServer("/local/hacker","read",[{"entity":"cve","data":{"codigo" : cve}, "order" : [{"field" : "_id", "order" : "desc"}]}]);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        print("Sem conexao com edb.");

def cve_save_mitre(cve_id, mitre, script_version):
    db = Database();
    try:
        json_envelope = [{"entity":"cve","data": {"_id" : cve_id},  "order" : [{"field" : "_id", "order" : "desc"}]}];
        for key, value in mitre.items():
            json_envelope[0]['data'][key] = value;
        retorno = db.SendServer("/local/hacker","write", json_envelope);
    
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        traceback.print_exc();
        print("Sem conexao com edb.");

# -------------------------- NOTE ----------------------------

def note_read(field, _id, hidden_key = None):
    db = Database();
    try:
        data = {field : _id};
        if hidden_key != None:
            data["hidden_key"] = hidden_key;
        retorno = db.SendServer("/local/hacker","read",[{"entity":"note","data": data, "order" : [{"field" : "_id", "order" : "desc"}]}]);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        print("Sem conexao com edb.");

def note_save(entity_id, titulo, note, hidden_key):
    db = Database();
    try:
        elemento_do_banco = note_read("entity_id", hidden_key);
        json_envelope = {};
        if elemento_do_banco[0]['font'] == "database":
            json_envelope = [{"entity":"note","data": {"_id" : elemento_do_banco[0]['data']['_id'] , "note" : note , "titulo" : titulo, "date_note" : date.today().strftime('%Y-%m-%d %H:%M:%S')}, "order" : [{"field" : "_id", "order" : "desc"}]}];
        else:
            json_envelope = [{"entity":"note","data": {"_id" : hashlib.md5((entity_id + hidden_key).encode()).hexdigest() , "note" : note,  "entity_id" : entity_id, "hidden_key" : hidden_key, "titulo" : titulo , "date_note" : date.today().strftime('%Y-%m-%d %H:%M:%S')},  "order" : [{"field" : "_id", "order" : "desc"}]}];
        retorno = db.SendServer("/local/hacker","write", json_envelope);
        if retorno['status'] == True:
            return retorno['rows'];
        return None;
    except:
        traceback.print_exc();
        print("Sem conexao com edb.");







