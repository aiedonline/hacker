import time, os, sys, json;

#os.environ["ROOT"] = "/var/well/secanalysis/bot/";
#data = {"ip" : "179.215.131.76", "key" :  "bpNf1YCAA9mXRBTl1N3ZqB5oePvJDOHF"}

#https://ipqualityscore.com/api/json/ip/bpNf1YCAA9mXRB/179.215.131.76
#{"success":true,"message":"Success","fraud_score":70,"country_code":"BR",
# "region":"Sao Paulo","city":"","ISP":"","ASN":28573,
# "organization":"NET Virtua","is_crawler":false,"timezone":"",
# "mobile":false,"host":"b3d7834c.virtua.com.br","proxy":true,"vpn":false,"tor":false,
# "active_vpn":false,"active_tor":false,"recent_abuse":false,"bot_status":false,
# "connection_type":"Premium required.","abuse_velocity":"Premium required.",
# "zip_code":"N\/A","latitude":,"longitude":,"request_id":""}

# server_ip, port, protocol, ip, key

sys.path.insert(0, os.environ["ROOT"]);
data = sys.stdin.readlines();
data = json.loads(data[0]);

from api.util import *;
from api.cacherequest import *;
from api.hacker import *;

browser = CacheRequest(cache=True);
browser.get("https://ipqualityscore.com/api/json/ip/"+ data["key"] +"/" + data["ip"] )

json_result = json.loads(browser.text);

json_result['_id'] =   data["_id"];
json_result['user'] =  data["user"];

if json_result["success"] == True:
    retorno = SendService(data["server_ip"], "ipquality_host.php", json_result, port=data["port"], protocol=data["protocol"]);



