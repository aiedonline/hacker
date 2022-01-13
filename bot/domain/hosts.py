# RETORNA UM [] COM IPS
import time, os, sys, json;

sys.path.insert(0, os.environ["ROOT"]);

from api.util import *;

data = sys.stdin.readlines();
data = json.loads(data[0]);

ipinfo = shodan_search(data['domain']);
ips_saida = [];
for i in range(len(ipinfo['matches'])):
    ips_saida.append(ipinfo['matches'][i]['ip_str']);
print(json.dumps([ipinfo, ips_saida]));



