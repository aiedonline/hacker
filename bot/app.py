#!/bin/python3
import argparse, subprocess, json, time, traceback, sys, os, inspect, datetime, uuid


# ============================ IMPORTAÇOES EM GERAL =====================================
ROOT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
os.environ["ROOT"] = ROOT;
sys.path.insert(0,ROOT);

from api.cachecommand import *;
from threading import Thread
from api.hacker import *;
from pyfiglet import Figlet;


# ============================= ROTINAS DE APOIO ====================================

def install_dependence(dependence):
    sub = subprocess.run(["python3", os.environ["ROOT"] + "/api/module.py", dependence['name']]);
    if sub.returncode != 0:
        print("[*] Install: ",  dependence['install']);
        subprocess.run(["python3", "-m", "pip", "install", dependence['install']]);



# ============================ ARGUMENTOS SENDO PROCESSADOS =========================
ap = argparse.ArgumentParser();
ap.add_argument("-s", "--server",  required=True, help="IP do server");
ap.add_argument("-p", "--project", required=True, help="_id do projeto");
ap.add_argument("-t", "--token",   required=True, help="Chave gerada para o projeto");
ap.add_argument("-u", "--user",    required=True, help="Código do usuário");

ap.add_argument("-pt", "--protocol",    required=True, help="Protocolo");
ap.add_argument("-po", "--port",        required=True, help="Porta");
args = vars(ap.parse_args())


# Retorna um CacheExec
def run_commands(script, dados_de_input, dependencias=[], timeout=3, key=None, time=1):
    global args;
    global stderr, stdout;
    dados_de_input['port'] = port=args["port"];
    dados_de_input['protocol'] = port=args["protocol"];
    for dependencia in dependencias:
        install_dependence(dependencia);
    ex = CacheExec();
    ex.run(["python3", os.environ["ROOT"] + script], dados_de_input, key=key, timeout=timeout);
    if ex.stderr.strip() != "" or ex.stdout.strip() != "":
        print(ex.stdout, '\033[91m', ex.stderr, end="");
    
    stdout += script.upper() + "\n" +  ex.stdout + "\n\n";
    stderr += script.upper() + "\n" +  ex.stderr+ "\n\n";
    return ex;

# =============================== VARIAVEIS E VALORES ==========================

VERSION = "0.2";
DATA_INICIO = datetime.datetime.now();
threads = [];
stdout = ""; stderr = "";
EXEC_ID = str( uuid.uuid4() );
# =============================== BANNER E ABERTURA ================================

f = Figlet(font="slant");
print("\033[93m");
print( f.renderText("SecAnalysis BOT") );
print("\033[00m");
# Nome do produto, versão e site do produto
print("\033[96m\033[1m", "SecAnalysis", "\033[00m", "v." + VERSION, "https://www.cyberframewor.online/cyber/", "\n");


# =============================== EXECUÇÒES =======================================
dados_json = {"project_id" : args["project"], "token" : args["token"],  "user" : args["user"]};
projeto = SendService(args["server"], "project.php", dados_json, port=args["port"], protocol=args["protocol"] );

def nmap_switch(server_ip, user, token, nmap_json):
    # nmap_json = {'arguments': {'_id': '', 'project_id': '', 'enable': '1', 'arguments': '-sV -O'}, 'lans': []}
    # lans [{"_id":"","name":"","_user":"","ambiente_id":"","address":"","mask":""}]
    if len(nmap_json['lans']) == 0:
        return;

    print('\033[91m\033[1m', "Run nmap LAN:", '\033[00m');
    for lan in nmap_json['lans']:
        envelop_nmap = {"server_ip" : server_ip, "address" : lan['address'], "mask" : lan['mask'], "arguments" : nmap_json['arguments']['arguments'], "lan_id" : lan["_id" ], "project_id" : nmap_json['arguments']["project_id" ], "user" : user, "token" : token };
        run_commands("network/nmap_scanner.py", envelop_nmap, dependencias=[{"name" : "nmap", "install" : "python-nmap"}], key=("network/nmap_scanner.py" + envelop_nmap['address']), timeout=10);



def nmap_domain_switch(server_ip, user, token, nmap_json):
    if len(nmap_json['ips']) == 0:
        return;
    
    print('\033[91m\033[1m', "Run nmap in Domain:", '\033[00m');
    for ip in nmap_json['ips']:
        ip["server_ip"] = server_ip;
        ip["user"] = user;
        ip["token"] = token;
        ip["project_id"] = args["project"];
        run_commands("domain/nmap_ip.py", ip, dependencias=[{"name" : "nmap", "install" : "python-nmap"}], key=("domain/nmap_ip.py" + ip['ip']));


def ipquality_switch(server_ip, user, token, ipquality_json):
    if ipquality_json['ipquality_key'] == None or ipquality_json['ipquality_key'] == "":
        print("\033[94m\033[1mInforme a key ipquality\033[00m");
        return;
    if len(ipquality_json['hosts']) == 0:
        return;
    
    print('\033[91m\033[1m', "Run Ipquality:", '\033[00m');
    for i in range(len( ipquality_json['hosts'])):   
        host = ipquality_json['hosts'][i];
        if host== None or host.get("ip") == None:
            continue;
        # ## server_ip, port, protocol, ip, key
        envelop = {"server_ip" : server_ip, "key" : ipquality_json['ipquality_key'], "_id" : host["_id"],  "ip" : host["ip"], "project_id" : args["project"], "user" : user, "token" : token };
        run_commands("network/ipquality.py", envelop, dependencias=[{"name" : "requests", "install" : "requests"}], key=("network/ipquality.py_" + host["_id"]));


def shodan_switch(server_ip, user, token, shodan_json):
    if shodan_json['shodan_key'] == None or shodan_json['shodan_key'] == "":
        print("\033[94m\033[1mInforme a key shodan\033[00m");
        return;
    if len(shodan_json['hosts']) == 0:
        return;
    
    print('\033[91m\033[1m', "Run Shodan.io:", '\033[00m');
    for i in range(len( shodan_json['hosts'])):   
        host = shodan_json['hosts'][i];
        if host== None or host.get("ip") == None:
            continue;
        
        envelop = {"server_ip" : server_ip, "shodan_key" : shodan_json['shodan_key'], "_id" : host["_id"],  "host" : host["ip"], "project_id" : args["project"], "user" : user, "token" : token };
        run_commands("shodan/app.py", envelop, dependencias=[{"name" : "shodan", "install" : "shodan"}], key=("shodan/app.py_" + host["_id"]));

def whois_switch(server_ip, user, token, whois_json):
    if len(whois_json['domains']) == 0:
        return;
    
    print('\033[91m\033[1m', "Run Search Whois:", '\033[00m');
    for domain in  whois_json['domains']:
        domain["server_ip"] = server_ip;
        domain["user"] = user;
        run_commands("domain/whois_ips.py", domain,[{"name" : "whois", "install" : "python-whois"}], key=("domain/whois_ips.py_" + domain["domain"]));

def dns_switch(server_ip, user, token, whois_json):
    if len(whois_json['domains']) == 0:
        return;
    print('\033[91m\033[1m', "Run Search DNS IP:", '\033[00m');
    for domain in  whois_json['domains']:
        domain["server_ip"] = server_ip;
        domain["user"] = user;
        run_commands("domain/dns_ips.py", domain, dependencias=[{"name" : "dns", "install" : "dnspython"}] ,key=("domain/dns_ips.py" + domain["domain"]  ));

try:
    # INSERT NA TABELA execution COM PROJECT_ID
    envelope = {"_id" : EXEC_ID , "project_id" : args["project"], "user" : args["user"], "token" : args["token"], "action" : "start" };
    retorno = SendService(args["server"], "exec.php", envelope, port=args["port"], protocol=args["protocol"] );

    # TEMOS QUE COLOCAR ANTES DE RODAR IS SCRIPTS UMA DESCRIÇÀO DO QUE VAI SER RODADO
    #     POIS UM SCRIPT PODE DEMORAR E ACABAR DEIXANDO O USUÁRIO NA DÚVIDA.
    print('\033[91m\033[1m', "Routine:", '\033[00m');
    print("\t-> Search Whois", "\t-> Search DNS IP", sep="\n");

    if projeto.get('nmap') != None and projeto['nmap']['arguments'].get("enable")  == "1":
        print("\t-> Run nmap in LAN");
    if projeto.get('nmap_domain') != None and projeto['nmap_domain']["arguments"].get("enable")  == "1":
        print("\t-> Run nmap in Domain");
    if projeto.get('shodan') != None and projeto['shodan'].get("enable") == "1":
        print("\t-> Run Shodan.io");
    if projeto.get('ipquality') != None and projeto['ipquality'].get("enable")  == "1":
        print("\t-> Run IPQuality");

    buffer_exec = whois_switch(args["server"], args["user"], args["token"], projeto['whois']);
    buffer_exec = dns_switch(args["server"], args["user"], args["token"], projeto['whois']);

    if projeto.get('nmap_domain') != None and projeto['nmap_domain']["arguments"].get("enable")  == "1":
        buffer_exec = nmap_domain_switch(args["server"], args["user"], args["token"], projeto['nmap_domain'  ]);
    if projeto.get('nmap') != None and projeto['nmap']['arguments'].get("enable")  == "1":
        buffer_exec = nmap_switch(args["server"], args["user"], args["token"], projeto['nmap'  ]);
    if projeto.get('shodan') != None and projeto['shodan'].get("enable")  == "1":
        buffer_exec = shodan_switch(args["server"], args["user"], args["token"], projeto['shodan']);
    if projeto.get('ipquality') != None and projeto['ipquality'].get("enable")  == "1":
        buffer_exec = ipquality_switch(args["server"], args["user"], args["token"], projeto['ipquality']);
    # ============================ RELATÓRIO FINAL =============================

    DATA_FINALIZACAO = datetime.datetime.now();
    DIFERENCA = DATA_FINALIZACAO - DATA_INICIO;
    for t in threads:
        t.join();
    print("\nProcedimento executado em: ", (DIFERENCA.seconds // 60), "minuto(s) e", (DIFERENCA.seconds % 60), "segundo(s)", '\033[0m');
    # UPDATE NA TABELA execution COM PROJECT_ID
    envelope = {"_id" : EXEC_ID , "project_id" : args["project"], "user" : args["user"], "token" : args['token'], "stdout" : stdout, "stderr" : stderr, "status_code" : "0",  "action" : "sucess" };
    retorno = SendService(args["server"], "exec.php", envelope, port=args["port"], protocol=args["protocol"] ); 
except:
    traceback.print_exc();
    # UPDATE NA TABELA execution COM PROJECT_ID
    envelope = {"_id" : EXEC_ID , "project_id" : args["project"], "user" : args["user"], "token" : args['token'], "stdout" : stdout, "stderr" : stderr, "status_code" : "1",  "action" : "error" };
    retorno = SendService(args["server"], "exec.php", envelope, port=args["port"], protocol=args["protocol"] );

sys.exit(0);
