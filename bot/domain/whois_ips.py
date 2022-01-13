#!/usr/bin/python3
# Deve ser instalado: python3 -m pip install python-whois
#                     sudo apt install whois -y
import whois, sys, os, json, traceback, inspect, socket;
sys.path.insert(0, os.environ["ROOT"]);
from api.hacker import *;

data = sys.stdin.readlines();
data = json.loads(data[0]);

PATH_CACHE = "/tmp/";

def get_whois(domain):
    path_to_cache_file = PATH_CACHE + "_whois_" + domain;
    if os.path.exists(path_to_cache_file):
        return json.loads( open( path_to_cache_file ).read());
    else:
        w = whois.whois(domain);
        try:
            with open( path_to_cache_file, "w") as f:
                f.write(json.dumps(w, indent=4, sort_keys=True, default=str));
                f.close();
            return w;
        except:
            traceback.print_exc();
            sys.exit(0);

def get_ip(url):
    ip_list = []
    ais = socket.getaddrinfo(url, 0,0,0,0)
    for result in ais:
        ip_list.append(result[-1][0])
        ip_list = list(set(ip_list))
    return ip_list;

dados_whois = get_whois( data['domain'] );
for dns_server in dados_whois["name_servers"]:
    print('\033[92m', "[+]", '\033[0m', " - ", dns_server, '\033[0m');
    ip_list = get_ip(dns_server);
    for ip in ip_list:
        print('\033[93m', "\t[->]", '\033[0m', " - ", ip, '\033[0m');
        envelope = {"ip" : ip , "domain_id" : data['domain_id'], "user" : data["user"] };
        retorno = SendService(data["server_ip"], "add_ip_domain.php", envelope);






