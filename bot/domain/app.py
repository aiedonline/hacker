import sys, os, subprocess, traceback, time;

sys.path.insert(0, os.environ["ROOT"]);
from api.edb import *;

SCRIPT_VERSION_SHODAN = "1";

def executar(script, dados ):
    p = subprocess.Popen(args=["python3", os.environ["ROOT"] + script], stdout=subprocess.PIPE,  stdin=subprocess.PIPE, stderr=subprocess.PIPE);
    p_out = p.communicate(input=json.dumps(dados).encode('utf-8'));  
    return {"code" : p.returncode, "stdout" : str(p_out[0], 'utf-8'), "stderr" : str(p_out[1], 'utf-8')};

def shodan_busca_ip():
    hosts = host_list();
    for host in hosts[0]['data']:
        if host['shodan'] == None:
            try:
                retorno_shodan = executar("domain/host.py", {"ip" : host['ip']});
                if retorno_shodan['stdout'] == "":
                    continue;
                retorno_ip = json.loads(retorno_shodan['stdout']);
                host_save_shodan(host['ip'], host['domain_id'], retorno_ip, SCRIPT_VERSION_SHODAN);
            except:
                traceback.print_exc();
                print('\033[91m[-]\033[0m', "Continuar: ", host['ip']);

def shodan_buca_dominio():
    dominios = domains();
    for consulta in dominios:
        for dominio in consulta['data']:
            print('\033[92m[+]\033[0m', dominio['domain']);
            shodan_e_ips = json.loads(executar("domain/hosts.py", {"domain" : dominio['domain']} )["stdout"]);
            for ip in shodan_e_ips[1]:
                host_server = host_read(ip);
                if host_server[0].get("font") == None:
                    try:
                        shodan_data = json.loads(executar("domain/host.py", {"ip" : ip})['stdout']);
                        host_init_shodan(ip, dominio['_id'],  shodan_data[1], SCRIPT_VERSION_SHODAN);
                    except:
                        print('\033[91m[-]\033[0m', "Falha ao registrar o IP", ip);    
            shodan_save(dominio['_id'], shodan_e_ips[0], SCRIPT_VERSION_SHODAN);       

def whois_busca_dominio():
    dominios = domains();
    for consulta in dominios:
        for dominio in consulta['data']:
            try:
                print('\033[92m[+]\033[0m', dominio['domain']);
                dados_whois = json.loads(executar("domain/whois_ips.py", {"domain" : dominio['domain']} )["stdout"]);
                time.sleep(5);
                whois_save( dominio['_id'], dados_whois, SCRIPT_VERSION_SHODAN);
            except:
                traceback.print_exc();

whois_busca_dominio();
shodan_buca_dominio();
shodan_busca_ip();

