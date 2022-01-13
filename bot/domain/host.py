import time, os, sys, json;

sys.path.insert(0, os.environ["ROOT"]);
from api.util import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);

def limpar(js, key):
    try:
        return js[key];
    except:
        return None;

ipinfo = shodan_host(data['ip']);
saida = {"region_code" : ipinfo.get("region_code"), "postal_code" : ipinfo.get("postal_code"), "country_name" : ipinfo.get("country_name"), 
"city" : ipinfo.get("city"), "vulns" : ipinfo.get("vulns"), "latitude" : ipinfo.get("latitude"), "longitude" : ipinfo.get("longitude"), 
"hostnames" : ipinfo.get("hostnames"), "os" : ipinfo.get("os"), "ports" : ipinfo.get("ports")};
print(json.dumps(saida));


