#!/
# Executa o shodan.io
# Requisitos:
#   2 - Instalar o shodan: python3 -m pip install shodan
#
# Dados:    {"_id" : "", "project_id" : "", "token" : "", "user" : "",  "shodan_key" : "", "host" : ""};
import os, json, sys, inspect, nmap

sys.path.insert(0, os.environ["ROOT"]);

from api.hacker import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);
#data = {"_id" : "aaaa", "project_id" : "aaa", "token" : "aaaa", "user" : "aaa",  "shodan_key" : "", "host" : "127.0.0.1"};

from shodan import Shodan

try:
    api = Shodan(data["shodan_key"])
    print('\t\033[92m', "[+] (shodan.io)", '\033[0m', data['host'].strip());
    # Lookup an IP
    shodan = api.host(data["host"])
    geo = shodan["country_name"] + " " + shodan["region_code"] + " " + shodan["city"];
    script_version = 1

    envelope = {"_id" : data["_id"], "host" :  data["host"] , "geo" : geo, "shodan" : json.dumps(shodan), "project_id" : data['project_id'], "token" : data["token"], "user" : data["user"] };
    retorno = SendService(data["server_ip"], "update_ip.php", envelope);
    for port in shodan["ports"]:
        n = nmap.PortScanner();
        
        n.scan(data["host"],  str(port));
        evidence = ""; service_name = ""; font = ""; protocol = "";
        if n._scan_result['scan'].get(data["host"]) == None:
            continue;
        if n._scan_result['scan'][data["host"]].get("tcp") != None :
            evidence = json.dumps(n._scan_result['scan'][data["host"]]["tcp"][port]);
            service_name = n._scan_result['scan'][data["host"]]["tcp"][port]["name"] + " " + n._scan_result['scan'][data["host"]]["tcp"][port]["version"];
            font = "nmap";
            protocol = "tcp";
        elif n._scan_result['scan'][data["host"]].get("udp") != None :
            evidence = json.dumps(n._scan_result['scan'][data["host"]]["udp"][port]);
            service_name = n._scan_result['scan'][data["host"]]["udp"][port]["name"] + " " + n._scan_result['scan'][data["host"]]["udp"][port]["version"];
            font = "nmap";
            protocol = "udp";
        envelope = {"ip_id" : data["_id"], "port" :  port , "evidence" : evidence, "font" : font, "protocol" : protocol ,"project_id" : data['project_id'], "token" : data["token"], "user" : data["user"] };
        retorno = SendService(data["server_ip"], "add_ip_port.php", envelope);
except:
    # limpa a API e suprime, não adianta parar por migalha...
    api = None;

