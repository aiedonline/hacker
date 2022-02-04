#!/
# Executa um nmpa na rede com dados provenientes do painel web
# Requisitos:
#   1 - Instalar o nmpa: sudo apt install nmap -y
#   2 - Instalar o python-nmap: python3 -m pip install python-nmap
#
# Dados:    {"server_ip" :, "address" : , "mask" : , "arguments" : , "lan_id" : , "project_id" : , "user" : , "token" : }
#   server_ip
#   address
#   mask
#   arguments
#   lan_id
#   project_id
#   user
#   token
import nmap, subprocess, os, json, sys;

sys.path.insert(0, os.environ["ROOT"]);

from api.hacker import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);

print('\033[91m', "\t----==== NMAP ====----");

n = nmap.PortScanner();
n.scan(data["address"] + "/" + data["mask"], arguments=data["arguments"]);
if hasattr(n, 'all_hosts'):
    for host in n.all_hosts():
        print('\033[92m', "[+]", '\033[0m', " -", host, '\033[0m');
        os_type = "";
        if n[host].get("osmatch"):
            for i in range(len(n[host].get("osmatch"))):
                os_type += n[host].get("osmatch")[i].get("name") + " ";

        envelope = {"lan_id" : data["lan_id"], "ip" :  host ,  "name" : n[host].hostname(), "os" : os_type, "nmap" : json.dumps(n[host]),"project_id" : data['project_id'], "token" : data["token"], "user" : data["user"] };
        retorno = SendService(data["server_ip"], "add_host.php", envelope);
        for protocol in  n[host].all_protocols():
            for port in n[host][protocol].keys():
                buffer = ""; servico = "";
                for key_port, value_port in n[host][protocol][port].items():
                    if buffer != "":
                        buffer = buffer + ", ";
                    buffer = buffer + key_port + ": " + value_port;
                if n[host][protocol][port].get("name"):
                    servico = n[host][protocol][port]['name'];
                if n[host][protocol][port].get("version"):
                    servico = servico + " " + n[host][protocol][port]['version'];
                print('\033[93m', " [>]", '\033[0m', "- ", port, servico);
                envelope = {"lan_host_id" : data["lan_id"] + host, "port" :  port ,  "nmap" : buffer , "service" : servico,
                                            "project_id" : data['project_id'], "token" : data["token"], "user" : data["user"] };
                retorno = SendService(data["server_ip"], "add_host_port.php", envelope);