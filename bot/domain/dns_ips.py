#!/usr/bin/python

import dns.resolver;
import sys, os, json;

sys.path.insert(0, os.environ["ROOT"]);
from api.hacker import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);

machines = ["www", "mail", "ftp", "localhost", "webmail", "smtp", "pop", "cpanel", "ns", "www2", "pop3", "admin", "vpn", "mx",  "imap", "phpmyadmin"];

myquery = dns.resolver.Resolver();

def func_generic(_target, type_ip):
    retorno = [];
    try:
        question = myquery.query(_target, type_ip);
        for _addr in question:
            retorno.append(str(_addr));
            print('\t\033[92m[+]\033[00m ' + _target + '\033[96m -> ' + str(_addr), "\033[00m");
    except:
        print('\t\033[91m[-]\033[00m ' + _target);
    return retorno;
    
def bruteforce_dns_ipv4():
    ips_dns = func_generic( data['domain'], "A" );
    for machine in machines:
        buffer_ips = func_generic(machine + "." + data['domain'], "A" );
        for buffer_ip in buffer_ips:
            if not buffer_ip in ips_dns:
                ips_dns.append(buffer_ip);
    return ips_dns;

# Persistência na ferramenta
ip_list_all = bruteforce_dns_ipv4();        
for ip in ip_list_all:
    envelope = {"ip" : ip , "domain_id" : data['domain_id'], "user" : data["user"] };
    retorno = SendService(data["server_ip"], "add_ip_domain.php", envelope, port=data["port"], protocol=data["protocol"]);