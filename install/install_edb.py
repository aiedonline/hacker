#!/usr/bin/python3
#install/install_edb.py
#
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import os, sys, mysql.connector, json, requests
import traceback, inspect

CURRENT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
sys.path.insert(0, CURRENT);
from edb import *;

url = 'http://127.0.0.1/edb/update.php'
myobj = {'domain': '/local/hacker'}
x = requests.post(url, data = json.dumps(myobj), headers= {'Content-type': 'application/json', 'Accept': 'text/plain'});
print("Database: /local/hacker", x.text);

myobj = {'domain': '/local/edb'}
x = requests.post(url, data = json.dumps(myobj), headers= {'Content-type': 'application/json', 'Accept': 'text/plain'});
print("Database: /local/edb", x.text);

db = Database();
db.SendServer("/local/edb","write",[{"entity":"user","data":{"_id": "81dc9bdb52d04dc20036dbd8313ed055",  "password" : "81dc9bdb52d04dc20036dbd8313ed055", "cpf" : "11111111111", "email" : "root@system"}}]);