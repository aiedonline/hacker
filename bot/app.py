#!/bin/python3
#sudo /bin/python3 /var/well/hacker/bot/app.py -s '127.0.0.1' -p '9e424a98-7ef4-d865-40f6-ebb439931f44' -t 'e8fd6417-cfa8-4b0d-a662-0de4a2d3c204' -u 'c308f6804bdd1a856355d3a34113f22a5d5f799b'

import argparse, subprocess, json, time, traceback, sys, os, inspect

ROOT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
os.environ["ROOT"] = ROOT;
sys.path.insert(0,ROOT);

from api.cachecommand import *;
from threading import Thread
from api.hacker import *;

def run_commands(script, dados_de_input):
    ex = CacheExec();
    ex.run(["python3", os.environ["ROOT"] + script], dados_de_input);
    print(ex.stdout, '\033[91m', ex.stderr);

ap = argparse.ArgumentParser();
ap.add_argument("-s", "--server",  required=True, help="IP do server");
ap.add_argument("-p", "--project", required=True, help="_id do projeto");
ap.add_argument("-t", "--token",   required=True, help="Chave gerada para o projeto");
ap.add_argument("-u", "--user",    required=True, help="Código do usuário");
args = vars(ap.parse_args())

dados_json = {"project_id" : args["project"], "token" : args["token"],  "user" : args["user"]};
projeto = SendService(args["server"], "project.php", dados_json );

#{'project': {'_id': '', '_user': '', 'nome': '', 'descricao': '', 'token': ''}, 
#      'nmap': {'arguments': {'_id': ''}, 'lans': []}}

#{'project': {'_id': '', '_user': '', 'nome': '',  'descricao': '', 'token': ''}, 
# 'nmap': {'arguments': {'_id': '', 'project_id': '', 'enable': '1', 'arguments': '-sV -O'}, 'lans': []}}

def nmap_switch(server_ip, user, token, nmap_json):
    # nmap_json = {'arguments': {'_id': '', 'project_id': '', 'enable': '1', 'arguments': '-sV -O'}, 'lans': []}
    # lans [{"_id":"","name":"","_user":"","ambiente_id":"","address":"","mask":""}]
    for lan in nmap_json['lans']:
        envelop_nmap = {"server_ip" : server_ip, "address" : lan['address'], "mask" : lan['mask'], "arguments" : nmap_json['arguments']['arguments'], "lan_id" : lan["_id" ], "project_id" : nmap_json['arguments']["project_id" ], "user" : user, "token" : token };
        Thread(target = run_commands, args = ("network/nmap_scanner.py", envelop_nmap,  )).start();

def nmap_domain_switch(server_ip, user, token, nmap_json):
    for ip in nmap_json['ips']:
        ip["server_ip"] = server_ip;
        ip["user"] = user;
        ip["token"] = token;
        ip["project_id"] = args["project"];
        Thread(target = run_commands, args = ("domain/nmap_ip.py", ip,  )).start();


def shodan_switch(server_ip, user, token, shodan_json):
    for i in range(len( shodan_json['hosts'])):
        host = shodan_json['hosts'][i];
        if host== None or host.get("ip") == None:
            continue;
        envelop = {"server_ip" : server_ip, "shodan_key" : shodan_json['shodan_key'], "_id" : host["_id"],  "host" : host["ip"], "project_id" : args["project"], "user" : user, "token" : token };
        run_commands("shodan/app.py", envelop);

def whois_switch(server_ip, user, token, whois_json):
    for domain in  whois_json['domains']:
        domain["server_ip"] = server_ip;
        domain["user"] = user;
        run_commands("domain/whois_ips.py", domain);

def dns_switch(server_ip, user, token, whois_json):
    for domain in  whois_json['domains']:
        domain["server_ip"] = server_ip;
        domain["user"] = user;
        run_commands("domain/dns_ips.py", domain);

# TEMOS QUE COLOCAR ANTES DE RODAR IS SCRIPTS UMA DESCRIÇÀO DO QUE VAI SER RODADO
#     POIS UM SCRIPT PODE DEMORAR E ACABAR DEIXANDO O USUÁRIO NA DÚVIDA.
print('\033[91m', "\t----==== BOT SECANALYSIS ====----", '\033[0m');
if projeto.get('nmap') != None and projeto['nmap'].get("enable") != None and projeto['nmap']["enable"] == "1":
    print("\033[92m -> Run:\033[0m Nmap in LAN");
if projeto.get('nmap_domain') != None and projeto['nmap_domain']["arguments"].get("enable") != None and projeto['nmap_domain']["arguments"]["enable"] == "1":
    print("\033[92m -> Run:\033[0m Nmap in Domains");
if projeto.get('shodan') != None and projeto['shodan'].get("enable") != None and projeto['shodan']["enable"] == "1":
    print("\033[92m -> Run:\033[0m Shodan in domains");
#print(projeto);

print('\033[91m', "\t----==== START ====----", '\033[0m');
whois_switch(args["server"], args["user"], args["token"], projeto['whois']);
dns_switch(args["server"], args["user"], args["token"], projeto['whois']);

if projeto.get('nmap_domain') != None and projeto['nmap_domain']["arguments"].get("enable") != None and projeto['nmap_domain']["arguments"]["enable"] == "1":
    Thread(target=nmap_domain_switch,   args=(args["server"], args["user"], args["token"], projeto['nmap_domain'  ], ) ).start();
if projeto.get('nmap') != None and projeto['nmap'].get("enable") != None and projeto['nmap']["enable"] == "1":
    Thread(target=nmap_switch,   args=(args["server"], args["user"], args["token"], projeto['nmap'  ], ) ).start();
if projeto.get('shodan') != None and projeto['shodan'].get("enable") != None and projeto['shodan']["enable"] == "1":
    Thread(target=shodan_switch, args=(args["server"], args["user"], args["token"], projeto['shodan'], ) ).start();

print('\033[91m', "\t----==== END ====----", '\033[0m');