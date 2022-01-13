#!/usr/bin/python
import sys, os, json, nmap;

sys.path.insert(0, os.environ["ROOT"]);
from api.hacker import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);

#{'_id': '', 'ip': '208.80.124.13', 'domain_id': ''}

def ports_ip(ip):
    n = nmap.PortScanner();
    n.scan(ip["ip"], arguments="-sV");
    envelopes = [];
    if n._scan_result['scan'].get(ip['ip']) == None:
        return None;
    if n._scan_result['scan'][ip['ip']].get("tcp") != None :
        for key, value in n._scan_result['scan'][ip['ip']]["tcp"].items():
            envelope = {"port" : key, "evidence" : json.dumps(value), "ip" : ip["ip"], "ip_id" : ip["_id"], "protocol" : "tcp",
                "project_id" : ip["project_id"], "token" : ip["token"], "user" : ip["user"]};
            envelopes.append(envelope);
    if n._scan_result['scan'][ip['ip']].get("udp") != None :
        for key, value in n._scan_result['scan'][ip["ip"]]["udp"].items():
            envelope = {"port" : key, "evidence" : json.dumps(value), "ip" : ip["ip"], "ip_id" : ip["_id"], "protocol" : "udp",
            "project_id" : ip["project_id"], "token" : ip["token"], "user" : ip["user"]};
            envelopes.append(envelope);
    
    for envelope in envelopes:
        retorno = SendService(ip["server_ip"], "add_ip_port.php", envelope);

ports_ip(data);